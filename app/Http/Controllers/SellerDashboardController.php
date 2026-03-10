<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // ── Chart Data ──────────────────────────────────────────

        // 1. Weekly revenue (last 7 days)
        $weeklyRevenue = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
                ->whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');
            $weeklyRevenue->push([
                'day'   => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'total' => $revenue,
            ]);
        }

        // 2. Order status distribution (today)
        $statusCounts = [
            'pending'   => $todaysOrders->where('status', 'pending')->count(),
            'preparing' => $todaysOrders->where('status', 'preparing')->count(),
            'ready'     => $todaysOrders->where('status', 'ready')->count(),
            'completed' => $todaysOrders->where('status', 'completed')->count(),
            'cancelled' => $todaysOrders->where('status', 'cancelled')->count(),
        ];

        // 3. Top selling menus (last 7 days)
        $topMenus = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled')
                ->where('created_at', '>=', Carbon::today()->subDays(7)))
            ->select('menu_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 4. Hourly order distribution (today)
        $hourlyOrders = collect();
        $validOrders = $todaysOrders->where('status', '!=', 'cancelled');
        for ($h = 7; $h <= 17; $h++) {
            $count = $validOrders->filter(fn($o) => (int)$o->created_at->format('H') === $h)->count();
            $hourlyOrders->push([
                'hour'  => sprintf('%02d:00', $h),
                'count' => $count,
            ]);
        }

        return view('seller.dashboard', compact(
            'totalMenus', 'activeMenus', 'todayOrderCount', 'todayRevenue', 'pendingOrders', 'recentOrders',
            'weeklyRevenue', 'statusCounts', 'topMenus', 'hourlyOrders'
        ));
    }
}
