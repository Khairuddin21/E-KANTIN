@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
@endpush

@section('content')
<div class="space-y-6" x-data="dashboardPage()">

    {{-- Row 1: Welcome + Balance --}}
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
                <p class="text-3xl font-bold mt-1 tracking-tight" data-balance>Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                <button @click="showTopup = true" class="mt-4 inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Top Up Saldo
                </button>
            </div>
        </div>
    </div>

    {{-- Row 2: Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Total Pesanan</p>
                    <p class="text-xl font-bold text-dark">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Total Belanja</p>
                    <p class="text-xl font-bold text-dark">{{ $totalSpent > 0 ? 'Rp ' . number_format($totalSpent / 1000, 0, ',', '.') . 'K' : 'Rp 0' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Pesanan Aktif</p>
                    <p class="text-xl font-bold text-dark">{{ $activeOrders->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">Selesai</p>
                    <p class="text-xl font-bold text-dark">{{ $statusCounts['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Charts + Active Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Weekly Spending Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-dark">Aktivitas Belanja</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pengeluaran 7 hari terakhir</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Total minggu ini</p>
                    <p class="text-lg font-bold text-brand-500">Rp {{ number_format($weeklySpending->sum('amount'), 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        {{-- Order Status Donut --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <h3 class="font-bold text-dark mb-1">Status Pesanan</h3>
            <p class="text-xs text-gray-400 mb-4">Distribusi semua pesanan</p>
            @if($totalOrders > 0)
            <div class="relative flex justify-center">
                <div class="w-48 h-48">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                @php
                    $statusLabels = [
                        'pending' => ['Menunggu', 'bg-yellow-400'],
                        'preparing' => ['Disiapkan', 'bg-blue-400'],
                        'ready' => ['Siap Ambil', 'bg-green-400'],
                        'completed' => ['Selesai', 'bg-gray-400'],
                        'cancelled' => ['Batal', 'bg-red-400'],
                    ];
                @endphp
                @foreach($statusLabels as $key => [$label, $color])
                    @if($statusCounts[$key] > 0)
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $color }} shrink-0"></span>
                        <span class="text-xs text-gray-500">{{ $label }} <strong class="text-dark">{{ $statusCounts[$key] }}</strong></span>
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-48 text-center">
                <svg class="w-12 h-12 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"/></svg>
                <p class="text-xs text-gray-400">Belum ada data pesanan</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Row 4: Active Orders (compact accordion) --}}
    @if($activeOrders->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h3 class="font-bold text-dark">Pesanan Aktif</h3>
                <span class="w-6 h-6 bg-brand-500 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $activeOrders->count() }}</span>
            </div>
            <p class="text-xs text-gray-400">Klik untuk lihat detail</p>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($activeOrders as $order)
            <div class="group">
                {{-- Order row (clickable) --}}
                <button @click="toggleOrder({{ $order->id }})"
                        class="w-full flex items-center gap-4 px-5 sm:px-6 py-4 hover:bg-gray-50/50 transition-colors text-left">
                    {{-- Status indicator dot --}}
                    @php
                        $dotColor = match($order->status) {
                            'pending'   => 'bg-yellow-400',
                            'preparing' => 'bg-blue-400',
                            'ready'     => 'bg-green-400',
                            default     => 'bg-gray-400',
                        };
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
                            default     => $order->status,
                        };
                    @endphp
                    <div class="relative shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <span class="text-xs font-bold text-gray-500">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full {{ $dotColor }} ring-2 ring-white"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-dark truncate">
                            {{ $order->items->pluck('menu_name')->implode(', ') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->pickup_time }} · {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badge }} shrink-0 hidden sm:inline-block">{{ $label }}</span>
                    <p class="text-sm font-bold text-dark shrink-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <svg class="w-5 h-5 text-gray-300 shrink-0 transition-transform duration-200"
                         :class="openOrder === {{ $order->id }} ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                {{-- Expandable detail --}}
                <div x-show="openOrder === {{ $order->id }}"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     style="display: none;"
                     class="px-5 sm:px-6 pb-4">
                    <div class="ml-14 bg-gray-50 rounded-xl p-4 space-y-2">
                        @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-400 shrink-0"></span>
                                <span class="text-dark font-medium">{{ $item->menu_name }}</span>
                                <span class="text-gray-400">x{{ $item->quantity }}</span>
                            </div>
                            <span class="font-semibold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="flex items-center justify-between pt-2 mt-2 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-gray-500">Pembayaran:</span>
                                <span class="text-xs font-semibold {{ $order->payment_method === 'wallet' ? 'text-brand-600' : 'text-blue-600' }}">
                                    {{ $order->payment_method === 'wallet' ? 'Saldo' : 'Midtrans' }}
                                </span>
                            </div>
                            <span class="text-sm font-bold text-brand-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Row 5: Recent Orders --}}
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
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Order</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Waktu</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-dark truncate max-w-[200px]">
                                    @foreach($order->items as $item)
                                        {{ $item->menu_name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </td>
                            <td class="px-6 py-4 text-gray-500 hidden sm:table-cell">{{ $order->pickup_time }}</td>
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
                                        'preparing' => 'Disiapkan',
                                        'ready'     => 'Siap Ambil',
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
            <a href="{{ route('student.menu') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">
                Pesan sekarang →
            </a>
        </div>
        @endif
    </div>

    {{-- Top Up Modal --}}
    <div x-show="showTopup" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showTopup = false"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-5"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="showTopup = false">

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-dark">Top Up Saldo</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih nominal atau masukkan jumlah sendiri</p>
                </div>
                <button @click="showTopup = false" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Preset Amounts --}}
            <div class="grid grid-cols-3 gap-2">
                <template x-for="amt in [10000, 20000, 50000, 100000, 200000, 500000]" :key="amt">
                    <button @click="topupAmount = amt"
                            :class="topupAmount === amt ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-300'"
                            class="border-2 rounded-xl py-3 text-sm font-semibold transition-all duration-150">
                        <span x-text="'Rp ' + amt.toLocaleString('id-ID')"></span>
                    </button>
                </template>
            </div>

            {{-- Custom Amount --}}
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Atau masukkan nominal lain</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">Rp</span>
                    <input type="number"
                           x-model.number="topupAmount"
                           min="10000" max="1000000" step="1000"
                           placeholder="Min. 10.000"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-500 focus:ring-0 text-sm font-semibold text-dark placeholder-gray-300 transition-colors">
                </div>
                <p class="text-xs text-gray-400 mt-1">Minimal Rp 10.000 · Maksimal Rp 1.000.000</p>
            </div>

            {{-- Error Message --}}
            <p x-show="topupError" x-text="topupError" class="text-xs text-red-500 font-medium"></p>

            {{-- Success Message --}}
            <div x-show="topupSuccess" class="flex items-center gap-2 bg-green-50 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <span>Top up berhasil! Saldo telah ditambahkan.</span>
            </div>

            {{-- Submit Button --}}
            <button @click="processTopup()"
                    :disabled="topupLoading || topupAmount < 10000 || topupAmount > 1000000"
                    :class="topupLoading || topupAmount < 10000 || topupAmount > 1000000 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-brand-500 hover:bg-brand-600 text-white'"
                    class="w-full py-3 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <svg x-show="topupLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span x-text="topupLoading ? 'Memproses...' : 'Bayar Sekarang'"></span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function dashboardPage() {
    return {
        openOrder: null,
        showTopup: false,
        topupAmount: 0,
        topupLoading: false,
        topupError: '',
        topupSuccess: false,
        toggleOrder(id) {
            this.openOrder = this.openOrder === id ? null : id;
        },
        async processTopup() {
            this.topupError = '';
            this.topupSuccess = false;

            if (this.topupAmount < 10000 || this.topupAmount > 1000000) {
                this.topupError = 'Nominal harus antara Rp 10.000 - Rp 1.000.000';
                return;
            }

            this.topupLoading = true;

            try {
                const res = await fetch('{{ route("student.topup.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ amount: this.topupAmount }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.topupError = data.error || data.message || 'Terjadi kesalahan.';
                    this.topupLoading = false;
                    return;
                }

                this.topupLoading = false;

                const topupId = data.topup_id;

                window.snap.pay(data.snap_token, {
                    onSuccess: async (result) => {
                        try {
                            const confirmRes = await fetch('/student/topup/' + topupId + '/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            });
                            const confirmData = await confirmRes.json();

                            if (confirmData.status === 'paid') {
                                const balanceEl = document.querySelector('[data-balance]');
                                if (balanceEl) {
                                    balanceEl.textContent = 'Rp ' + Number(confirmData.balance).toLocaleString('id-ID');
                                }
                            }
                        } catch (e) {
                            // Fallback: reload to get updated balance
                        }

                        this.topupSuccess = true;
                        this.topupAmount = 0;
                        setTimeout(() => {
                            this.showTopup = false;
                            this.topupSuccess = false;
                            window.location.reload();
                        }, 1500);
                    },
                    onPending: (result) => {
                        this.topupError = 'Pembayaran tertunda. Silakan selesaikan pembayaran.';
                    },
                    onError: (result) => {
                        this.topupError = 'Pembayaran gagal. Silakan coba lagi.';
                    },
                    onClose: () => {
                        // User closed the popup without completing
                    }
                });
            } catch (e) {
                this.topupError = 'Gagal menghubungi server. Silakan coba lagi.';
                this.topupLoading = false;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Weekly Spending Bar Chart
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        const weeklyData = @json($weeklySpending);
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(d => d.day),
                datasets: [{
                    label: 'Pengeluaran',
                    data: weeklyData.map(d => d.amount),
                    backgroundColor: weeklyData.map((d, i) => i === weeklyData.length - 1 ? '#E8850A' : '#FED7AA'),
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1A1A1A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            title: function(items) {
                                return weeklyData[items[0].dataIndex].date;
                            },
                            label: function(ctx) {
                                return 'Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 500 }, color: '#9CA3AF' },
                        border: { display: false },
                    },
                    y: {
                        grid: { color: '#F3F4F6', drawBorder: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#9CA3AF',
                            callback: function(v) { return v >= 1000 ? (v / 1000) + 'K' : v; },
                            maxTicksLimit: 5,
                        },
                        border: { display: false },
                        beginAtZero: true,
                    }
                }
            }
        });
    }

    // Status Donut Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = @json($statusCounts);
        const colors = {
            pending: '#FBBF24', preparing: '#60A5FA',
            ready: '#34D399', completed: '#9CA3AF', cancelled: '#F87171'
        };
        const labels = {
            pending: 'Menunggu', preparing: 'Disiapkan',
            ready: 'Siap Ambil', completed: 'Selesai', cancelled: 'Batal'
        };
        const filtered = Object.entries(statusData).filter(([, v]) => v > 0);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: filtered.map(([k]) => labels[k]),
                datasets: [{
                    data: filtered.map(([, v]) => v),
                    backgroundColor: filtered.map(([k]) => colors[k]),
                    borderWidth: 0,
                    spacing: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1A1A1A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
});
</script>
@endpush
