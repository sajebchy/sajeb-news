<!DOCTYPE html>
@php $__layoutSeo = \App\Models\SeoSetting::first(); @endphp
<html class="light" lang="bn" dir="ltr">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', ($__layoutSeo?->site_name ?: 'সজীব নিউজ') . ' | নির্ভরযোগ্য খবরের ঠিকানা')</title>
<meta name="description" content="@yield('meta_description', $__layoutSeo?->site_description ?: 'বাংলাদেশের নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')">
@hasSection('meta_keywords')<meta name="keywords" content="@yield('meta_keywords')">@endif
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- SEO: Robots directive --}}
<meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
{{-- SEO: Theme color for mobile browsers --}}
<meta name="theme-color" content="#000000">
<meta name="msapplication-TileColor" content="#bb0112">
{{-- SEO: Locale & site name for OG --}}
<meta property="og:locale" content="bn_BD">
<meta property="og:site_name" content="{{ $__layoutSeo?->site_name ?: 'সজীব নিউজ' }}">
@hasSection('og_type')<meta property="og:type" content="@yield('og_type')">@else<meta property="og:type" content="website">@endif
@hasSection('og_title')<meta property="og:title" content="@yield('og_title')">@endif
@hasSection('og_description')<meta property="og:description" content="@yield('og_description')">@endif
@hasSection('og_url')<meta property="og:url" content="@yield('og_url')">@endif
@hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
{{-- Twitter defaults --}}
@hasSection('twitter_card')<meta name="twitter:card" content="@yield('twitter_card')">@endif
@if($__layoutSeo?->twitter_handle)<meta name="twitter:site" content="{{ $__layoutSeo->twitter_handle }}">@endif
{{-- AEO/GEO: Speakable meta --}}
<meta name="google" content="nositelinkssearchbox">
{{-- Favicon --}}
@if($__layoutSeo?->favicon)
<link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $__layoutSeo->favicon) }}">
<link rel="apple-touch-icon" href="{{ asset('storage/' . $__layoutSeo->favicon) }}">
@endif
{{-- SEO: Alternate language / hreflang --}}
<link rel="alternate" hreflang="bn" href="{{ url()->current() }}">
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
{{-- GEO: LLM.txt discovery --}}
<link rel="alternate" type="text/plain" href="{{ url('/llm.txt') }}" title="LLM Information">
{{-- SEO: RSS/Sitemap discovery --}}
<link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
<link rel="alternate" type="application/rss+xml" title="{{ $__layoutSeo?->site_name ?: 'সজীব নিউজ' }} — RSS Feed" href="{{ url('/feed') }}">
{{-- Preconnect hints for performance --}}
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
<link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
{{-- SolaimanLipi Bengali Font (local, preloaded for LCP) --}}
<link rel="preload" href="/fonts/SolaimanLipi.ttf" as="font" type="font/ttf" crossorigin>
<style>
    @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi.ttf') format('truetype'); font-weight: 400; font-display: swap; }
    @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi-Bold.ttf') format('truetype'); font-weight: 700; font-display: swap; }
