@extends('layouts.admin')

@section('title', 'Kelola Penarikan')
@section('heading', 'Kelola Penarikan Saldo')

@section('content')
<div x-data="adminWithdrawals()" class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Pending</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Disetujui</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['paid'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Dibayar</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Ditolak</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.withdrawals') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penjual, bank, nomor rekening..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
            </div>
            <select name="status" onchange="this.form.submit()"
                class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 bg-white transition">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-dark rounded-xl hover:bg-gray-800 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($withdrawals->isEmpty())
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
                <p class="text-gray-400 font-medium">Tidak ada data penarikan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-3.5 font-medium">ID</th>
                            <th class="px-6 py-3.5 font-medium">Penjual</th>
                            <th class="px-6 py-3.5 font-medium">Jumlah</th>
                            <th class="px-6 py-3.5 font-medium">Bank</th>
                            <th class="px-6 py-3.5 font-medium">No. Rekening</th>
                            <th class="px-6 py-3.5 font-medium">Status</th>
                            <th class="px-6 py-3.5 font-medium">Tanggal</th>
                            <th class="px-6 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($withdrawals as $wd)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-dark">#{{ $wd->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-dark">{{ $wd->seller->name }}</div>
                                <div class="text-xs text-gray-400">{{ $wd->seller->email }}</div>
                            </td>
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
                            <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">{{ $wd->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($wd->status === 'pending')
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $wd) }}"
                                              onsubmit="return confirm('Setujui penarikan #{{ $wd->id }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                                Setujui
                                            </button>
                                        </form>
                                        <button @click="openReject({{ $wd->id }})" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            Tolak
                                        </button>
                                    @elseif($wd->status === 'approved')
                                        <form method="POST" action="{{ route('admin.withdrawals.paid', $wd) }}"
                                              onsubmit="return confirm('Tandai penarikan #{{ $wd->id }} sudah dibayar?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors">
                                                Tandai Dibayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $withdrawals->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Reject Modal --}}
    <div x-show="rejectModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="rejectModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10"
             x-transition:enter="transition ease-out duration-200 delay-75"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <h3 class="text-lg font-bold text-dark mb-4">Tolak Penarikan</h3>
            <p class="text-sm text-gray-500 mb-5">Penarikan <span class="font-semibold text-dark" x-text="'#' + rejectId"></span> akan ditolak dan saldo dikembalikan.</p>

            <form :action="'/admin/withdrawals/' + rejectId + '/reject'" method="POST">
                @csrf @method('PATCH')
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Catatan (Opsional)</label>
                    <textarea name="admin_note" rows="3" maxlength="500" placeholder="Alasan penolakan..."
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="rejectModal = false" class="flex-1 py-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors">
                        Tolak Penarikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('head')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none !important;}</style>
@endpush

@push('scripts')
<script>
function adminWithdrawals() {
    return {
        rejectModal: false,
        rejectId: null,
        openReject(id) {
            this.rejectId = id;
            this.rejectModal = true;
        }
    }
}
</script>
@endpush
@endsection
