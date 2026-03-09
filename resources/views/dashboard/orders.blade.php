@extends('layouts.dashboard')

@section('title', 'Riwayat Pesanan')
@section('heading', 'Riwayat Pesanan')

@section('content')
<div class="max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Riwayat Pesanan</h2>
            <p class="text-sm text-gray-400 mt-1">Semua pesanan yang pernah kamu buat</p>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white rounded-xl border border-gray-200 px-4 py-2.5 shadow-sm">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                <input type="text" id="searchOrders" placeholder="Cari pesanan..." class="bg-transparent text-sm w-36 outline-none placeholder:text-gray-300">
            </div>
            <form method="GET" action="{{ route('orders') }}" id="filterForm">
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm shadow-sm outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition cursor-pointer">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Table --}}
    @if($orders->count())
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
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="ordersBody">
                    @foreach($orders as $order)
                    <tr class="order-row hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-medium text-dark">
                            @foreach($order->items as $item)
                                {{ $item->menu_name }} (x{{ $item->quantity }}){{ !$loop->last ? ', ' : '' }}
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
                        <td class="px-6 py-4 text-right text-gray-400 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-2xl p-16 shadow-sm border border-gray-100 text-center">
        <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
        </svg>
        <p class="text-gray-400 font-medium text-lg">Belum ada riwayat pesanan</p>
        <a href="{{ route('preorder') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">
            Mulai pesan sekarang →
        </a>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Client-side search filtering
    document.getElementById('searchOrders').addEventListener('input', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection
