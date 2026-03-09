<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Canteen Pre-Order System — Order your school canteen food before break time. Skip the queue, pay digitally, pick up fast.">
    <title>E-Canteen — Pre-Order Your Canteen Food</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#FFF7ED',
                            100: '#FFEDD5',
                            200: '#FED7AA',
                            300: '#FDBA74',
                            400: '#FB923C',
                            500: '#F97316',
                            600: '#EA580C',
                            700: '#C2410C',
                            800: '#9A3412',
                            900: '#7C2D12',
                            950: '#431407',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .hero-gradient { background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 40%, #FED7AA 100%); }
        .cta-gradient { background: linear-gradient(135deg, #F97316 0%, #EA580C 50%, #C2410C 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px -12px rgba(249,115,22,0.2); }
        .btn-hover { transition: all 0.3s ease; }
        .btn-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 24px -6px rgba(249,115,22,0.4); }
        .step-line { position: relative; }
        .step-line::after { content: ''; position: absolute; top: 32px; left: calc(50% + 40px); width: calc(100% - 80px); height: 2px; background: #FDBA74; }
        @media (max-width: 768px) { .step-line::after { display: none; } }
        .fade-up { opacity: 0; transform: translateY(30px); animation: fadeUp 0.6s ease forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .nav-blur { backdrop-filter: blur(16px) saturate(180%); -webkit-backdrop-filter: blur(16px) saturate(180%); }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased bg-white">

    {{-- ========================================= --}}
    {{-- NAVIGATION --}}
    {{-- ========================================= --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur bg-white/80 border-b border-gray-100/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="#" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:shadow-brand-500/50 transition-shadow duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">E-<span class="text-brand-500">Canteen</span></span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="text-sm font-semibold text-gray-600 hover:text-brand-600 transition-colors duration-200">Home</a>
                    <a href="#features" class="text-sm font-semibold text-gray-600 hover:text-brand-600 transition-colors duration-200">Features</a>
                    <a href="#how-it-works" class="text-sm font-semibold text-gray-600 hover:text-brand-600 transition-colors duration-200">How It Works</a>
                    <a href="#contact" class="text-sm font-semibold text-gray-600 hover:text-brand-600 transition-colors duration-200">Contact</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="/login" class="px-5 py-2.5 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-100 transition-colors duration-200">Login</a>
                    <a href="/register" class="btn-hover px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-lg shadow-brand-500/25">Register</a>
                </div>

                {{-- Mobile Menu Button --}}
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobileMenu" class="hidden md:hidden pb-6 border-t border-gray-100">
                <div class="flex flex-col gap-1 pt-4">
                    <a href="#home" class="px-4 py-2.5 text-sm font-semibold text-gray-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">Home</a>
                    <a href="#features" class="px-4 py-2.5 text-sm font-semibold text-gray-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">Features</a>
                    <a href="#how-it-works" class="px-4 py-2.5 text-sm font-semibold text-gray-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">How It Works</a>
                    <a href="#contact" class="px-4 py-2.5 text-sm font-semibold text-gray-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">Contact</a>
                    <div class="flex gap-3 mt-4 px-4">
                        <a href="/login" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">Login</a>
                        <a href="/register" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ========================================= --}}
    {{-- HERO SECTION --}}
    {{-- ========================================= --}}
    <section id="home" class="hero-gradient pt-28 lg:pt-36 pb-16 lg:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left Content --}}
                <div class="fade-up">
                    <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white/80 rounded-full shadow-sm border border-brand-100">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-brand-700 tracking-wide uppercase">Now Open for Pre-Orders</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-[1.1] tracking-tight">
                        Order Your Canteen Food
                        <span class="text-brand-500 block mt-1">Before Break Time</span>
                    </h1>

                    <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-xl">
                        Students and teachers can now pre-order food from the school canteen, skip the long queues, pay digitally, and pick up their meals right when break starts.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">
                        <a href="/register" class="btn-hover inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-white bg-brand-500 rounded-2xl hover:bg-brand-600 shadow-xl shadow-brand-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Order Now
                        </a>
                        <a href="#menu" class="btn-hover inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-gray-700 bg-white rounded-2xl hover:bg-gray-50 shadow-lg border border-gray-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            View Menu
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-8 mt-10 pt-8 border-t border-brand-200/50">
                        <div>
                            <div class="text-2xl font-extrabold text-gray-900">500+</div>
                            <div class="text-sm text-gray-500 font-medium">Active Users</div>
                        </div>
                        <div>
                            <div class="text-2xl font-extrabold text-gray-900">15+</div>
                            <div class="text-sm text-gray-500 font-medium">Canteen Stalls</div>
                        </div>
                        <div>
                            <div class="text-2xl font-extrabold text-gray-900">2k+</div>
                            <div class="text-sm text-gray-500 font-medium">Orders/Week</div>
                        </div>
                    </div>
                </div>

                {{-- Right Illustration --}}
                <div class="fade-up relative" style="animation-delay: 0.2s;">
                    <div class="relative">
                        {{-- Background decorations --}}
                        <div class="absolute -top-8 -right-8 w-72 h-72 bg-brand-300/30 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-8 -left-8 w-56 h-56 bg-brand-400/20 rounded-full blur-3xl"></div>

                        {{-- Main Card --}}
                        <div class="relative bg-white rounded-3xl shadow-2xl shadow-brand-900/10 p-6 sm:p-8 border border-brand-100/50">
                            {{-- Fake App UI --}}
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">Ahmad Rizki</div>
                                        <div class="text-xs text-gray-400">Student · Class 10A</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 bg-green-50 rounded-lg">
                                    <span class="text-xs font-bold text-green-600">Rp 85.000</span>
                                </div>
                            </div>

                            {{-- Order Card Inside --}}
                            <div class="bg-brand-50 rounded-2xl p-4 mb-4">
                                <div class="text-xs font-semibold text-brand-600 uppercase tracking-wider mb-3">Current Order</div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-2xl shadow-sm">🍛</div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">Nasi Goreng Spesial</div>
                                                <div class="text-xs text-gray-400">×1 · Extra pedas</div>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">15K</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-2xl shadow-sm">🧊</div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">Es Teh Manis</div>
                                                <div class="text-xs text-gray-400">×2</div>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">10K</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-green-800">Ready for Pickup!</div>
                                        <div class="text-xs text-green-600">Break 1 · Counter 3</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-lg">QR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- FEATURES SECTION --}}
    {{-- ========================================= --}}
    <section id="features" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-brand-50 rounded-full">
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Features</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Everything You Need for a
                    <span class="text-brand-500">Smarter Canteen</span>
                </h2>
                <p class="mt-4 text-lg text-gray-500">Our platform transforms the school canteen experience with powerful digital tools.</p>
            </div>

            {{-- Feature Cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Feature 1 --}}
                <div class="card-hover bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:border-brand-200">
                    <div class="w-14 h-14 bg-brand-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pre-Order Food</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Students can order food before break time from their classroom or anywhere in the school.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="card-hover bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:border-brand-200">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Skip The Queue</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Orders are prepared before you arrive. No more wasting break time standing in long lines.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="card-hover bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:border-brand-200">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Digital Payment</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Pay instantly using your virtual balance or scan QRIS code. No cash needed.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="card-hover bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:border-brand-200">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75H16.5v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Fast Pickup</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Just scan your QR order code at the counter. Grab your food and go in seconds.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- HOW IT WORKS --}}
    {{-- ========================================= --}}
    <section id="how-it-works" class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-brand-50 rounded-full">
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">How It Works</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Four Simple Steps to
                    <span class="text-brand-500">Your Meal</span>
                </h2>
                <p class="mt-4 text-lg text-gray-500">From browsing the menu to picking up your food — it's fast, easy, and organized.</p>
            </div>

            {{-- Steps --}}
            <div class="grid md:grid-cols-4 gap-8 lg:gap-12">
                {{-- Step 1 --}}
                <div class="text-center step-line">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-500 rounded-2xl shadow-xl shadow-brand-500/30 mb-5 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Browse Menu</h3>
                    <p class="text-sm text-gray-500">Explore available food and drinks from all canteen stalls.</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center step-line">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-500 rounded-2xl shadow-xl shadow-brand-500/30 mb-5 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Place Order</h3>
                    <p class="text-sm text-gray-500">Add items to your cart, choose pickup time, and pay digitally.</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center step-line">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-500 rounded-2xl shadow-xl shadow-brand-500/30 mb-5 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1.001A3.75 3.75 0 0012 18z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kitchen Prepares</h3>
                    <p class="text-sm text-gray-500">The canteen starts cooking your order before break time begins.</p>
                </div>

                {{-- Step 4 --}}
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-500 rounded-2xl shadow-xl shadow-brand-500/30 mb-5 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 4</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pick Up & Enjoy</h3>
                    <p class="text-sm text-gray-500">Show your QR code at the counter, grab your food, and enjoy break time.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- PREVIEW MENU SECTION --}}
    {{-- ========================================= --}}
    <section id="menu" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-brand-50 rounded-full">
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Menu Preview</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Today's
                    <span class="text-brand-500">Popular Menu</span>
                </h2>
                <p class="mt-4 text-lg text-gray-500">Delicious meals prepared fresh by our canteen stalls every day.</p>
            </div>

            {{-- Menu Cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Menu Item 1 --}}
                <div class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group">
                    <div class="h-52 bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center overflow-hidden">
                        <span class="text-8xl group-hover:scale-110 transition-transform duration-500">🍛</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-brand-50 text-brand-600 rounded-full">Rice</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-green-50 text-green-600 rounded-full">Available</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Nasi Goreng Spesial</h3>
                        <p class="text-sm text-gray-400 mt-1">Fried rice with chicken, egg, and crispy crackers</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <span class="text-xl font-extrabold text-brand-600">Rp 15.000</span>
                            <button class="btn-hover px-5 py-2.5 text-sm font-bold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-lg shadow-brand-500/25">
                                + Order
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Menu Item 2 --}}
                <div class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group">
                    <div class="h-52 bg-gradient-to-br from-yellow-100 to-yellow-200 flex items-center justify-center overflow-hidden">
                        <span class="text-8xl group-hover:scale-110 transition-transform duration-500">🍜</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-yellow-50 text-yellow-600 rounded-full">Noodle</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-green-50 text-green-600 rounded-full">Available</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Mie Ayam Bakso</h3>
                        <p class="text-sm text-gray-400 mt-1">Chicken noodle with meatball and savory broth</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <span class="text-xl font-extrabold text-brand-600">Rp 12.000</span>
                            <button class="btn-hover px-5 py-2.5 text-sm font-bold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-lg shadow-brand-500/25">
                                + Order
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Menu Item 3 --}}
                <div class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group">
                    <div class="h-52 bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center overflow-hidden">
                        <span class="text-8xl group-hover:scale-110 transition-transform duration-500">🍗</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-red-50 text-red-600 rounded-full">Chicken</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-green-50 text-green-600 rounded-full">Available</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Ayam Geprek Sambal</h3>
                        <p class="text-sm text-gray-400 mt-1">Crispy smashed chicken with spicy chili sambal</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <span class="text-xl font-extrabold text-brand-600">Rp 18.000</span>
                            <button class="btn-hover px-5 py-2.5 text-sm font-bold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-lg shadow-brand-500/25">
                                + Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- View All --}}
            <div class="text-center mt-12">
                <a href="/register" class="btn-hover inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-brand-600 bg-brand-50 rounded-2xl hover:bg-brand-100 transition-colors">
                    View Full Menu
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- BENEFITS SECTION --}}
    {{-- ========================================= --}}
    <section class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-brand-50 rounded-full">
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Benefits</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Built for
                    <span class="text-brand-500">Everyone</span>
                </h2>
                <p class="mt-4 text-lg text-gray-500">Every stakeholder in the school cafeteria ecosystem benefits from E-Canteen.</p>
            </div>

            {{-- Benefit Columns --}}
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Students --}}
                <div class="card-hover bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For Students</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">No more standing in long queues during break</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Order from classroom before break starts</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Cashless payment — no need to carry money</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Track order status in real-time</span>
                        </li>
                    </ul>
                </div>

                {{-- Teachers --}}
                <div class="card-hover bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For Teachers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Save valuable break time for rest</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Priority pickup options available</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Special teacher pricing on selected items</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Order history and digital receipts</span>
                        </li>
                    </ul>
                </div>

                {{-- Sellers --}}
                <div class="card-hover bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">For Canteen Sellers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Receive orders early — prepare food in advance</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Organized kitchen queue and batch cooking</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">Complete sales reports and analytics</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-600">No cash handling — digital payments only</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- CALL TO ACTION --}}
    {{-- ========================================= --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cta-gradient rounded-3xl p-10 sm:p-16 lg:p-20 text-center relative overflow-hidden">
                {{-- Decorative elements --}}
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
                <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white/5 rounded-full"></div>

                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Make Your Break Time<br>
                        <span class="text-brand-100">Faster and Smarter</span>
                    </h2>
                    <p class="mt-6 text-lg text-white/80 max-w-2xl mx-auto">
                        Join hundreds of students and teachers who already enjoy a queue-free canteen experience. Start ordering today.
                    </p>

                    <div class="flex flex-wrap justify-center gap-4 mt-10">
                        <a href="/register" class="btn-hover inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-brand-600 bg-white rounded-2xl hover:bg-brand-50 shadow-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Start Ordering
                        </a>
                        <a href="/register" class="btn-hover inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-white bg-white/20 rounded-2xl hover:bg-white/30 border border-white/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================= --}}
    <footer id="contact" class="bg-gray-900 text-gray-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">E-<span class="text-brand-400">Canteen</span></span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-sm">
                        E-Canteen is a modern pre-order system for school canteens. Students and teachers can order food digitally, pay cashless, and pick up during break time with zero wait.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Home</a></li>
                        <li><a href="#features" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">How It Works</a></li>
                        <li><a href="#menu" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Menu</a></li>
                    </ul>
                </div>

                {{-- Support --}}
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="mailto:support@ecanteen.sch.id" class="text-sm text-gray-400 hover:text-brand-400 transition-colors">support@ecanteen.sch.id</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} E-Canteen. All rights reserved.</p>
                <p class="text-sm text-gray-600">Made with <span class="text-brand-500">&hearts;</span> for school canteens</p>
            </div>
        </div>
    </footer>

    {{-- ========================================= --}}
    {{-- SCROLL NAVBAR EFFECT --}}
    {{-- ========================================= --}}
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-lg', 'shadow-gray-900/5');
            } else {
                navbar.classList.remove('shadow-lg', 'shadow-gray-900/5');
            }
        });

        // Close mobile menu on link click
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
