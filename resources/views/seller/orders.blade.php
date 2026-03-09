@extends('layouts.seller')

@section('title', 'Antrian Pesanan')
@section('heading', 'Antrian Pesanan')

@section('content')
<div class="space-y-6">

    {{-- Header with filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Antrian Pesanan</h2>
            <p class="text-gray-500 mt-1">Kelola pesanan masuk dan perbarui status.</p>
        </div>
        <form method="GET" action="{{ route('seller.orders') }}" class="flex items-center gap-3">
            <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}"
                   class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none"
                   onchange="this.form.submit()">
            <select name="status" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            <p class="text-gray-400 font-medium">Tidak ada pesanan untuk tanggal ini.</p>
        </div>
    @else
        {{-- Orders grouped by pickup time --}}
        @foreach($orders as $pickupTime => $group)
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <span class="text-sm font-semibold text-brand-600">Ambil: {{ $pickupTime }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ $group->count() }} pesanan</span>
                <div class="flex-1 border-t border-gray-100"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($group as $order)
                @php
                    $statusColors = [
                        'pending'   => 'border-yellow-200 bg-yellow-50/30',
                        'preparing' => 'border-blue-200 bg-blue-50/30',
                        'ready'     => 'border-green-200 bg-green-50/30',
                        'completed' => 'border-gray-200 bg-gray-50/30',
                        'cancelled' => 'border-red-200 bg-red-50/30',
                    ];
                    $badgeColors = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'preparing' => 'bg-blue-100 text-blue-700',
                        'ready'     => 'bg-green-100 text-green-700',
                        'completed' => 'bg-gray-100 text-gray-600',
                        'cancelled' => 'bg-red-100 text-red-600',
                    ];
                    $statusLabels = [
                        'pending'   => 'Menunggu',
                        'preparing' => 'Diproses',
                        'ready'     => 'Siap Diambil',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                <div class="bg-white rounded-2xl border {{ $statusColors[$order->status] ?? 'border-gray-100' }} shadow-sm overflow-hidden">
                    {{-- Order Header --}}
                    <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100">
                        <div>
                            <span class="text-sm font-bold text-dark">#{{ $order->id }}</span>
                            <span class="text-gray-400 mx-2">·</span>
                            <span class="text-sm text-gray-500">{{ $order->user->name }}</span>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeColors[$order->status] ?? '' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>

                    {{-- Order Items --}}
                    <div class="px-5 py-3 space-y-2">
                        @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-brand-50 text-brand-500 text-xs font-bold flex items-center justify-center">{{ $item->quantity }}</span>
                                <span class="text-gray-700">{{ $item->menu_name }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Order Footer --}}
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400">Total</span>
                            <p class="text-sm font-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        @if(!in_array($order->status, ['completed', 'cancelled']))
                        <div class="flex items-center gap-2">
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                        Proses
                                    </button>
                                </form>
                            @elseif($order->status === 'preparing')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                        Siap Diambil
                                    </button>
                                </form>
                            @elseif($order->status === 'ready')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                        Selesai
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('seller.orders.update', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                    Batal
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
