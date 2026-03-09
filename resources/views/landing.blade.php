<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Canteen Pre-Order System — Order your school canteen food before break time.">
    <title>E-Canteen — Pre-Order Your Canteen Food</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800&family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
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
                            800: '#7C4308',
                            900: '#5C3206',
                        },
                        dark: '#1A1A1A',
                        muted: '#6B7280',
                    }
                }
            }
        }
    </script>

    <style>
        /* ================================================
           GOLDEN RATIO UTILITIES
           φ = 1.618 | 1/φ = 0.618
           Fibonacci spacing: 8, 13, 21, 34, 55, 89, 144
        ================================================ */

        :root {
            --phi: 1.618;
            --phi-inv: 0.618;
            --gold-sm: 13px;
            --gold-md: 21px;
            --gold-lg: 34px;
            --gold-xl: 55px;
            --gold-2xl: 89px;
            --gold-3xl: 144px;
        }

        /* ================================================
           ORGANIC BLOB SHAPES (for image containers)
        ================================================ */

        .blob-main {
            border-radius: 62% 38% 46% 54% / 60% 44% 56% 40%;
            overflow: hidden;
            position: relative;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.12)) drop-shadow(0 8px 16px rgba(0,0,0,0.08));
        }

        .blob-food {
            border-radius: 50% 50% 42% 58% / 55% 45% 55% 45%;
            overflow: hidden;
            position: relative;
            filter: drop-shadow(0 12px 28px rgba(0,0,0,0.12)) drop-shadow(0 4px 10px rgba(0,0,0,0.06));
        }

        /* ================================================
           ROTATING CIRCULAR TEXT
        ================================================ */

        .circular-text {
            animation: rotateText 20s linear infinite;
        }

        @keyframes rotateText {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ================================================
           LEAF / BOTANICAL DECORATIONS
        ================================================ */

        .leaf-float {
            animation: leafFloat 6s ease-in-out infinite;
        }

        .leaf-float-delay {
            animation: leafFloat 7s ease-in-out 1s infinite;
        }

        .leaf-float-slow {
            animation: leafFloat 9s ease-in-out 2s infinite;
        }

        @keyframes leafFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-8px) rotate(2deg); }
            50% { transform: translateY(-4px) rotate(-1deg); }
            75% { transform: translateY(-12px) rotate(3deg); }
        }



        /* ================================================
           SCROLL REVEAL ANIMATIONS
        ================================================ */

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.85);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        /* ================================================
           SMOOTH TRANSITIONS & INTERACTIONS
        ================================================ */

        .nav-blur {
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }

        .card-lift {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 32px 64px -16px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px -8px rgba(232, 133, 10, 0.45);
        }

        .btn-outline {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-outline:hover {
            background-color: #1A1A1A;
            color: #FFFFFF;
            border-color: #1A1A1A;
        }

        /* ================================================
           PARALLAX LAYERS
        ================================================ */

        .parallax-layer {
            will-change: transform;
            transition: transform 0.1s linear;
        }

        /* ================================================
           DECORATIVE LINE PATTERNS
        ================================================ */

        .ornament-line {
            width: 55px;
            height: 2px;
            background: #E8850A;
            display: inline-block;
            border-radius: 1px;
        }

        /* Golden Section Divider */
        .golden-divider {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .golden-divider::before,
        .golden-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #E2E8F0, transparent);
        }

        /* Custom Cursor Effect for Hero */
        .hero-section {
            position: relative;
            overflow: hidden;
        }

        /* Smooth Image Zoom on Hover */
        .img-zoom img {
            transition: transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .img-zoom:hover img {
            transform: scale(1.08);
        }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-white overflow-x-hidden">

    {{-- ============================================================ --}}
    {{-- NAVBAR --}}
    {{-- ============================================================ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur bg-white/90 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="#" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-dark tracking-tight">E-<span class="text-brand-500">Canteen</span></span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-10">
                    <a href="#home" class="nav-link text-sm font-medium text-muted hover:text-dark transition-colors duration-300 relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-brand-500 after:transition-all after:duration-300 hover:after:w-full">Home</a>
                    <a href="#menu" class="nav-link text-sm font-medium text-muted hover:text-dark transition-colors duration-300 relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-brand-500 after:transition-all after:duration-300 hover:after:w-full">Menu</a>
                    <a href="#features" class="nav-link text-sm font-medium text-muted hover:text-dark transition-colors duration-300 relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-brand-500 after:transition-all after:duration-300 hover:after:w-full">Features</a>
                    <a href="#how-it-works" class="nav-link text-sm font-medium text-muted hover:text-dark transition-colors duration-300 relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-brand-500 after:transition-all after:duration-300 hover:after:w-full">How It Works</a>
                    <a href="#contact" class="nav-link text-sm font-medium text-muted hover:text-dark transition-colors duration-300 relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-brand-500 after:transition-all after:duration-300 hover:after:w-full">Contact</a>
                </div>

                {{-- CTA --}}
                <div class="hidden lg:flex items-center gap-4">
                    <a href="/login" class="text-sm font-medium text-muted hover:text-dark transition-colors duration-300">Login</a>
                    <a href="/register" class="btn-primary px-6 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-full">
                        Order Now
                    </a>
                </div>

                {{-- Mobile Hamburger --}}
                <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobileMenu" class="hidden lg:hidden pb-6 border-t border-gray-100">
                <div class="flex flex-col gap-1 pt-4">
                    <a href="#home" class="px-4 py-3 text-sm font-medium text-muted hover:text-dark hover:bg-gray-50 rounded-lg transition-all">Home</a>
                    <a href="#menu" class="px-4 py-3 text-sm font-medium text-muted hover:text-dark hover:bg-gray-50 rounded-lg transition-all">Menu</a>
                    <a href="#features" class="px-4 py-3 text-sm font-medium text-muted hover:text-dark hover:bg-gray-50 rounded-lg transition-all">Features</a>
                    <a href="#how-it-works" class="px-4 py-3 text-sm font-medium text-muted hover:text-dark hover:bg-gray-50 rounded-lg transition-all">How It Works</a>
                    <a href="#contact" class="px-4 py-3 text-sm font-medium text-muted hover:text-dark hover:bg-gray-50 rounded-lg transition-all">Contact</a>
                    <div class="flex gap-3 mt-3 px-4">
                        <a href="/login" class="flex-1 text-center py-2.5 text-sm font-medium text-dark border border-gray-200 rounded-full hover:bg-gray-50 transition-colors">Login</a>
                        <a href="/register" class="flex-1 text-center py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-full">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{-- HERO SECTION
         Layout uses Golden Ratio: 38.2% text | 61.8% visual
         Image containers use organic blob shapes + leaf SVG overlays
    ============================================================ --}}
    <section id="home" class="hero-section pt-20 min-h-screen flex items-center bg-white relative pb-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-4 items-center min-h-[calc(100vh-80px)] py-12 lg:py-0">

                {{-- LEFT COLUMN — 38.2% (≈5/12 cols) --}}
                <div class="lg:col-span-5 relative z-10">

                    {{-- Rotating Circular Text --}}
                    <div class="absolute -left-6 top-1/2 -translate-y-1/2 w-[120px] h-[120px] opacity-[0.12] hidden xl:block">
                        <svg class="circular-text w-full h-full" viewBox="0 0 200 200">
                            <defs>
                                <path id="circlePath" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/>
                            </defs>
                            <text fill="#1A1A1A" font-size="14" font-weight="600" letter-spacing="6">
                                <textPath href="#circlePath">FRESH • FOOD • PRE-ORDER • CANTEEN •</textPath>
                            </text>
                        </svg>
                    </div>

                    {{-- Badge --}}
                    <div class="reveal-left inline-flex items-center gap-2 mb-6">
                        <span class="ornament-line"></span>
                        <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em]">Pre-Order System</span>
                    </div>

                    {{-- Heading — Golden ratio typography: 55px heading --}}
                    <h1 class="reveal-left font-serif text-[clamp(2.4rem,5vw,3.6rem)] font-bold text-dark leading-[1.15] tracking-tight" style="transition-delay: 0.1s;">
                        Order Fresh Food
                        <span class="block text-brand-500">Before Break.</span>
                    </h1>

                    {{-- Description --}}
                    <p class="reveal-left mt-6 text-base text-muted leading-relaxed max-w-md" style="transition-delay: 0.2s;">
                        Students and teachers can now pre-order meals from the school canteen, skip the long queues, and pick up right when break starts.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="reveal-left flex items-center gap-4 mt-10" style="transition-delay: 0.3s;">
                        <a href="/register" class="btn-primary inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-white bg-brand-500 rounded-full">
                            Order Now
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="#menu" class="btn-outline inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-dark border-2 border-dark rounded-full">
                            View Menu
                        </a>
                    </div>

                    {{-- Social Icons --}}
                    <div class="reveal-left flex items-center gap-5 mt-16" style="transition-delay: 0.4s;">
                        <a href="#" class="text-muted hover:text-dark transition-colors duration-300">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-muted hover:text-dark transition-colors duration-300">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="text-muted hover:text-dark transition-colors duration-300">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- RIGHT COLUMN — 61.8% (≈7/12 cols) — Image Composition --}}
                <div class="lg:col-span-7 relative flex items-center justify-center lg:justify-end">

                    {{-- IMAGE COMPOSITION --}}
                    <div class="relative w-full max-w-[600px] lg:max-w-none" id="heroImageGroup">

                        {{-- Leaf background — behind restaurant blob --}}
                        <img
                            src="https://i.pinimg.com/1200x/aa/79/84/aa79842f854316ff8e1ce866e8939df0.jpg"
                            alt=""
                            class="absolute z-0 pointer-events-none select-none"
                            style="
                                width: 72%;
                                top: -8%;
                                left: -4%;
                                opacity: 0.18;
                                mix-blend-mode: multiply;
                                filter: sepia(0.2) saturate(0.7);
                                transform: rotate(-12deg) scaleX(-1);
                            "
                            loading="eager"
                        />

                        {{-- Main blob — Restaurant / Background Image --}}
                        <div class="reveal-right blob-main img-zoom relative w-[85%] lg:w-[80%] ml-auto aspect-[4/5] z-[1]" style="transition-delay: 0.15s;">
                            <img
                                src="https://i.pinimg.com/1200x/4e/bf/2a/4ebf2ad7f05e83c3f0467e89c95dcce7.jpg"
                                alt="Restaurant interior with warm ambiance"
                                class="w-full h-full object-cover"
                                loading="eager"
                            />
                        </div>

                        {{-- Overlapping food blob --}}
                        <div class="reveal-scale blob-food img-zoom absolute -bottom-6 -left-4 sm:left-4 lg:-left-8 w-[50%] sm:w-[45%] aspect-square border-[5px] border-white z-10" style="transition-delay: 0.35s;">
                            <img
                                src="https://i.pinimg.com/736x/0c/b2/69/0cb269e7d4abea0809fab8b5a15295ee.jpg"
                                alt="Delicious fresh food plate"
                                class="w-full h-full object-cover"
                                loading="eager"
                            />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FEATURES --}}
    {{-- ============================================================ --}}
    <section id="features" class="py-24 lg:py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-xl mx-auto mb-20 reveal">
                <div class="golden-divider mb-5">
                    <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em] whitespace-nowrap">Why E-Canteen</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-dark leading-tight">
                    A Smarter Way to Get
                    <span class="text-brand-500">Your Meal</span>
                </h2>
                <p class="mt-4 text-muted text-base leading-relaxed">Powerful digital tools for a seamless school canteen experience.</p>
            </div>

            {{-- Feature Grid: 4 cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="card-lift reveal bg-white rounded-2xl p-7 border border-gray-100 group" style="transition-delay: 0s;">
                    <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-brand-500 transition-colors duration-500">
                        <svg class="w-6 h-6 text-brand-500 group-hover:text-white transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-dark mb-2">Pre-Order Meals</h3>
                    <p class="text-sm text-muted leading-relaxed">Order food before break time from your classroom — meals ready when you arrive.</p>
                </div>

                <div class="card-lift reveal bg-white rounded-2xl p-7 border border-gray-100 group" style="transition-delay: 0.1s;">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500 transition-colors duration-500">
                        <svg class="w-6 h-6 text-blue-500 group-hover:text-white transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-dark mb-2">Skip The Queue</h3>
                    <p class="text-sm text-muted leading-relaxed">No more wasting your break time standing in long lines at the canteen counter.</p>
                </div>

                <div class="card-lift reveal bg-white rounded-2xl p-7 border border-gray-100 group" style="transition-delay: 0.2s;">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 transition-colors duration-500">
                        <svg class="w-6 h-6 text-emerald-500 group-hover:text-white transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-dark mb-2">Digital Payment</h3>
                    <p class="text-sm text-muted leading-relaxed">Pay using virtual balance or QRIS code. Cashless, fast, and fully transparent.</p>
                </div>

                <div class="card-lift reveal bg-white rounded-2xl p-7 border border-gray-100 group" style="transition-delay: 0.3s;">
                    <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-violet-500 transition-colors duration-500">
                        <svg class="w-6 h-6 text-violet-500 group-hover:text-white transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75H16.5v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-dark mb-2">QR Pickup</h3>
                    <p class="text-sm text-muted leading-relaxed">Show your QR order code at the counter. Grab your food and go in seconds.</p>
                </div>
            </div>
        </div>

    </section>

    {{-- ============================================================ --}}
    {{-- HOW IT WORKS --}}
    {{-- ============================================================ --}}
    <section id="how-it-works" class="py-24 lg:py-32 bg-[#FAFAF8] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-xl mx-auto mb-20 reveal">
                <div class="golden-divider mb-5">
                    <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em] whitespace-nowrap">How It Works</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-dark leading-tight">
                    Four Simple Steps
                    <span class="text-brand-500">to Your Meal</span>
                </h2>
            </div>

            {{-- Steps --}}
            <div class="grid md:grid-cols-4 gap-10 lg:gap-14 relative">

                {{-- Connecting line --}}
                <div class="hidden md:block absolute top-[40px] left-[12.5%] right-[12.5%] h-[1px] bg-gradient-to-r from-brand-200 via-brand-300 to-brand-200 z-0"></div>

                <div class="reveal text-center relative z-10" style="transition-delay: 0s;">
                    <div class="inline-flex items-center justify-center w-[80px] h-[80px] bg-white rounded-full shadow-lg border border-gray-100 mb-6 mx-auto">
                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 01</div>
                    <h3 class="text-base font-bold text-dark mb-2">Browse Menu</h3>
                    <p class="text-sm text-muted leading-relaxed">Explore food and drinks from all canteen stalls in one app.</p>
                </div>

                <div class="reveal text-center relative z-10" style="transition-delay: 0.1s;">
                    <div class="inline-flex items-center justify-center w-[80px] h-[80px] bg-white rounded-full shadow-lg border border-gray-100 mb-6 mx-auto">
                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 02</div>
                    <h3 class="text-base font-bold text-dark mb-2">Place Order</h3>
                    <p class="text-sm text-muted leading-relaxed">Add to cart, choose pickup time, and pay digitally.</p>
                </div>

                <div class="reveal text-center relative z-10" style="transition-delay: 0.2s;">
                    <div class="inline-flex items-center justify-center w-[80px] h-[80px] bg-white rounded-full shadow-lg border border-gray-100 mb-6 mx-auto">
                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1.001A3.75 3.75 0 0012 18z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 03</div>
                    <h3 class="text-base font-bold text-dark mb-2">Kitchen Prepares</h3>
                    <p class="text-sm text-muted leading-relaxed">The canteen starts cooking before break time begins.</p>
                </div>

                <div class="reveal text-center relative z-10" style="transition-delay: 0.3s;">
                    <div class="inline-flex items-center justify-center w-[80px] h-[80px] bg-white rounded-full shadow-lg border border-gray-100 mb-6 mx-auto">
                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-brand-500 uppercase tracking-wider mb-2">Step 04</div>
                    <h3 class="text-base font-bold text-dark mb-2">Pick Up & Enjoy</h3>
                    <p class="text-sm text-muted leading-relaxed">Show QR code at the counter, grab food, enjoy break time.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- MENU PREVIEW --}}
    {{-- ============================================================ --}}
    <section id="menu" class="py-24 lg:py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-xl mx-auto mb-20 reveal">
                <div class="golden-divider mb-5">
                    <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em] whitespace-nowrap">Menu Preview</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-dark leading-tight">
                    Today's
                    <span class="text-brand-500">Popular Menu</span>
                </h2>
                <p class="mt-4 text-muted text-base leading-relaxed">Delicious meals prepared fresh by our canteen stalls every day.</p>
            </div>

            {{-- Menu Cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="card-lift reveal bg-white rounded-2xl overflow-hidden border border-gray-100 group" style="transition-delay: 0s;">
                    <div class="h-56 bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center overflow-hidden">
                        <span class="text-[6rem] group-hover:scale-110 transition-transform duration-700 ease-out">🍛</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-brand-50 text-brand-600 rounded-full">Rice</span>
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-600 rounded-full">Available</span>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-dark">Nasi Goreng Spesial</h3>
                        <p class="text-sm text-muted mt-1">Fried rice with chicken, egg, and crispy crackers</p>
                        <div class="flex items-center justify-between mt-5 pt-5 border-t border-gray-50">
                            <span class="text-xl font-bold text-brand-600">Rp 15.000</span>
                            <button class="btn-primary px-5 py-2.5 text-xs font-semibold text-white bg-brand-500 rounded-full">
                                + Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-lift reveal bg-white rounded-2xl overflow-hidden border border-gray-100 group" style="transition-delay: 0.1s;">
                    <div class="h-56 bg-gradient-to-br from-yellow-50 to-yellow-100 flex items-center justify-center overflow-hidden">
                        <span class="text-[6rem] group-hover:scale-110 transition-transform duration-700 ease-out">🍜</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-yellow-50 text-yellow-700 rounded-full">Noodle</span>
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-600 rounded-full">Available</span>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-dark">Mie Ayam Bakso</h3>
                        <p class="text-sm text-muted mt-1">Chicken noodle with meatball and savory broth</p>
                        <div class="flex items-center justify-between mt-5 pt-5 border-t border-gray-50">
                            <span class="text-xl font-bold text-brand-600">Rp 12.000</span>
                            <button class="btn-primary px-5 py-2.5 text-xs font-semibold text-white bg-brand-500 rounded-full">
                                + Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-lift reveal bg-white rounded-2xl overflow-hidden border border-gray-100 group" style="transition-delay: 0.2s;">
                    <div class="h-56 bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center overflow-hidden">
                        <span class="text-[6rem] group-hover:scale-110 transition-transform duration-700 ease-out">🍗</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-red-50 text-red-600 rounded-full">Chicken</span>
                            <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-600 rounded-full">Available</span>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-dark">Ayam Geprek Sambal</h3>
                        <p class="text-sm text-muted mt-1">Crispy smashed chicken with spicy chili sambal</p>
                        <div class="flex items-center justify-between mt-5 pt-5 border-t border-gray-50">
                            <span class="text-xl font-bold text-brand-600">Rp 18.000</span>
                            <button class="btn-primary px-5 py-2.5 text-xs font-semibold text-white bg-brand-500 rounded-full">
                                + Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- View All --}}
            <div class="text-center mt-14 reveal">
                <a href="/register" class="btn-outline inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-dark border-2 border-dark rounded-full">
                    Explore Full Menu
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- BENEFITS --}}
    {{-- ============================================================ --}}
    <section class="py-24 lg:py-32 bg-[#FAFAF8] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-xl mx-auto mb-20 reveal">
                <div class="golden-divider mb-5">
                    <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em] whitespace-nowrap">Benefits</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-dark leading-tight">
                    Built for
                    <span class="text-brand-500">Everyone</span>
                </h2>
                <p class="mt-4 text-muted text-base leading-relaxed">Every stakeholder in the school canteen ecosystem benefits.</p>
            </div>

            {{-- Benefit Columns (Golden Ratio spacing) --}}
            <div class="grid md:grid-cols-3 gap-8">

                {{-- Students --}}
                <div class="card-lift reveal bg-white rounded-2xl p-8 border border-gray-100" style="transition-delay: 0s;">
                    <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-dark mb-4">For Students</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-brand-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">No more standing in long queues</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-brand-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Order from classroom before break</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-brand-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Cashless — no need to carry money</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-brand-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Real-time order status tracking</span>
                        </li>
                    </ul>
                </div>

                {{-- Teachers --}}
                <div class="card-lift reveal bg-white rounded-2xl p-8 border border-gray-100" style="transition-delay: 0.1s;">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-dark mb-4">For Teachers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Save break time for rest</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Priority pickup options</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Special teacher pricing available</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Digital receipts & history</span>
                        </li>
                    </ul>
                </div>

                {{-- Sellers --}}
                <div class="card-lift reveal bg-white rounded-2xl p-8 border border-gray-100" style="transition-delay: 0.2s;">
                    <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-dark mb-4">For Canteen Sellers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Receive orders early, prepare ahead</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Organized kitchen queue system</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">Sales reports & analytics</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center mt-0.5 shrink-0">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-muted">No cash handling — digital only</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CTA BANNER --}}
    {{-- ============================================================ --}}
    <section class="py-24 lg:py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="reveal-scale relative rounded-[2rem] overflow-hidden">

                {{-- Background Image Container (not background-image, but actual img in container) --}}
                <div class="absolute inset-0">
                    <img
                        src="https://i.pinimg.com/1200x/4e/bf/2a/4ebf2ad7f05e83c3f0467e89c95dcce7.jpg"
                        alt=""
                        class="w-full h-full object-cover"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-dark/75"></div>
                </div>

                {{-- Content --}}
                <div class="relative text-center py-20 px-8 sm:px-16">

                    <h2 class="font-serif text-3xl sm:text-4xl lg:text-[2.8rem] font-bold text-white leading-tight max-w-2xl mx-auto">
                        Make Your Break Time Faster
                        <span class="text-brand-300">and Smarter</span>
                    </h2>
                    <p class="mt-5 text-white/70 text-base max-w-lg mx-auto leading-relaxed">
                        Join hundreds of students and teachers enjoying a queue-free canteen experience.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 mt-10">
                        <a href="/register" class="btn-primary inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-dark bg-white rounded-full hover:bg-brand-50">
                            Start Ordering
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="/register" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-white border border-white/30 rounded-full hover:bg-white/10 transition-colors duration-300">
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================ --}}
    <footer id="contact" class="bg-dark text-gray-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-12 gap-10 pb-12 border-b border-gray-800">

                {{-- Brand (Golden ratio: 5/12 columns) --}}
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-lg font-bold text-white">E-<span class="text-brand-400">Canteen</span></span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-sm">
                        A modern pre-order system for school canteens. Order food digitally, pay cashless, pick up with zero wait.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links (3/12) --}}
                <div class="md:col-span-3 md:col-start-7">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Home</a></li>
                        <li><a href="#menu" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Menu</a></li>
                        <li><a href="#features" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Features</a></li>
                        <li><a href="#how-it-works" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">How It Works</a></li>
                    </ul>
                </div>

                {{-- Support (4/12) --}}
                <div class="md:col-span-3">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Help Center</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Terms of Service</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="mailto:support@ecanteen.sch.id" class="text-sm text-gray-500 hover:text-brand-400 transition-colors duration-300">support@ecanteen.sch.id</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
                <p class="text-xs text-gray-600">&copy; {{ date('Y') }} E-Canteen. All rights reserved.</p>
                <p class="text-xs text-gray-600">Made with <span class="text-brand-400">&hearts;</span> for school canteens</p>
            </div>
        </div>
    </footer>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT
         - Scroll-based reveal animations (IntersectionObserver)
         - Navbar shadow on scroll
         - Mobile menu toggle
         - Parallax on hero images (mousemove)
         - Smooth scroll with golden ratio easing
    ============================================================ --}}
    <script>
    (function() {
        'use strict';

        /* ============================================
           1. SCROLL REVEAL — IntersectionObserver
        ============================================ */
        const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');

        const revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Stagger based on transition-delay already set in CSS
                    entry.target.classList.add('active');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        revealElements.forEach(function(el) {
            revealObserver.observe(el);
        });

        /* ============================================
           2. NAVBAR — Shadow on scroll
        ============================================ */
        const navbar = document.getElementById('navbar');
        let lastScrollY = 0;
        let ticking = false;

        function updateNavbar() {
            if (window.scrollY > 30) {
                navbar.classList.add('shadow-md', 'border-b', 'border-gray-100');
            } else {
                navbar.classList.remove('shadow-md', 'border-b', 'border-gray-100');
            }
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            lastScrollY = window.scrollY;
            if (!ticking) {
                window.requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        });

        /* ============================================
           3. MOBILE MENU TOGGLE
        ============================================ */
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close on link click
            mobileMenu.querySelectorAll('a[href^="#"]').forEach(function(link) {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        }

        /* ============================================
           4. HERO PARALLAX — Mousemove on images
              Uses golden ratio for movement intensity:
              Main image:  moves at 1/φ² ≈ 0.382 factor
              Food image:  moves at 1/φ  ≈ 0.618 factor
        ============================================ */
        const heroImageGroup = document.getElementById('heroImageGroup');

        if (heroImageGroup && window.innerWidth >= 1024) {
            const PHI_INV = 0.618;
            const PHI_INV_SQ = 0.382;

            document.querySelector('.hero-section').addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const mouseX = e.clientX - rect.left - centerX;
                const mouseY = e.clientY - rect.top - centerY;

                // Normalize to -1 to 1
                const nx = mouseX / centerX;
                const ny = mouseY / centerY;

                // Main blob — slower, more subtle (φ² factor)
                const mainBlob = heroImageGroup.querySelector('.blob-main');
                if (mainBlob) {
                    mainBlob.style.transform = 'translate(' + (nx * 8 * PHI_INV_SQ) + 'px, ' + (ny * 8 * PHI_INV_SQ) + 'px)';
                }

                // Food blob — slightly more movement (φ factor)
                const foodBlob = heroImageGroup.querySelector('.blob-food');
                if (foodBlob) {
                    foodBlob.style.transform = 'translate(' + (nx * 14 * PHI_INV) + 'px, ' + (ny * 14 * PHI_INV) + 'px)';
                }
            });

            // Reset on mouse leave
            document.querySelector('.hero-section').addEventListener('mouseleave', function() {
                const mainBlob = heroImageGroup.querySelector('.blob-main');
                const foodBlob = heroImageGroup.querySelector('.blob-food');
                if (mainBlob) mainBlob.style.transform = 'translate(0, 0)';
                if (foodBlob) foodBlob.style.transform = 'translate(0, 0)';
            });
        }

        /* ============================================
           5. SMOOTH ANCHOR SCROLL
              Easing uses golden ratio sine curve
        ============================================ */
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('href');
                if (targetId === '#') return;

                var target = document.querySelector(targetId);
                if (target) {
                    var headerOffset = 80; // navbar height
                    var elementPosition = target.getBoundingClientRect().top + window.scrollY;
                    var offsetPosition = elementPosition - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        /* ============================================
           6. COUNTER ANIMATION (for stats if needed)
        ============================================ */
        function animateCounters() {
            document.querySelectorAll('[data-count]').forEach(function(counter) {
                var target = parseInt(counter.getAttribute('data-count'), 10);
                var duration = 2000; // ms
                var start = 0;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    // Golden ratio easing: ease-out using φ
                    var eased = 1 - Math.pow(1 - progress, 1.618);
                    counter.textContent = Math.floor(eased * target);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        counter.textContent = target;
                    }
                }

                window.requestAnimationFrame(step);
            });
        }

        /* ============================================
           7. SCROLL PROGRESS INDICATOR (optional)
        ============================================ */
        // Can be enabled if needed for a top progress bar

    })();
    </script>

</body>
</html>
