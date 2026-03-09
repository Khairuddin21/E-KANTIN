<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — E-Canteen</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700&family=plus-jakarta-sans:300,400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#FFF8F0',
                            100: '#FFEDD5',
                            300: '#FDBA74',
                            400: '#FB923C',
                            500: '#E8850A',
                            600: '#C2700A',
                        },
                        dark: '#1A1A1A',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-brand-50 min-h-screen flex items-center justify-center px-4 py-12">

    {{-- Background decoration --}}
    <img src="{{ asset('img/bunga pinggir.png') }}" alt=""
         class="fixed bottom-0 right-0 w-80 opacity-30 pointer-events-none select-none"
         style="transform: scaleX(-1);">
    <img src="{{ asset('img/bunga pinggir.png') }}" alt=""
         class="fixed top-0 left-0 w-64 opacity-20 pointer-events-none select-none"
         style="transform: rotate(180deg);">

    <div class="relative w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl shadow-brand-500/10 px-8 py-10 sm:px-10">

            {{-- Logo + Brand --}}
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="flex items-center gap-3 group mb-1">
                    <img src="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}"
                         alt="SMK Negeri 40 Jakarta"
                         class="w-12 h-12 object-contain group-hover:scale-110 transition-transform duration-300">
                    <span class="text-xl font-bold text-dark tracking-tight">E-<span class="text-brand-500">Canteen</span></span>
                </a>
                <p class="text-sm text-gray-400 mt-1">Pre-Order System · SMK Negeri 40 Jakarta</p>
            </div>

            <h1 class="font-serif text-2xl font-bold text-dark text-center mb-6">Buat Akun Baru</h1>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Google Button --}}
            <a href="/auth/google/redirect"
               class="flex items-center justify-center gap-3 w-full border border-gray-200 rounded-xl py-3 px-4 text-sm font-semibold text-dark hover:bg-gray-50 transition-colors duration-200 mb-5">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>

            {{-- Divider --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium uppercase tracking-widest">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                    <input
                        type="text" id="name" name="name"
                        value="{{ old('name') }}"
                        required autocomplete="name"
                        placeholder="Nama kamu"
                        class="w-full px-4 py-3 text-sm border @error('name') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input
                        type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        required autocomplete="email"
                        placeholder="email@sekolah.com"
                        class="w-full px-4 py-3 text-sm border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                    >
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Daftar sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 px-4 py-3 border rounded-xl cursor-pointer transition-all duration-200
                            {{ old('role') === 'student' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-brand-300' }}">
                            <input type="radio" name="role" value="student" class="text-brand-500 focus:ring-brand-400"
                                {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-dark">Siswa</span>
                        </label>
                        <label class="flex items-center gap-2.5 px-4 py-3 border rounded-xl cursor-pointer transition-all duration-200
                            {{ old('role') === 'teacher' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-brand-300' }}">
                            <input type="radio" name="role" value="teacher" class="text-brand-500 focus:ring-brand-400"
                                {{ old('role') === 'teacher' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-dark">Guru</span>
                        </label>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Password</label>
                    <div class="relative">
                        <input
                            type="password" id="password" name="password"
                            required autocomplete="new-password"
                            placeholder="Min. 8 karakter"
                            class="w-full px-4 py-3 text-sm border @error('password') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition pr-11"
                        >
                        <button type="button" onclick="togglePass('password','eye1-open','eye1-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye1-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="eye1-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Konfirmasi Password</label>
                    <div class="relative">
                        <input
                            type="password" id="password_confirmation" name="password_confirmation"
                            required autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition pr-11"
                        >
                        <button type="button" onclick="togglePass('password_confirmation','eye2-open','eye2-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye2-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="eye2-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 active:scale-[0.98] transition-all duration-200 mt-2">
                    Daftar Sekarang
                </button>
            </form>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors">Masuk</a>
            </p>

            {{-- Back to landing --}}
            <div class="mt-4 text-center">
                <a href="/" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-dark transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePass(inputId, openId, closedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Highlight selected role card
        document.querySelectorAll('input[name="role"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="role"]').forEach(function(r) {
                    r.closest('label').classList.remove('border-brand-500', 'bg-brand-50');
                    r.closest('label').classList.add('border-gray-200');
                });
                this.closest('label').classList.remove('border-gray-200');
                this.closest('label').classList.add('border-brand-500', 'bg-brand-50');
            });
        });
    </script>
</body>
</html>
