@extends('public.layout')

@section('title', 'সজীব নিউজ | নির্ভরযোগ্য খবরের ঠিকানা')
@section('meta_description', 'সজীব নিউজ - বাংলাদেশের সর্বশেষ সংবাদ, রাজনীতি, খেলাধুলা, বিনোদন ও প্রযুক্তির নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')

@section('content')

@if(session('success'))
<div class="max-w-container-max mx-auto px-gutter mt-4">
  <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded flex items-center gap-3">
    <span class="material-symbols-outlined text-green-600">check_circle</span>
    <p class="font-body-main">{{ session('success') }}</p>
  </div>
</div>
@endif
@if(session('error'))
<div class="max-w-container-max mx-auto px-gutter mt-4">
  <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded flex items-center gap-3">
    <span class="material-symbols-outlined text-red-600">error</span>
    <p class="font-body-main">{{ session('error') }}</p>
  </div>
</div>
@endif

@php
  $heroNews      = $featured->first() ?? $latest->first();
  $secondaryNews = $featured->skip(1)->take(4)->merge($latest->take(4))->unique('id')->take(4);
  $popularNews   = \App\Models\News::where('status','published')->orderBy('views','desc')->limit(5)->get();
@endphp

<!-- Breaking News Ticker -->
@if($breaking->count() > 0)
<div class="w-full bg-primary text-white overflow-hidden h-10 flex items-center">
  <div class="px-4 py-1 bg-secondary font-headline-md text-white whitespace-nowrap z-10 flex items-center gap-2 flex-shrink-0">
    <span class="animate-pulse w-2 h-2 bg-white rounded-full inline-block"></span>
    সদ্য সংবাদ
  </div>
  <div class="overflow-hidden flex-1">
    <div class="ticker-scroll whitespace-nowrap font-body-main text-body-sm flex gap-16 items-center">
      @foreach($breaking as $bk)
      <a href="{{ route('news.show', $bk->slug) }}" class="hover:underline">{{ $bk->title }}</a>
      @endforeach
    </div>
  </div>
</div>
@endif

<!-- Homepage Top Ad -->
@php $__hpTopAd = \App\Helpers\AdHelper::getRandomAdByPlacement('homepage_top'); @endphp
@if($__hpTopAd)
<div class="max-w-container-max mx-auto px-gutter pt-4 text-center">
  <p class="text-[10px] text-outline-variant uppercase tracking-widest mb-1">বিজ্ঞাপন</p>
  {!! \App\Helpers\AdHelper::renderAd($__hpTopAd) !!}
</div>
@endif

