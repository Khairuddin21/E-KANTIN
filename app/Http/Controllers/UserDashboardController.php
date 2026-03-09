<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('dashboard.index', compact('user', 'recentOrders', 'activeOrders'));
    }
}
