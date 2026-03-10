<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'seller')
            ->select('users.*')
            ->addSelect([
                'menu_count' => Menu::selectRaw('count(*)')
                    ->whereColumn('seller_id', 'users.id'),
                'order_count' => Order::selectRaw('count(DISTINCT orders.id)')
                    ->join('order_items as oi1', 'orders.id', '=', 'oi1.order_id')
                    ->join('menus as m1', 'oi1.menu_id', '=', 'm1.id')
                    ->whereColumn('m1.seller_id', 'users.id')
                    ->where('orders.payment_status', 'paid'),
                'revenue' => Order::selectRaw('COALESCE(SUM(oi.subtotal), 0)')
                    ->join('order_items as oi', 'orders.id', '=', 'oi.order_id')
                    ->join('menus', 'oi.menu_id', '=', 'menus.id')
                    ->whereColumn('menus.seller_id', 'users.id')
                    ->where('orders.payment_status', 'paid'),
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sellers = $query->latest()->paginate(20);

        $stats = [
            'total'    => User::where('role', 'seller')->count(),
            'active'   => User::where('role', 'seller')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'seller')->where('is_active', false)->count(),
        ];

        return view('admin.sellers', compact('sellers', 'stats'));
    }

    public function approveSeller(User $user)
    {
        if ($user->role !== 'seller') {
            return back()->with('error', 'Pengguna ini bukan seller.');
        }

        $user->update(['is_active' => true]);

        return back()->with('success', "Penjual {$user->name} berhasil diaktifkan.");
    }

    public function deactivateSeller(User $user)
    {
        if ($user->role !== 'seller') {
            return back()->with('error', 'Pengguna ini bukan seller.');
        }

        $user->update(['is_active' => false]);

        return back()->with('success', "Penjual {$user->name} berhasil dinonaktifkan.");
    }

    public function menus(User $user)
    {
        if ($user->role !== 'seller') {
            return response()->json([], 404);
        }

        $menus = Menu::where('seller_id', $user->id)
            ->select('id', 'name', 'price', 'category', 'image', 'is_available')
            ->orderBy('name')
            ->get();

        return response()->json($menus);
    }
}
