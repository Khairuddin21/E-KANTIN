@extends('layouts.dashboard')

@section('title', 'Edit Profil')
@section('heading', 'Edit Profil')

@section('content')
<div class="space-y-6" x-data="profilePage()">

    {{-- Success Toast --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-4 flex items-center gap-3 toast-dismiss">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Gradient Banner --}}
            <div class="h-32 bg-gradient-to-br from-brand-500 via-brand-400 to-brand-600 relative">
                <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-12 w-20 h-20 bg-white/5 rounded-full translate-y-1/3"></div>
            </div>

            {{-- Avatar + Name --}}
            <div class="px-6 sm:px-8 pb-6 -mt-14 relative">
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-5">
                    {{-- Avatar --}}
                    <div class="relative group cursor-pointer" @click="$refs.avatarInput.click()">
                        <div class="w-28 h-28 rounded-2xl bg-white border-4 border-white shadow-lg overflow-hidden">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" alt="Avatar" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!avatarPreview">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center">
                                        <span class="text-3xl font-bold text-brand-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                            </template>
                        </div>
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center border-4 border-transparent">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/>
                            </svg>
                        </div>
                        <input type="file" name="avatar" x-ref="avatarInput" accept="image/jpeg,image/png,image/webp" class="hidden" @change="previewAvatar($event)">
                    </div>

                    {{-- Name & role --}}
                    <div class="mt-2 sm:mt-0 sm:pb-1">
                        <h2 class="text-xl font-bold text-dark">{{ $user->name }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-2.5 py-0.5 rounded-full capitalize">{{ $user->role }}</span>
                            @if($user->class)
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">{{ $user->class }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Klik foto untuk mengganti avatar (maks. 2MB)</p>
                @error('avatar')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Info Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Personal Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="font-bold text-dark mb-1">Informasi Pribadi</h3>
                <p class="text-xs text-gray-400 mb-5">Data dasar profil kamu.</p>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-600 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-600 mb-1.5">Kelas</label>
                        <input type="text" name="class" id="class" value="{{ old('class', $user->class) }}" placeholder="Contoh: XII RPL 1"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                        @error('class')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Password Change --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="font-bold text-dark mb-1">Ubah Password</h3>
                <p class="text-xs text-gray-400 mb-5">Kosongkan jika tidak ingin mengubah password.</p>

                <div class="space-y-4">
                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-600 mb-1.5">Password Lama</label>
                        <div class="relative">
                            <input :type="showCurrentPw ? 'text' : 'password'" name="current_password" id="current_password" placeholder="Masukkan password saat ini"
                                   class="w-full px-4 py-3 pr-11 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                            <button type="button" @click="showCurrentPw = !showCurrentPw" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showCurrentPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                <svg x-show="showCurrentPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-600 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input :type="showNewPw ? 'text' : 'password'" name="new_password" id="new_password" placeholder="Minimal 8 karakter"
                                   class="w-full px-4 py-3 pr-11 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                            <button type="button" @click="showNewPw = !showNewPw" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showNewPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                <svg x-show="showNewPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Ulangi password baru"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark font-medium focus:ring-2 focus:ring-brand-400 focus:border-brand-400 focus:bg-white transition-all outline-none">
                    </div>
                </div>

                {{-- Account info --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Info Akun</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Role</span>
                            <span class="font-semibold text-dark capitalize">{{ $user->role }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Saldo</span>
                            <span class="font-semibold text-brand-600">Rp {{ number_format($user->balance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Bergabung</span>
                            <span class="font-medium text-dark">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-3 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 active:scale-[0.98] transition-all shadow-sm shadow-brand-500/20">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function profilePage() {
    return {
        avatarPreview: null,
        showCurrentPw: false,
        showNewPw: false,

        previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.avatarPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>
@endpush