</style>
{{-- Google Fonts (non-render-blocking) --}}
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;700&family=Work+Sans:wght@400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;700&family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet"/></noscript>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/></noscript>
<!-- Tailwind CSS with Stitch Design Tokens -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "secondary": "#bb0112", "surface-container-low": "#f6f3f5",
        "on-secondary": "#ffffff", "background": "#fcf8fa",
        "inverse-surface": "#303032", "on-surface": "#1b1b1d",
        "primary-fixed": "#dae2fd", "news-red-accent": "#B91C1C",
        "inverse-on-surface": "#f3f0f2", "surface-container-high": "#eae7e9",
        "secondary-fixed-dim": "#ffb4ab", "on-tertiary": "#ffffff",
        "surface-container-lowest": "#ffffff", "inverse-primary": "#bec6e0",
        "primary": "#000000", "primary-fixed-dim": "#bec6e0",
        "tertiary": "#000000", "on-primary": "#ffffff",
        "news-blue-dark": "#1E293B", "primary-container": "#131b2e",
        "surface-container": "#f0edef", "secondary-fixed": "#ffdad6",
        "on-surface-variant": "#45464d", "on-error-container": "#93000a",
        "on-secondary-fixed": "#410002", "on-background": "#1b1b1d",
        "surface-tint": "#565e74", "surface-bright": "#fcf8fa",
        "surface-dim": "#dcd9db", "on-primary-container": "#7c839b",
        "secondary-container": "#e02928", "on-primary-fixed": "#131b2e",
        "outline-variant": "#c6c6cd", "surface": "#fcf8fa",
        "tertiary-fixed": "#fcdeb5", "on-error": "#ffffff",
        "surface-variant": "#e4e2e4", "on-tertiary-container": "#98805d",
        "surface-container-highest": "#e4e2e4", "border-subtle": "#E2E8F0",
        "on-secondary-container": "#fffbff", "tertiary-fixed-dim": "#dec29a",
        "tertiary-container": "#271901", "outline": "#76777d",
        "error-container": "#ffdad6", "error": "#ba1a1a",
        "on-primary-fixed-variant": "#3f465c", "surface-muted": "#F8FAFC",
        "on-tertiary-fixed": "#271901", "on-tertiary-fixed-variant": "#574425",
        "on-secondary-fixed-variant": "#93000b"
      },
      borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
      spacing: {
        "container-max": "100%", "stack-lg": "2rem", "stack-sm": "0.5rem",
        "gutter": "2rem", "section-padding": "4rem", "stack-md": "1rem"
      },
      fontFamily: {
        "body-main": ["Libre Franklin"], "body-sm": ["Libre Franklin"],
        "display-breaking": ["SolaimanLipi"], "headline-lg": ["SolaimanLipi"],
        "label-caps": ["Work Sans"], "headline-md": ["SolaimanLipi"],
        "meta-data": ["Work Sans"], "headline-lg-mobile": ["SolaimanLipi"]
      },
      fontSize: {
        "body-main": ["18px", {"lineHeight":"1.6","fontWeight":"400"}],
        "body-sm": ["15px", {"lineHeight":"1.5","fontWeight":"400"}],
        "display-breaking": ["48px", {"lineHeight":"1.1","letterSpacing":"-0.02em","fontWeight":"700"}],
        "headline-lg": ["32px", {"lineHeight":"1.2","fontWeight":"700"}],
        "label-caps": ["12px", {"lineHeight":"1","letterSpacing":"0.05em","fontWeight":"700"}],
        "headline-md": ["20px", {"lineHeight":"1.3","fontWeight":"700"}],
        "meta-data": ["13px", {"lineHeight":"1","fontWeight":"400"}],
        "headline-lg-mobile": ["24px", {"lineHeight":"1.2","fontWeight":"700"}]
      }
    }
  }
}
</script>
<style>
  #nav-drawer { transition: transform 0.3s ease-in-out; }
  .nav-drawer-closed { transform: translateX(100%); }
  .nav-drawer-open { transform: translateX(0) !important; }
  .ticker-scroll { animation: ticker 35s linear infinite; }
  .ticker-scroll:hover { animation-play-state: paused; }
  @keyframes ticker { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; display: inline-block; vertical-align: middle; line-height: 1; }
  .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
  .line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  #reading-progress { position: fixed; top: 0; left: 0; height: 3px; background: #bb0112; z-index: 9999; width: 0%; transition: width 0.1s ease; pointer-events: none; }
  .hover-lift { transition: transform 0.2s ease; }
  .hover-lift:hover { transform: translateY(-2px); }
  .prose-readable { max-width: 800px; }
  @media (min-width: 1600px) {
    .max-w-container-max { padding-left: 3rem; padding-right: 3rem; }
  }
</style>
@stack('styles')
@if($__layoutSeo?->analytics_head_code)
{!! $__layoutSeo->analytics_head_code !!}
@endif
</head>
<body class="bg-background text-on-surface font-body-main selection:bg-secondary selection:text-on-secondary">
@if($__layoutSeo?->analytics_body_code)
{!! $__layoutSeo->analytics_body_code !!}
@endif
<div id="reading-progress"></div>

