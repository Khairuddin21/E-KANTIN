<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — E-Canteen</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#FFF8F0',
                            100: '#FFEDD5',
                            200: '#FED7AA',
                            300: '#FDBA74',
                            400: '#FB923C',
                            500: '#E8850A',
                            600: '#C2700A',
                            700: '#9A5508',
                        },
                        dark: '#1A1A1A',
                        sidebar: '#111318',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.9);
        }
        .sidebar-link.active {
            background: rgba(232, 133, 10, 0.15);
            color: #FB923C;
        }
        .sidebar-link.active svg { color: #FB923C; }

        .toast-notification {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 1.25rem;
            border-radius: 0.875rem;
            margin-bottom: 1.5rem;
            animation: toastSlideIn 0.4s ease, toastFadeOut 0.5s ease 4s forwards;
        }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toastFadeOut { to { opacity: 0; transform: translateY(-10px); } }
    </style>
    @stack('head')
</head>
<body class="font-sans bg-gray-50 text-dark antialiased">

    {{-- Mobile Overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-sidebar text-white z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
        <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
            <img src="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <span class="text-lg font-bold tracking-tight">E-<span class="text-brand-400">Canteen</span></span>
                <p class="text-[10px] uppercase tracking-widest text-white/40 -mt-0.5">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.withdrawals') }}"
               class="sidebar-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
                Penarikan Saldo
            </a>
        </nav>

        <div class="px-4 pb-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-400 hover:bg-red-500/10 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="lg:ml-64 min-h-screen flex flex-col">
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-gray-100">
            <div class="flex items-center justify-between px-6 py-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-dark hidden lg:block">@yield('heading', 'Admin Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-dark">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-8">
            @if(session('success'))
                <div class="toast-notification bg-green-50 border border-green-200 text-green-700">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="toast-notification bg-red-50 border border-red-200 text-red-700">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
