@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('heading', 'Kelola Kategori Menu')

@section('content')
<div x-data="categoryManagement()" x-cloak class="space-y-6">

    {{-- Header + Add Button --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-dark">Kategori Menu</h2>
            <p class="text-sm text-gray-400 mt-0.5">Kelola kategori untuk menu kantin</p>
        </div>
        <button @click="openCreate()" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Kategori
        </button>
    </div>

    {{-- Categories Grid --}}
    @if($categories->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-16 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
            </svg>
            <p class="text-gray-400 font-medium">Belum ada kategori.</p>
            <button @click="openCreate()" class="mt-4 text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors">Tambah kategori pertama →</button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $category)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 group hover:border-brand-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-dark">{{ $category->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $category->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button @click="openEdit({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            class="flex-1 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-center">
                        Edit
                    </button>
                    <button @click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            class="flex-1 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-center">
                        Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Create / Edit Modal --}}
    <div x-show="formModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="formModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10"
             x-transition:enter="transition ease-out duration-200 delay-75"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <h3 class="text-lg font-bold text-dark mb-5" x-text="editId ? 'Edit Kategori' : 'Tambah Kategori'"></h3>

            <form :action="editId ? '/admin/categories/' + editId : '{{ route('admin.categories.store') }}'" method="POST">
                @csrf
                <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Kategori</label>
                    <input type="text" name="name" x-model="formName" required maxlength="100" placeholder="contoh: Makanan"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
                </div>
                @if($errors->has('name'))
                    <p class="text-xs text-red-500 mb-4">{{ $errors->first('name') }}</p>
                @endif
                <div class="flex gap-3">
                    <button type="button" @click="formModal = false" class="flex-1 py-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors" x-text="editId ? 'Perbarui' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10"
             x-transition:enter="transition ease-out duration-200 delay-75"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
            </div>

            <h3 class="text-lg font-bold text-dark text-center mb-2">Hapus Kategori</h3>
            <p class="text-sm text-gray-500 text-center mb-6">Kategori <span class="font-semibold text-dark" x-text="deleteName"></span> akan dihapus.</p>

            <form :action="'/admin/categories/' + deleteId" method="POST">
                @csrf @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors">
                        Hapus
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
function categoryManagement() {
    return {
        formModal: false,
        editId: null,
        formName: '',
        deleteModal: false,
        deleteId: null,
        deleteName: '',
        openCreate() {
            this.editId = null;
            this.formName = '';
            this.formModal = true;
        },
        openEdit(id, name) {
            this.editId = id;
            this.formName = name;
            this.formModal = true;
        },
        confirmDelete(id, name) {
            this.deleteId = id;
            this.deleteName = name;
            this.deleteModal = true;
        }
    }
}
</script>
@endpush
@endsection