@php
  $navCategories = \App\Models\Category::where('is_active', true)->whereNull('parent_id')->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('name')])->orderByRaw('featured_order IS NULL, featured_order ASC')->orderBy('name')->limit(8)->get();
  $currentRouteName = optional(request()->route())->getName() ?? '';
  $globalSeo = $globalSeo ?? \App\Models\SeoSetting::first();
  $siteName = $globalSeo?->site_name ?: 'সজীব নিউজ';
  $siteNameEn = $globalSeo?->site_title ?: 'Sajeb News';
  $siteDesc  = $globalSeo?->site_description ?: '';
  $siteLogo  = $globalSeo?->logo ? storage_image_url($globalSeo->logo) : null;
  $defaultFeaturedImage = $globalSeo?->default_featured_image
      ? asset('storage/'.$globalSeo->default_featured_image)
      : ($globalSeo?->logo ? asset('storage/'.$globalSeo->logo) : null);
@endphp

<!-- ═══════════════════════════════════════════════════════════
     UNIFIED STICKY HEADER (Desktop + Mobile)
     ═══════════════════════════════════════════════════════════ -->

{{-- Overlay backdrop --}}
<div id="drawer-overlay"
     class="fixed inset-0 z-[60] bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeDrawer()"></div>

{{-- Sidebar Drawer --}}
<aside id="nav-drawer"
       class="fixed top-0 right-0 h-full w-[300px] z-[70] bg-surface shadow-2xl flex flex-col overflow-y-auto nav-drawer-closed">

  {{-- Drawer Header --}}
  <div class="flex items-center justify-between px-5 py-4 border-b border-subtle flex-shrink-0">
    <a href="{{ route('home') }}" onclick="closeDrawer()">
      @if($siteLogo)
        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-10 object-contain"/>
      @else
        <span class="font-headline-lg text-primary text-2xl tracking-tight leading-none">{{ $siteName }}</span>
        <span class="block font-meta-data text-[10px] text-outline tracking-[0.25em] uppercase mt-0.5">{{ $siteNameEn }}</span>
      @endif
    </a>
    <button onclick="closeDrawer()"
            class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>

  {{-- Search inside drawer --}}
  <div class="px-5 py-3 border-b border-subtle flex-shrink-0">
    <form action="{{ route('news.search') }}" method="GET" class="relative">
      <input name="q" value="{{ request('q') }}"
             class="w-full pl-4 pr-10 py-2 bg-surface-container-low border border-subtle rounded-lg text-body-sm focus:outline-none focus:border-secondary transition-all"
             placeholder="সংবাদ অনুসন্ধান করুন..."/>
      <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
        <span class="material-symbols-outlined text-[20px]">search</span>
      </button>
    </form>
  </div>

  {{-- Live TV button --}}
  <div class="px-5 py-3 border-b border-subtle flex-shrink-0">
    <a href="{{ route('live.index') }}" onclick="closeDrawer()"
       class="flex items-center gap-2 bg-secondary text-white px-4 py-2.5 rounded-lg hover:bg-news-red-accent transition-colors w-full justify-center">
      <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
      <span class="font-label-caps text-label-caps">সরাসরি সম্প্রচার (Live TV)</span>
    </a>
  </div>

  {{-- Main Nav Links --}}
  <nav class="flex-grow px-3 py-3 overflow-y-auto">
    <p class="font-label-caps text-label-caps text-on-surface-variant px-3 mb-2 tracking-widest">মূল বিভাগ</p>
    <ul class="space-y-0.5">
      <li>
        <a href="{{ route('home') }}" onclick="closeDrawer()"
           class="flex items-center justify-between px-3 py-3 rounded-lg font-headline-md text-[16px]
                  {{ $currentRouteName === 'home' ? 'bg-surface-container text-secondary' : 'text-on-surface hover:bg-surface-container-low hover:text-secondary' }} transition-colors group">
          <span class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary transition-colors">home</span>
            হোম
          </span>
          <span class="material-symbols-outlined text-[18px] text-outline-variant">chevron_right</span>
        </a>
      </li>
      @foreach($navCategories as $navCat)
      <li>
        <div class="flex items-center">
          <a href="{{ route('category.show', $navCat->slug) }}" onclick="closeDrawer()"
             class="flex-1 flex items-center justify-between px-3 py-3 rounded-lg font-headline-md text-[16px]
                    {{ (request()->route('category') && request()->route('category')->slug === $navCat->slug) ? 'bg-surface-container text-secondary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-secondary' }} transition-colors group">
            <span class="flex items-center gap-3">
              <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary transition-colors">article</span>
              {{ $navCat->name }}
            </span>
            @if($navCat->children->isEmpty())
            <span class="material-symbols-outlined text-[18px] text-outline-variant">chevron_right</span>
            @endif
          </a>
          @if($navCat->children->isNotEmpty())
          <button onclick="toggleSubcats({{ $navCat->id }})" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container-low transition-colors text-on-surface-variant flex-shrink-0">
            <span class="material-symbols-outlined text-[20px] transition-transform duration-200" id="subcat-arrow-{{ $navCat->id }}">expand_more</span>
          </button>
          @endif
        </div>
        @if($navCat->children->isNotEmpty())
        <ul id="subcat-{{ $navCat->id }}" class="hidden ml-8 space-y-0.5 pb-1">
          @foreach($navCat->children as $subCat)
          <li>
            <a href="{{ route('category.show', $subCat->slug) }}" onclick="closeDrawer()"
               class="flex items-center justify-between px-3 py-2 rounded-lg font-headline-md text-[14px]
                      {{ (request()->route('category') && request()->route('category')->slug === $subCat->slug) ? 'bg-surface-container text-secondary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-secondary' }} transition-colors group">
              <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-outline group-hover:text-secondary transition-colors">subdirectory_arrow_right</span>
                {{ $subCat->name }}
              </span>
              <span class="material-symbols-outlined text-[16px] text-outline-variant">chevron_right</span>
            </a>
          </li>
          @endforeach
        </ul>
        @endif
      </li>
      @endforeach
    </ul>

    <div class="h-px bg-subtle mx-3 my-3 border-t border-subtle"></div>
    <p class="font-label-caps text-label-caps text-on-surface-variant px-3 mb-2 tracking-widest">অন্যান্য</p>
    <ul class="space-y-0.5">
      <li>
        <a href="{{ route('about') }}" onclick="closeDrawer()"
           class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
          <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">info</span> আমাদের সম্পর্কে
        </a>
      </li>
      <li>
        <a href="{{ route('contact') }}" onclick="closeDrawer()"
           class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
          <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">mail</span> যোগাযোগ
        </a>
      </li>
      <li>
        <a href="{{ route('privacy') }}" onclick="closeDrawer()"
           class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
          <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">shield</span> গোপনীয়তা নীতি
        </a>
      </li>
      <li>
        <a href="{{ route('terms') }}" onclick="closeDrawer()"
           class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
          <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">gavel</span> শর্তাবলী
        </a>
      </li>
      @auth
        @if(auth()->user()->hasRole(['super-admin', 'admin', 'editor', 'reporter']))
        <li>
          <a href="{{ route('admin.dashboard') }}" onclick="closeDrawer()"
             class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
            <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">admin_panel_settings</span> অ্যাডমিন
          </a>
        </li>
        @else
        <li>
          <a href="{{ route('reader.profile') }}" onclick="closeDrawer()"
             class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
            <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">person</span> প্রোফাইল
          </a>
        </li>
        @endif
      @endauth
    </ul>
  </nav>

  {{-- Drawer Footer: Social + Date --}}
  <div class="px-5 py-4 border-t border-subtle flex-shrink-0 bg-surface-container-lowest">
    <div class="flex gap-3 mb-3">
      <a href="#" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
        <span class="material-symbols-outlined text-[18px]">face_nod</span>
      </a>
      <a href="#" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
        <span class="material-symbols-outlined text-[18px]">brand_family</span>
      </a>
      <a href="{{ route('sitemap.xml') }}" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
        <span class="material-symbols-outlined text-[18px]">rss_feed</span>
      </a>
    </div>
    <p class="font-meta-data text-[11px] text-outline" id="drawer-date"></p>
  </div>
