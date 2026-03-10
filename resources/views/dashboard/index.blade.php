@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome + Balance Row --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Welcome Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Selamat datang kembali,</p>
                <h2 class="text-xl font-bold text-dark">{{ $user->name }}</h2>
                <span class="inline-block mt-1 text-xs font-semibold text-brand-600 bg-brand-50 px-2.5 py-0.5 rounded-full capitalize">{{ $user->role }}</span>
            </div>
        </div>

        {{-- Balance Card --}}
        <div class="relative overflow-hidden rounded-2xl p-6 shadow-sm bg-gradient-to-br from-brand-500 to-brand-700 text-white">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            <div class="relative">
                <p class="text-sm text-white/70 font-medium">Saldo Virtual</p>
                <p class="text-3xl font-bold mt-1 tracking-tight">Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                <button class="mt-4 inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Top Up Saldo
                </button>
            </div>
        </div>
    </div>

    {{-- Active Orders --}}
    @if($activeOrders->count())
    <div>
        <h3 class="text-lg font-bold text-dark mb-4">Pesanan Aktif</h3>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activeOrders as $order)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-400">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    @php
                        $badge = match($order->status) {
                            'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'preparing' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'ready'     => 'bg-green-50 text-green-700 border-green-200',
                            default     => 'bg-gray-50 text-gray-600 border-gray-200',
                        };
                        $label = match($order->status) {
                            'pending'   => 'Menunggu',
                            'preparing' => 'Sedang Disiapkan',
                            'ready'     => 'Siap Diambil',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            default     => $order->status,
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badge }}">{{ $label }}</span>
                </div>
                <div class="space-y-1">
                    @foreach($order->items as $item)
                    <p class="text-sm font-semibold text-dark">{{ $item->menu_name }} <span class="text-gray-400 font-normal">x{{ $item->quantity }}</span></p>
                    @endforeach
                </div>
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <span class="text-xs text-gray-400">{{ $order->pickup_time }}</span>
                    <span class="text-sm font-bold text-brand-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Orders --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-dark">Pesanan Terakhir</h3>
            <a href="{{ route('orders') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">Lihat Semua →</a>
        </div>

        @if($recentOrders->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Order ID</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu Ambil</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-dark">
                                @foreach($order->items as $item)
                                    {{ $item->menu_name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->pickup_time }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($order->status) {
                                        'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'preparing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'ready'     => 'bg-green-50 text-green-700 border-green-200',
                                        'completed' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                        default     => 'bg-gray-50 text-gray-600 border-gray-200',
                                    };
                                    $label = match($order->status) {
                                        'pending'   => 'Menunggu',
                                        'preparing' => 'Sedang Disiapkan',
                                        'ready'     => 'Siap Diambil',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default     => $order->status,
                                    };
                                @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
            </svg>
            <p class="text-gray-400 font-medium">Belum ada pesanan</p>
            <a href="{{ route('preorder') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">
                Pesan sekarang →
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
