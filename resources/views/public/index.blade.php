@extends('public.layout')

@php $__homeSeo = \App\Models\SeoSetting::first(); @endphp
@section('title', ($__homeSeo?->site_name ?: 'সজীব নিউজ') . ' | ' . ($__homeSeo?->site_title ?: 'নির্ভরযোগ্য খবরের ঠিকানা'))
@section('meta_description', $__homeSeo?->site_description ?: 'বাংলাদেশের সর্বশেষ সংবাদ, রাজনীতি, খেলাধুলা, বিনোদন ও প্রযুক্তির নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')
@section('canonical', route('home'))
@section('og_type', 'website')
@section('og_title', ($__homeSeo?->site_name ?: 'সজীব নিউজ') . ' — ' . ($__homeSeo?->site_title ?: 'নির্ভরযোগ্য খবরের ঠিকানা'))
@section('og_description', $__homeSeo?->site_description ?: 'বাংলাদেশের নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')
@section('og_url', route('home'))
@if($__homeSeo?->og_image)
@section('og_image', asset('storage/' . $__homeSeo->og_image))
@elseif($__homeSeo?->logo)
@section('og_image', asset('storage/' . $__homeSeo->logo))
@endif

@section('content')

@if(session('success'))
<div class="max-w-container-max mx-auto px-gutter mt-3">
  <div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded flex items-center gap-2">
    <span class="material-symbols-outlined text-green-600 text-lg">check_circle</span>
    <p class="font-body-main text-sm">{{ session('success') }}</p>
  </div>
</div>
@endif
@if(session('error'))
<div class="max-w-container-max mx-auto px-gutter mt-3">
  <div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded flex items-center gap-2">
    <span class="material-symbols-outlined text-red-600 text-lg">error</span>
    <p class="font-body-main text-sm">{{ session('error') }}</p>
  </div>
</div>
@endif

@php
  $heroNews      = $featured->first() ?? $latest->first();
  $sideHeroNews  = $featured->skip(1)->take(3)->merge($latest->take(3))->unique('id')->take(3);
  $secondaryNews = $featured->skip(4)->take(4)->merge($latest->skip(1)->take(4))->unique('id')->take(4);
  $popularNews   = \App\Models\News::where('status','published')->orderBy('views','desc')->limit(8)->get();
@endphp

<!-- Breaking News Ticker -->
@if($breaking->count() > 0)
<div class="w-full bg-primary text-white overflow-hidden h-10">
  <div class="w-full h-full flex items-center">
    <div class="bg-secondary px-2 md:px-4 text-[11px] md:text-headline-md font-bold md:font-headline-md whitespace-nowrap z-10 flex items-center gap-1.5 md:gap-2 flex-shrink-0 h-full">
      <span class="animate-pulse w-1.5 h-1.5 md:w-2 md:h-2 bg-white rounded-full inline-block"></span>
      ব্রেকিং
    </div>
    <div class="overflow-hidden flex-1 h-full flex items-center">
      <div class="ticker-scroll whitespace-nowrap font-body-main text-body-sm flex gap-6 items-center" style="font-family:'SolaimanLipi',serif;">
        @foreach($breaking as $bk)
        <a href="{{ route('news.show', $bk->slug) }}" class="hover:underline">{{ $bk->title }}</a>
        <span class="text-white/50">•</span>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

<!-- Homepage Top Ad -->
@php $__hpTopAd = \App\Helpers\AdHelper::getRandomAdByPlacement('homepage_top'); @endphp
@if($__hpTopAd)
<div class="max-w-container-max mx-auto px-gutter pt-3 text-center hp-top-ad" style="max-height:90px;overflow:hidden;">
  <style>.hp-top-ad .ad-wrapper{margin:0 auto!important}.hp-top-ad .ad-wrapper img{max-height:90px;width:auto;max-width:970px;object-fit:contain}</style>
  {!! \App\Helpers\AdHelper::renderAd($__hpTopAd) !!}
</div>
@endif