</aside>

{{-- Header Top Ad --}}
@php $__headerAd = \App\Helpers\AdHelper::getRandomAdByPlacement('header_top'); @endphp
@if($__headerAd)
<div class="w-full bg-surface-container-low border-b border-subtle py-1 text-center">
  {!! \App\Helpers\AdHelper::renderAd($__headerAd) !!}
</div>
@endif

{{-- Unified Sticky Header --}}
<header class="sticky top-0 w-full z-50 bg-surface/95 backdrop-blur-sm border-b border-subtle shadow-sm">
  <div class="max-w-container-max mx-auto px-gutter py-3 flex items-center justify-between gap-4">

    {{-- LEFT: Logo --}}
    <div class="flex-shrink-0">
      <a href="{{ route('home') }}" class="inline-block group">
        @if($siteLogo)
          <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-10 md:h-14 object-contain group-hover:opacity-80 transition-opacity"/>
        @else
          <span class="font-headline-lg text-primary text-2xl md:text-4xl tracking-tight leading-none group-hover:text-secondary transition-colors">{{ $siteName }}</span>
          <span class="hidden md:block font-meta-data text-[10px] text-outline tracking-[0.3em] uppercase mt-0.5">{{ $siteNameEn }}</span>
        @endif
      </a>
    </div>

    {{-- CENTER: spacer --}}
    <div class="flex-1"></div>

    {{-- RIGHT: Date + Search + Live + Hamburger --}}
    <div class="flex items-center gap-2 flex-shrink-0">
      {{-- Date — desktop only --}}
      <span class="hidden lg:block font-meta-data text-[11px] text-on-surface-variant border-r border-subtle pr-3 mr-1" id="current-date"></span>

      {{-- Search icon --}}
      <a href="{{ route('news.search') }}"
         class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-primary">
        <span class="material-symbols-outlined text-[22px]">search</span>
      </a>

      {{-- Live TV button --}}
      <a href="{{ route('live.index') }}"
         class="hidden sm:flex items-center gap-1.5 bg-secondary text-white px-3 py-1.5 rounded-lg hover:bg-news-red-accent transition-colors whitespace-nowrap">
        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
        <span class="font-label-caps text-label-caps text-[11px]">লাইভ</span>
      </a>
      <a href="{{ route('live.index') }}"
         class="sm:hidden w-9 h-9 flex items-center justify-center rounded-full text-secondary">
        <span class="material-symbols-outlined text-[22px]">live_tv</span>
      </a>

      {{-- Hamburger menu button --}}
      <button id="hamburger-btn" onclick="openDrawer()" aria-label="মেনু খুলুন"
              class="flex flex-col justify-center items-center w-10 rounded-lg hover:bg-surface-container-low transition-colors flex-shrink-0 group py-1">
        <div class="flex flex-col justify-center items-center gap-[5px] h-6">
          <span id="hb-line1" class="block w-5 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary"></span>
          <span id="hb-line2" class="block w-4 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary group-hover:w-5"></span>
          <span id="hb-line3" class="block w-5 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary"></span>
        </div>
        <span class="text-[9px] font-bold text-on-surface group-hover:text-secondary leading-none mt-0.5">Menu</span>
      </button>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════════════════════════════════
     PAGE CONTENT
     ═══════════════════════════════════════════════════════════ -->
