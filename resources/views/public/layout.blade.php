<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="2000">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ env('VAPID_PUBLIC_KEY', '') }}">
    <title>@yield('title', $settings->site_title ?? 'Sajeb NEWS - বাংলাদেশী নিউজ পোর্টাল')</title>
    <meta name="description" content="@yield('description', $settings->site_description ?? 'বাংলাদেশের সর্বশেষ খবর, রাজনীতি, খেলাধুলা এবং আরও অনেক কিছু।')">
    <meta name="keywords" content="@yield('keywords', $settings->meta_keywords ?? 'বাংলাদেশ, খবর, নিউজ, বাংলা সংবাদ')">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', $settings->site_title ?? 'Sajeb NEWS')">
    <meta property="og:description" content="@yield('og_description', $settings->site_description ?? 'বাংলাদেশের সর্বশেষ খবর')">
    <meta property="og:image" content="@yield('og_image', $settings->og_image ? asset('storage/' . $settings->og_image) : '')">
    <meta property="og:url" content="@yield('og_url', url('/'))">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $settings->site_title ?? 'Sajeb NEWS')">
    <meta name="twitter:description" content="@yield('twitter_description', $settings->site_description ?? 'বাংলাদেশের সর্বশেষ খবর')">
    <meta name="twitter:image" content="@yield('twitter_image', $settings->og_image ? asset('storage/' . $settings->og_image) : '')">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', isset($metaTags['canonical']) ? $metaTags['canonical'] : url()->current())">
    
    <!-- Favicon -->
    @if($settings && $settings->favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Shurjo:wght@400;700&display=swap" as="style">
    
    <!-- JSON-LD Schema -->
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    <!-- Organization Schema -->
    @if($schemaSettings->enable_organization_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::organizationSchema()) !!}
        </script>
    @endif
    
    <!-- Website Schema -->
    @if($schemaSettings->enable_website_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::websiteSchema()) !!}
        </script>
    @endif
    
    <!-- Additional Schema (page-specific) -->
    @yield('schema')
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Shurjo (Bengali Font) -->
    <link href="https://fonts.googleapis.com/css2?family=Shurjo:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        /* Shurjo font for Bengali content */
        h1, h2, h3, h4, h5, h6, .display-5 {
            font-family: 'Shurjo', sans-serif;
            font-weight: 700;
        }
        
        body, p, span, a, li, div {
            font-family: 'Shurjo', sans-serif;
            font-weight: 400;
        }
        
        .article-content {
            font-family: 'Shurjo', sans-serif;
            font-weight: 400;
            line-height: 1.8;
        }
    </style>
    
    @yield('styles')
    
    <!-- Google Analytics 4 -->
    @if($settings && $settings->ga_tracking_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->ga_tracking_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings->ga_tracking_id }}');
    </script>
    @endif
    
    <!-- Google Tag Manager -->
    @if($settings && $settings->gtm_tracking_id)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $settings->gtm_tracking_id }}');</script>
    @endif

    <!-- Facebook Pixel -->
    @if($settings && $settings->facebook_pixel_id)
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $settings->facebook_pixel_id }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $settings->facebook_pixel_id }}&ev=PageView&noscript=1" /></noscript>
    @endif

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/bn_IN/sdk.js#xfbml=1&version=v18.0" nonce="FACEBOOK_NONCE"></script>
</head>
<body>
    <!-- Header Top Banner Ad -->
    <div class="ad-header-top" style="background: white; border-bottom: 1px solid #eee; padding: 10px 0;">
        <div class="container" style="text-align: center;">
            <x-ad-placement placement="header_top" random="true" class="ad-header" />
        </div>
    </div>

    <!-- Navigation -->
    <!-- Desktop Navigation -->
    <div class="DHeaderMenu MobileHide d-none d-lg-block sticky-top bg-white shadow-sm">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <!-- Logo Section -->
                        <div class="Dlogo">
                            <a href="{{ route('home') }}" class="navbar-brand fw-bold">
                                @if($settings && $settings->logo)
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name ?? 'লোগো' }}" title="{{ $settings->site_name ?? 'Sajeb NEWS' }}" style="max-height: 50px; max-width: 180px;">
                                @else
                                    <span class="fw-bold" style="font-size: 20px;">{{ $settings->site_name ?? 'Sajeb NEWS' }}</span>
                                @endif
                            </a>
                        </div>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link fw-500" href="{{ route('home') }}"><i class="fa fa-home"></i></a>
                                </li>
                                
                                @php
                                    // Get featured categories (1-5)
                                    $featuredCategories = \App\Models\Category::where('is_active', true)
                                        ->whereNotNull('featured_order')
                                        ->orderBy('featured_order', 'asc')
                                        ->get();
                                    
                                    // Get non-featured categories for popup menu
                                    $otherCategories = \App\Models\Category::where('is_active', true)
                                        ->whereNull('featured_order')
                                        ->whereNull('parent_id')
                                        ->get();
                                @endphp

                                @foreach($featuredCategories as $category)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                                </li>
                                @endforeach

                                @if($otherCategories->count() > 0 || \App\Models\Category::where('is_active', true)->count() > $featuredCategories->count())
                                <li class="nav-item others-menu">
                                    <a class="nav-link" href="javascript:void(0)" onclick="togglePopupMenu()">
                                        অন্যান্য
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </li>
                                @endif

                                <li class="nav-item menu-search">
                                    <a class="nav-link-search" href="javascript:void(0)" onclick="toggleSearch()">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </li>

                                @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        @can('create_news')
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                        @endcan
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগআউট</a></li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </ul>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">লগইন</a>
                                </li>
                                @endauth
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Search Block -->
        <div class="search_block Hide MobileHide" id="searchBlock" style="display: none;">
            <div class="container">
                <div class="col-xl">
                    <form action="{{ route('news.search') }}" method="get" class="d-flex">
                        <input type="text" name="q" class="form-control" placeholder="অনুসন্ধান করুন...">
                        <button type="submit" class="btn btn-link"><i class="fa fa-search"></i></button>
                        <a href="javascript:void(0)" class="btn btn-link" onclick="toggleSearch()"><i class="fa fa-times"></i></a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Popup Menu -->
        <div class="popup-menu" id="popupMenu" style="display: none;">
            <div class="popup-menu-content">
                <div class="popup-menu-table">
                    @php
                        $allCategories = \App\Models\Category::where('is_active', true)
                            ->orderBy('name', 'asc')
                            ->get();
                        
                        // Filter out featured categories
                        $displayCategories = $allCategories->filter(function($cat) {
                            return $cat->featured_order === null;
                        })->take(25); // Show maximum 25 categories
                    @endphp

                    @if($displayCategories->count() > 0)
                        @php
                            $chunkSize = 5;
                            $chunks = $displayCategories->chunk($chunkSize);
                        @endphp
                        
                        @foreach($chunks as $chunk)
                        <div class="popup-menu-row">
                            @foreach($chunk as $category)
                            <div class="popup-menu-col">
                                <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            </div>
                            @endforeach
                            
                            {{-- Fill remaining cells if less than 5 items --}}
                            @for($i = $chunk->count(); $i < $chunkSize; $i++)
                            <div class="popup-menu-col"></div>
                            @endfor
                        </div>
                        @endforeach
                    @else
                        <div class="popup-menu-row">
                            <div class="popup-menu-col" style="text-align: center; grid-column: 1 / -1; padding: 20px;">
                                <p style="color: #999; margin: 0;">কোন অন্যান্য ক্যাটাগরি নেই</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Popup Menu Overlay -->
        <div class="popup-menu-overlay" id="popupMenuOverlay" style="display: none;" onclick="togglePopupMenu()"></div>
    </div>

    <!-- Navigation Sticky Ad -->
    <div class="ad-navigation-sticky" style="background: white; border-bottom: 1px solid #eee; padding: 10px 0; display: none;">
        <div class="container d-none d-lg-block" style="text-align: center;">
            <x-ad-placement placement="navigation_sticky" random="true" class="ad-navigation" />
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="visible-header d-lg-none">
        <span class="burger" onclick="openNav()">☰</span>
        <a href="{{ route('home') }}" class="logo">
            @if($settings && $settings->mobile_logo)
                <img src="{{ asset('storage/' . $settings->mobile_logo) }}" alt="{{ $settings->site_name ?? 'লোগো' }}" style="max-height: 35px;">
            @else
                <span class="fw-bold">{{ $settings->site_name ?? 'Sajeb NEWS' }}</span>
            @endif
        </a>
        <span class="search" onclick="openSearch()">🔍</span>
        <form id="searchBox" action="{{ route('news.search') }}" method="get" style="display: none;">
            <label for="q"></label>
            <input type="text" name="q" id="q" inputmode="search" placeholder="অনুসন্ধান..." autofocus="">
            <span onclick="closeSearch()">×</span>
        </form>
    </div>

    <!-- Mobile Sidebar Menu -->
    <div id="navOverlay" class="nav-overlay" onclick="closeNav()"></div>
    <div id="sideNav" class="side-nav">
        <button class="closebtn" onclick="closeNav()">&times;</button>
        <a href="{{ route('home') }}">হোম</a>
        @foreach(\App\Models\Category::limit(10)->get() as $category)
        <a href="{{ route('category.show', $category->slug) }}" onclick="closeNav()">{{ $category->name }}</a>
        @endforeach
        <hr>
        @auth
        <a href="{{ route('admin.dashboard') }}" onclick="closeNav()">ড্যাশবোর্ড</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit(); closeNav();">লগআউট</a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @else
        <a href="{{ route('login') }}" onclick="closeNav()">লগইন</a>
        @endauth
    </div>

    <!-- DateTime Section -->
    <div class="DTime MobileHide hidden-print" style="background: #f9f9f9; border-bottom: 1px solid #e5e5e0; padding: 12px 0; margin-bottom: 20px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <p style="margin: 0; font-size: 14px; color: #333;">
                        <i class="fa fa-map-marker" aria-hidden="true" style="margin-right: 8px;"></i>
                        <strong>ঢাকা</strong> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px;"></i>
                        <span id="date-display" style="font-weight: 500;"></span>
                    </p>
                </div>
                <div class="col-sm-6 text-end">
                    <div class="DSocialLink">
                        <ul style="list-style: none; margin: 0; padding: 0; display: flex; justify-content: flex-end; gap: 15px;">
                            @if($settings && $settings->rss_feed)
                            <li><a href="{{ $settings->rss_feed }}" target="_blank" style="color: #ff6600; font-size: 16px;"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                            @endif
                            @if($settings && $settings->instagram_url)
                            <li><a href="{{ $settings->instagram_url }}" target="_blank" style="color: #E4405F; font-size: 16px;"><i class="fa fa-instagram"></i></a></li>
                            @endif
                            @if($settings && $settings->youtube_url)
                            <li><a href="{{ $settings->youtube_url }}" target="_blank" style="color: #FF0000; font-size: 16px;"><i class="fa fa-youtube"></i></a></li>
                            @endif
                            @if($settings && $settings->twitter_url)
                            <li><a href="{{ $settings->twitter_url }}" target="_blank" style="color: #1DA1F2; font-size: 16px;"><i class="fa fa-twitter"></i></a></li>
                            @endif
                            @if($settings && $settings->facebook_url)
                            <li><a href="{{ $settings->facebook_url }}" target="_blank" style="color: #1877F2; font-size: 16px;"><i class="fa fa-facebook"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Banner Ad -->
    <div class="ad-footer-banner" style="background: white; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 20px 0; margin: 40px 0 0 0;">
        <div class="container" style="text-align: center;">
            <x-ad-placement placement="footer_banner" random="true" class="ad-footer" />
        </div>
    </div>

    <!-- Footer -->
    <hr style="margin: 60px 0 0 0; border: none; border-top: 3px solid #e5e5e0;">
    
    <footer class="footer" style="background: white; margin-top: 0;"
        <!-- Footer Logo Section -->
        <div class="footer-logo" style="padding: 40px 0; border-bottom: 1px solid #e5e5e0;">
            <div class="container">
                <!-- Subscription Area -->
                <div class="row hidden-print" style="margin-bottom: 30px;">
                    <div class="col-sm-12 text-center">
                        <div id="subscription"></div>
                    </div>
                </div>

                <!-- Logo and Apps Section -->
                <div class="row" style="align-items: center;">
                    <!-- Logo -->
                    <div class="col-sm-4">
                        <a href="{{ route('home') }}">
                            @if($settings && $settings->footer_logo)
                                <img src="{{ asset('storage/' . $settings->footer_logo) }}" alt="{{ $settings->site_name ?? 'লোগো' }}" style="max-width: 260px; height: auto; display: block;" loading="lazy">
                            @else
                                <span style="font-size: 24px; font-weight: bold; color: #1b1b18;">{{ $settings->site_name ?? 'Sajeb NEWS' }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Center Space -->
                    <div class="col-sm-4 text-center subscribe hidden-print"></div>

                    <!-- Mobile Apps -->
                    <div class="col-sm-4 text-end hidden-print">
                        <div class="apps" style="display: flex; justify-content: flex-end; gap: 15px; flex-wrap: wrap;">
                            @if($settings && $settings->android_app_link)
                            <a href="{{ $settings->android_app_link }}" rel="nofollow" target="_blank">
                                <img src="https://cdn.jagonews24.com/media/common/Android-app-jagonews.png" alt="Android App" title="Android" loading="lazy" style="max-height: 40px;">
                            </a>
                            @endif
                            @if($settings && $settings->ios_app_link)
                            <a href="{{ $settings->ios_app_link }}" rel="nofollow" target="_blank">
                                <img src="https://cdn.jagonews24.com/media/common/apple-app-jagonews.png" alt="iPhone" title="iPhone" loading="lazy" style="max-height: 40px;">
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Top - About Section -->
        <div class="footer-top" style="background: #f9f9f9; padding: 40px 0; border-bottom: 1px solid #e5e5e0; display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 style="font-size: 24px; margin-bottom: 15px; color: #1b1b18;">{{ $settings->site_name ?? 'Sajeb NEWS' }}</h2>
                        <p style="color: #706f6c; line-height: 1.8; margin-bottom: 0;">
                            {{ $settings->site_description ?? 'বাংলাদেশের সর্বাধুনিক নিউজ পোর্টাল। আমরা আপনাকে সত্য এবং নির্ভরযোগ্য সংবাদ প্রদান করি।' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom - Contact and Menu -->
        <div class="footer-bottom" style="background: white; padding: 40px 0; border-top: 1px solid #e5e5e0;">
            <div class="container">
                <div class="row">
                    <!-- Left Section - Contact Info -->
                    <div class="col-sm-7">
                        <p style="font-size: 13px; line-height: 1.8; margin-bottom: 0; color: #666;">
                            <span style="display: block; margin-bottom: 12px;">
                                <strong style="color: #1b1b18;">সম্পাদক:</strong> {{ $settings->editor_name ?? 'সম্পাদক নাম' }}<br>
                                <strong style="color: #1b1b18;">© {{ date('Y') }} সর্বস্বত্ব সংরক্ষিত |</strong> {{ $settings->site_name ?? 'Sajeb NEWS' }}{{ $settings->organization_name ? ', ' . $settings->organization_name : '' }}
                            </span>
                            
                            <!-- Address -->
                            @if($settings && $settings->address)
                            <span style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;">
                                <svg style="width: 16px; height: 16px; color: #ff6600; flex-shrink: 0; margin-top: 2px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="map-marker-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"></path></svg>
                                <span>{{ $settings->address }}</span>
                            </span>
                            @endif
                            
                            <!-- Phone -->
                            @if($settings && $settings->phone)
                            <span style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;">
                                <svg style="width: 16px; height: 16px; color: #ff6600; flex-shrink: 0; margin-top: 2px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="phone" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-35 76.7-94.6 135.2-171.3 171.3l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 415.4-2.6 427 .5 438.4l24 104c2.1 8.3 10.3 14.4 19.3 14.4 84.6 0 163.9-33.6 223.5-95.1 54.5-52.4 88.9-128.1 95.1-207.6.4-4.4.16-8.8 0-13.2 0-9 6.1-17.2 14.4-19.3l104-24c10.6-2.4 16.5-13.1 13.9-23.7z"></path></svg>
                                <span>{{ $settings->phone }}</span>
                            </span>
                            @endif
                            
                            <!-- Email -->
                            @if($settings && $settings->email)
                            <span style="display: flex; gap: 10px; align-items: flex-start;">
                                <svg style="width: 16px; height: 16px; color: #ff6600; flex-shrink: 0; margin-top: 2px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path></svg>
                                <a href="mailto:{{ $settings->email }}" style="color: #ff6600; text-decoration: none;">{{ $settings->email }}</a>
                            </span>
                            @endif
                        </p>
                    </div>

                    <!-- Right Section - Menu -->
                    <div class="col-sm-5 footer-top hidden-print">
                        <ul class="footer-menu" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                            <li><a href="{{ route('home') }}" style="color: #1b1b18; text-decoration: none; font-size: 13px; transition: color 0.3s; font-weight: 500;">হোম</a></li>
                            <li><a href="#" style="color: #1b1b18; text-decoration: none; font-size: 13px; transition: color 0.3s; font-weight: 500;">আমাদের কথা</a></li>
                            <li><a href="#" style="color: #1b1b18; text-decoration: none; font-size: 13px; transition: color 0.3s; font-weight: 500;">যোগাযোগ</a></li>
                            <li><a href="#" style="color: #1b1b18; text-decoration: none; font-size: 13px; transition: color 0.3s; font-weight: 500;">প্রাইভেসি পলিসি</a></li>
                            <li><a href="#" target="_blank" style="color: #1b1b18; text-decoration: none; font-size: 13px; transition: color 0.3s; font-weight: 500;">শর্তাবলী</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll to Top Button -->
        <div class="scroll-top-wrapper hidden-print" id="scrollToTopBtn" style="display: none; position: fixed; bottom: 180px; right: 30px; z-index: 99; cursor: pointer;">
            <span class="scroll-top-inner" style="background: #ff6600; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.3s; color: white;">
                <svg class="backtotop" style="width: 24px; height: 24px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"></path></svg>
            </span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Mobile Navigation Styles -->
    <style>
        /* Header Menu */
        .DHeaderMenu {
            background: white;
            border-bottom: 1px solid #e5e5e0;
        }

        .Dlogo a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #1b1b18;
        }

        .Dlogo img {
            max-height: 50px;
            object-fit: contain;
        }

        .navbar {
            padding: 12px 0 !important;
        }

        .navbar-nav {
            gap: 5px;
        }

        .nav-link {
            padding: 8px 16px !important;
            font-size: 14px;
            font-weight: 500;
            color: #1b1b18 !important;
            transition: all 0.3s;
            position: relative;
        }

        .nav-link:hover {
            color: #3b82f6 !important;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 4px;
        }

        .nav-link-search {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            color: #1b1b18;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }

        .nav-link-search:hover {
            background: #3b82f6;
            color: white;
        }

        /* Search Block */
        .search_block {
            background: white;
            border-bottom: 1px solid #e5e5e0;
            padding: 15px 0;
        }

        .search_block form {
            gap: 10px;
        }

        .search_block input {
            border: 1px solid #e5e5e0;
            padding: 10px 16px;
            border-radius: 4px;
            font-size: 14px;
        }

        .search_block input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search_block button {
            color: #706f6c;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
        }

        .search_block button:hover {
            color: #3b82f6;
        }

        /* Popup Menu */
        .popup-menu {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: white;
            border-bottom: 1px solid #e5e5e0;
        }

        .popup-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1040;
        }

        .popup-menu-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 16px;
        }

        .popup-menu-table {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .popup-menu-row {
            display: table-row;
        }

        .popup-menu-col {
            display: table-cell;
            padding: 12px 20px;
            border-right: 1px solid #e5e5e0;
        }

        .popup-menu-col:last-child {
            border-right: none;
        }

        .popup-menu-col a {
            color: #1b1b18;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: block;
            padding: 8px 0;
            transition: all 0.3s;
        }

        .popup-menu-col a:hover {
            color: #3b82f6;
            padding-left: 8px;
        }

        .others-menu {
            position: relative;
        }

        .others-menu i {
            margin-left: 0;
            font-size: 12px;
            transition: all 0.3s;
        }

        @media (max-width: 1199px) {
            .popup-menu-col {
                padding: 10px 15px;
            }
        }

        .visible-header {
            background-color: #212529;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .visible-header .burger {
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
            background: none;
            border: none;
            color: white;
            padding: 5px;
        }

        .visible-header .logo {
            flex: 1;
            text-align: center;
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visible-header .logo img {
            max-height: 35px;
        }

        .visible-header .search {
            cursor: pointer;
            font-size: 18px;
            margin-left: auto;
        }

        #searchBox {
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: none;
        }

        #searchBox input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        #searchBox span {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            font-size: 24px;
            color: #999;
        }

        /* Overlay for sidebar */
        .nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
        }

        .nav-overlay.active {
            display: block;
        }

        .side-nav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 999;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-y: auto;
            transition: width 0.3s ease;
            padding-top: 20px;
        }

        .side-nav a {
            padding: 12px 32px;
            text-decoration: none;
            font-size: 16px;
            color: #818181;
            display: block;
            transition: 0.3s;
            border-bottom: 1px solid #333;
        }

        .side-nav a:hover {
            color: #f1f1f1;
            background-color: #262626;
        }

        .side-nav .closebtn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 32px;
            padding: 0;
            margin: 0;
            cursor: pointer;
            color: #818181;
            background: none;
            border: none;
        }

        .side-nav .closebtn:hover {
            color: #f1f1f1;
        }

        .side-nav hr {
            border-color: #333;
            margin: 10px 0;
        }

        @media (min-width: 992px) {
            .visible-header {
                display: none !important;
            }
            .side-nav {
                display: none !important;
            }
            .nav-overlay {
                display: none !important;
            }
        }
    </style>

    <script>
        // Scroll to Top Functionality
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Hover effect for scroll button
        scrollToTopBtn.addEventListener('mouseenter', function() {
            this.querySelector('.scroll-top-inner').style.background = '#ff5500';
            this.querySelector('.scroll-top-inner').style.transform = 'scale(1.1)';
        });

        scrollToTopBtn.addEventListener('mouseleave', function() {
            this.querySelector('.scroll-top-inner').style.background = '#ff6600';
            this.querySelector('.scroll-top-inner').style.transform = 'scale(1)';
        });

        // Footer menu link hover effect
        document.querySelectorAll('.footer-menu a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.color = '#ff6600';
            });
            link.addEventListener('mouseleave', function() {
                this.style.color = '#1b1b18';
            });
        });

        // Popup Menu Toggle
        function togglePopupMenu() {
            const popupMenu = document.getElementById('popupMenu');
            const overlay = document.getElementById('popupMenuOverlay');
            
            if (popupMenu.style.display === 'none' || !popupMenu.style.display) {
                popupMenu.style.display = 'block';
                overlay.style.display = 'block';
            } else {
                popupMenu.style.display = 'none';
                overlay.style.display = 'none';
            }
        }

        // Search Toggle
        function toggleSearch() {
            const searchBlock = document.getElementById('searchBlock');
            
            if (searchBlock.style.display === 'none' || !searchBlock.style.display) {
                searchBlock.style.display = 'block';
                const searchInput = searchBlock.querySelector('input');
                if (searchInput) searchInput.focus();
            } else {
                searchBlock.style.display = 'none';
            }
        }

        // Close popup menu when clicking outside
        document.addEventListener('click', function(e) {
            const popupMenu = document.getElementById('popupMenu');
            const othersMenu = document.querySelector('.others-menu');
            
            if (popupMenu && othersMenu && !othersMenu.contains(e.target) && !popupMenu.contains(e.target)) {
                popupMenu.style.display = 'none';
                document.getElementById('popupMenuOverlay').style.display = 'none';
            }
        });

        function openNav() {
            document.getElementById("sideNav").style.width = "250px";
            document.getElementById("navOverlay").classList.add("active");
            document.body.style.overflow = "hidden";
        }

        function closeNav() {
            document.getElementById("sideNav").style.width = "0";
            document.getElementById("navOverlay").classList.remove("active");
            document.body.style.overflow = "auto";
        }

        function openSearch() {
            document.getElementById("searchBox").style.display = "block";
            document.getElementById("q").focus();
        }

        function closeSearch() {
            document.getElementById("searchBox").style.display = "none";
        }

        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('লিঙ্ক কপি করা হয়েছে!');
            }).catch(err => {
                console.error('কপি করতে ব্যর্থ:', err);
            });
        }

        // Close menu when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeNav();
                closeSearch();
                document.getElementById('popupMenu').style.display = 'none';
                document.getElementById('popupMenuOverlay').style.display = 'none';
            }
        });

        // Hide ticker when footer is visible
        function manageTickerVisibility() {
            const ticker = document.querySelector('.news-ticker');
            const footer = document.querySelector('footer');

            if (!ticker || !footer) return;

            // Get the position of footer from top
            const footerRect = footer.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            // If footer is visible in the viewport (top of footer is above bottom of viewport)
            if (footerRect.top < windowHeight) {
                // Hide ticker with smooth transition
                ticker.style.opacity = '0';
                ticker.style.pointerEvents = 'none';
                ticker.style.transition = 'opacity 0.3s ease-in-out';
            } else {
                // Show ticker
                ticker.style.opacity = '1';
                ticker.style.pointerEvents = 'auto';
                ticker.style.transition = 'opacity 0.3s ease-in-out';
            }
        }

        // Throttle function to optimize scroll performance
        function throttle(func, delay) {
            let lastCall = 0;
            return function(...args) {
                const now = new Date().getTime();
                if (now - lastCall < delay) return;
                lastCall = now;
                func(...args);
            };
        }

        // Add scroll listener with throttling
        window.addEventListener('scroll', throttle(manageTickerVisibility, 100));

        // Initial check on page load
        document.addEventListener('DOMContentLoaded', manageTickerVisibility);
    </script>

    <!-- Push Notification Manager -->
    <script src="{{ asset('js/push-notification-manager.js') }}" defer></script>
    <script defer>
        // Initialize Push Notification Manager
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize if push notifications are supported
            const manager = new PushNotificationManager();
            
            if (manager.isSupported) {
                // Store manager globally for use in UI
                window.pushNotificationManager = manager;
                
                // Optionally show notification banner if not subscribed
                // This can be customized based on your needs
            }
        });
    </script>

    <!-- Bangla Date Converter Script -->
    <script>
        // Convert English numbers to Bangla numerals
        function enToBn(str) {
            const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            let result = String(str);
            for (let i = 0; i < englishNumbers.length; i++) {
                result = result.replace(new RegExp(englishNumbers[i], 'g'), banglaNumbers[i]);
            }
            return result;
        }

        // Get Bangla day name
        function getBanglaDayName(dayIndex) {
            const banglaDays = ['রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'];
            return banglaDays[dayIndex];
        }

        // Get Bangla month name
        function getBanglaMonthName(monthIndex) {
            const banglaMonths = [
                'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
                'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
            ];
            return banglaMonths[monthIndex];
        }

        // Get Bengali calendar date from English date
        function getBeninglCalendarDate(englishDate) {
            // Simple Bengali calendar calculation
            // Note: This is a simplified conversion. For accurate Bengali calendar, use a library
            const adjustementDays = 79; // Approximate difference
            const bengaliDate = new Date(englishDate.getTime() + adjustementDays * 24 * 60 * 60 * 1000);
            
            // Bengali months (they don't align perfectly with English calendar)
            // This is a simplified version
            const bengaliMonths = ['বৈশাখ', 'জৈষ্ঠ', 'আষাঢ়', 'শ্রাবণ', 'ভাদ্র', 'আশ্বিন', 
                                  'কার্তিক', 'অগ্রহায়ণ', 'পৌষ', 'মাঘ', 'ফাল্গুন', 'চৈত্র'];
            
            // Month mapping (simplified)
            let bengaliMonth = bengaliMonths[(bengaliDate.getMonth() + 9) % 12];
            let bengaliDay = bengaliDate.getDate();
            let bengaliYear = bengaliDate.getFullYear() - 593; // Approximate Bengali year
            
            return {
                month: bengaliMonth,
                day: bengaliDay,
                year: bengaliYear
            };
        }

        // Update date display
        function updateBanglaDate() {
            const now = new Date();
            
            // English date
            const dayName = getBanglaDayName(now.getDay());
            const monthName = getBanglaMonthName(now.getMonth());
            const day = enToBn(now.getDate());
            const year = enToBn(now.getFullYear());
            
            // Bengali calendar date
            const bengaliDate = getBeninglCalendarDate(now);
            const bengaliMonthName = bengaliDate.month;
            const bengaliDay = enToBn(bengaliDate.day);
            const bengaliYear = enToBn(bengaliDate.year);
            
            // Format: দিনবার, তারিখ মাস বছর || বাংলা মাস বাংলা তারিখ বাংলা বছর
            const dateString = dayName + ', ' + day + ' ' + monthName + ' ' + year + 
                             ' || ' + bengaliMonthName + ' ' + bengaliDay + ' ' + bengaliYear;
            
            const dateDisplay = document.getElementById('date-display');
            if (dateDisplay) {
                dateDisplay.textContent = dateString;
            }
        }

        // Update date when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateBanglaDate);
        } else {
            updateBanglaDate();
        }

        // Update date every minute
        setInterval(updateBanglaDate, 60000);
    </script>
    
    <!-- Custom JS -->
    @yield('scripts')
    
    <!-- Google Tag Manager (noscript) -->
    @if(config('services.google_tag_manager_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.google_tag_manager_id') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
</body>
</html>
