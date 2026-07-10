<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'ড্যাশবোর্ড') — সজীব নিউজ অ্যাডমিন</title>

    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="manifest" href="/manifest.json">

    @php $seoSettings = \App\Models\SeoSetting::first(); @endphp
    @if($seoSettings?->favicon)
        <link rel="icon" type="image/png" href="{{ storage_image_url($seoSettings->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Tailwind CSS (layout) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts (Inter only) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- SolaimanLipi Bengali Font (local) -->
    <style>
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi.ttf') format('truetype'); font-weight: 400; font-display: swap; }
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi-Bold.ttf') format('truetype'); font-weight: 700; font-display: swap; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Bootstrap 5 (inner content) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary":                  "#004e9f",
            "on-primary":               "#ffffff",
            "primary-container":        "#0066cc",
            "on-primary-container":     "#dfe8ff",
            "primary-fixed":            "#d7e3ff",
            "primary-fixed-dim":        "#aac7ff",
            "on-primary-fixed":         "#001b3e",
            "on-primary-fixed-variant": "#00458e",
            "secondary":                "#ab3500",
            "on-secondary":             "#ffffff",
            "secondary-container":      "#fe6a34",
            "on-secondary-container":   "#5d1900",
            "secondary-fixed":          "#ffdbd0",
            "secondary-fixed-dim":      "#ffb59d",
            "on-secondary-fixed":       "#390c00",
            "on-secondary-fixed-variant":"#832600",
            "tertiary":                 "#005e2c",
            "on-tertiary":              "#ffffff",
            "tertiary-container":       "#00793b",
            "on-tertiary-container":    "#98ffaf",
            "tertiary-fixed":           "#76fd9d",
            "tertiary-fixed-dim":       "#57df83",
            "on-tertiary-fixed":        "#00210b",
            "on-tertiary-fixed-variant":"#005226",
            "error":                    "#ba1a1a",
            "on-error":                 "#ffffff",
            "error-container":          "#ffdad6",
            "on-error-container":       "#93000a",
            "background":               "#fcf9f8",
            "on-background":            "#1c1b1b",
            "surface":                  "#fcf9f8",
            "on-surface":               "#1c1b1b",
            "surface-variant":          "#e5e2e1",
            "on-surface-variant":       "#414753",
            "surface-container-lowest": "#ffffff",
            "surface-container-low":    "#f6f3f2",
            "surface-container":        "#f0eded",
            "surface-container-high":   "#eae7e7",
            "surface-container-highest":"#e5e2e1",
            "surface-bright":           "#fcf9f8",
            "surface-dim":              "#dcd9d9",
            "surface-tint":             "#005cba",
            "outline":                  "#727784",
            "outline-variant":          "#c1c6d5",
            "inverse-surface":          "#313030",
            "inverse-on-surface":       "#f3f0ef",
            "inverse-primary":          "#aac7ff",
          },
          borderRadius: {
            "DEFAULT": "0.125rem",
            "lg":      "0.25rem",
            "xl":      "0.5rem",
            "full":    "0.75rem",
          },
          spacing: {
            "xs":   "4px",
            "sm":   "8px",
            "md":   "16px",
            "lg":   "24px",
            "xl":   "32px",
            "xxl":  "48px",
            "xxxl": "64px",
          },
          fontFamily: {
            "display": ["SolaimanLipi", "serif"],
            "sans":    ["Inter", "sans-serif"],
          },
        }
      }
    }
    </script>

    <style>
        body { font-family: 'Inter', 'SolaimanLipi', sans-serif; background: #fcf9f8; }
        .font-display { font-family: 'SolaimanLipi', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; vertical-align: middle; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c6d5; border-radius: 10px; }

        /* Sidebar transition */
        #admin-sidebar {
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }
        @media (max-width: 1023px) {
            #admin-sidebar { transform: translateX(-100%); }
            #admin-sidebar.sidebar-open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
        }

        /* Overlay */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 40;
            opacity: 0;
            transition: opacity 0.3s;
        }
        #sidebar-overlay.show { display: block; opacity: 1; }

        /* Icon spin animation (replaces Font Awesome fa-spin) */
        .spin { display: inline-block; animation: bi-spin 0.9s linear infinite; }
        @keyframes bi-spin { to { transform: rotate(360deg); } }

        /* Bootstrap overrides inside content area */
        .content-area .table { font-size: 14px; }
        .content-area .card { border-radius: 12px; border-color: #c1c6d5; }
        .content-area .btn-primary { background-color: #004e9f; border-color: #004e9f; }
        .content-area .btn-primary:hover { background-color: #003d80; border-color: #003d80; }
        .content-area .form-control:focus, .content-area .form-select:focus {
            border-color: #004e9f;
            box-shadow: 0 0 0 0.2rem rgba(0,78,159,.18);
        }
        .content-area .alert { border-radius: 10px; }
        .content-area .badge { border-radius: 6px; }
        .content-area .nav-tabs .nav-link.active { color: #004e9f; border-color: #c1c6d5 #c1c6d5 #fff; font-weight: 600; }
        .content-area .nav-tabs .nav-link { color: #414753; }
    </style>

    @stack('styles')
</head>
<body class="bg-background text-on-background antialiased">

<!-- Sidebar Overlay (mobile) -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
<aside id="admin-sidebar"
       class="h-screen w-64 fixed left-0 top-0 bg-surface-container-low border-r border-outline-variant
              flex flex-col py-lg px-md z-50 shadow-xl lg:shadow-none">

    {{-- Brand --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="block">
            @if($seoSettings?->logo)
                <img src="{{ storage_image_url($seoSettings->logo) }}" alt="Logo" class="h-10 object-contain"/>
            @else
                <h1 class="font-display text-xl font-bold text-primary leading-tight">
                    {{ $seoSettings?->site_name ?: 'সজীব নিউজ' }}
                </h1>
                <p class="text-on-surface-variant text-[10px] uppercase tracking-wider mt-0.5">অ্যাডমিন পোর্টাল</p>
            @endif
        </a>
        {{-- Close button (mobile only) --}}
        <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-variant text-on-surface-variant">
            <i class="bi bi-x-lg text-[20px]"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar space-y-0.5 pr-1">

        {{-- nav helper macro --}}
        @php
            function adminNavLink($route, $icon, $label, $routePattern = null) {
                $routePattern = $routePattern ?? $route;
                $active = request()->routeIs($routePattern . '*') || request()->routeIs($route);
                $base = 'flex items-center gap-3 px-md py-sm rounded-xl text-sm font-medium transition-all duration-150 ease-in-out';
                $cls  = $active
                    ? $base . ' bg-primary-container text-on-primary-container font-bold shadow-sm'
                    : $base . ' text-on-surface-variant hover:bg-surface-variant hover:text-on-surface';
                return '<a href="' . route($route) . '" class="' . $cls . '" onclick="if(window.innerWidth<1024)closeSidebar()">
                            <i class=\"bi ' . $icon . '\" style=\"font-size:20px;line-height:1;\"></i>
                            <span>' . $label . '</span>
                        </a>';
            }
        @endphp

        {!! adminNavLink('admin.dashboard', 'bi-speedometer2', 'ড্যাশবোর্ড') !!}
        {!! adminNavLink('admin.news.index', 'bi-newspaper', 'খবর', 'admin.news') !!}

        @if(auth()->user()->hasRole(['super-admin', 'admin', 'editor']))
        {!! adminNavLink('admin.categories.index', 'bi-grid-3x3-gap', 'বিভাগসমূহ', 'admin.categories') !!}
        {!! adminNavLink('admin.tags.index', 'bi-tags', 'ট্যাগ', 'admin.tags') !!}
        @endif

        @if(auth()->user()->hasRole(['super-admin', 'admin']))
        {!! adminNavLink('admin.job-posts.index', 'bi-briefcase', 'চাকরি', 'admin.job-posts') !!}
        {!! adminNavLink('admin.advertisements.index', 'bi-megaphone', 'বিজ্ঞাপন', 'admin.advertisements') !!}
        {!! adminNavLink('admin.users.index', 'bi-people', 'ব্যবহারকারী', 'admin.users') !!}
        {!! adminNavLink('admin.newsletters.index', 'bi-envelope', 'নিউজলেটার', 'admin.newsletters') !!}
        {!! adminNavLink('admin.analytics', 'bi-graph-up', 'অ্যানালিটিক্স') !!}
        {!! adminNavLink('admin.activities', 'bi-clock-history', 'অ্যাক্টিভিটি লগ') !!}
        @endif

        {!! adminNavLink('admin.live-streams.index', 'bi-broadcast', 'লাইভ স্ট্রিমিং', 'admin.live-streams') !!}

        {!! adminNavLink('admin.photo-card.index', 'bi-card-image', 'ফটোকার্ড', 'admin.photo-card') !!}

        @if(auth()->user()->hasRole(['super-admin', 'admin']))
        {!! adminNavLink('admin.settings', 'bi-gear', 'সেটিংস') !!}
        @endif

        <div class="border-t border-outline-variant my-3"></div>

        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 px-md py-sm rounded-xl text-sm text-on-surface-variant hover:bg-surface-variant hover:text-on-surface transition-all">
            <i class="bi bi-box-arrow-up-right text-[20px]"></i>
            <span>সাইট দেখুন</span>
        </a>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-md py-sm rounded-xl text-sm text-on-surface-variant hover:bg-surface-variant hover:text-on-surface transition-all {{ request()->routeIs('profile.*') ? 'bg-surface-variant text-on-surface font-semibold' : '' }}">
            <i class="bi bi-person-gear text-[20px]"></i>
            <span>আমার প্রোফাইল</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-md py-sm rounded-xl text-sm text-error hover:bg-red-50 transition-all text-left">
                <i class="bi bi-box-arrow-right text-[20px]"></i>
                <span>লগআউট</span>
            </button>
        </form>
    </nav>

    {{-- User info + New Post --}}
    <div class="mt-auto pt-lg border-t border-outline-variant space-y-2">
        <div class="flex items-center gap-3 px-md py-sm">
            <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm flex-shrink-0 uppercase">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wide">
                    {{ auth()->user()->getRoleNames()->first() ?? 'অ্যাডমিন' }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.news.create') }}"
           class="w-full bg-primary text-on-primary py-sm rounded-xl text-sm font-bold flex items-center justify-center gap-xs hover:opacity-90 transition-all active:scale-95">
            <i class="bi bi-plus-lg text-[18px]"></i>
            <span>নতুন পোস্ট</span>
        </a>
    </div>
</aside>

<!-- ═══════════════════════ MAIN CONTENT ═══════════════════════ -->
<div id="main-content" class="lg:ml-64 min-h-screen flex flex-col">

    {{-- Top Bar --}}
    <header class="sticky top-0 z-30 bg-surface/95 backdrop-blur border-b border-outline-variant
                   flex items-center justify-between px-md lg:px-lg py-3 gap-3 shadow-sm">

        {{-- Hamburger (mobile) --}}
        <button onclick="openSidebar()"
                class="lg:hidden w-10 h-10 flex flex-col justify-center items-center gap-[5px] rounded-xl
                       hover:bg-surface-container transition-colors flex-shrink-0">
            <span class="block w-5 h-[2px] bg-on-surface rounded-full"></span>
            <span class="block w-4 h-[2px] bg-on-surface rounded-full"></span>
            <span class="block w-5 h-[2px] bg-on-surface rounded-full"></span>
        </button>

        {{-- Page title --}}
        <h2 class="font-display text-base lg:text-lg font-bold text-primary flex-1 truncate">
            @yield('page-title', 'ড্যাশবোর্ড')
        </h2>

        {{-- Right: user dropdown --}}
        <div class="relative flex-shrink-0" id="user-menu-wrapper">
            <button onclick="toggleUserMenu()"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-surface-container-high transition-all">
                <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm uppercase">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <span class="hidden sm:block text-sm font-medium text-on-surface max-w-[120px] truncate">
                    {{ auth()->user()->name }}
                </span>
                <i class="bi bi-chevron-down text-on-surface-variant text-[18px]"></i>
            </button>

            {{-- Dropdown --}}
            <div id="user-dropdown"
                 class="hidden absolute right-0 mt-1 w-52 bg-surface rounded-xl border border-outline-variant shadow-lg py-1 z-50">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container transition-colors">
                    <i class="bi bi-person-gear text-[18px] text-on-surface-variant"></i>
                    আমার প্রোফাইল
                </a>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container transition-colors">
                    <i class="bi bi-box-arrow-up-right text-[18px] text-on-surface-variant"></i>
                    সাইট দেখুন
                </a>
                <div class="border-t border-outline-variant my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-error hover:bg-red-50 transition-colors">
                        <i class="bi bi-box-arrow-right text-[18px]"></i>
                        লগআউট
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Alerts + Content --}}
    <main class="flex-1 p-md lg:p-lg content-area">

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong><i class="bi bi-exclamation-circle"></i> ত্রুটি!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-outline-variant px-lg py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <p class="text-xs text-on-surface-variant font-display">
            © {{ date('Y') }} {{ $seoSettings?->site_name ?: 'সজীব নিউজ' }}। সর্বস্বত্ব সংরক্ষিত।
        </p>
        <div class="flex gap-4 text-xs text-on-surface-variant">
            <a href="{{ route('privacy') }}" target="_blank" class="hover:text-primary transition-colors">গোপনীয়তা নীতি</a>
            <a href="{{ route('home') }}" target="_blank" class="hover:text-primary transition-colors">সাইট দেখুন</a>
        </div>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
    // Sidebar
    function openSidebar() {
        document.getElementById('admin-sidebar').classList.add('sidebar-open');
        const ov = document.getElementById('sidebar-overlay');
        ov.style.display = 'block';
        requestAnimationFrame(() => ov.classList.add('show'));
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('admin-sidebar').classList.remove('sidebar-open');
        const ov = document.getElementById('sidebar-overlay');
        ov.classList.remove('show');
        setTimeout(() => { ov.style.display = 'none'; }, 300);
        document.body.style.overflow = '';
    }
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) closeSidebar();
    });

    // User dropdown
    function toggleUserMenu() {
        document.getElementById('user-dropdown').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('user-menu-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('user-dropdown').classList.add('hidden');
        }
    });

    // Card hover lift
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.hover\\:shadow-md').forEach(card => {
            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-3px)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
        });
    });
</script>

@stack('scripts')

<script>
    // Detect browser back-forward cache (bfcache) restore and force reload
    // This prevents logged-out users from seeing the admin panel via browser back button
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
</body>
</html>
