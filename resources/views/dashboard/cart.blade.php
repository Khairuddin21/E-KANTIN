@extends('layouts.dashboard')

@section('title', 'Keranjang')
@section('heading', 'Keranjang Belanja')

@section('content')
<div class="space-y-6" x-data="cartPage()">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-dark">Keranjang Belanja</h2>
            <p class="text-gray-500 mt-1">Review pesanan sebelum checkout.</p>
        </div>
        <a href="{{ route('student.menu') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Lanjut Belanja
        </a>
    </div>

    @if($cartItems->isEmpty())
        {{-- Empty Cart --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.865l1.855-8.13A1.125 1.125 0 0 0 21.77 3H7.106"/>
            </svg>
            <p class="text-gray-400 font-medium text-lg">Keranjang masih kosong</p>
            <p class="text-gray-300 text-sm mt-1">Yuk pilih menu favorit kamu!</p>
            <a href="{{ route('student.menu') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold text-sm hover:bg-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                Lihat Menu
            </a>
        </div>
    @else
        {{-- Cart Items --}}
        <div class="space-y-4">
            @foreach($cartItems as $item)
            <div class="cart-item bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 hover:shadow-md transition-all duration-200" id="cart-item-{{ $item->id }}" x-data="{ removing: false }">
                <div class="flex gap-4">
                    {{-- Image --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                        @if($item->menu->image)
                            @php $imgSrc = str_starts_with($item->menu->image, 'http') ? $item->menu->image : asset('storage/' . $item->menu->image); @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $item->menu->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-dark text-sm sm:text-base">{{ $item->menu->name }}</h3>
                                <p class="text-brand-500 font-bold text-sm mt-0.5">Rp {{ number_format($item->menu->price, 0, ',', '.') }}</p>
                            </div>
                            <button @click="removing = true; removeItem({{ $item->id }})"
                                    :disabled="removing"
                                    class="p-1.5 text-gray-300 hover:text-red-400 hover:bg-red-50 rounded-lg transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </div>

                        {{-- Quantity + Subtotal --}}
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center gap-1">
                                <button @click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors {{ $item->quantity <= 1 ? 'opacity-30 pointer-events-none' : '' }}"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                                </button>
                                <span class="w-10 text-center text-sm font-semibold text-dark" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                <button @click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                            </div>
                            <p class="font-bold text-dark text-sm sm:text-base" id="subtotal-{{ $item->id }}">
                                Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Order Summary + Checkout --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6 sticky bottom-4">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-500 font-medium">Total Pembayaran</span>
                <span class="text-2xl font-bold text-dark" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2 mb-4 text-sm">
                <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/></svg>
                <span class="text-gray-400">Saldo kamu: <span class="font-semibold text-dark">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span></span>
            </div>
            <a href="{{ route('student.checkout') }}"
               class="block w-full text-center px-6 py-3.5 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 active:scale-[0.98] transition-all shadow-sm shadow-brand-500/20">
                Lanjut ke Checkout
            </a>
        </div>
    @endif

    {{-- Toast --}}
    <div x-show="toast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        <span class="text-sm font-medium" x-text="toastMsg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function cartPage() {
    return {
        toast: false,
        toastMsg: '',

        formatRp(n) {
            return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },

        async updateQty(cartId, qty) {
            if (qty < 1) return;
            if (qty > 20) return;
            try {
                const res = await fetch('{{ route("student.cart.update") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ cart_id: cartId, quantity: qty }),
                });
                const data = await res.json();
                if (res.ok) {
                    document.getElementById('qty-' + cartId).textContent = qty;
                    document.getElementById('subtotal-' + cartId).textContent = this.formatRp(data.subtotal);
                    document.getElementById('cart-total').textContent = this.formatRp(data.total);
                    if (typeof updateSidebarCartBadge === 'function') updateSidebarCartBadge(data.cart_count);
                    this.showToast(data.message);
                    // Reload to update button states
                    setTimeout(() => window.location.reload(), 300);
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan.');
            }
        },

        async removeItem(cartId) {
            try {
                const res = await fetch('{{ route("student.cart.remove") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ cart_id: cartId }),
                });
                const data = await res.json();
                if (res.ok) {
                    const el = document.getElementById('cart-item-' + cartId);
                    if (el) {
                        el.style.transition = 'all 0.3s ease';
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            el.remove();
                            document.getElementById('cart-total').textContent = this.formatRp(data.total);                            if (typeof updateSidebarCartBadge === 'function') updateSidebarCartBadge(data.cart_count);                            if (data.cart_count === 0) window.location.reload();
                        }, 300);
                    }
                    this.showToast(data.message);
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan.');
            }
        },

        showToast(msg) {
            this.toastMsg = msg;
            this.toast = true;
            setTimeout(() => this.toast = false, 2500);
        }
    }
}
</script>
@endpush
