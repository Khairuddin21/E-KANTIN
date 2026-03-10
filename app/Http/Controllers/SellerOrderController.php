<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\SellerWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    public function index(Request $request)
    {
        $menuIds = Menu::where('seller_id', Auth::id())->pluck('id');

        $query = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->where('dismissed_by_seller', false)
            ->with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', today());
        }

        if ($request->filled('pickup')) {
            $query->where('pickup_time', $request->pickup);
        }

        // FIFO: oldest first
        $orders = $query->oldest()->get();

        // Defined pickup time order
        $pickupOrder = ['Istirahat 1', 'Istirahat 2', 'Pulang', 'Sekarang'];

        $grouped = $orders->groupBy('pickup_time')
            ->sortBy(fn($group, $key) => array_search($key, $pickupOrder) !== false ? array_search($key, $pickupOrder) : 999);

        // Summary counts
        $summary = [
            'total'     => $orders->count(),
            'pending'   => $orders->where('status', 'pending')->count(),
            'preparing' => $orders->where('status', 'preparing')->count(),
            'ready'     => $orders->where('status', 'ready')->count(),
            'completed' => $orders->where('status', 'completed')->count(),
        ];

        return view('seller.orders', [
            'orders'  => $grouped,
            'summary' => $summary,
            'pickupTimes' => $pickupOrder,
        ]);
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

        // Credit seller wallet when order is completed
        if ($request->status === 'completed') {
            $wallet = SellerWallet::firstOrCreate(
                ['seller_id' => Auth::id()],
                ['balance' => 0]
            );
            $wallet->increment('balance', $order->total_price);
        }

        $labels = [
            'pending'   => 'Menunggu',
            'preparing' => 'Diproses',
            'ready'     => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return back()->with('success', "Status pesanan #$order->id diubah menjadi {$labels[$request->status]}!");
    }

    public function dismiss(Order $order)
    {
        $menuIds = Menu::where('seller_id', Auth::id())->pluck('id');
        $hasSellerItems = $order->items()->whereIn('menu_id', $menuIds)->exists();

        if (!$hasSellerItems) {
            abort(403);
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Hanya pesanan selesai atau dibatalkan yang bisa dihapus dari antrian.');
        }

        $order->update(['dismissed_by_seller' => true]);

        return back()->with('success', "Pesanan #{$order->id} dihapus dari antrian.");
    }
}
