<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class TopupController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$clientKey    = config('services.midtrans.client_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:10000|max:1000000',
        ]);

        $user = Auth::user();

        $topup = Topup::create([
            'user_id' => $user->id,
            'amount'  => $validated['amount'],
            'status'  => 'pending',
        ]);

        $midtransOrderId = 'TOPUP-' . str_pad($topup->id, 5, '0', STR_PAD_LEFT) . '-' . time();
        $topup->update(['midtrans_order_id' => $midtransOrderId]);

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $validated['amount'],
            ],
            'item_details' => [[
                'id'       => 'topup-' . $topup->id,
                'price'    => (int) $validated['amount'],
                'quantity' => 1,
                'name'     => 'Top Up Saldo E-Canteen',
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $topup->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'topup_id'   => $topup->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Topup Snap Error: ' . $e->getMessage());
            $topup->update(['status' => 'failed']);
            return response()->json(['error' => 'Gagal membuat pembayaran. Silakan coba lagi.'], 500);
        }
    }

    public function confirm(Topup $topup)
    {
        $user = Auth::user();

        if ($topup->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($topup->status === 'paid') {
            return response()->json([
                'status'  => 'paid',
                'balance' => $user->fresh()->balance,
            ]);
        }

        try {
            $status = Transaction::status($topup->midtrans_order_id);
        } catch (\Exception $e) {
            Log::error('Midtrans status check failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengecek status pembayaran.'], 500);
        }

        $txStatus    = $status->transaction_status ?? null;
        $fraudStatus = $status->fraud_status ?? null;

        if (in_array($txStatus, ['capture', 'settlement']) && ($fraudStatus === 'accept' || $fraudStatus === null)) {
            if ($topup->status !== 'paid') {
                DB::transaction(function () use ($topup) {
                    $topup->update(['status' => 'paid']);

                    User::where('id', $topup->user_id)->increment('balance', $topup->amount);

                    WalletTransaction::create([
                        'user_id'     => $topup->user_id,
                        'amount'      => $topup->amount,
                        'type'        => 'credit',
                        'description' => 'Top Up Saldo via Midtrans #' . str_pad($topup->id, 5, '0', STR_PAD_LEFT),
                    ]);
                });
            }

            return response()->json([
                'status'  => 'paid',
                'balance' => $user->fresh()->balance,
            ]);
        }

        return response()->json([
            'status'  => $txStatus ?? 'pending',
            'balance' => $user->balance,
        ]);
    }

    public function notification(Request $request)
    {
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Topup Notification Parse Error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId           = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status ?? null;

        $topup = Topup::where('midtrans_order_id', $orderId)->first();

        if (!$topup) {
            return response()->json(['message' => 'Not a topup transaction'], 404);
        }

        // Verify signature
        $serverKey   = config('services.midtrans.server_key');
        $grossAmount = $notification->gross_amount;
        $statusCode  = $notification->status_code;
        $signatureKey = $notification->signature_key;
        $expectedSig  = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSig) {
            Log::warning("Midtrans topup signature mismatch: {$orderId}");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        Log::info("Midtrans topup notification: order={$orderId} status={$transactionStatus}");

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                if ($topup->status !== 'paid') {
                    DB::transaction(function () use ($topup) {
                        $topup->update(['status' => 'paid']);

                        User::where('id', $topup->user_id)->increment('balance', $topup->amount);

                        WalletTransaction::create([
                            'user_id'     => $topup->user_id,
                            'amount'      => $topup->amount,
                            'type'        => 'credit',
                            'description' => 'Top Up Saldo via Midtrans #' . str_pad($topup->id, 5, '0', STR_PAD_LEFT),
                        ]);
                    });
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $topup->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
        }

        return response()->json(['message' => 'OK']);
    }
}
