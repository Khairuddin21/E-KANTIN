<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers   = User::whereIn('role', ['student', 'teacher'])->count();
        $totalSellers = User::where('role', 'seller')->count();
        $ordersToday  = Order::whereDate('created_at', Carbon::today())->count();
        $platformRevenue = Order::where('payment_status', 'paid')->sum('total_price');

        // User distribution chart
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $sellerCount  = $totalSellers;

        $userDistribution = [
            'pelanggan' => $studentCount + $teacherCount,
            'kantin'    => $sellerCount,
        ];

        // Daily orders last 7 days
        $dailyOrders = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyOrders->push([
                'day'   => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'count' => Order::whereDate('created_at', $date)->count(),
            ]);
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'ordersToday', 'platformRevenue',
            'userDistribution', 'dailyOrders'
        ));
    }
}