@yield('content')

<!-- ═══════════════════════════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════════════════════════ -->
{{-- Footer Banner Ad --}}
@php $__footerAd = \App\Helpers\AdHelper::getRandomAdByPlacement('footer_banner'); @endphp
@if($__footerAd)
<div class="w-full bg-surface-container-low border-t border-subtle py-2 text-center">
  {!! \App\Helpers\AdHelper::renderAd($__footerAd) !!}
</div>
@endif

<footer class="bg-surface-container-lowest text-on-surface pt-section-padding border-t border-subtle mt-12">
  <div class="max-w-container-max mx-auto px-gutter grid grid-cols-1 md:grid-cols-4 gap-stack-lg pb-stack-lg">
    <div class="md:col-span-1">
      @if($siteLogo)
        <a href="{{ route('home') }}"><img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-12 object-contain mb-4"/></a>
      @else
        <h2 class="font-headline-lg text-primary text-3xl mb-4">{{ $siteName }}</h2>
      @endif
      <p class="text-body-sm text-on-surface-variant mb-6">{{ $siteDesc ?: 'সঠিক ও নিরপেক্ষ খবর সবার আগে পৌঁছে দিতে আমরা দায়বদ্ধ।' }}</p>
      <div class="flex gap-3">
        <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all" href="#"><span class="material-symbols-outlined text-[18px]">face_nod</span></a>
        <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all" href="#"><span class="material-symbols-outlined text-[18px]">brand_family</span></a>
        <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-secondary hover:text-white transition-all" href="{{ route('sitemap.xml') }}"><span class="material-symbols-outlined text-[18px]">rss_feed</span></a>
      </div>
    </div>
    <div>
      <h4 class="font-headline-md text-lg mb-4 border-b border-subtle pb-2">বিভাগসমূহ</h4>
      <ul class="space-y-2 text-body-sm text-on-surface-variant">
        @foreach($navCategories->take(6) as $fCat)
        <li><a href="{{ route('category.show', $fCat->slug) }}" class="hover:text-secondary hover:underline transition-all">{{ $fCat->name }}</a></li>
        @endforeach
      </ul>
    </div>
    <div>
      <h4 class="font-headline-md text-lg mb-4 border-b border-subtle pb-2">অন্যান্য</h4>
      <ul class="space-y-2 text-body-sm text-on-surface-variant">
        <li><a href="{{ route('about') }}" class="hover:text-secondary hover:underline transition-all">আমাদের সম্পর্কে</a></li>
        <li><a href="{{ route('contact') }}" class="hover:text-secondary hover:underline transition-all">যোগাযোগ</a></li>
        <li><a href="{{ route('privacy') }}" class="hover:text-secondary hover:underline transition-all">গোপনীয়তা নীতি</a></li>
        <li><a href="{{ route('terms') }}" class="hover:text-secondary hover:underline transition-all">ব্যবহারের শর্তাবলি</a></li>
        <li><a href="{{ route('sitemap') }}" class="hover:text-secondary hover:underline transition-all">সাইটম্যাপ</a></li>
        <li><a href="{{ route('live.index') }}" class="hover:text-secondary hover:underline transition-all">লাইভ টিভি</a></li>
      </ul>
    </div>
    <div>
      {{-- যোগাযোগ / প্রকাশক তথ্য (Admin → Settings → Basic Settings থেকে এডিটযোগ্য) --}}
      @if($globalSeo?->editor_publisher || $globalSeo?->office_address || $globalSeo?->office_mobile || $globalSeo?->office_email)
      <h4 class="font-headline-md text-lg mb-4 border-b border-subtle pb-2">যোগাযোগ</h4>
      <ul class="space-y-2 text-body-sm text-on-surface-variant mb-6">
        @if($globalSeo?->editor_publisher)
        <li class="flex items-start gap-2">
          <span class="material-symbols-outlined text-[18px] text-outline mt-0.5">edit_note</span>
          <span>সম্পাদক ও প্রকাশক: <span class="text-on-surface font-medium">{{ $globalSeo->editor_publisher }}</span></span>
        </li>
        @endif
        @if($globalSeo?->office_address)
        <li class="flex items-start gap-2">
          <span class="material-symbols-outlined text-[18px] text-outline mt-0.5">location_on</span>
          <span>{{ $globalSeo->office_address }}</span>
        </li>
        @endif
        @if($globalSeo?->office_mobile)
        <li class="flex items-start gap-2">
          <span class="material-symbols-outlined text-[18px] text-outline mt-0.5">call</span>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $globalSeo->office_mobile) }}" class="hover:text-secondary transition-colors">{{ $globalSeo->office_mobile }}</a>
        </li>
        @endif
        @if($globalSeo?->office_email)
        <li class="flex items-start gap-2">
          <span class="material-symbols-outlined text-[18px] text-outline mt-0.5">mail</span>
          <a href="mailto:{{ $globalSeo->office_email }}" class="hover:text-secondary transition-colors break-all">{{ $globalSeo->office_email }}</a>
        </li>
        @endif
      </ul>
      @endif
    </div>
  </div>
  <div class="border-t border-subtle py-6 bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-gutter flex flex-col md:flex-row justify-between items-center text-meta-data text-outline gap-4 text-center md:text-left">
      <p>© {{ date('Y') }} {{ $siteName }}। সকল স্বত্ব সংরক্ষিত।</p>
    </div>
  </div>
