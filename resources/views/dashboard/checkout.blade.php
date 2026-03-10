@extends('layouts.dashboard')

@section('title', 'Checkout')
@section('heading', 'Checkout')

@section('content')
<div class="space-y-6" x-data="checkoutPage()">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-dark">Checkout</h2>
        <p class="text-gray-500 mt-1">Konfirmasi pesanan dan pilih metode pembayaran.</p>
    </div>

    {{-- Two-column desktop layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- LEFT: Order Items + Pickup Time + Payment Method --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-dark">Ringkasan Pesanan</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full font-medium">{{ $cartItems->sum('quantity') }} item</span>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4 px-5 sm:px-6 py-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                            @if($item->menu->image)
                                @php $imgSrc = str_starts_with($item->menu->image, 'http') ? $item->menu->image : asset('storage/' . $item->menu->image); @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $item->menu->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-dark">{{ $item->menu->name }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->quantity }}x @ Rp {{ number_format($item->menu->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-sm font-bold text-dark shrink-0">Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-600">Total Pembayaran</span>
                        <span class="text-xl font-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pickup Time --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="font-semibold text-dark mb-1">Waktu Pengambilan</h3>
                <p class="text-xs text-gray-400 mb-4">Pilih sesi istirahat untuk mengambil pesananmu.</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <button type="button" @click="pickupTime = 'Istirahat 1'"
                            :class="pickupTime === 'Istirahat 1' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 rounded-xl border text-center transition-all">
                        <p class="font-semibold text-dark text-sm">Istirahat 1</p>
                        <p class="text-xs text-gray-400 mt-0.5">10:00 – 10:30</p>
                    </button>
                    <button type="button" @click="pickupTime = 'Istirahat 2'"
                            :class="pickupTime === 'Istirahat 2' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 rounded-xl border text-center transition-all">
                        <p class="font-semibold text-dark text-sm">Istirahat 2</p>
                        <p class="text-xs text-gray-400 mt-0.5">12:00 – 12:30</p>
                    </button>
                    <button type="button" @click="pickupTime = 'Pulang'"
                            :class="pickupTime === 'Pulang' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 rounded-xl border text-center transition-all">
                        <p class="font-semibold text-dark text-sm">Pulang</p>
                        <p class="text-xs text-gray-400 mt-0.5">14:30 – 15:00</p>
                    </button>
                    <button type="button" @click="pickupTime = 'Sekarang'"
                            :class="pickupTime === 'Sekarang' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 rounded-xl border text-center transition-all">
                        <p class="font-semibold text-dark text-sm">Sekarang</p>
                        <p class="text-xs text-gray-400 mt-0.5">Langsung ambil</p>
                    </button>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="font-semibold text-dark mb-1">Metode Pembayaran</h3>
                <p class="text-xs text-gray-400 mb-4">Pilih cara bayar yang kamu inginkan.</p>
                <div class="space-y-3">

                    {{-- Wallet Option --}}
                    <button type="button" @click="paymentMethod = 'wallet'"
                            :class="paymentMethod === 'wallet' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50/50' : 'border-gray-200 hover:bg-gray-50'"
                            class="w-full flex items-center gap-4 p-4 rounded-xl border transition-all text-left">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                             :class="paymentMethod === 'wallet' ? 'bg-brand-100' : 'bg-gray-100'">
                            <svg class="w-6 h-6" :class="paymentMethod === 'wallet' ? 'text-brand-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-dark text-sm">Saldo E-Canteen</p>
                            <p class="text-xs text-gray-400 mt-0.5">Bayar langsung dari saldo virtual</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold {{ $user->balance >= $total ? 'text-green-600' : 'text-red-500' }}">Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                            @if($user->balance >= $total)
                                <p class="text-[10px] text-green-500 font-medium">Saldo cukup</p>
                            @else
                                <p class="text-[10px] text-red-400 font-medium">Saldo kurang</p>
                            @endif
                        </div>
                    </button>

                    {{-- Midtrans Option --}}
                    <button type="button" @click="paymentMethod = 'midtrans'"
                            :class="paymentMethod === 'midtrans' ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50/50' : 'border-gray-200 hover:bg-gray-50'"
                            class="w-full flex items-center gap-4 p-4 rounded-xl border transition-all text-left">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                             :class="paymentMethod === 'midtrans' ? 'bg-blue-100' : 'bg-gray-100'">
                            <svg class="w-6 h-6" :class="paymentMethod === 'midtrans' ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-dark text-sm">QRIS / Bank / E-Wallet</p>
                            <p class="text-xs text-gray-400 mt-0.5">GoPay, OVO, DANA, QRIS, Kartu Kredit/Debit</p>
                        </div>
                        <div class="shrink-0">
                            <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Midtrans</span>
                        </div>
                    </button>

                </div>
            </div>
        </div>

        {{-- RIGHT: Summary + Confirm --}}
        <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-24">

            {{-- Price breakdown --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="font-semibold text-dark mb-4">Detail Pembayaran</h3>

                {{-- Selected method indicator --}}
                <div class="flex items-center gap-2 p-3 rounded-lg bg-gray-50 mb-4">
                    <template x-if="paymentMethod === 'wallet'">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-brand-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-600">Saldo E-Canteen</span>
                        </div>
                    </template>
                    <template x-if="paymentMethod === 'midtrans'">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-600">QRIS / Bank / E-Wallet</span>
                        </div>
                    </template>
                    <template x-if="!paymentMethod">
                        <span class="text-xs text-gray-400">Pilih metode pembayaran</span>
                    </template>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
                        <span class="font-medium text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Biaya layanan</span>
                        <span class="font-medium text-green-600">Gratis</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="font-bold text-dark">Total Bayar</span>
                        <span class="text-lg font-bold text-brand-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Wallet balance note --}}
                <template x-if="paymentMethod === 'wallet'">
                    <div class="mt-4 p-3 rounded-lg {{ $user->balance >= $total ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs {{ $user->balance >= $total ? 'text-green-600' : 'text-red-600' }} font-medium">Sisa saldo setelah bayar</span>
                            <span class="text-sm font-bold {{ $user->balance >= $total ? 'text-green-700' : 'text-red-700' }}">Rp {{ number_format(max(0, $user->balance - $total), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Confirm Button --}}
            <form method="POST" action="{{ route('student.checkout.process') }}" id="checkoutForm">
                @csrf
                <input type="hidden" name="pickup_time" :value="pickupTime">
                <input type="hidden" name="payment_method" :value="paymentMethod">
                <button type="button" @click="confirmOrder()"
                        :disabled="!pickupTime || !paymentMethod || (paymentMethod === 'wallet' && !walletSufficient)"
                        class="w-full px-6 py-4 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 active:scale-[0.98] transition-all shadow-sm shadow-brand-500/20 disabled:opacity-40 disabled:cursor-not-allowed">
                    <span x-text="buttonLabel"></span>
                </button>
            </form>

            <a href="{{ route('student.cart') }}" class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Kembali ke Keranjang
            </a>
        </div>
    </div>

    {{-- Wallet Confirmation Modal --}}
    <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="scale-95" x-transition:enter-end="scale-100">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-dark">Konfirmasi Pembayaran</h3>
                <p class="text-sm text-gray-500 mt-2">Saldo sebesar <strong class="text-dark">Rp {{ number_format($total, 0, ',', '.') }}</strong> akan dipotong dari akun kamu.</p>
                <p class="text-sm text-gray-400 mt-1">Pengambilan: <strong class="text-dark" x-text="pickupTime"></strong></p>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="showModal = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">Batal</button>
                <button @click="submitWallet()" :disabled="submitting" class="flex-1 px-4 py-3 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 transition-colors disabled:opacity-50">
                    <span x-text="submitting ? 'Memproses...' : 'Bayar Sekarang'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function checkoutPage() {
    return {
        pickupTime: '',
        paymentMethod: '',
        showModal: false,
        submitting: false,
        walletSufficient: {{ $user->balance >= $total ? 'true' : 'false' }},

        get buttonLabel() {
            if (!this.pickupTime) return 'Pilih waktu pengambilan dulu';
            if (!this.paymentMethod) return 'Pilih metode pembayaran';
            if (this.paymentMethod === 'wallet' && !this.walletSufficient) return 'Saldo tidak cukup';
            return 'Bayar — Rp {{ number_format($total, 0, ",", ".") }}';
        },

        confirmOrder() {
            if (!this.pickupTime || !this.paymentMethod) return;

            if (this.paymentMethod === 'wallet') {
                if (!this.walletSufficient) return;
                this.showModal = true;
            } else {
                this.processMidtrans();
            }
        },

        submitWallet() {
            this.submitting = true;
            document.getElementById('checkoutForm').submit();
        },

        async processMidtrans() {
            this.submitting = true;

            try {
                const form = document.getElementById('checkoutForm');
                const formData = new FormData(form);

                const response = await fetch('{{ route("student.checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.error) {
                    alert(data.error);
                    this.submitting = false;
                    return;
                }

                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => {
                        window.location.href = '/student/order-success/' + data.order_id;
                    },
                    onPending: (result) => {
                        window.location.href = '/student/order-success/' + data.order_id;
                    },
                    onError: (result) => {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        this.submitting = false;
                    },
                    onClose: () => {
                        this.submitting = false;
                    }
                });
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                this.submitting = false;
            }
        }
    }
}
</script>
@endpush
