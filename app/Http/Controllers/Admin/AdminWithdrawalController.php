<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerWallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('seller');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhereHas('seller', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $withdrawals = $query->latest()->paginate(20);

        $stats = [
            'pending'  => Withdrawal::where('status', 'pending')->count(),
            'approved' => Withdrawal::where('status', 'approved')->count(),
            'paid'     => Withdrawal::where('status', 'paid')->count(),
            'rejected' => Withdrawal::where('status', 'rejected')->count(),
        ];

        return view('admin.withdrawals', compact('withdrawals', 'stats'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini tidak dalam status pending.');
        }

        $withdrawal->update(['status' => 'approved']);

        return back()->with('success', "Penarikan #{$withdrawal->id} berhasil disetujui.");
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini tidak dalam status pending.');
        }

        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status'     => 'rejected',
                'admin_note' => $request->admin_note,
            ]);

            // Return balance to seller wallet
            SellerWallet::where('seller_id', $withdrawal->seller_id)
                ->increment('balance', $withdrawal->amount);
        });

        return back()->with('success', "Penarikan #{$withdrawal->id} ditolak. Saldo dikembalikan.");
    }

    public function markPaid(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'approved') {
            return back()->with('error', 'Penarikan ini belum disetujui.');
        }

        $withdrawal->update(['status' => 'paid']);

        return back()->with('success', "Penarikan #{$withdrawal->id} ditandai sudah dibayar.");
    }
}