</footer>

<!-- Mobile Bottom Nav -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 md:hidden bg-surface border-t border-subtle shadow-lg">
  <a href="{{ route('home') }}" class="flex flex-col items-center justify-center {{ $currentRouteName==='home'?'text-secondary':'text-on-surface-variant' }} p-2 rounded">
    <span class="material-symbols-outlined">home</span><span class="font-label-caps text-[10px] mt-1">হোম</span>
  </a>
  <a href="{{ route('news.search') }}" class="flex flex-col items-center justify-center text-on-surface-variant p-2 rounded">
    <span class="material-symbols-outlined">search</span><span class="font-label-caps text-[10px] mt-1">খুঁজুন</span>
  </a>
  <a href="{{ route('live.index') }}" class="flex flex-col items-center justify-center text-on-surface-variant p-2 rounded">
    <span class="material-symbols-outlined">live_tv</span><span class="font-label-caps text-[10px] mt-1">লাইভ</span>
  </a>
  <a href="{{ route('about') }}" class="flex flex-col items-center justify-center text-on-surface-variant p-2 rounded">
    <span class="material-symbols-outlined">info</span><span class="font-label-caps text-[10px] mt-1">সম্পর্কে</span>
  </a>
</nav>
<div class="h-14 md:hidden"></div>

