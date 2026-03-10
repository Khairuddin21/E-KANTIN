@extends('layouts.dashboard')

@section('title', 'Pre-Order Menu')
@section('heading', 'Pre-Order Menu')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Menu Tersedia</h2>
            <p class="text-sm text-gray-400 mt-1">Pilih menu dan pesan sebelum istirahat</p>
        </div>
        <div class="flex items-center gap-2 bg-white rounded-xl border border-gray-200 px-4 py-2.5 shadow-sm w-full sm:w-72">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <input type="text" id="searchMenu" placeholder="Cari menu..." class="bg-transparent text-sm w-full outline-none placeholder:text-gray-300">
        </div>
    </div>

    {{-- Menu Grid --}}
    @if($menus->count())
    <div id="menuGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($menus as $menu)
        <div class="menu-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer group" data-name="{{ strtolower($menu->name) }}">
            {{-- Image --}}
            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100">
                    <svg class="w-12 h-12 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0L3 16.5m18-12.75h-18"/>
                    </svg>
                </div>
                @endif
                <span class="absolute top-3 right-3 text-xs font-semibold bg-white/90 backdrop-blur-sm text-brand-600 px-2.5 py-1 rounded-full capitalize">{{ $menu->category }}</span>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-bold text-dark text-base">{{ $menu->name }}</h3>
                @if($menu->description)
                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $menu->description }}</p>
                @endif
                <div class="flex items-center justify-between mt-4">
                    <span class="text-lg font-bold text-brand-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <button onclick="openOrderModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                        class="inline-flex items-center gap-1.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all duration-200 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                        Pesan
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl p-16 shadow-sm border border-gray-100 text-center">
        <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
        </svg>
        <p class="text-gray-400 font-medium text-lg">Belum ada menu tersedia</p>
        <p class="text-gray-300 text-sm mt-1">Nantikan menu baru dari kantin</p>
    </div>
    @endif
</div>

{{-- Order Modal --}}
<div id="orderModal" class="fixed inset-0 z-50 hidden">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeOrderModal()"></div>

    {{-- Modal Content --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="orderModalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-dark">Buat Pesanan</h3>
                <button onclick="closeOrderModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('preorder.store') }}" id="orderForm">
                @csrf
                <input type="hidden" name="menu_id" id="modalMenuId">

                <div class="p-6 space-y-5">
                    {{-- Menu Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Menu</label>
                        <p id="modalMenuName" class="text-base font-bold text-dark"></p>
                        <p id="modalMenuPrice" class="text-sm text-brand-600 font-semibold mt-0.5"></p>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Jumlah</label>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="changeQty(-1)" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors active:scale-90">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                            </button>
                            <input type="number" name="quantity" id="modalQty" value="1" min="1" max="20" readonly
                                   class="w-16 text-center text-lg font-bold text-dark bg-transparent outline-none">
                            <button type="button" onclick="changeQty(1)" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors active:scale-90">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Pickup Time --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Waktu Pengambilan</label>
                        <select name="pickup_time" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition bg-white">
                            <option value="Istirahat 1">Istirahat 1</option>
                            <option value="Istirahat 2">Istirahat 2</option>
                        </select>
                    </div>

                    {{-- Total --}}
                    <div class="bg-brand-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total Pembayaran</span>
                            <span id="modalTotal" class="text-xl font-bold text-brand-600"></span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Saldo kamu: <span class="font-semibold text-dark">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-6 pt-0">
                    <button type="submit" id="submitOrder"
                        class="w-full py-3.5 text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2">
                        <span id="submitText">Pesan Sekarang</span>
                        <svg id="submitSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var currentPrice = 0;

    // Search menu
    document.getElementById('searchMenu').addEventListener('input', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('.menu-card').forEach(function(card) {
            card.style.display = card.dataset.name.includes(val) ? '' : 'none';
        });
    });

    function openOrderModal(menuId, menuName, price) {
        currentPrice = price;
        document.getElementById('modalMenuId').value = menuId;
        document.getElementById('modalMenuName').textContent = menuName;
        document.getElementById('modalMenuPrice').textContent = 'Rp ' + price.toLocaleString('id-ID') + ' / porsi';
        document.getElementById('modalQty').value = 1;
        updateTotal();

        var modal = document.getElementById('orderModal');
        var content = document.getElementById('orderModalContent');
        modal.classList.remove('hidden');
        setTimeout(function() {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeOrderModal() {
        var content = document.getElementById('orderModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(function() {
            document.getElementById('orderModal').classList.add('hidden');
        }, 200);
    }

    function changeQty(delta) {
        var input = document.getElementById('modalQty');
        var val = parseInt(input.value) + delta;
        if (val >= 1 && val <= 20) {
            input.value = val;
            updateTotal();
        }
    }

    function updateTotal() {
        var qty = parseInt(document.getElementById('modalQty').value);
        var total = currentPrice * qty;
        document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Loading on submit
    document.getElementById('orderForm').addEventListener('submit', function() {
        var btn = document.getElementById('submitOrder');
        btn.disabled = true;
        btn.classList.add('opacity-70');
        document.getElementById('submitText').textContent = 'Memproses...';
        document.getElementById('submitSpinner').classList.remove('hidden');
    });

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeOrderModal();
    });
</script>
@endpush
@endsection
