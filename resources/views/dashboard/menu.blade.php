@extends('layouts.dashboard')

@section('title', 'Menu Kantin')
@section('heading', 'Menu Kantin')

@section('content')
<div class="space-y-6" x-data="menuPage()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Menu Kantin</h2>
            <p class="text-gray-500 mt-1">Pilih menu favorit dan tambahkan ke keranjang.</p>
        </div>
        <a href="{{ route('student.cart') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-xl font-semibold text-sm hover:bg-brand-600 transition-colors shadow-sm relative">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.865l1.855-8.13A1.125 1.125 0 0 0 21.77 3H7.106"/></svg>
            Keranjang
            <span x-show="cartCount > 0" x-text="cartCount" x-transition
                  class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"></span>
        </a>
    </div>

    {{-- Search & Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="text" x-model="search" placeholder="Cari menu..." autocomplete="off"
                   class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all shadow-sm">
        </div>
        <div class="flex gap-2 flex-wrap">
            <button @click="category = ''" :class="category === '' ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                Semua
            </button>
            <template x-for="cat in ['makanan', 'minuman', 'snack']" :key="cat">
                <button @click="category = cat" :class="category === cat ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-4 py-2.5 rounded-xl text-sm font-medium transition-colors capitalize" x-text="cat">
                </button>
            </template>
        </div>
    </div>

    {{-- Menu Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($menus as $menu)
        <div class="menu-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:scale-[1.02] transition-all duration-300"
             x-show="matchesFilter('{{ strtolower($menu->name) }}', '{{ $menu->category }}')"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Image --}}
            <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                @if($menu->image)
                    @php $imgSrc = str_starts_with($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image); @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                    </div>
                @endif

                {{-- Category Badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-black/60 text-white capitalize backdrop-blur-sm">{{ $menu->category }}</span>
                </div>

                {{-- Availability --}}
                @if(!$menu->is_available)
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <span class="px-4 py-2 bg-red-500 text-white text-sm font-bold rounded-xl">Habis</span>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-dark text-base leading-tight">{{ $menu->name }}</h3>
                @if($menu->description)
                    <p class="text-gray-400 text-xs mt-1 line-clamp-2">{{ $menu->description }}</p>
                @endif
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                    <p class="text-brand-500 font-bold text-lg">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    @if($menu->is_available)
                    <button @click="addToCart({{ $menu->id }})"
                            :disabled="addingId === {{ $menu->id }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-brand-500 text-white text-xs font-semibold rounded-xl hover:bg-brand-600 active:scale-95 transition-all disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span x-text="addingId === {{ $menu->id }} ? 'Menambah...' : 'Keranjang'"></span>
                    </button>
                    @else
                    <span class="text-xs font-semibold text-red-400">Tidak Tersedia</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Empty State --}}
    <div x-show="filteredEmpty" x-transition class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
        <p class="text-gray-400 font-medium">Menu tidak ditemukan.</p>
    </div>

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
function menuPage() {
    return {
        search: '',
        category: '',
        cartCount: {{ $cartCount ?? 0 }},
        addingId: null,
        toast: false,
        toastMsg: '',
        filteredEmpty: false,

        matchesFilter(name, cat) {
            const nameMatch = name.includes(this.search.toLowerCase());
            const catMatch = this.category === '' || cat === this.category;
            const match = nameMatch && catMatch;

            this.$nextTick(() => {
                const cards = document.querySelectorAll('.menu-card');
                let anyVisible = false;
                cards.forEach(c => { if (c.style.display !== 'none') anyVisible = true; });
                this.filteredEmpty = !anyVisible;
            });

            return match;
        },

        async addToCart(menuId) {
            this.addingId = menuId;
            try {
                const res = await fetch('{{ route("student.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ menu_id: menuId, quantity: 1 }),
                });
                const data = await res.json();
                if (res.ok) {
                    this.cartCount = data.cart_count;
                    this.showToast(data.message);
                } else {
                    this.showToast(data.message || 'Gagal menambahkan ke keranjang.');
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan.');
            }
            this.addingId = null;
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
