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

    {{-- ══════════ SUCCESS POPUP OVERLAY ══════════ --}}
    <div x-show="showSuccess" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">

        {{-- Backdrop with confetti particles --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- Confetti canvas --}}
        <canvas x-ref="confetti" class="absolute inset-0 w-full h-full pointer-events-none z-10"></canvas>

        {{-- Card --}}
        <div x-show="showSuccess"
             x-transition:enter="transition ease-[cubic-bezier(.34,1.56,.64,1)] duration-500 delay-100"
             x-transition:enter-start="opacity-0 scale-75 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative z-20 bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

            {{-- Green header wave --}}
            <div class="relative bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500 px-6 pt-10 pb-14 text-center text-white overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-20">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white rounded-full animate-pulse" style="animation-delay:.5s"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-white/10 rounded-full animate-ping" style="animation-duration:2s"></div>
                </div>
                <div class="relative">
                    {{-- Animated checkmark --}}
                    <div class="w-20 h-20 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full animate-ping" style="animation-duration:1.5s"></div>
                        <div class="relative w-20 h-20 bg-white/25 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-10 h-10 text-white success-checkmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path class="checkmark-path" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-extrabold tracking-tight">Pesanan Berhasil!</h2>
                    <p class="text-white/80 text-sm mt-1.5">Pembayaran kamu telah dikonfirmasi</p>
                </div>
            </div>

            {{-- Order info --}}
            <div class="px-6 -mt-6 relative z-10">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nomor Pesanan</span>
                        <span class="text-sm font-bold text-dark font-mono" x-text="successData.orderCode"></span>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Bayar</span>
                        <span class="text-lg font-extrabold text-emerald-600" x-text="successData.total"></span>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pengambilan</span>
                        <span class="text-sm font-semibold text-dark" x-text="successData.pickup"></span>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pembayaran</span>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                              :class="successData.method === 'wallet' ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'bg-blue-50 text-blue-700 border border-blue-200'"
                              x-text="successData.method === 'wallet' ? 'Saldo E-Canteen' : 'Midtrans'"></span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 pt-5 pb-6 space-y-3">
                <a :href="'/orders'" class="flex items-center justify-center gap-2 w-full px-5 py-3.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 active:scale-[0.98] transition-all shadow-sm shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
                    Lihat Pesanan Saya
                </a>
                <a href="{{ route('student.menu') }}" class="flex items-center justify-center gap-2 w-full px-5 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                    Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
    [x-cloak] { display: none !important; }

    /* Checkmark draw animation */
    .checkmark-path {
        stroke-dasharray: 30;
        stroke-dashoffset: 30;
        animation: drawCheck 0.6s ease 0.4s forwards;
    }
    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }
</style>
<script>
function checkoutPage() {
    return {
        pickupTime: '',
        paymentMethod: '',
        showModal: false,
        showSuccess: false,
        submitting: false,
        walletSufficient: {{ $user->balance >= $total ? 'true' : 'false' }},
        successData: { orderCode: '', total: '', pickup: '', method: '' },

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

        async submitWallet() {
            this.submitting = true;
            this.showModal = false;

            try {
                const formData = new FormData(document.getElementById('checkoutForm'));

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

                if (data.success) {
                    this.successData = {
                        orderCode: data.order_code,
                        total: data.total,
                        pickup: data.pickup,
                        method: data.method,
                    };
                    this.showSuccess = true;
                    this.$nextTick(() => this.launchConfetti());
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                this.submitting = false;
            }
        },

        async processMidtrans() {
            this.submitting = true;

            try {
                const formData = new FormData(document.getElementById('checkoutForm'));

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

                const orderId = data.order_id;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => {
                        this.successData = {
                            orderCode: '#' + String(orderId).padStart(5, '0'),
                            total: 'Rp {{ number_format($total, 0, ",", ".") }}',
                            pickup: this.pickupTime,
                            method: 'midtrans',
                        };
                        this.showSuccess = true;
                        this.$nextTick(() => this.launchConfetti());
                    },
                    onPending: (result) => {
                        this.successData = {
                            orderCode: '#' + String(orderId).padStart(5, '0'),
                            total: 'Rp {{ number_format($total, 0, ",", ".") }}',
                            pickup: this.pickupTime,
                            method: 'midtrans',
                        };
                        this.showSuccess = true;
                        this.$nextTick(() => this.launchConfetti());
                    },
                    onError: (result) => {
                        alert('Pembayaran gagal. Pesanan dibatalkan.');
                        this.cancelMidtransOrder(orderId, csrfToken);
                        this.submitting = false;
                    },
                    onClose: () => {
                        // User closed the popup without completing — cancel the order
                        this.cancelMidtransOrder(orderId, csrfToken);
                        this.submitting = false;
                    }
                });
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                this.submitting = false;
            }
        },

        async cancelMidtransOrder(orderId, csrfToken) {
            try {
                await fetch('/student/order/' + orderId + '/cancel', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                });
            } catch (e) {
                console.error('Failed to cancel order:', e);
            }
        },

        launchConfetti() {
            const canvas = this.$refs.confetti;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            const colors = ['#E8850A', '#34D399', '#60A5FA', '#F472B6', '#FBBF24', '#A78BFA'];
            const particles = [];

            for (let i = 0; i < 120; i++) {
                particles.push({
                    x: canvas.width / 2 + (Math.random() - 0.5) * 100,
                    y: canvas.height / 2,
                    vx: (Math.random() - 0.5) * 16,
                    vy: (Math.random() - 1) * 18 - 4,
                    w: Math.random() * 8 + 4,
                    h: Math.random() * 4 + 2,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    rotation: Math.random() * 360,
                    rotationSpeed: (Math.random() - 0.5) * 12,
                    opacity: 1,
                });
            }

            let frame = 0;
            const maxFrames = 120;

            function animate() {
                if (frame > maxFrames) {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    return;
                }
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach(p => {
                    p.x += p.vx;
                    p.vy += 0.35;
                    p.y += p.vy;
                    p.rotation += p.rotationSpeed;
                    p.opacity = Math.max(0, 1 - frame / maxFrames);

                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.rotation * Math.PI / 180);
                    ctx.globalAlpha = p.opacity;
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                    ctx.restore();
                });

                frame++;
                requestAnimationFrame(animate);
            }
            animate();
        }
    }
}
</script>
@endpush
