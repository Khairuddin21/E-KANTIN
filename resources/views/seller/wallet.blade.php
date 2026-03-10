@extends('layouts.seller')

@section('title', 'Dompet Penjual')
@section('heading', 'Dompet Penjual')

@section('content')
<div x-data="walletPage()" class="space-y-8">

    {{-- Balance Hero Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 p-8 text-white shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>
                </svg>
                <p class="text-sm font-medium text-white/70 uppercase tracking-wider">Saldo Penjual</p>
            </div>
            <p class="text-4xl font-bold mt-2 tracking-tight">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
            <p class="text-sm text-white/50 mt-2">Saldo tersedia untuk penarikan</p>

            <button @click="showModal = true"
                class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-white text-brand-600 font-semibold text-sm rounded-xl hover:bg-white/90 active:scale-[0.97] transition-all duration-200 shadow-lg shadow-black/10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                </svg>
                Tarik Saldo
            </button>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Pendapatan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Dicairkan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu Proses</p>
        </div>
    </div>

    {{-- Recent Withdrawals --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-dark">Riwayat Penarikan Terakhir</h3>
            <a href="{{ route('seller.withdrawals') }}" class="text-sm text-brand-500 hover:text-brand-600 font-medium">Lihat Semua →</a>
        </div>

        @if($recentWithdrawals->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
                <p class="text-gray-400">Belum ada riwayat penarikan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Jumlah</th>
                            <th class="px-6 py-3 font-medium">Bank</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentWithdrawals as $wd)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-dark">#{{ $wd->id }}</td>
                            <td class="px-6 py-3 font-semibold text-dark">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $wd->bank_name }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $colors = ['pending'=>'bg-yellow-50 text-yellow-700','approved'=>'bg-blue-50 text-blue-700','rejected'=>'bg-red-50 text-red-600','paid'=>'bg-green-50 text-green-700'];
                                    $labels = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak','paid'=>'Dibayar'];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $colors[$wd->status] ?? '' }}">
                                    {{ $labels[$wd->status] ?? $wd->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-400 text-xs">{{ $wd->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Withdrawal Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false"></div>

        {{-- Modal Card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10"
             x-transition:enter="transition ease-out duration-200 delay-75"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-dark">Tarik Saldo</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-5 p-4 rounded-xl bg-brand-50 border border-brand-100">
                <p class="text-xs text-gray-500 mb-1">Saldo tersedia</p>
                <p class="text-xl font-bold text-brand-600">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
            </div>

            <form method="POST" action="{{ route('seller.withdraw.store') }}" @submit="submitForm($event)">
                @csrf

                {{-- Amount --}}
                <div class="mb-4">
                    <label for="amount" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Jumlah Penarikan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="number" id="amount" name="amount"
                            x-model="amount"
                            min="50000" max="{{ $wallet->balance }}" step="1000" required
                            placeholder="Min. 50.000"
                            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                            :class="amountError ? 'ring-2 ring-red-400 border-red-300' : ''">
                    </div>
                    <p x-show="amountError" x-text="amountError" class="text-xs text-red-500 mt-1"></p>
                </div>

                {{-- Bank Name --}}
                <div class="mb-4">
                    <label for="bank_name" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Bank</label>
                    <select id="bank_name" name="bank_name" required
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition bg-white">
                        <option value="">Pilih Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BSI">BSI</option>
                        <option value="CIMB Niaga">CIMB Niaga</option>
                        <option value="Permata">Permata</option>
                        <option value="Dana">Dana</option>
                        <option value="OVO">OVO</option>
                        <option value="GoPay">GoPay</option>
                        <option value="ShopeePay">ShopeePay</option>
                    </select>
                </div>

                {{-- Account Number --}}
                <div class="mb-4">
                    <label for="account_number" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nomor Rekening</label>
                    <input type="text" id="account_number" name="account_number" required maxlength="50"
                        placeholder="Nomor rekening / e-wallet"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
                </div>

                {{-- Account Name --}}
                <div class="mb-6">
                    <label for="account_name" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Pemilik Rekening</label>
                    <input type="text" id="account_name" name="account_name" required maxlength="100"
                        placeholder="Sesuai buku rekening"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading || !!amountError"
                    class="w-full py-3.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 active:scale-[0.98] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Memproses...' : 'Ajukan Penarikan'"></span>
                </button>
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
function walletPage() {
    return {
        showModal: false,
        amount: '',
        loading: false,
        get amountError() {
            if (!this.amount) return '';
            const val = parseInt(this.amount);
            const balance = {{ $wallet->balance }};
            if (isNaN(val)) return 'Masukkan angka yang valid.';
            if (val < 50000) return 'Minimum penarikan Rp 50.000.';
            if (val > balance) return 'Jumlah melebihi saldo tersedia.';
            return '';
        },
        submitForm(e) {
            if (this.amountError) {
                e.preventDefault();
                return;
            }
            this.loading = true;
        }
    }
}
</script>
@endpush
@endsection
