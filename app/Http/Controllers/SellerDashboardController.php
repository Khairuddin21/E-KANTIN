<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();

        $menuIds = Menu::where('seller_id', $sellerId)->pluck('id');

        $todaysOrders = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->whereDate('created_at', today())
            ->get();

        $totalMenus      = Menu::where('seller_id', $sellerId)->count();
        $activeMenus      = Menu::where('seller_id', $sellerId)->where('is_available', true)->count();
        $todayOrderCount  = $todaysOrders->count();
        $todayRevenue     = $todaysOrders->where('status', '!=', 'cancelled')->sum('total_price');
        $pendingOrders    = $todaysOrders->whereIn('status', ['pending', 'preparing'])->count();

        $recentOrders = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'totalMenus', 'activeMenus', 'todayOrderCount', 'todayRevenue', 'pendingOrders', 'recentOrders'
        ));
    }
}