<!-- ═══════════ HERO SECTION ═══════════ -->
<main class="max-w-container-max mx-auto px-gutter pt-4 pb-2">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

    <!-- Hero Left: Big featured news -->
    @if($heroNews)
    <div class="lg:col-span-7">
      <article class="group cursor-pointer">
        <a href="{{ route('news.show', $heroNews->slug) }}">
          <div class="relative overflow-hidden rounded-lg aspect-[16/9]">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 src="{{ $heroNews->featured_image ? storage_image_url($heroNews->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
                 alt="{{ $heroNews->title }}" width="800" height="450" fetchpriority="high"/>
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 pt-16">
              @if($heroNews->category)
              <span class="inline-block bg-secondary text-white px-2 py-0.5 text-xs font-label-caps mb-2">{{ $heroNews->category->name }}</span>
              @endif
              <h2 class="font-headline-lg text-white text-xl md:text-2xl lg:text-3xl leading-tight group-hover:underline">{{ $heroNews->title }}</h2>
              @if($heroNews->published_at)
              <p class="text-white/70 text-xs mt-2">{{ $heroNews->published_at->locale('bn')->isoFormat('D MMMM, YYYY') }}</p>
              @endif
            </div>
          </div>
        </a>
      </article>
    </div>
    @endif

    <!-- Hero Right: 3 stacked news -->
    <div class="lg:col-span-5 flex flex-col gap-3">
      @foreach($sideHeroNews as $shn)
      <a href="{{ route('news.show', $shn->slug) }}" class="flex gap-3 group cursor-pointer border-b border-subtle pb-3 last:border-0 last:pb-0">
        <div class="flex-shrink-0 w-28 h-20 md:w-36 md:h-24 overflow-hidden rounded-lg">
          <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
               src="{{ $shn->featured_image ? storage_image_url($shn->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
               alt="{{ $shn->title }}" loading="lazy" width="144" height="96"/>
        </div>
        <div class="min-w-0 flex-1">
          @if($shn->category)
          <span class="text-secondary text-xs font-label-caps uppercase">{{ $shn->category->name }}</span>
          @endif
          <h3 class="font-headline-md text-base leading-snug group-hover:text-secondary transition-colors line-clamp-2 mt-0.5">{{ $shn->title }}</h3>
          @if($shn->published_at)
          <p class="text-xs text-outline mt-1">{{ $shn->published_at->diffForHumans() }}</p>
          @endif
        </div>
      </a>
      @endforeach
    </div>

  </div>
</main>

<!-- ═══════════ SECONDARY NEWS STRIP ═══════════ -->
@if($secondaryNews->count() > 0)
<div class="max-w-container-max mx-auto px-gutter py-3">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($secondaryNews as $sn)
    <a href="{{ route('news.show', $sn->slug) }}" class="group block">
      <div class="aspect-[16/10] overflow-hidden rounded-lg mb-2">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             src="{{ $sn->featured_image ? storage_image_url($sn->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
             alt="{{ $sn->title }}" loading="lazy" width="300" height="188"/>
      </div>
      @if($sn->category)
      <span class="text-secondary font-label-caps text-xs uppercase">{{ $sn->category->name }}</span>
      @endif
      <h4 class="font-headline-md text-base leading-snug mt-0.5 group-hover:text-secondary transition-colors line-clamp-2">{{ $sn->title }}</h4>
    </a>
    @endforeach
  </div>
</div>
<hr class="border-subtle max-w-container-max mx-auto"/>
@endif

