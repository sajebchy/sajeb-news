@extends('public.layout')

@section('title', 'সজীব নিউজ | নির্ভরযোগ্য খবরের ঠিকানা')
@section('meta_description', 'সজীব নিউজ - বাংলাদেশের সর্বশেষ সংবাদ, রাজনীতি, খেলাধুলা, বিনোদন ও প্রযুক্তির নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল')

@section('content')

@php
  $heroNews = $featured->first() ?? $latest->first();
  $secondaryNews = $featured->skip(1)->take(4)->merge($latest->take(4))->unique('id')->take(4);
  $popularNews = \App\Models\News::where('status','published')->orderBy('views','desc')->limit(5)->get();
  $featuredCats = \App\Models\Category::where('is_active',true)->whereNotNull('featured_order')->orderBy('featured_order')->limit(4)->get();
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

<!-- Main Content -->
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <!-- ── Left: Main Content (70%) ────────────────────────── -->
    <div class="lg:col-span-8 space-y-stack-lg">

      <!-- Hero Article -->
      @if($heroNews)
      <article class="group cursor-pointer">
        <a href="{{ route('news.show', $heroNews->slug) }}">
          <div class="relative overflow-hidden rounded-xl aspect-[16/9] mb-stack-md">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 src="{{ $heroNews->featured_image ?: 'https://picsum.photos/seed/'.($heroNews->id ?? 1).'/800/450' }}"
                 alt="{{ $heroNews->title }}"/>
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
                 src="{{ $sn->featured_image ?: 'https://picsum.photos/seed/'.($sn->id ?? rand()).'/300/200' }}"
                 alt="{{ $sn->title }}"/>
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

      <!-- Category Sections -->
      @foreach($featuredCats as $fcat)
      @php
        $catNews = \App\Models\News::where('status','published')->where('category_id', $fcat->id)->latest('published_at')->limit(3)->get();
      @endphp
      @if($catNews->count() > 0)
      <section class="pt-stack-lg">
        <div class="flex items-center justify-between mb-4 border-b-2 border-primary">
          <h2 class="bg-primary text-white px-4 py-1 font-headline-md">{{ $fcat->name }}</h2>
          <a class="text-body-sm font-body-sm text-outline hover:text-secondary transition-colors" href="{{ route('category.show', $fcat->slug) }}">সব খবর →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach($catNews as $cn)
          <a href="{{ route('news.show', $cn->slug) }}" class="space-y-3 group cursor-pointer">
            <div class="aspect-video overflow-hidden rounded-lg">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                   src="{{ $cn->featured_image ?: 'https://picsum.photos/seed/'.($cn->id ?? rand()).'/400/225' }}"
                   alt="{{ $cn->title }}"/>
            </div>
            <h4 class="font-headline-md group-hover:text-secondary transition-colors line-clamp-2">{{ $cn->title }}</h4>
            @if($cn->published_at)
            <p class="text-meta-data font-meta-data text-outline">{{ $cn->published_at->diffForHumans() }}</p>
            @endif
          </a>
          @endforeach
        </div>
      </section>
      @endif
      @endforeach

    </div><!-- /left col-8 -->

    <!-- ── Right: Sidebar (30%) ───────────────────────────── -->
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
          <form class="space-y-3" action="{{ route('contact.store') }}" method="POST">
            @csrf
            <input name="email" type="email" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:border-white focus:ring-1 focus:ring-white text-white placeholder:text-white/50" placeholder="আপনার ইমেইল অ্যাড্রেস"/>
            <button type="submit" class="w-full bg-secondary hover:bg-news-red-accent transition-colors py-2 rounded-lg font-label-caps text-label-caps uppercase tracking-widest">নিউজলেটার সাবস্ক্রাইব</button>
          </form>
        </div>
        <div class="absolute -right-8 -bottom-8 opacity-10">
          <span class="material-symbols-outlined text-[120px]">mail</span>
        </div>
      </div>

      <!-- Ad Placeholder -->
      <div class="w-full aspect-[4/5] bg-surface-variant flex items-center justify-center rounded-lg border border-subtle border-dashed">
        <p class="text-meta-data font-meta-data text-outline-variant uppercase tracking-widest">বিজ্ঞাপন</p>
      </div>

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

<!-- Bento Section: Featured Categories (Tech & Entertainment) -->
@php
  $bentoCategories = \App\Models\Category::where('is_active',true)->whereNotNull('featured_order')->orderBy('featured_order')->skip(2)->take(2)->get();
@endphp
@if($bentoCategories->count() > 0)
<section class="bg-surface-muted py-section-padding border-y border-subtle">
  <div class="max-w-container-max mx-auto px-gutter">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
      @foreach($bentoCategories as $bc)
      @php $bcNews = \App\Models\News::where('status','published')->where('category_id',$bc->id)->latest('published_at')->limit(3)->get(); @endphp
      @if($bcNews->count() > 0)
      <div class="space-y-6">
        <h2 class="font-headline-lg border-l-4 border-secondary pl-4">{{ $bc->name }}</h2>
        <div class="grid grid-cols-2 gap-4">
          @if($bcNews->first())
          <div class="col-span-2 group cursor-pointer">
            <a href="{{ route('news.show', $bcNews->first()->slug) }}">
              <div class="relative rounded-xl overflow-hidden aspect-video">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700"
                     src="{{ $bcNews->first()->featured_image ?: 'https://picsum.photos/seed/'.$bcNews->first()->id.'/600/338' }}"
                     alt="{{ $bcNews->first()->title }}"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-6">
                  <h3 class="font-headline-md text-white text-xl leading-tight">{{ $bcNews->first()->title }}</h3>
                </div>
              </div>
            </a>
          </div>
          @endif
          @foreach($bcNews->skip(1) as $bn)
          <a href="{{ route('news.show', $bn->slug) }}" class="bg-white p-4 rounded-xl border border-subtle hover:border-secondary transition-colors cursor-pointer block">
            <h4 class="font-headline-md text-primary text-base line-clamp-2 hover:text-secondary transition-colors">{{ $bn->title }}</h4>
          </a>
          @endforeach
        </div>
      </div>
      @endif
      @endforeach
    </div>
  </div>
</section>
@endif

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
             src="{{ $ln->featured_image ?: 'https://picsum.photos/seed/'.$ln->id.'/400/225' }}"
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