<!-- Main Content -->
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <!-- ── Left: Main Content ────────────────────────── -->
    <div class="lg:col-span-8 space-y-stack-lg">

      <!-- Hero Article -->
      @if($heroNews)
      <article class="group cursor-pointer">
        <a href="{{ route('news.show', $heroNews->slug) }}">
          <div class="relative overflow-hidden rounded-xl aspect-[16/9] mb-stack-md">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 src="{{ $heroNews->featured_image ? \Storage::url($heroNews->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                 alt="{{ $heroNews->title }}" width="1200" height="675" fetchpriority="high"/>
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary/90 to-transparent p-6 pt-20">
              @if($heroNews->category)
              <span class="inline-block bg-secondary text-white px-3 py-1 text-label-caps font-label-caps mb-3">{{ $heroNews->category->name }}</span>
              @endif
              <h2 class="font-headline-lg text-white text-3xl md:text-5xl group-hover:underline transition-all leading-tight">{{ $heroNews->title }}</h2>
            </div>
          </div>
          @if($heroNews->excerpt)
          <p class="font-body-main text-on-surface-variant line-clamp-3">{{ $heroNews->excerpt }}</p>
          @endif
          <div class="mt-4 flex items-center gap-4 text-meta-data font-meta-data text-outline">
            @if($heroNews->published_at)
            <span>{{ $heroNews->published_at->locale('bn')->isoFormat('D MMMM, YYYY') }}</span>
            @endif
            @if($heroNews->reading_time)
            <span>•</span><span>{{ $heroNews->reading_time }} মিনিট পাঠ</span>
            @endif
          </div>
        </a>
      </article>
      <hr class="border-subtle"/>
      @endif

      <!-- Secondary News Grid (2×2) -->
      @if($secondaryNews->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md">
        @foreach($secondaryNews as $sn)
        <a href="{{ route('news.show', $sn->slug) }}" class="flex gap-4 group cursor-pointer">
          <div class="flex-shrink-0 w-32 h-24 overflow-hidden rounded-lg">
            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                 src="{{ $sn->featured_image ? \Storage::url($sn->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                 alt="{{ $sn->title }}" loading="lazy" width="128" height="96"/>
          </div>
          <div>
            <h3 class="font-headline-md text-primary leading-tight group-hover:text-secondary transition-colors line-clamp-2">{{ $sn->title }}</h3>
            <p class="text-meta-data font-meta-data text-outline mt-2">
              {{ $sn->category->name ?? '' }}
              @if($sn->published_at) | {{ $sn->published_at->diffForHumans() }} @endif
            </p>
          </div>
        </a>
        @endforeach
      </div>
      @endif

      <!-- ── All Category Sections ── -->
      @isset($allCategories)
        @foreach($allCategories as $cat)
        @php $catNews = $cat->latestNews; @endphp
        @if($catNews->count() > 0)
        <section class="pt-stack-lg">
          <!-- Section Header -->
          <div class="flex items-center justify-between mb-5 border-b-2 border-primary pb-1">
            <h2 class="bg-primary text-white px-4 py-1.5 font-headline-md text-base tracking-wide">{{ $cat->name }}</h2>
            <a class="text-body-sm font-body-sm text-outline hover:text-secondary transition-colors flex items-center gap-1"
               href="{{ route('category.show', $cat->slug) }}">
              সব খবর
              <span class="material-symbols-outlined text-base leading-none">arrow_forward</span>
            </a>
          </div>

          @php
            $firstNews  = $catNews->first();
            $restNews   = $catNews->skip(1);
          @endphp

          <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Big card (spans 2 cols on md) -->
            <a href="{{ route('news.show', $firstNews->slug) }}" class="md:col-span-2 group block">
              <div class="relative aspect-[16/9] overflow-hidden rounded-xl mb-3">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                     src="{{ $firstNews->featured_image ? \Storage::url($firstNews->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                     alt="{{ $firstNews->title }}" loading="lazy" width="800" height="450"/>
                @if($firstNews->is_breaking)
                <span class="absolute top-3 left-3 bg-secondary text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">ব্রেকিং</span>
                @endif
              </div>
              <h3 class="font-headline-md text-lg leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $firstNews->title }}</h3>
              @if($firstNews->excerpt)
              <p class="text-body-sm font-body-main text-on-surface-variant mt-1 line-clamp-2">{{ $firstNews->excerpt }}</p>
              @endif
              @if($firstNews->published_at)
              <p class="text-meta-data font-meta-data text-outline mt-2">{{ $firstNews->published_at->diffForHumans() }}</p>
              @endif
            </a>

            <!-- Side list -->
            <div class="flex flex-col gap-4">
              @foreach($restNews->take(5) as $cn)
              <a href="{{ route('news.show', $cn->slug) }}" class="flex gap-3 group cursor-pointer border-b border-subtle pb-4 last:border-0 last:pb-0">
                <div class="flex-shrink-0 w-20 h-16 overflow-hidden rounded-lg">
                  <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                       src="{{ $cn->featured_image ? \Storage::url($cn->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                       alt="{{ $cn->title }}" loading="lazy" width="80" height="64"/>
                </div>
                <div class="min-w-0">
                  <h4 class="font-headline-md text-sm leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $cn->title }}</h4>
                  @if($cn->published_at)
                  <p class="text-[11px] text-outline mt-1">{{ $cn->published_at->diffForHumans() }}</p>
                  @endif
                </div>
              </a>
              @endforeach
            </div>
          </div>
        </section>
        @endif
        @endforeach
      @endisset

    </div><!-- /left col-8 -->

    <!-- ── Right: Sidebar ───────────────────────────── -->
    <aside class="lg:col-span-4 space-y-stack-lg">

      <!-- Popular News Widget -->
      @if($popularNews->count() > 0)
      <div class="bg-surface-container-low p-6 rounded-xl border border-subtle">
        <h2 class="font-headline-md border-b border-subtle pb-3 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary">trending_up</span>
          পাঠকপ্রিয় খবর
        </h2>
        <ul class="space-y-4">
          @foreach($popularNews as $idx => $pn)
          <li>
            <a href="{{ route('news.show', $pn->slug) }}" class="flex gap-4 group cursor-pointer">
              <span class="font-display-breaking text-3xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0">{{ str_pad($idx+1, 2, '0', STR_PAD_LEFT) }}</span>
              <p class="font-body-main text-body-sm leading-tight group-hover:underline">{{ $pn->title }}</p>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- Newsletter Signup -->
      <div class="bg-news-blue-dark text-white p-6 rounded-xl relative overflow-hidden shadow-lg">
        <div class="relative z-10">
          <h3 class="font-headline-md text-xl mb-2">প্রতিদিনের খবর ইমেইলে পান</h3>
          <p class="text-body-sm opacity-80 mb-4">সেরা খবরগুলো মিস করবেন না। এখনই সাবস্ক্রাইব করুন।</p>
          <form class="space-y-3" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input name="email" type="email" required class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:border-white focus:ring-1 focus:ring-white text-white placeholder:text-white/50" placeholder="আপনার ইমেইল অ্যাড্রেস"/>
            <button type="submit" class="w-full bg-secondary hover:bg-news-red-accent transition-colors py-2 rounded-lg font-label-caps text-label-caps uppercase tracking-widest">নিউজলেটার সাবস্ক্রাইব</button>
          </form>
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
          <span class="material-symbols-outlined text-[120px]">mail</span>
        </div>
      </div>

      <!-- Category Quick Links -->
      @isset($allCategories)
      <div class="bg-surface-container-low p-6 rounded-xl border border-subtle">
        <h2 class="font-headline-md border-b border-subtle pb-3 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">category</span>
          বিভাগ
        </h2>
        <div class="flex flex-wrap gap-2">
          @foreach($allCategories as $cat)
          <a href="{{ route('category.show', $cat->slug) }}"
             class="px-3 py-1.5 bg-primary/10 text-primary text-sm font-medium rounded-full hover:bg-primary hover:text-white transition-colors">
            {{ $cat->name }}
            <span class="text-xs opacity-70">({{ $cat->news_count }})</span>
          </a>
          @endforeach
        </div>
      </div>
      @endisset

      <!-- Sidebar Ads -->
      @php
        $__sidebarAd1 = \App\Helpers\AdHelper::getRandomAdByPlacement('sidebar_medium_rectangle');
        $__sidebarAd2 = \App\Helpers\AdHelper::getRandomAdByPlacement('sidebar_half_page');
      @endphp
      @if($__sidebarAd1)
      <div class="text-center">
        <p class="text-[10px] text-outline-variant uppercase tracking-widest mb-1">বিজ্ঞাপন</p>
        {!! \App\Helpers\AdHelper::renderAd($__sidebarAd1) !!}
      </div>
      @endif
      @if($__sidebarAd2)
      <div class="text-center">
        <p class="text-[10px] text-outline-variant uppercase tracking-widest mb-1">বিজ্ঞাপন</p>
        {!! \App\Helpers\AdHelper::renderAd($__sidebarAd2) !!}
      </div>
      @endif

      <!-- Trending Widget -->
      @if($trending && $trending->count() > 0)
      <div class="bg-surface-container-high p-6 rounded-xl border border-subtle">
        <h2 class="font-headline-md border-b border-subtle pb-3 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary">whatshot</span>
          ট্রেন্ডিং
        </h2>
        <ol class="space-y-4">
          @foreach($trending->take(5) as $ti => $tn)
          <li>
            <a href="{{ route('news.show', $tn->slug) }}" class="flex gap-4 group cursor-pointer {{ $ti > 0 ? 'border-t border-subtle pt-4' : '' }}">
              <span class="font-display-breaking text-3xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0">{{ str_pad($ti+1, 2, '0', STR_PAD_LEFT) }}</span>
              <div>
                <h4 class="font-headline-md text-[16px] leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $tn->title }}</h4>
                <span class="font-meta-data text-meta-data text-on-surface-variant">{{ number_format($tn->views) }} বার পঠিত</span>
              </div>
            </a>
          </li>
          @endforeach
        </ol>
      </div>
      @endif

    </aside>
  </div>
</main>

<!-- Latest News Section -->
@if($latest->count() > 0)
<section class="max-w-container-max mx-auto px-gutter py-section-padding">
  <div class="flex items-center justify-between mb-6 border-b-2 border-primary">
    <h2 class="bg-primary text-white px-4 py-1 font-headline-md">সর্বশেষ সংবাদ</h2>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($latest->take(6) as $ln)
    <a href="{{ route('news.show', $ln->slug) }}" class="group block">
      <div class="aspect-video overflow-hidden rounded-lg mb-3">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             src="{{ $ln->featured_image ? \Storage::url($ln->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
             alt="{{ $ln->title }}"/>
      </div>
      @if($ln->category)
      <span class="text-secondary font-label-caps text-label-caps">{{ $ln->category->name }}</span>
      @endif
      <h4 class="font-headline-md mt-1 group-hover:text-secondary transition-colors line-clamp-2">{{ $ln->title }}</h4>
      @if($ln->published_at)
      <p class="text-meta-data font-meta-data text-outline mt-2">{{ $ln->published_at->diffForHumans() }}</p>
      @endif
    </a>
    @endforeach
  </div>
</section>
@endif

@endsection
