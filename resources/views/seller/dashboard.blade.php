@extends('layouts.seller')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome --}}
    <div>
        <h2 class="text-2xl font-bold text-dark">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-gray-500 mt-1">Ringkasan toko Anda hari ini.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Menu --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-600">{{ $activeMenus }} aktif</span>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $totalMenus }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Menu</p>
        </div>

        {{-- Today Orders --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $todayOrderCount }}</p>
            <p class="text-xs text-gray-400 mt-1">Pesanan Hari Ini</p>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                @if($pendingOrders > 0)
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-amber-50 text-amber-600 animate-pulse">perlu aksi</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-dark">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Pesanan Menunggu</p>
        </div>

        {{-- Today Revenue --}}
        <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-white/70 mt-1">Pendapatan Hari Ini</p>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-dark">Pesanan Terakhir</h3>
            <a href="{{ route('seller.orders') }}" class="text-sm text-brand-500 hover:text-brand-600 font-medium">Lihat Semua →</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="text-gray-400">Belum ada pesanan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Pelanggan</th>
                            <th class="px-6 py-3 font-medium">Item</th>
                            <th class="px-6 py-3 font-medium">Total</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-dark">#{{ $order->id }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $order->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $order->items->count() }} item</td>
                            <td class="px-6 py-3 font-semibold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $statusColors = [
                                        'pending'   => 'bg-yellow-50 text-yellow-700',
                                        'preparing' => 'bg-blue-50 text-blue-700',
                                        'ready'     => 'bg-green-50 text-green-700',
                                        'completed' => 'bg-gray-100 text-gray-600',
                                        'cancelled' => 'bg-red-50 text-red-600',
                                    ];
                                    $statusLabels = [
                                        'pending'   => 'Menunggu',
                                        'preparing' => 'Diproses',
                                        'ready'     => 'Siap',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Batal',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-400 text-xs">{{ $order->created_at->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
