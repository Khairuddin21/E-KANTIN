<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E-Canteen</title>
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
<body class="font-sans min-h-screen flex items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- ===== LOCAL VIDEO BACKGROUND ===== --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none" id="videoBg">
        <video
            id="bgVideo"
            autoplay muted loop playsinline
            preload="auto"
            style="
                position: absolute;
                top: 50%; left: 50%;
                width: 100vw; height: 56.25vw;
                min-height: 100vh; min-width: 177.78vh;
                transform: translate(-50%, -50%);
                object-fit: cover;
                pointer-events: none;
                filter: blur(6px) brightness(0.85);
                transform: translate(-50%, -50%) scale(1.06);
            "
        >
            <source src="{{ asset('img/background login.mp4') }}" type="video/mp4">
        </video>
        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/55"></div>
        {{-- Subtle warm tint --}}
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(232,133,10,0.08) 0%, rgba(0,0,0,0) 60%);"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl px-8 py-10 sm:px-10" style="background: rgba(255,255,255,0.10); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.20); box-shadow: 0 24px 64px rgba(0,0,0,0.35);">

            {{-- Logo + Brand --}}
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="flex items-center gap-3 group mb-1">
                    <img src="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}"
                         alt="SMK Negeri 40 Jakarta"
                         class="w-12 h-12 object-contain group-hover:scale-110 transition-transform duration-300">
                    <span class="text-xl font-bold text-dark tracking-tight">E-<span class="text-brand-500">Canteen</span></span>
                </a>
                <p class="text-sm text-white/50 mt-1">Pre-Order System · SMK Negeri 40 Jakarta</p>
            </div>

            <h1 class="font-serif text-2xl font-bold text-white text-center mb-6">Selamat Datang Kembali</h1>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Google Button --}}
            <a href="/auth/google/redirect"
               class="flex items-center justify-center gap-3 w-full rounded-xl py-3 px-4 text-sm font-semibold text-white hover:bg-white/20 transition-colors duration-200 mb-5" style="border: 1px solid rgba(255,255,255,0.30); background: rgba(255,255,255,0.10);">
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
                <div class="flex-1 h-px bg-white/20"></div>
                <span class="text-xs text-white/40 font-medium uppercase tracking-widest">atau</span>
                <div class="flex-1 h-px bg-white/20"></div>
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-white/70 mb-1.5 uppercase tracking-wide">Email</label>
                    <input
                        type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        required autocomplete="email"
                        placeholder="email@sekolah.com"
                        class="w-full px-4 py-3 text-sm text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition @error('email') ring-2 ring-red-400 @enderror"
                        style="background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.20);"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-white/70 mb-1.5 uppercase tracking-wide">Password</label>
                    <div class="relative">
                        <input
                            type="password" id="password" name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 text-sm text-white placeholder-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition pr-11"
                            style="background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.20);"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/80">
                            <svg id="eye-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-white/60 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/30 text-brand-500 focus:ring-brand-400">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 active:scale-[0.98] transition-all duration-200 mt-2">
                    Masuk
                </button>
            </form>

            {{-- Register link --}}
            <p class="text-center text-sm text-white/60 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-brand-300 hover:text-brand-400 transition-colors">Daftar sekarang</a>
            </p>

            {{-- Back to landing --}}
            <div class="mt-4 text-center">
                <a href="/" class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-white/80 transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
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
    </script>

    {{-- ===== LOCAL VIDEO AUTOPLAY HANDLER ===== --}}
    <script>
    (function() {
        var video = document.getElementById('bgVideo');
        if (!video) return;

        video.muted = true;
        video.playbackRate = 1.0;

        function tryPlay() {
            var p = video.play();
            if (p !== undefined) {
                p.catch(function() {
                    // autoplay blocked — try again on first user interaction
                    document.addEventListener('click', function handler() {
                        video.play();
                        document.removeEventListener('click', handler);
                    }, { once: true });
                    document.addEventListener('touchstart', function handler() {
                        video.play();
                        document.removeEventListener('touchstart', handler);
                    }, { once: true });
                });
            }
        }

        if (video.readyState >= 2) {
            tryPlay();
        } else {
            video.addEventListener('canplay', tryPlay, { once: true });
        }
    })();
    </script>
</body>
</html>
