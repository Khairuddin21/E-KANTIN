@extends('layouts.admin')

@section('title', 'Kelola Penjual')
@section('heading', 'Kelola Penjual')

@section('content')
<div x-data="sellerManagement()" x-cloak class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Penjual</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['inactive'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Nonaktif</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.sellers') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email penjual..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
            </div>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-dark rounded-xl hover:bg-gray-800 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($sellers->isEmpty())
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                </svg>
                <p class="text-gray-400 font-medium">Tidak ada data penjual.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-3.5 font-medium">Penjual</th>
                            <th class="px-6 py-3.5 font-medium">Email</th>
                            <th class="px-6 py-3.5 font-medium">Total Menu</th>
                            <th class="px-6 py-3.5 font-medium">Total Pesanan</th>
                            <th class="px-6 py-3.5 font-medium">Pendapatan</th>
                            <th class="px-6 py-3.5 font-medium">Status</th>
                            <th class="px-6 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($sellers as $seller)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-dark">{{ $seller->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $seller->email }}</td>
                            <td class="px-6 py-4 font-medium text-dark">{{ $seller->menu_count ?? 0 }}</td>
                            <td class="px-6 py-4 font-medium text-dark">{{ $seller->order_count ?? 0 }}</td>
                            <td class="px-6 py-4 font-semibold text-dark">Rp {{ number_format($seller->revenue ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($seller->is_active)
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-50 text-green-600">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-50 text-red-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($seller->is_active)
                                        <form method="POST" action="{{ route('admin.sellers.deactivate', $seller) }}"
                                              onsubmit="return confirm('Nonaktifkan penjual {{ addslashes($seller->name) }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.sellers.approve', $seller) }}"
                                              onsubmit="return confirm('Aktifkan penjual {{ addslashes($seller->name) }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                    <button @click="viewMenus({{ $seller->id }}, '{{ addslashes($seller->name) }}')"
                                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        Lihat Menu
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $sellers->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- View Menus Modal --}}
    <div x-show="menuModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="menuModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10 max-h-[80vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200 delay-75"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-dark">Menu Penjual</h3>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="menuSellerName"></p>
                </div>
                <button @click="menuModal = false" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-show="menuLoading" class="py-8 text-center">
                <svg class="w-8 h-8 text-brand-400 animate-spin mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <p class="text-sm text-gray-400 mt-2">Memuat menu...</p>
            </div>

            <div x-show="!menuLoading && menus.length === 0" class="py-8 text-center">
                <p class="text-sm text-gray-400">Penjual ini belum memiliki menu.</p>
            </div>

            <div x-show="!menuLoading && menus.length > 0" class="space-y-3">
                <template x-for="menu in menus" :key="menu.id">
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                        <div class="w-12 h-12 rounded-xl bg-gray-200 overflow-hidden shrink-0">
                            <img :src="menu.image ? '/storage/' + menu.image : '/img/no-image.png'"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='/img/no-image.png'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-dark truncate" x-text="menu.name"></p>
                            <p class="text-xs text-gray-400" x-text="menu.category"></p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-brand-600" x-text="'Rp ' + Number(menu.price).toLocaleString('id-ID')"></p>
                            <span class="text-xs font-medium"
                                  :class="menu.is_available ? 'text-green-500' : 'text-red-400'"
                                  x-text="menu.is_available ? 'Tersedia' : 'Habis'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('head')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none !important;}</style>
@endpush

@push('scripts')
<script>
function sellerManagement() {
    return {
        menuModal: false,
        menuLoading: false,
        menuSellerName: '',
        menus: [],
        async viewMenus(sellerId, sellerName) {
            this.menuSellerName = sellerName;
            this.menus = [];
            this.menuLoading = true;
            this.menuModal = true;

            try {
                const res = await fetch('/admin/sellers/' + sellerId + '/menus', {
                    headers: { 'Accept': 'application/json' }
                });
                this.menus = await res.json();
            } catch (e) {
                this.menus = [];
            }
            this.menuLoading = false;
        }
    }
}
</script>
@endpush
@endsection
