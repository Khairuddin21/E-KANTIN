<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard') — E-Canteen</title>
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
    <link rel="stylesheet" href="{{ asset('css/seller.css') }}">
    @stack('head')
</head>
<body class="font-sans bg-gray-50 text-dark antialiased">

    {{-- Mobile Overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-sidebar text-white z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
            <img src="{{ asset('img/Logo_SMK_Negeri_40_Jakarta.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <span class="text-lg font-bold tracking-tight">E-<span class="text-brand-400">Canteen</span></span>
                <p class="text-[10px] uppercase tracking-widest text-white/40 -mt-0.5">Seller Panel</p>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('seller.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('seller.menus') }}"
               class="sidebar-link {{ request()->routeIs('seller.menus*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
                Kelola Menu
            </a>
            <a href="{{ route('seller.orders') }}"
               class="sidebar-link {{ request()->routeIs('seller.orders*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
                Antrian Pesanan
            </a>
            <a href="{{ route('seller.reports') }}"
               class="sidebar-link {{ request()->routeIs('seller.reports*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                </svg>
                Laporan Harian
            </a>
            <a href="{{ route('seller.wallet') }}"
               class="sidebar-link {{ request()->routeIs('seller.wallet*', 'seller.withdrawals*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>
                </svg>
                Dompet
            </a>
        </nav>

        {{-- Logout --}}
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

    {{-- Main Content --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Top Bar --}}
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-gray-100">
            <div class="flex items-center justify-between px-6 py-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                    </svg>
                </button>

                <h1 class="text-lg font-bold text-dark hidden lg:block">@yield('heading', 'Seller Dashboard')</h1>

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

        {{-- Page Content --}}
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

    <script src="{{ asset('js/seller.js') }}"></script>
    @stack('scripts')
</body>
</html>
