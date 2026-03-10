@extends('layouts.seller')

@section('title', 'Riwayat Penarikan')
@section('heading', 'Riwayat Penarikan')

@section('content')
<div x-data="withdrawalHistory()" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Riwayat Penarikan</h2>
            <p class="text-gray-500 mt-1">Daftar semua permintaan penarikan saldo Anda.</p>
        </div>
        <a href="{{ route('seller.wallet') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-500 hover:text-brand-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke Dompet
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" x-model="search" placeholder="Cari bank, nama, nomor rekening..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
            </div>
            <div class="flex gap-2">
                <button @click="filterStatus = ''" :class="filterStatus === '' ? 'bg-dark text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-4 py-2.5 text-xs font-medium rounded-xl transition-all duration-200">Semua</button>
                <button @click="filterStatus = 'pending'" :class="filterStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100'"
                    class="px-4 py-2.5 text-xs font-medium rounded-xl transition-all duration-200">Menunggu</button>
                <button @click="filterStatus = 'approved'" :class="filterStatus === 'approved' ? 'bg-blue-500 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'"
                    class="px-4 py-2.5 text-xs font-medium rounded-xl transition-all duration-200">Disetujui</button>
                <button @click="filterStatus = 'paid'" :class="filterStatus === 'paid' ? 'bg-green-500 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100'"
                    class="px-4 py-2.5 text-xs font-medium rounded-xl transition-all duration-200">Dibayar</button>
                <button @click="filterStatus = 'rejected'" :class="filterStatus === 'rejected' ? 'bg-red-500 text-white' : 'bg-red-50 text-red-600 hover:bg-red-100'"
                    class="px-4 py-2.5 text-xs font-medium rounded-xl transition-all duration-200">Ditolak</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($withdrawals->isEmpty())
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
                <p class="text-gray-400 font-medium">Belum ada riwayat penarikan.</p>
                <a href="{{ route('seller.wallet') }}" class="inline-block mt-3 text-sm text-brand-500 hover:text-brand-600 font-medium">← Kembali ke Dompet</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-3.5 font-medium">ID</th>
                            <th class="px-6 py-3.5 font-medium">Jumlah</th>
                            <th class="px-6 py-3.5 font-medium">Bank</th>
                            <th class="px-6 py-3.5 font-medium">No. Rekening</th>
                            <th class="px-6 py-3.5 font-medium">Status</th>
                            <th class="px-6 py-3.5 font-medium">Catatan</th>
                            <th class="px-6 py-3.5 font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($withdrawals as $wd)
                        <tr class="hover:bg-gray-50/50 transition-colors withdrawal-row"
                            data-status="{{ $wd->status }}"
                            data-search="{{ strtolower($wd->bank_name . ' ' . $wd->account_name . ' ' . $wd->account_number) }}">
                            <td class="px-6 py-4 font-medium text-dark">#{{ $wd->id }}</td>
                            <td class="px-6 py-4 font-semibold text-dark">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $wd->bank_name }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <div>{{ $wd->account_number }}</div>
                                <div class="text-xs text-gray-400">{{ $wd->account_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = ['pending'=>'bg-yellow-50 text-yellow-700','approved'=>'bg-blue-50 text-blue-700','rejected'=>'bg-red-50 text-red-600','paid'=>'bg-green-50 text-green-700'];
                                    $labels = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak','paid'=>'Dibayar'];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $colors[$wd->status] ?? '' }}">
                                    {{ $labels[$wd->status] ?? $wd->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs max-w-[200px] truncate">{{ $wd->admin_note ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">{{ $wd->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>

@push('head')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('scripts')
<script>
function withdrawalHistory() {
    return {
        search: '',
        filterStatus: '',
        init() {
            this.$watch('search', () => this.applyFilters());
            this.$watch('filterStatus', () => this.applyFilters());
        },
        applyFilters() {
            const rows = document.querySelectorAll('.withdrawal-row');
            const q = this.search.toLowerCase().trim();
            const status = this.filterStatus;

            rows.forEach(row => {
                const matchSearch = !q || row.dataset.search.includes(q);
                const matchStatus = !status || row.dataset.status === status;
                row.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }
    }
}
</script>
@endpush
@endsection
