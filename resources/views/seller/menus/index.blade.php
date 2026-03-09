@extends('layouts.seller')

@section('title', 'Kelola Menu')
@section('heading', 'Kelola Menu')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Kelola Menu</h2>
            <p class="text-gray-500 mt-1">Tambah, edit, atau ubah status menu Anda.</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-xl font-semibold text-sm hover:bg-brand-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Menu
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('seller.menus') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('category') ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>
        @foreach(['makanan' => 'Makanan', 'minuman' => 'Minuman', 'snack' => 'Snack'] as $key => $label)
            <a href="{{ route('seller.menus', ['category' => $key]) }}"
               class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('category') === $key ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Menu Grid --}}
    @if($menus->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            <p class="text-gray-400 font-medium">Belum ada menu. Tambah menu pertama Anda!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($menus as $menu)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group">
                {{-- Image --}}
                <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                        </div>
                    @endif
                    {{-- Status Badge --}}
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $menu->is_available ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    {{-- Category Badge --}}
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-black/60 text-white capitalize">{{ $menu->category }}</span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-semibold text-dark text-base">{{ $menu->name }}</h3>
                    @if($menu->description)
                        <p class="text-gray-400 text-xs mt-1 line-clamp-2">{{ $menu->description }}</p>
                    @endif
                    <p class="text-brand-500 font-bold text-lg mt-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                        <button onclick="openEditModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', '{{ addslashes($menu->description ?? '') }}', {{ $menu->price }}, '{{ $menu->category }}')"
                                class="flex-1 px-3 py-2 text-xs font-medium text-brand-500 bg-brand-50 rounded-lg hover:bg-brand-100 transition-colors text-center">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('seller.menus.toggle', $menu) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full px-3 py-2 text-xs font-medium {{ $menu->is_available ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} rounded-lg transition-colors">
                                {{ $menu->is_available ? 'Habiskan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('seller.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Add / Edit Modal --}}
<div id="menuModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeMenuModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto relative">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h3 id="modalTitle" class="text-lg font-bold text-dark">Tambah Menu</h3>
                <button onclick="closeMenuModal()" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="menuForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div id="methodField"></div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Menu</label>
                    <div class="relative">
                        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(this)">
                        <div id="imagePreview" onclick="document.getElementById('imageInput').click()" class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center cursor-pointer hover:border-brand-300 hover:bg-brand-50/30 transition-colors overflow-hidden">
                            <div id="imagePlaceholder" class="text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                                <p class="text-xs text-gray-400">Klik untuk upload gambar</p>
                                <p class="text-[10px] text-gray-300 mt-0.5">JPG, PNG, WebP · Maks 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label for="menuName" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Menu <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="menuName" required placeholder="Contoh: Nasi Goreng Spesial"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all">
                </div>

                {{-- Description --}}
                <div>
                    <label for="menuDesc" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="menuDesc" rows="3" placeholder="Deskripsi singkat menu..."
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all resize-none"></textarea>
                </div>

                {{-- Price --}}
                <div>
                    <label for="menuPrice" class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" id="menuPrice" required min="0" placeholder="15000"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all">
                </div>

                {{-- Category --}}
                <div>
                    <label for="menuCategory" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                    <select name="category" id="menuCategory" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all">
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="snack">Snack</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeMenuModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 px-4 py-2.5 bg-brand-500 text-white rounded-xl font-semibold text-sm hover:bg-brand-600 transition-colors">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="fixed bottom-6 right-6 z-50 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 shadow-lg max-w-sm">
        <p class="font-semibold text-sm mb-1">Validasi gagal:</p>
        <ul class="text-xs space-y-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

@push('scripts')
<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Menu';
    document.getElementById('menuForm').action = '{{ route("seller.menus.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('menuName').value = '';
    document.getElementById('menuDesc').value = '';
    document.getElementById('menuPrice').value = '';
    document.getElementById('menuCategory').value = 'makanan';
    document.getElementById('submitBtn').textContent = 'Simpan Menu';
    resetImagePreview();
    document.getElementById('menuModal').classList.remove('hidden');
}

function openEditModal(id, name, desc, price, category) {
    document.getElementById('modalTitle').textContent = 'Edit Menu';
    document.getElementById('menuForm').action = '/seller/menus/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('menuName').value = name;
    document.getElementById('menuDesc').value = desc;
    document.getElementById('menuPrice').value = price;
    document.getElementById('menuCategory').value = category;
    document.getElementById('submitBtn').textContent = 'Perbarui Menu';
    resetImagePreview();
    document.getElementById('menuModal').classList.remove('hidden');
}

function closeMenuModal() {
    document.getElementById('menuModal').classList.add('hidden');
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            placeholder.classList.add('hidden');
            // Remove existing preview img if any
            const existingImg = preview.querySelector('img');
            if (existingImg) existingImg.remove();
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover';
            preview.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function resetImagePreview() {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    const existingImg = preview.querySelector('img');
    if (existingImg) existingImg.remove();
    placeholder.classList.remove('hidden');
    document.getElementById('imageInput').value = '';
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMenuModal();
});
</script>
@endpush
