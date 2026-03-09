<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    public function index(Request $request)
    {
        $menuIds = Menu::where('seller_id', Auth::id())->pluck('id');

        $query = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders = $query->latest()->get()
            ->groupBy('pickup_time');

        return view('seller.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $menuIds = Menu::where('seller_id', Auth::id())->pluck('id');
        $hasSellerItems = $order->items()->whereIn('menu_id', $menuIds)->exists();

        if (!$hasSellerItems) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        $labels = [
            'pending'   => 'Menunggu',
            'preparing' => 'Diproses',
            'ready'     => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return back()->with('success', "Status pesanan #$order->id diubah menjadi {$labels[$request->status]}!");
    }
}