<!-- ═══════════ MAIN CONTENT + SIDEBAR ═══════════ -->
<div class="max-w-container-max mx-auto px-gutter py-4">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

    <!-- ── Left: Main Content ── -->
    <div class="lg:col-span-8 space-y-5">

      <!-- ── Category Sections ── -->
      @isset($allCategories)
        @foreach($allCategories as $catIdx => $cat)
        @php $catNews = $cat->latestNews; @endphp
        @if($catNews->count() > 0)
        <section>
          <!-- Section Header -->
          <div class="flex items-center justify-between mb-3 border-b-2 border-primary pb-1">
            <h2 class="bg-primary text-white px-3 py-1 font-headline-md text-base tracking-wide">{{ $cat->name }}</h2>
            <a class="text-sm font-body-sm text-outline hover:text-secondary transition-colors flex items-center gap-0.5"
               href="{{ route('category.show', $cat->slug) }}">
              সব খবর <span class="material-symbols-outlined text-base leading-none">arrow_forward</span>
            </a>
          </div>

          @php
            $firstNews = $catNews->first();
            $restNews  = $catNews->skip(1);
          @endphp

          <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Lead story (left) -->
            <a href="{{ route('news.show', $firstNews->slug) }}" class="md:col-span-7 group block">
              <div class="relative aspect-[16/9] overflow-hidden rounded-lg mb-2">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     src="{{ $firstNews->featured_image ? storage_image_url($firstNews->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
                     alt="{{ $firstNews->title }}" loading="lazy" width="600" height="338"/>
                @if($firstNews->is_breaking)
                <span class="absolute top-2 left-2 bg-secondary text-white text-[9px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">ব্রেকিং</span>
                @endif
              </div>
              <h3 class="font-headline-md text-lg leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $firstNews->title }}</h3>
              @if($firstNews->excerpt)
              <p class="text-sm font-body-main text-on-surface-variant mt-1 line-clamp-2">{{ $firstNews->excerpt }}</p>
              @endif
            </a>

            <!-- Side list (right) -->
            <div class="md:col-span-5 flex flex-col gap-2">
              @foreach($restNews->take(4) as $cn)
              <a href="{{ route('news.show', $cn->slug) }}" class="flex gap-2 group cursor-pointer border-b border-subtle pb-2 last:border-0 last:pb-0">
                <div class="flex-shrink-0 w-20 h-14 overflow-hidden rounded">
                  <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                       src="{{ $cn->featured_image ? storage_image_url($cn->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
                       alt="{{ $cn->title }}" loading="lazy" width="80" height="56"/>
                </div>
                <div class="min-w-0 flex-1">
                  <h4 class="font-headline-md text-sm leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $cn->title }}</h4>
                  @if($cn->published_at)
                  <p class="text-xs text-outline mt-0.5">{{ $cn->published_at->diffForHumans() }}</p>
                  @endif
                </div>
              </a>
              @endforeach
            </div>
          </div>

          {{-- Extra row: remaining items in compact grid --}}
          @if($restNews->count() > 4)
          <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            @foreach($restNews->skip(4)->take(3) as $extraNews)
            <a href="{{ route('news.show', $extraNews->slug) }}" class="group block">
              <div class="aspect-[16/10] overflow-hidden rounded mb-1.5">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     src="{{ $extraNews->featured_image ? storage_image_url($extraNews->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
                     alt="{{ $extraNews->title }}" loading="lazy" width="240" height="150"/>
              </div>
              <h4 class="font-headline-md text-sm leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $extraNews->title }}</h4>
            </a>
            @endforeach
          </div>
          @endif
        </section>

        {{-- Ad between every 2 category sections --}}
        @if($catIdx % 2 === 1)
          @php $__midAd = \App\Helpers\AdHelper::getRandomAdByPlacement('between_news_listings'); @endphp
          @if($__midAd)
          <div class="text-center py-2">{!! \App\Helpers\AdHelper::renderAd($__midAd) !!}</div>
          @endif
        @endif

        @endif
        @endforeach
      @endisset

    </div><!-- /left col-8 -->

    <!-- ── Right: Sidebar ── -->
    <aside class="lg:col-span-4 space-y-5">

      <!-- Popular News Widget -->
      @if($popularNews->count() > 0)
      <div class="bg-surface-container-low p-4 rounded-xl border border-subtle">
        <h2 class="font-headline-md text-base border-b border-subtle pb-2 mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary text-lg">trending_up</span>
          পাঠকপ্রিয় খবর
        </h2>
        <ul class="space-y-2">
          @foreach($popularNews as $idx => $pn)
          <li class="{{ $idx > 0 ? 'border-t border-subtle pt-2' : '' }}">
            <a href="{{ route('news.show', $pn->slug) }}" class="flex gap-3 group cursor-pointer">
              <span class="font-display-breaking text-2xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0 w-7 text-center">{{ str_pad($idx+1, 2, '0', STR_PAD_LEFT) }}</span>
              <p class="font-body-main text-sm leading-tight group-hover:underline line-clamp-2">{{ $pn->title }}</p>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- Sidebar Ads -->
      @php $__sidebarAd1 = \App\Helpers\AdHelper::getRandomAdByPlacement('sidebar_medium_rectangle'); @endphp
      @if($__sidebarAd1)
      <div class="text-center">{!! \App\Helpers\AdHelper::renderAd($__sidebarAd1) !!}</div>
      @endif

      <!-- Trending Widget -->
      @if($trending && $trending->count() > 0)
      <div class="bg-surface-container-high p-4 rounded-xl border border-subtle">
        <h2 class="font-headline-md text-base border-b border-subtle pb-2 mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary text-lg">whatshot</span>
          ট্রেন্ডিং
        </h2>
        <ol class="space-y-2">
          @foreach($trending->take(6) as $ti => $tn)
          <li class="{{ $ti > 0 ? 'border-t border-subtle pt-2' : '' }}">
            <a href="{{ route('news.show', $tn->slug) }}" class="flex gap-3 group cursor-pointer">
              <span class="font-display-breaking text-2xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0 w-7 text-center">{{ str_pad($ti+1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="min-w-0">
                <h4 class="font-headline-md text-sm leading-snug group-hover:text-secondary transition-colors line-clamp-2">{{ $tn->title }}</h4>
                <span class="text-xs text-on-surface-variant">{{ number_format($tn->views) }} বার পঠিত</span>
              </div>
            </a>
          </li>
          @endforeach
        </ol>
      </div>
      @endif

      <!-- Newsletter Signup -->
      <div class="bg-news-blue-dark text-white p-4 rounded-xl relative overflow-hidden shadow-lg">
        <div class="relative z-10">
          <h3 class="font-headline-md text-base mb-1">প্রতিদিনের খবর ইমেইলে পান</h3>
          <p class="text-xs opacity-80 mb-3">সেরা খবরগুলো মিস করবেন না।</p>
          <form class="space-y-2" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input name="email" type="email" required class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:border-white text-white placeholder:text-white/50 text-sm" placeholder="আপনার ইমেইল অ্যাড্রেস"/>
            <button type="submit" class="w-full bg-secondary hover:bg-news-red-accent transition-colors py-2 rounded-lg font-label-caps text-label-caps text-xs uppercase tracking-widest">সাবস্ক্রাইব</button>
          </form>
        </div>
        <div class="absolute -right-6 -bottom-6 opacity-10">
          <span class="material-symbols-outlined text-[80px]">mail</span>
        </div>
      </div>

      <!-- Category Quick Links -->
      @isset($allCategories)
      <div class="bg-surface-container-low p-4 rounded-xl border border-subtle">
        <h2 class="font-headline-md text-base border-b border-subtle pb-2 mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-lg">category</span>
          বিভাগ
        </h2>
        <div class="flex flex-wrap gap-1.5">
          @foreach($allCategories as $cat)
          <a href="{{ route('category.show', $cat->slug) }}"
             class="px-2.5 py-1 bg-primary/10 text-primary text-xs font-medium rounded-full hover:bg-primary hover:text-white transition-colors">
            {{ $cat->name }} <span class="opacity-70">({{ $cat->news_count }})</span>
          </a>
          @endforeach
        </div>
      </div>
      @endisset

      @php $__sidebarAd2 = \App\Helpers\AdHelper::getRandomAdByPlacement('sidebar_half_page'); @endphp
      @if($__sidebarAd2)
      <div class="text-center">{!! \App\Helpers\AdHelper::renderAd($__sidebarAd2) !!}</div>
      @endif

    </aside>
  </div>
</div>

<!-- ═══════════ LATEST NEWS (BOTTOM GRID) ═══════════ -->
@if($latest->count() > 0)
<section class="max-w-container-max mx-auto px-gutter pt-2 pb-6">
  <div class="flex items-center justify-between mb-3 border-b-2 border-primary pb-1">
    <h2 class="bg-primary text-white px-3 py-1 font-headline-md text-base">সর্বশেষ সংবাদ</h2>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($latest->take(8) as $ln)
    <a href="{{ route('news.show', $ln->slug) }}" class="group block">
      <div class="aspect-[16/10] overflow-hidden rounded-lg mb-1.5">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             src="{{ $ln->featured_image ? storage_image_url($ln->featured_image) : asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}"
             alt="{{ $ln->title }}" loading="lazy"/>
      </div>
      @if($ln->category)
      <span class="text-secondary font-label-caps text-xs uppercase">{{ $ln->category->name }}</span>
      @endif
      <h4 class="font-headline-md text-sm mt-0.5 group-hover:text-secondary transition-colors line-clamp-2 leading-snug">{{ $ln->title }}</h4>
      @if($ln->published_at)
      <p class="text-xs text-outline mt-1">{{ $ln->published_at->diffForHumans() }}</p>
      @endif
    </a>
    @endforeach
  </div>
</section>
@endif

@endsection
