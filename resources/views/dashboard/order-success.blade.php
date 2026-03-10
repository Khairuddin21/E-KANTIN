@extends('layouts.dashboard')

@section('title', 'Pesanan Berhasil')
@section('heading', 'Pesanan Berhasil')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Success Header --}}
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-6 py-10 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            <div class="relative">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h2 class="text-2xl font-bold">Pesanan Berhasil!</h2>
                <p class="text-white/80 text-sm mt-1">Pesananmu sedang diproses.</p>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <span class="text-sm text-gray-400">Nomor Pesanan</span>
                <span class="text-sm font-bold text-dark font-mono">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <span class="text-sm text-gray-400">Waktu Pengambilan</span>
                <span class="text-sm font-semibold text-dark">{{ $order->pickup_time }}</span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <span class="text-sm text-gray-400">Status</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border bg-yellow-50 text-yellow-700 border-yellow-200">Menunggu</span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <span class="text-sm text-gray-400">Total Pembayaran</span>
                <span class="text-lg font-bold text-brand-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Items --}}
            <div class="pt-2">
                <p class="text-sm font-semibold text-gray-600 mb-3">Detail Pesanan</p>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">{{ $item->menu_name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                        <span class="font-medium text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 space-y-3">
            <a href="{{ route('orders') }}" class="block w-full text-center px-6 py-3.5 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 transition-colors shadow-sm shadow-brand-500/20">
                Lihat Pesanan Saya
            </a>
            <a href="{{ route('student.menu') }}" class="block w-full text-center px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">
                Kembali ke Menu
            </a>
        </div>
    </div>
</div>
@endsection
