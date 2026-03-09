<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreOrderController extends Controller
{
    public function menuList()
    {
        $menus = Menu::where('is_available', true)->latest()->get();
        return view('dashboard.preorder', compact('menus'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'menu_id'     => ['required', 'exists:menus,id'],
            'quantity'    => ['required', 'integer', 'min:1', 'max:20'],
            'pickup_time' => ['required', 'in:Istirahat 1,Istirahat 2'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $totalPrice = $menu->price * $validated['quantity'];
        $user = Auth::user();

        if ($user->balance < $totalPrice) {
            return back()->with('error', 'Saldo tidak cukup. Silakan top up terlebih dahulu.');
        }

        DB::transaction(function () use ($user, $menu, $validated, $totalPrice) {
            $order = Order::create([
                'user_id'     => $user->id,
                'pickup_time' => $validated['pickup_time'],
                'total_price' => $totalPrice,
                'status'      => 'pending',
            ]);

            OrderItem::create([
                'order_id'  => $order->id,
                'menu_id'   => $menu->id,
                'menu_name' => $menu->name,
                'price'     => $menu->price,
                'quantity'  => $validated['quantity'],
                'subtotal'  => $totalPrice,
            ]);

            User::where('id', $user->id)->decrement('balance', $totalPrice);
        });

        return back()->with('success', 'Pesanan berhasil dibuat!');
    }
}
