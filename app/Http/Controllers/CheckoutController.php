<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$clientKey    = config('services.midtrans.client_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }

    public function checkout()
    {
        $cartItems = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('student.menu')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);
        $user = Auth::user();
        $midtransClientKey = config('services.midtrans.client_key');

        return view('dashboard.checkout', compact('cartItems', 'total', 'user', 'midtransClientKey'));
    }

    /**
     * Process checkout — wallet payment (instant).
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'pickup_time'    => 'required|in:Istirahat 1,Istirahat 2,Pulang,Sekarang',
            'payment_method' => 'required|in:wallet,midtrans',
        ]);

        $user = Auth::user();

        $cartItems = Cart::with('menu')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('student.menu')->with('error', 'Keranjang kosong.');
        }

        foreach ($cartItems as $item) {
            if (!$item->menu || !$item->menu->is_available) {
                return back()->with('error', "Menu \"{$item->menu->name}\" sudah tidak tersedia.");
            }
        }

        $totalPrice = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);

        // ─── WALLET PAYMENT ─────────────────────────────────────────
        if ($validated['payment_method'] === 'wallet') {
            if ($user->balance < $totalPrice) {
                return back()->with('error', 'Saldo tidak cukup.');
            }

            $order = DB::transaction(function () use ($user, $cartItems, $validated, $totalPrice) {
                $order = Order::create([
                    'user_id'        => $user->id,
                    'pickup_time'    => $validated['pickup_time'],
                    'total_price'    => $totalPrice,
                    'status'         => 'pending',
                    'payment_method' => 'wallet',
                    'payment_status' => 'paid',
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'  => $order->id,
                        'menu_id'   => $item->menu->id,
                        'menu_name' => $item->menu->name,
                        'price'     => $item->menu->price,
                        'quantity'  => $item->quantity,
                        'subtotal'  => $item->menu->price * $item->quantity,
                    ]);
                }

                User::where('id', $user->id)->decrement('balance', $totalPrice);

                WalletTransaction::create([
                    'user_id'     => $user->id,
                    'amount'      => $totalPrice,
                    'type'        => 'debit',
                    'description' => 'Pembayaran pesanan #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                ]);

                Cart::where('user_id', $user->id)->delete();

                return $order;
            });

            return redirect()->route('student.order.success', $order)->with('success', 'Pesanan berhasil dibuat!');
        }

        // ─── MIDTRANS PAYMENT ───────────────────────────────────────
        $order = DB::transaction(function () use ($user, $cartItems, $validated, $totalPrice) {
            $order = Order::create([
                'user_id'        => $user->id,
                'pickup_time'    => $validated['pickup_time'],
                'total_price'    => $totalPrice,
                'status'         => 'pending',
                'payment_method' => 'midtrans',
                'payment_status' => 'unpaid',
            ]);

            $midtransOrderId = 'ECANTEEN-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '-' . time();
            $order->update(['midtrans_order_id' => $midtransOrderId]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item->menu->id,
                    'menu_name' => $item->menu->name,
                    'price'     => $item->menu->price,
                    'quantity'  => $item->quantity,
                    'subtotal'  => $item->menu->price * $item->quantity,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            return $order;
        });

        // Create Snap token
        $itemDetails = $cartItems->map(fn($item) => [
            'id'       => $item->menu->id,
            'price'    => (int) $item->menu->price,
            'quantity' => (int) $item->quantity,
            'name'     => substr($item->menu->name, 0, 50),
        ])->toArray();

        $params = [
            'transaction_details' => [
                'order_id'     => $order->midtrans_order_id,
                'gross_amount' => (int) $totalPrice,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'callbacks' => [
                'finish' => route('student.order.success', $order),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $order->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return response()->json(['error' => 'Gagal membuat pembayaran. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Midtrans notification webhook (server-to-server).
     */
    public function midtransNotification(Request $request)
    {
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Parse Error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId           = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status ?? null;

        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::warning("Midtrans notification for unknown order: {$orderId}");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Verify signature
        $serverKey     = config('services.midtrans.server_key');
        $grossAmount   = $notification->gross_amount;
        $statusCode    = $notification->status_code;
        $signatureKey  = $notification->signature_key;
        $expectedSig   = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSig) {
            Log::warning("Midtrans signature mismatch for order: {$orderId}");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        Log::info("Midtrans notification: order={$orderId} status={$transactionStatus} fraud={$fraudStatus}");

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status'         => 'pending',
                    ]);

                    WalletTransaction::create([
                        'user_id'     => $order->user_id,
                        'amount'      => $order->total_price,
                        'type'        => 'debit',
                        'description' => 'Pembayaran Midtrans pesanan #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $order->update([
                'payment_status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
                'status'         => 'cancelled',
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update(['payment_status' => 'unpaid']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function orderSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('dashboard.order-success', compact('order'));
    }
}
