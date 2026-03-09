@extends('layouts.seller')

@section('title', 'Laporan Harian')
@section('heading', 'Laporan Harian')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Laporan Harian</h2>
            <p class="text-gray-500 mt-1">Ringkasan penjualan tanggal <span class="font-medium text-dark">{{ $date->translatedFormat('d F Y') }}</span></p>
        </div>
        <form method="GET" action="{{ route('seller.reports') }}">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                   class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none"
                   onchange="this.form.submit()">
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Pesanan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $completedOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Pesanan Selesai</p>
        </div>

        <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-5 shadow-sm text-white">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
            </div>
            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-white/70 mt-1">Total Pendapatan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Hourly Revenue Chart --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-dark mb-4">Pendapatan Per Jam</h3>
            @if($hourlyRevenue->isEmpty())
                <div class="h-64 flex items-center justify-center text-gray-300 text-sm">Belum ada data</div>
            @else
                <div class="h-64">
                    <canvas id="hourlyChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Top Sold Items --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-dark">Menu Terlaris</h3>
            </div>
            @if($topItems->isEmpty())
                <div class="px-6 py-12 text-center text-gray-300 text-sm">Belum ada data</div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($topItems as $index => $item)
                    <div class="flex items-center justify-between px-6 py-3">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg {{ $index < 3 ? 'bg-brand-50 text-brand-500' : 'bg-gray-50 text-gray-400' }} text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                            <div>
                                <p class="text-sm font-medium text-dark">{{ $item->menu_name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->total_qty }} porsi terjual</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-dark">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!$hourlyRevenue->isEmpty())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyRevenue->keys()->values()) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($hourlyRevenue->values()) !!},
                backgroundColor: 'rgba(232, 133, 10, 0.15)',
                borderColor: '#E8850A',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return 'Rp ' + ctx.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: function(v) { return 'Rp ' + (v/1000) + 'k'; },
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
});
</script>
@endif
@endpush
