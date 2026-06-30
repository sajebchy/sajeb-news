<!DOCTYPE html>
@php $__layoutSeo = \App\Models\SeoSetting::first(); @endphp
<html class="light" lang="bn">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', ($__layoutSeo?->site_name ?: 'সজীব নিউজ') . ' | নির্ভরযোগ্য খবরের ঠিকানা')</title>
<meta name="description" content="@yield('meta_description', $__layoutSeo?->site_description ?: 'বাংলাদেশের নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')">
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;700&family=Noto+Serif+Bengali:wght@400;600;700&family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        "container-max": "1280px", "stack-lg": "2rem", "stack-sm": "0.5rem",
        "gutter": "1.5rem", "section-padding": "4rem", "stack-md": "1rem"
      },
      fontFamily: {
        "body-main": ["Libre Franklin"], "body-sm": ["Libre Franklin"],
        "display-breaking": ["Noto Serif Bengali"], "headline-lg": ["Noto Serif Bengali"],
        "label-caps": ["Work Sans"], "headline-md": ["Noto Serif Bengali"],
        "meta-data": ["Work Sans"], "headline-lg-mobile": ["Noto Serif Bengali"]
      },
      fontSize: {
        "body-main": ["18px", {"lineHeight":"1.6","fontWeight":"400"}],
        "body-sm": ["15px", {"lineHeight":"1.5","fontWeight":"400"}],
        "display-breaking": ["48px", {"lineHeight":"1.1","letterSpacing":"-0.02em","fontWeight":"700"}],
        "headline-lg": ["32px", {"lineHeight":"1.2","fontWeight":"700"}],
        "label-caps": ["12px", {"lineHeight":"1","letterSpacing":"0.05em","fontWeight":"700"}],
        "headline-md": ["20px", {"lineHeight":"1.3","fontWeight":"600"}],
        "meta-data": ["13px", {"lineHeight":"1","fontWeight":"400"}],
        "headline-lg-mobile": ["24px", {"lineHeight":"1.2","fontWeight":"700"}]
      }
    }
  }
}
</script>
<style>
  .ticker-scroll { animation: ticker 35s linear infinite; }
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
</style>
@stack('styles')
</head>
<body class="bg-background text-on-surface font-body-main selection:bg-secondary selection:text-on-secondary">
<div id="reading-progress"></div>

@php
  $navCategories = \App\Models\Category::where('is_active', true)->orderByRaw('featured_order IS NULL, featured_order ASC')->orderBy('name')->limit(8)->get();
  $currentRouteName = optional(request()->route())->getName() ?? '';
  $globalSeo = $globalSeo ?? \App\Models\SeoSetting::first();
  $siteName = $globalSeo?->site_name ?: 'সজীব নিউজ';
  $siteNameEn = $globalSeo?->site_title ?: 'Sajeb News';
  $siteDesc  = $globalSeo?->site_description ?: '';
  $siteLogo  = $globalSeo?->logo ? \Storage::url($globalSeo->logo) : null;
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
       class="fixed top-0 left-0 h-full w-[300px] z-[70] bg-surface shadow-2xl flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">

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
        <a href="{{ route('category.show', $navCat->slug) }}" onclick="closeDrawer()"
           class="flex items-center justify-between px-3 py-3 rounded-lg font-headline-md text-[16px]
                  {{ (request()->route('category') && request()->route('category')->slug === $navCat->slug) ? 'bg-surface-container text-secondary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-secondary' }} transition-colors group">
          <span class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary transition-colors">article</span>
            {{ $navCat->name }}
          </span>
          <span class="material-symbols-outlined text-[18px] text-outline-variant">chevron_right</span>
        </a>
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
      <li>
        <a href="{{ route('admin.dashboard') }}" onclick="closeDrawer()"
           class="flex items-center gap-3 px-3 py-3 rounded-lg font-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors group">
          <span class="material-symbols-outlined text-[20px] text-outline group-hover:text-secondary">admin_panel_settings</span> অ্যাডমিন
        </a>
      </li>
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

{{-- Unified Sticky Header --}}
<header class="sticky top-0 w-full z-50 bg-surface/95 backdrop-blur-sm border-b border-subtle shadow-sm">
  <div class="max-w-container-max mx-auto px-gutter py-3 flex items-center justify-between gap-4">

    {{-- LEFT: Hamburger button --}}
    <button id="hamburger-btn" onclick="openDrawer()" aria-label="মেনু খুলুন"
            class="flex flex-col justify-center items-center gap-[5px] w-10 h-10 rounded-lg hover:bg-surface-container-low transition-colors flex-shrink-0 group">
      <span id="hb-line1" class="block w-5 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary"></span>
      <span id="hb-line2" class="block w-4 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary group-hover:w-5"></span>
      <span id="hb-line3" class="block w-5 h-[2px] bg-on-surface rounded-full transition-all duration-300 group-hover:bg-secondary"></span>
    </button>

    {{-- CENTER: Logo --}}
    <div class="flex-1 text-center">
      <a href="{{ route('home') }}" class="inline-block group">
        @if($siteLogo)
          <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-10 md:h-14 object-contain mx-auto group-hover:opacity-80 transition-opacity"/>
        @else
          <span class="font-headline-lg text-primary text-2xl md:text-4xl tracking-tight leading-none group-hover:text-secondary transition-colors">{{ $siteName }}</span>
          <span class="hidden md:block font-meta-data text-[10px] text-outline tracking-[0.3em] uppercase mt-0.5">{{ $siteNameEn }}</span>
        @endif
      </a>
    </div>

    {{-- RIGHT: Search + Date (desktop) + Live --}}
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
      <h4 class="font-headline-md text-lg mb-4 border-b border-subtle pb-2">মোবাইল অ্যাপ</h4>
      <p class="text-body-sm text-on-surface-variant mb-4">আমাদের অ্যান্ড্রয়েড ও আইওএস অ্যাপ নামিয়ে নিন।</p>
      <div class="space-y-3">
        <a href="#" class="w-full flex items-center justify-center gap-3 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
          <span class="material-symbols-outlined">phone_iphone</span>
          <div class="text-left"><p class="text-[10px] uppercase leading-none opacity-70">Download on the</p><p class="text-sm font-semibold leading-none">App Store</p></div>
        </a>
        <a href="#" class="w-full flex items-center justify-center gap-3 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
          <span class="material-symbols-outlined">play_arrow</span>
          <div class="text-left"><p class="text-[10px] uppercase leading-none opacity-70">Get it on</p><p class="text-sm font-semibold leading-none">Google Play</p></div>
        </a>
      </div>
    </div>
  </div>
  <div class="border-t border-subtle py-6 bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-gutter flex flex-col md:flex-row justify-between items-center text-meta-data text-outline gap-4">
      <p>© {{ date('Y') }} {{ $siteName }}। সকল স্বত্ব সংরক্ষিত।</p>
      <p>সম্পাদক ও প্রকাশক: সজীব রহমান</p>
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
  drawer.classList.remove('-translate-x-full');
  overlay.classList.remove('opacity-0','pointer-events-none');
  overlay.classList.add('opacity-100');
  document.body.style.overflow = 'hidden';
}
function closeDrawer() {
  const drawer = document.getElementById('nav-drawer');
  const overlay = document.getElementById('drawer-overlay');
  drawer.classList.add('-translate-x-full');
  overlay.classList.remove('opacity-100');
  overlay.classList.add('opacity-0','pointer-events-none');
  document.body.style.overflow = '';
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
</body>
</html>
