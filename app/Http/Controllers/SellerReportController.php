<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SellerReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();
        $menuIds = Menu::where('seller_id', Auth::id())->pluck('id');

        $orders = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'cancelled')
            ->with('items')
            ->get();

        $totalOrders   = $orders->count();
        $totalRevenue  = $orders->sum('total_price');
        $completedOrders = $orders->where('status', 'completed')->count();

        // Top sold items for this seller
        $topItems = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->whereDate('created_at', $date)->where('status', '!=', 'cancelled'))
            ->selectRaw('menu_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Hourly revenue for chart
        $hourlyRevenue = $orders->groupBy(fn($o) => $o->created_at->format('H:00'))
            ->map(fn($group) => $group->sum('total_price'))
            ->sortKeys();

        return view('seller.reports', compact(
            'date', 'totalOrders', 'totalRevenue', 'completedOrders', 'topItems', 'hourlyRevenue'
        ));
    }
}
