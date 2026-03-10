@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('heading', 'Kelola Pengguna')

@section('content')
<div x-data="userManagement()" x-cloak class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Pengguna</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['student'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Siswa</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['teacher'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Guru</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Aktif</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition">
            </div>
            <select name="role" onchange="this.form.submit()"
                class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 bg-white transition">
                <option value="">Semua Role</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Siswa</option>
                <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Guru</option>
            </select>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-dark rounded-xl hover:bg-gray-800 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($users->isEmpty())
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                <p class="text-gray-400 font-medium">Tidak ada data pengguna.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-3.5 font-medium">Nama</th>
                            <th class="px-6 py-3.5 font-medium">Email</th>
                            <th class="px-6 py-3.5 font-medium">Role</th>
                            <th class="px-6 py-3.5 font-medium">Status</th>
                            <th class="px-6 py-3.5 font-medium">Terdaftar</th>
                            <th class="px-6 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-dark">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roleBadge = $user->role === 'student'
                                        ? 'bg-indigo-50 text-indigo-600'
                                        : 'bg-purple-50 text-purple-600';
                                    $roleLabel = $user->role === 'student' ? 'Siswa' : 'Guru';
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $roleBadge }}">{{ $roleLabel }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-50 text-green-600">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-50 text-red-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $user->is_active ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <button @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
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

            <h3 class="text-lg font-bold text-dark text-center mb-2">Hapus Pengguna</h3>
            <p class="text-sm text-gray-500 text-center mb-6">Akun <span class="font-semibold text-dark" x-text="deleteName"></span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>

            <form :action="'/admin/users/' + deleteId" method="POST">
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
function userManagement() {
    return {
        deleteModal: false,
        deleteId: null,
        deleteName: '',
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
