<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\SellerWallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerWalletController extends Controller
{
    public function wallet()
    {
        $seller = Auth::user();
        $wallet = SellerWallet::firstOrCreate(
            ['seller_id' => $seller->id],
            ['balance' => 0]
        );

        $totalEarnings = Withdrawal::where('seller_id', $seller->id)
            ->whereIn('status', ['approved', 'paid'])
            ->sum('amount') + $wallet->balance;

        $totalWithdrawn = Withdrawal::where('seller_id', $seller->id)
            ->where('status', 'paid')
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        $recentWithdrawals = Withdrawal::where('seller_id', $seller->id)
            ->latest()
            ->take(5)
            ->get();

        return view('seller.wallet', compact(
            'wallet', 'totalEarnings', 'totalWithdrawn', 'pendingWithdrawals', 'recentWithdrawals'
        ));
    }

    public function storeWithdrawal(Request $request)
    {
        $seller = Auth::user();
        $wallet = SellerWallet::firstOrCreate(
            ['seller_id' => $seller->id],
            ['balance' => 0]
        );

        $request->validate([
            'amount'         => ['required', 'integer', 'min:50000', 'max:' . $wallet->balance],
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name'   => ['required', 'string', 'max:100'],
        ], [
            'amount.min'  => 'Minimum penarikan adalah Rp 50.000.',
            'amount.max'  => 'Jumlah penarikan melebihi saldo tersedia.',
            'amount.required' => 'Jumlah penarikan harus diisi.',
        ]);

        DB::transaction(function () use ($wallet, $request, $seller) {
            $wallet->decrement('balance', $request->amount);

            Withdrawal::create([
                'seller_id'      => $seller->id,
                'amount'         => $request->amount,
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name'   => $request->account_name,
                'status'         => 'pending',
            ]);
        });

        return back()->with('success', 'Permintaan penarikan sebesar Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil diajukan.');
    }

    public function history(Request $request)
    {
        $seller = Auth::user();

        $query = Withdrawal::where('seller_id', $seller->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(15);

        return view('seller.withdrawals', compact('withdrawals'));
    }
}
