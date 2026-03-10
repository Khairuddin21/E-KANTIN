@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none !important;}</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Pengguna</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ number_format($totalSellers) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Penjual</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ number_format($ordersToday) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Pesanan Hari Ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">Rp {{ number_format($platformRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Pendapatan</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- User Distribution Bar Chart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="mb-5">
                <h3 class="font-bold text-dark">Distribusi Pengguna</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pelanggan vs Penjual Kantin</p>
            </div>
            <div class="relative h-56">
                <canvas id="userDistChart"></canvas>
            </div>
        </div>

        {{-- Daily Orders Line Chart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="mb-5">
                <h3 class="font-bold text-dark">Pesanan Harian</h3>
                <p class="text-xs text-gray-400 mt-0.5">Jumlah pesanan 7 hari terakhir</p>
            </div>
            <div class="relative h-56">
                <canvas id="dailyOrdersChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.users') }}" class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-brand-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-dark group-hover:text-brand-600 transition-colors">Kelola Pengguna</p>
                    <p class="text-xs text-gray-400 mt-0.5">Siswa & Guru</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </a>
        <a href="{{ route('admin.sellers') }}" class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-brand-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-dark group-hover:text-brand-600 transition-colors">Kelola Penjual</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kantin</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </a>
        <a href="{{ route('admin.categories') }}" class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-brand-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-dark group-hover:text-brand-600 transition-colors">Kelola Kategori</p>
                    <p class="text-xs text-gray-400 mt-0.5">Menu</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </a>
        <a href="{{ route('admin.withdrawals') }}" class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-brand-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-dark group-hover:text-brand-600 transition-colors">Penarikan Saldo</p>
                    <p class="text-xs text-gray-400 mt-0.5">Keuangan</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartDefaults = {
        font: { family: 'Plus Jakarta Sans' },
    };

    // User Distribution Bar Chart
    const userCtx = document.getElementById('userDistChart');
    if (userCtx) {
        const dist = @json($userDistribution);
        new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: ['Pelanggan', 'Kantin'],
                datasets: [{
                    label: 'Jumlah',
                    data: [dist.pelanggan, dist.kantin],
                    backgroundColor: ['#60A5FA', '#E8850A'],
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 60,
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
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 12, weight: 600 }, color: '#6B7280' },
                        border: { display: false },
                    },
                    y: {
                        grid: { color: '#F3F4F6', drawBorder: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#9CA3AF',
                            stepSize: 1,
                            maxTicksLimit: 6,
                        },
                        border: { display: false },
                        beginAtZero: true,
                    }
                }
            }
        });
    }

    // Daily Orders Line Chart
    const ordersCtx = document.getElementById('dailyOrdersChart');
    if (ordersCtx) {
        const ordersData = @json($dailyOrders);
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: ordersData.map(d => d.day),
                datasets: [{
                    label: 'Pesanan',
                    data: ordersData.map(d => d.count),
                    borderColor: '#E8850A',
                    backgroundColor: 'rgba(232, 133, 10, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#E8850A',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                                return ordersData[items[0].dataIndex].date;
                            },
                            label: function(ctx) {
                                return ctx.raw + ' pesanan';
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
                            stepSize: 1,
                            maxTicksLimit: 6,
                        },
                        border: { display: false },
                        beginAtZero: true,
                    }
                }
            }
        });
    }
});
</script>
@endpush
