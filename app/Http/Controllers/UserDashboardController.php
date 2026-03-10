<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $recentOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $activeOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->get();

        // Stats for summary cards
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent  = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // Weekly spending data (last 7 days)
        $weeklySpending = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $spent = Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $weeklySpending->push([
                'day'   => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'amount' => (int) $spent,
            ]);
        }

        // Order status distribution for donut chart
        $statusCounts = [
            'pending'   => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'preparing' => Order::where('user_id', $user->id)->where('status', 'preparing')->count(),
            'ready'     => Order::where('user_id', $user->id)->where('status', 'ready')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $midtransClientKey = config('services.midtrans.client_key');

        return view('dashboard.index', compact(
            'user', 'recentOrders', 'activeOrders',
            'totalOrders', 'totalSpent', 'weeklySpending', 'statusCounts',
            'midtransClientKey'
        ));
    }
}