<script>
// Bengali date
const _days=['রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার','শনিবার'];
const _months=['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
const _bn=['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
function toBn(n){return n.toString().split('').map(d=>_bn[+d]??d).join('');}
const _now=new Date();const _el=document.getElementById('current-date');
if(_el)_el.textContent=`${_days[_now.getDay()]}, ${toBn(_now.getDate())} ${_months[_now.getMonth()]} ${toBn(_now.getFullYear())}`;
// Hamburger drawer
function openDrawer() {
  const drawer = document.getElementById('nav-drawer');
  const overlay = document.getElementById('drawer-overlay');
  drawer.classList.remove('nav-drawer-closed');
  void drawer.offsetHeight;
  drawer.classList.add('nav-drawer-open');
  overlay.classList.remove('opacity-0','pointer-events-none');
  overlay.classList.add('opacity-100');
  document.body.style.overflow = 'hidden';
}
function closeDrawer() {
  const drawer = document.getElementById('nav-drawer');
  const overlay = document.getElementById('drawer-overlay');
  drawer.classList.remove('nav-drawer-open');
  void drawer.offsetHeight;
  drawer.classList.add('nav-drawer-closed');
  overlay.classList.remove('opacity-100');
  overlay.classList.add('opacity-0','pointer-events-none');
  document.body.style.overflow = '';
}
function toggleSubcats(id) {
  const list = document.getElementById('subcat-' + id);
  const arrow = document.getElementById('subcat-arrow-' + id);
  if (list.classList.contains('hidden')) {
    list.classList.remove('hidden');
    arrow.style.transform = 'rotate(180deg)';
  } else {
    list.classList.add('hidden');
    arrow.style.transform = '';
  }
}
// Close on Escape key
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
// Set drawer date
const _dd = document.getElementById('drawer-date');
if (_dd) _dd.textContent = new Date().toLocaleDateString('bn-BD', {weekday:'long',year:'numeric',month:'long',day:'numeric'});
// Reading progress
window.addEventListener('scroll',()=>{const b=document.getElementById('reading-progress');if(!b)return;const h=document.documentElement.scrollHeight-document.documentElement.clientHeight;b.style.width=(h>0?(window.scrollY/h*100):0)+'%';});
// Icon hover fill
document.querySelectorAll('.material-symbols-outlined').forEach(i=>{i.addEventListener('mouseover',()=>i.style.fontVariationSettings="'FILL' 1");i.addEventListener('mouseout',()=>i.style.fontVariationSettings="'FILL' 0");});
</script>
@stack('scripts')

{{-- Bottom Sticky Ad --}}
@php $__stickyBottomAd = \App\Helpers\AdHelper::getRandomAdByPlacement('sticky_bottom'); @endphp
@if($__stickyBottomAd)
<div id="bottom-sticky-ad"
     style="position:fixed;bottom:0;left:0;right:0;z-index:9998;background:#f8f9fa;border-top:1px solid #dee2e6;box-shadow:0 -2px 10px rgba(0,0,0,.12);display:flex;align-items:center;justify-content:center;padding:6px 12px 6px 12px;min-height:62px;">
  <button onclick="document.getElementById('bottom-sticky-ad').style.display='none'"
          aria-label="বন্ধ করুন"
          style="position:absolute;top:4px;right:8px;background:rgba(0,0,0,.45);color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;">✕</button>
  <div style="text-align:center;">
    @if($__stickyBottomAd->image_url)
      <a href="{{ $__stickyBottomAd->ad_url ?: '#' }}" target="_blank" rel="noopener"
         onclick="adTrackClick(event,{{ $__stickyBottomAd->id }},'{{ addslashes($__stickyBottomAd->ad_url ?? '') }}')"
         style="display:inline-block;">
        <img src="{{ str_starts_with($__stickyBottomAd->image_url, 'http') ? $__stickyBottomAd->image_url : asset($__stickyBottomAd->image_url) }}"
             alt="{{ $__stickyBottomAd->alt_text ?? $__stickyBottomAd->name ?? 'বিজ্ঞাপন' }}"
             style="max-height:60px;max-width:calc(100vw - 60px);width:auto;display:block;margin:0 auto;"/>
      </a>
    @elseif($__stickyBottomAd->ad_code ?? $__stickyBottomAd->code)
      <div>{!! $__stickyBottomAd->ad_code ?? $__stickyBottomAd->code !!}</div>
    @endif
  </div>
</div>
{{-- Push content above sticky ad on mobile --}}
<style>#bottom-sticky-ad~nav.fixed { bottom: 66px !important; }</style>
@endif

{{-- ── Global Schema: Organization (all pages) + WebSite (homepage) ── --}}
@php
  $__seoSvc = app(\App\Services\SeoService::class);
  $__orgSchema = $__seoSvc->getOrganizationSchema();
  $__isHome = request()->routeIs('home');
@endphp
<script type="application/ld+json">{!! json_encode($__orgSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@if($__isHome)
@php $__websiteSchema = $__seoSvc->getWebsiteSchema(); @endphp
<script type="application/ld+json">{!! json_encode($__websiteSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endif

{{-- Ensure adTrackClick is always available (popup may load before other ads) --}}
<script>
if (typeof adTrackClick === 'undefined') {
  function adTrackClick(e, adId, dest) {
    var token = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = token ? token.getAttribute('content') : '';
    var url = '{{ url("/api/ads") }}/' + adId + '/click';
    var data = JSON.stringify({ placement: document.location.pathname });
    if (navigator.sendBeacon) {
      var blob = new Blob([data], { type: 'application/json' });
      navigator.sendBeacon(url, blob);
    } else {
      fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: data, keepalive: true }).catch(function(){});
    }
  }
}
</script>

{{-- Popup / Interstitial Ad --}}
@php
  $__popupAd = \App\Helpers\AdHelper::getRandomAdByPlacement('popup_interstitial');
@endphp
@if($__popupAd)
<div id="popup-ad-overlay"
     style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.65);align-items:center;justify-content:center;">
  <div style="position:relative;max-width:540px;width:90%;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4);">
    <button onclick="closePopupAd()"
            style="position:absolute;top:8px;right:10px;z-index:2;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:18px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
    @if($__popupAd->image_url)
      <a href="{{ $__popupAd->ad_url ?: '#' }}" target="_blank" rel="noopener"
         onclick="adTrackClick(event,{{ $__popupAd->id }},'{{ addslashes($__popupAd->ad_url ?? '') }}')"
         style="display:block;">
        <img src="{{ str_starts_with($__popupAd->image_url, 'http') ? $__popupAd->image_url : asset($__popupAd->image_url) }}"
             alt="{{ $__popupAd->alt_text ?? $__popupAd->name ?? 'বিজ্ঞাপন' }}"
             style="width:100%;display:block;"/>
      </a>
    @elseif($__popupAd->ad_code ?? $__popupAd->code)
      <div style="padding:16px;">{!! $__popupAd->ad_code ?? $__popupAd->code !!}</div>
    @endif
    <p style="text-align:center;font-size:11px;color:#999;padding:6px 0 8px;"><button onclick="closePopupAd()" style="background:none;border:none;color:#bb0112;cursor:pointer;font-size:11px;">বন্ধ করুন</button></p>
  </div>
</div>
<script>
(function(){
  var key = 'popup_ad_{{ $__popupAd->id }}_shown';
  if (sessionStorage.getItem(key)) return;
  setTimeout(function(){
    var el = document.getElementById('popup-ad-overlay');
    if (el) { el.style.display = 'flex'; sessionStorage.setItem(key, '1'); }
  }, 2000);
})();
function closePopupAd(){
  var el = document.getElementById('popup-ad-overlay');
  if (el) el.style.display = 'none';
}
document.getElementById('popup-ad-overlay').addEventListener('click', function(e){
  if (e.target === this) closePopupAd();
});
</script>
@endif
</body>
</html>
