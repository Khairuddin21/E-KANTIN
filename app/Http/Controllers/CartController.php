<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function menuPage()
    {
        $menus = Menu::where('is_available', true)->latest()->get();
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return view('dashboard.menu', compact('menus', 'cartCount'));
    }

    public function index()
    {
        $cartItems = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);

        return view('dashboard.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'menu_id'  => 'required|exists:menus,id',
            'quantity' => 'nullable|integer|min:1|max:20',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);

        if (!$menu->is_available) {
            return response()->json(['message' => 'Menu ini sedang tidak tersedia.'], 422);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('menu_id', $validated['menu_id'])
            ->first();

        if ($cart) {
            $cart->increment('quantity', $validated['quantity'] ?? 1);
        } else {
            Cart::create([
                'user_id'  => Auth::id(),
                'menu_id'  => $validated['menu_id'],
                'quantity' => $validated['quantity'] ?? 1,
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'message'    => 'Berhasil ditambahkan ke keranjang!',
            'cart_count' => $cartCount,
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $validated = $request->validate([
            'cart_id'  => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        $cart = Cart::where('id', $validated['cart_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->update(['quantity' => $validated['quantity']]);

        $cartItems = Cart::with('menu')->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'message'    => 'Jumlah diperbarui.',
            'subtotal'   => $cart->menu->price * $validated['quantity'],
            'total'      => $total,
            'cart_count' => $cartCount,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $cart = Cart::where('id', $validated['cart_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        $cartItems = Cart::with('menu')->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'message'    => 'Item dihapus dari keranjang.',
            'total'      => $total,
            'cart_count' => $cartCount,
        ]);
    }

    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['cart_count' => $count]);
    }
}
