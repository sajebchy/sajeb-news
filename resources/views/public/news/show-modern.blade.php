@extends('public.layout')

@section('title', $news->meta_title ?? $news->title . ' - সজীব নিউজ')
@section('meta_description', $news->meta_description ?? $news->excerpt ?? Str::limit(strip_tags($news->content), 160))
@section('canonical', route('news.show', $news->slug))
@if($news->meta_keywords || $news->tags->count())
@section('meta_keywords', $news->meta_keywords ?: $news->tags->pluck('name')->implode(', '))
@endif

@push('styles')
@php
  $imageUrl = $news->featured_image
    ? (str_starts_with($news->featured_image,'http') ? $news->featured_image : asset($news->featured_image))
    : null;
  $heroPreload = $news->featured_image
    ? (Str::startsWith($news->featured_image, 'http') ? $news->featured_image : asset('storage/' . $news->featured_image))
    : null;
@endphp
@if($heroPreload)
<link rel="preload" as="image" href="{{ $heroPreload }}">
@endif
{{-- Open Graph / Twitter meta --}}
<meta property="og:type"               content="article">
<meta property="og:title"              content="{{ $news->title }}">
<meta property="og:description"        content="{{ $news->excerpt ?? Str::limit(strip_tags($news->content),160) }}">
<meta property="og:url"                content="{{ route('news.show', $news->slug) }}">
@if($imageUrl)
<meta property="og:image"              content="{{ $imageUrl }}">
<meta property="og:image:width"        content="1200">
<meta property="og:image:height"       content="630">
@endif
<meta property="article:published_time" content="{{ $news->published_at?->toIso8601String() }}">
<meta property="article:modified_time"  content="{{ $news->updated_at->toIso8601String() }}">
@if($news->category)
<meta property="article:section"        content="{{ $news->category->name }}">
@endif
<meta name="twitter:card"              content="summary_large_image">
<meta name="twitter:title"             content="{{ $news->title }}">
<meta name="twitter:description"       content="{{ $news->excerpt ?? Str::limit(strip_tags($news->content),160) }}">
@if($imageUrl)<meta name="twitter:image" content="{{ $imageUrl }}">@endif
@endpush

@push('scripts')
{{-- JSON-LD Schemas: NewsArticle + BreadcrumbList + ClaimReview --}}
@foreach($schema as $s)
<script type="application/ld+json">{!! json_encode($s, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}</script>
@endforeach
{{-- FAQ Schema (auto-extracted from content) --}}
@php $__faqSchema = app(\App\Services\SeoService::class)->extractFaqSchema($news); @endphp
@if($__faqSchema)
<script type="application/ld+json">{!! json_encode($__faqSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endpush

@section('content')

@php
  $relatedNews = \App\Models\News::where('status','published')
    ->where('id','!=',$news->id)
    ->where('category_id',$news->category_id)
    ->latest('published_at')->limit(4)->get();
  $trendingNews = \App\Models\News::where('status','published')
    ->where('id','!=',$news->id)
    ->orderBy('views','desc')->limit(5)->get();
  // Topic (tag) based related news — shown mid-article
  $topicTagIds = $news->tags->pluck('id');
  $topicRelated = $topicTagIds->isNotEmpty()
    ? \App\Models\News::where('status','published')
        ->where('id','!=',$news->id)
        ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $topicTagIds))
        ->latest('published_at')->limit(3)->get()
    : collect();
@endphp

{{-- ══════════════════════════════════════════
     LEADERBOARD / HEADER AD
═══════════════════════════════════════════ --}}
@php $adHeader = \App\Helpers\AdHelper::getRandomAdByPlacement('header_top'); @endphp
@if($adHeader)
<div class="w-full bg-surface-container-low border-b border-subtle py-2 text-center">
  {!! \App\Helpers\AdHelper::renderAd($adHeader) !!}
</div>
@endif

{{-- ══════════════════════════════════════════
     MAIN CONTENT — 70/30 Grid (Desktop)
         Single column (Mobile)
═══════════════════════════════════════════ --}}
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    {{-- ─────── LEFT: Article Content (70%) ─────── --}}
    <article class="lg:col-span-8" itemscope itemtype="https://schema.org/NewsArticle">

      {{-- Breadcrumbs (Desktop only) --}}
      <nav class="hidden md:flex items-center gap-2 mb-stack-md text-on-surface-variant">
        <a href="{{ route('home') }}" class="font-label-caps text-label-caps hover:text-secondary transition-colors">হোম</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        @if($news->category)
        <a href="{{ route('category.show', $news->category->slug) }}" class="font-label-caps text-label-caps text-secondary hover:underline">{{ $news->category->name }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        @endif
        <span class="font-label-caps text-label-caps text-on-surface-variant truncate max-w-[240px]">{{ Str::limit($news->title, 45) }}</span>
      </nav>

      {{-- Category Pill (Mobile + Desktop) --}}
      @if($news->category)
      <div class="mb-stack-sm">
        <a href="{{ route('category.show', $news->category->slug) }}"
           class="inline-block font-label-caps text-label-caps px-3 py-1 bg-secondary text-white rounded-full uppercase tracking-wider hover:opacity-90 transition-opacity">
          {{ $news->category->name }}
        </a>
      </div>
      @endif

      {{-- Headline --}}
      <h1 itemprop="headline" class="font-headline-lg text-headline-lg md:text-4xl text-2xl mb-stack-md leading-tight text-primary">
        {{ $news->title }}
      </h1>

      {{-- Meta Bar & Author --}}
      <div class="flex flex-wrap items-center justify-between border-y border-subtle py-stack-sm mb-stack-lg gap-3">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full bg-surface-container-high overflow-hidden flex-shrink-0">
            @php $authorAvatar = $news->author->profile_photo_path ?? $news->author->avatar ?? null; @endphp
            @if(isset($authorAvatar) && $authorAvatar)
            <img class="w-full h-full object-cover"
                 src="{{ asset('storage/' . $authorAvatar) }}" alt="{{ $news->author->name ?? 'সাংবাদিক' }}"/>
            @else
            <div class="w-full h-full flex items-center justify-center bg-secondary text-white font-headline-md text-xl">
              {{ mb_substr($news->author->name ?? 'স', 0, 1) }}
            </div>
            @endif
          </div>
          <div>
            @if($news->author)
            <a href="{{ route('author.show', $news->author->id) }}" itemprop="author" itemscope itemtype="https://schema.org/Person"
               class="font-label-caps text-label-caps font-bold text-primary hover:text-secondary transition-colors">
              <span itemprop="name">{{ $news->author->name }}</span>
            </a>
            @else
            <p class="font-label-caps text-label-caps font-bold">সজীব নিউজ ডেস্ক</p>
            @endif
            <p class="font-meta-data text-meta-data text-on-surface-variant">
              প্রকাশিত: <time itemprop="datePublished" datetime="{{ $news->published_at?->toIso8601String() }}">{{ $news->published_at ? $news->published_at->locale('bn')->isoFormat('D MMMM, YYYY [|] h:mm A') : '' }}</time>
            </p>
            <meta itemprop="dateModified" content="{{ $news->updated_at->toIso8601String() }}">
          </div>
        </div>
        <div class="flex items-center gap-stack-md">
          @if($news->reading_time)
          <span class="hidden md:flex items-center gap-1 text-meta-data font-meta-data text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px]">schedule</span> {{ $news->reading_time }} মিনিট
          </span>
          @endif
          <span class="flex items-center gap-1 text-meta-data font-meta-data text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px]">visibility</span> {{ number_format($news->views ?? 0) }}
          </span>
          <button onclick="navigator.share ? navigator.share({title:'{{ addslashes($news->title) }}',url:window.location.href}) : null"
                  class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">share</span>
            <span class="font-label-caps text-label-caps hidden sm:inline">শেয়ার</span>
          </button>
          <button id="bookmark-btn" onclick="toggleBookmark()" class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined" id="bookmark-icon">bookmark</span>
            <span class="font-label-caps text-label-caps hidden sm:inline">সেভ</span>
          </button>
        </div>
      </div>

      {{-- Hero Image --}}
      @php
        $heroImg = $news->featured_image
          ? (Str::startsWith($news->featured_image, 'http') ? $news->featured_image : asset('storage/' . $news->featured_image))
          : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')));
      @endphp
      <figure class="mb-stack-lg">
        <div class="aspect-video w-full overflow-hidden rounded-lg"
             style="box-shadow:0 4px 6px -1px rgba(0,0,0,.08),0 2px 4px -1px rgba(0,0,0,.04)">
          <img itemprop="image" class="w-full h-full object-cover" src="{{ $heroImg }}" alt="{{ $news->title }}"/>
        </div>
        @if($news->og_description)
        <figcaption class="mt-stack-sm font-meta-data text-meta-data text-on-surface-variant italic text-center">
          {{ $news->og_description }}
        </figcaption>
        @endif
      </figure>

      {{-- TL;DR / Summary Box (AEO: helps AI extract key info) --}}
      @if($news->excerpt)
      <div class="mb-6 bg-surface-container-low border border-subtle rounded-xl p-4 md:p-5">
        <div class="flex items-center gap-2 mb-2">
          <span class="material-symbols-outlined text-secondary text-[20px]">summarize</span>
          <h2 class="font-label-caps text-label-caps text-secondary tracking-wider">সংক্ষেপে</h2>
        </div>
        <p itemprop="description" class="font-body-main text-body-main text-on-surface leading-relaxed">
          {{ $news->excerpt }}
        </p>
      </div>
      @endif

      {{-- Article Body --}}
      @php
        // Split content into paragraphs to inject mid-article ad
        $paragraphs = preg_split('/(<\/p>\s*)/i', $news->content, -1, PREG_SPLIT_DELIM_CAPTURE);
        $total = count($paragraphs);
        $midPoint = (int)($total / 2);
        $midInjected = false;
        $topicBoxInjected = false;
      @endphp
      <div itemprop="articleBody" class="article-prose space-y-stack-md font-body-main text-body-main leading-relaxed text-on-surface">
        @foreach($paragraphs as $i => $para)
          {!! $para !!}
          @if(!$topicBoxInjected && $i >= $midPoint && $topicRelated->count() > 0)
            @php $topicBoxInjected = true; @endphp
            <div class="my-6 p-4 bg-surface-container-low border-l-4 border-primary rounded-r-xl not-prose">
              <h3 class="font-label-caps text-label-caps text-primary mb-3">সম্পর্কিত খবর</h3>
              <div class="space-y-3">
                @foreach($topicRelated as $tr)
                <a href="{{ route('news.show', $tr->slug) }}" class="flex gap-3 items-start group">
                  <img class="w-16 h-12 rounded-lg object-cover flex-shrink-0"
                       src="{{ $tr->featured_image ? (Str::startsWith($tr->featured_image,'http') ? $tr->featured_image : asset('storage/'.$tr->featured_image)) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                       alt="{{ $tr->title }}" loading="lazy">
                  <div class="min-w-0">
                    <p class="font-body-main text-body-main font-semibold leading-snug group-hover:text-primary transition-colors">{{ $tr->title }}</p>
                    <p class="text-meta-data font-meta-data text-on-surface-variant mt-1">{{ $tr->published_at?->diffForHumans() }}</p>
                  </div>
                </a>
                @endforeach
              </div>
            </div>
          @endif
          @if(!$midInjected && $i >= $midPoint && isset($adMiddle))
            @php $midInjected = true; @endphp
            <div style="margin:20px 0;text-align:center;">
              {!! \App\Helpers\AdHelper::renderAd($adMiddle) !!}
            </div>
          @endif
        @endforeach
      </div>

      {{-- Article Conclusion Ad --}}
      @if(isset($adConclusion) && $adConclusion)
      <div style="margin:20px 0;text-align:center;">
        {!! \App\Helpers\AdHelper::renderAd($adConclusion) !!}
      </div>
      @endif

      {{-- Below Article Ad --}}
      @if(isset($adBelowArticle) && $adBelowArticle)
      <div style="margin:24px 0;text-align:center;padding:12px 0;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
        {!! \App\Helpers\AdHelper::renderAd($adBelowArticle) !!}
      </div>
      @endif

      {{-- Tags --}}
      @if($news->tags && $news->tags->count() > 0)
      <div class="pt-stack-lg mt-stack-lg border-t border-subtle">
        <div class="flex flex-wrap gap-2 items-center">
          <span class="material-symbols-outlined text-on-surface-variant text-[18px]">sell</span>
          @foreach($news->tags as $tag)
          <a href="{{ route('tag.show', $tag->slug) }}"
             class="px-3 py-1.5 bg-surface-container-low border border-subtle rounded-full text-meta-data font-meta-data hover:bg-secondary hover:text-white hover:border-secondary transition-all">
            {{ $tag->name }}
          </a>
          @endforeach
        </div>
      </div>
      @endif

      {{-- More from this Author (E-E-A-T internal linking) --}}
      @if($news->author)
      @php
        $authorMore = \App\Models\News::where('status','published')
          ->where('id','!=',$news->id)
          ->where('author_id',$news->author_id)
          ->latest('published_at')->limit(3)->get();
      @endphp
      @if($authorMore->count() > 0)
      <div class="mt-stack-lg pt-stack-lg border-t border-subtle">
        <div class="flex items-center gap-2 mb-stack-md">
          <span class="material-symbols-outlined text-primary text-[20px]">person</span>
          <h3 class="font-label-caps text-label-caps text-primary">
            <a href="{{ route('author.show', $news->author->id) }}" class="hover:text-secondary transition-colors">{{ $news->author->name }}</a> এর আরও সংবাদ
          </h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          @foreach($authorMore as $am)
          <a href="{{ route('news.show', $am->slug) }}" class="group block">
            <div class="aspect-[16/10] overflow-hidden rounded-lg bg-surface-container mb-2">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                   src="{{ $am->featured_image ? (Str::startsWith($am->featured_image,'http') ? $am->featured_image : asset('storage/'.$am->featured_image)) : ($defaultFeaturedImage ?? '') }}"
                   alt="{{ $am->title }}" loading="lazy"/>
            </div>
            <h4 class="font-headline-md text-[14px] leading-snug line-clamp-2 group-hover:text-secondary transition-colors">{{ $am->title }}</h4>
            <p class="font-meta-data text-[11px] text-outline mt-1">{{ $am->published_at?->diffForHumans() }}</p>
          </a>
          @endforeach
        </div>
      </div>
      @endif
      @endif

      {{-- Social Sharing --}}
      <div class="mt-stack-lg pt-stack-lg border-t border-subtle">
        <h3 class="font-label-caps text-label-caps mb-stack-md text-on-surface-variant">এই সংবাদটি শেয়ার করুন</h3>
        <div class="flex flex-wrap gap-3">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}"
             target="_blank" rel="noopener" aria-label="ফেসবুকে শেয়ার করুন"
             class="w-10 h-10 rounded-full bg-[#1877F2] flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px]" aria-hidden="true"><path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}"
             target="_blank" rel="noopener" aria-label="এক্স (টুইটার)-এ শেয়ার করুন"
             class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-[16px] h-[16px]" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
          </a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}"
             target="_blank" rel="noopener" aria-label="হোয়াটসঅ্যাপে শেয়ার করুন"
             class="w-10 h-10 rounded-full bg-[#25D366] flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <svg viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px]" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
          </a>
          <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>{ this.classList.add('bg-green-600'); setTimeout(()=>this.classList.remove('bg-green-600'),2000); })"
                  class="w-10 h-10 rounded-full bg-on-surface flex items-center justify-center text-white hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-[18px]">link</span>
          </button>
        </div>
      </div>

      {{-- Comments Section --}}
      <section class="mt-section-padding bg-surface-container-low p-stack-lg rounded-xl">
        <h2 class="font-headline-md text-headline-md mb-stack-lg">মন্তব্য করুন</h2>
        <div class="space-y-stack-lg">
          {{-- Comment Form --}}
          <div class="mb-stack-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
              <input type="text" name="name" placeholder="আপনার নাম *"
                     class="w-full px-4 py-3 rounded-lg border border-subtle focus:border-secondary outline-none transition-colors bg-white font-body-sm"/>
              <input type="email" name="email" placeholder="ইমেইল (প্রকাশিত হবে না)"
                     class="w-full px-4 py-3 rounded-lg border border-subtle focus:border-secondary outline-none transition-colors bg-white font-body-sm"/>
            </div>
            <textarea class="w-full px-4 py-3 rounded-lg border border-subtle focus:border-secondary outline-none transition-colors min-h-[120px] bg-white font-body-sm resize-none"
                      placeholder="আপনার মতামত লিখুন..."></textarea>
            <button class="mt-stack-sm px-gutter py-2 bg-primary text-white font-label-caps text-label-caps rounded-full hover:bg-secondary transition-colors">
              মন্তব্য জমা দিন
            </button>
          </div>
        </div>
      </section>

      {{-- ─── MOBILE ONLY: Related Stories (after comments) ─── --}}
      @if($relatedNews->count() > 0)
      <section class="mt-section-padding lg:hidden px-0 bg-surface-container-lowest pt-stack-lg pb-stack-lg">
        <h3 class="font-label-caps text-label-caps text-secondary mb-stack-md border-b-2 border-secondary inline-block pb-1">সম্পর্কিত খবর</h3>
        <div class="space-y-gutter mt-stack-md">
          @foreach($relatedNews as $rn)
          <a href="{{ route('news.show', $rn->slug) }}" class="flex gap-stack-md group active:opacity-80 transition-opacity">
            <div class="flex-1 min-w-0">
              @if($rn->category)
              <span class="font-label-caps text-label-caps text-secondary text-[10px]">{{ $rn->category->name }}</span>
              @endif
              <h4 class="font-headline-md text-headline-md leading-tight line-clamp-2 group-hover:text-secondary transition-colors">{{ $rn->title }}</h4>
              <span class="font-meta-data text-meta-data text-on-surface-variant mt-1 block">{{ $rn->published_at?->diffForHumans() }}</span>
            </div>
            <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                   src="{{ $rn->featured_image ? (Str::startsWith($rn->featured_image,'http') ? $rn->featured_image : asset('storage/'.$rn->featured_image)) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                   alt="{{ $rn->title }}" loading="lazy"/>
            </div>
          </a>
          @endforeach
        </div>
        <a href="{{ $news->category ? route('category.show', $news->category->slug) : route('home') }}"
           class="block w-full mt-stack-lg py-stack-md text-center border border-primary text-primary font-label-caps text-label-caps rounded hover:bg-primary hover:text-white transition-all">
          আরও খবর দেখুন
        </a>
      </section>
      @endif

      {{-- MOBILE ONLY: Newsletter --}}
      <section class="mt-stack-lg lg:hidden py-section-padding bg-primary text-on-primary rounded-xl">
        <div class="px-stack-md">
          <h3 class="font-headline-lg-mobile text-2xl text-white mb-stack-sm">গেজেট ডেইলি</h3>
          <p class="font-body-sm text-body-sm text-white/80 mb-stack-md">প্রতিদিন সেরা খবর সরাসরি ইমেইলে পান।</p>
          <div class="flex flex-col gap-3">
            <input class="bg-white/10 border border-white/20 text-white placeholder:text-white/50 px-4 py-3 rounded focus:outline-none focus:ring-2 focus:ring-secondary transition-all font-body-sm"
                   placeholder="ইমেইল অ্যাড্রেস" type="email"/>
            <button class="bg-secondary text-white font-label-caps text-label-caps py-3 rounded hover:bg-news-red-accent transition-colors">
              এখনই সাবস্ক্রাইব করুন
            </button>
          </div>
        </div>
      </section>

    </article>

    {{-- ─────── RIGHT: Sidebar (30%) — Desktop only ─────── --}}
    <aside class="hidden lg:block lg:col-span-4 space-y-stack-lg">

      {{-- Related News --}}
      @if($relatedNews->count() > 0)
      <section>
        <h2 class="font-label-caps text-label-caps border-l-4 border-secondary pl-3 mb-stack-lg text-primary">সম্পর্কিত খবর</h2>
        <div class="space-y-stack-md">
          @foreach($relatedNews as $rn)
          <a href="{{ route('news.show', $rn->slug) }}" class="group flex gap-3 pb-stack-md border-b border-subtle cursor-pointer block">
            <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded bg-surface-container">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                   src="{{ $rn->featured_image ? (Str::startsWith($rn->featured_image,'http') ? $rn->featured_image : asset('storage/'.$rn->featured_image)) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                   alt="{{ $rn->title }}" loading="lazy"/>
            </div>
            <div class="flex flex-col justify-between">
              <h4 class="font-headline-md text-[15px] leading-tight group-hover:text-secondary transition-colors line-clamp-3">
                {{ $rn->title }}
              </h4>
              <span class="font-meta-data text-meta-data text-on-surface-variant">
                {{ $rn->category->name ?? '' }} | {{ $rn->published_at?->diffForHumans() }}
              </span>
            </div>
          </a>
          @endforeach
        </div>
      </section>
      @endif

      {{-- Trending Section --}}
      @if($trendingNews->count() > 0)
      <section class="bg-surface-container-low p-stack-lg rounded-xl">
        <h2 class="font-label-caps text-label-caps border-l-4 border-secondary pl-3 mb-stack-lg text-primary">ট্রেন্ডিং</h2>
        <ol class="space-y-stack-md">
          @foreach($trendingNews as $ti => $tn)
          <li class="{{ $ti > 0 ? 'border-t border-subtle pt-stack-md' : '' }}">
            <a href="{{ route('news.show', $tn->slug) }}" class="flex gap-4 group cursor-pointer">
              <span class="font-display-breaking text-3xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0">
                {{ str_pad($ti+1, 2, '0', STR_PAD_LEFT) }}
              </span>
              <div>
                <h4 class="font-headline-md text-[15px] leading-snug group-hover:text-secondary transition-colors line-clamp-2">
                  {{ $tn->title }}
                </h4>
                <span class="font-meta-data text-meta-data text-on-surface-variant">{{ number_format($tn->views) }} বার পঠিত</span>
              </div>
            </a>
          </li>
          @endforeach
        </ol>
      </section>
      @endif

      {{-- Sidebar Ad Slot --}}
      <section class="bg-surface-container-low p-stack-lg rounded-xl flex flex-col items-center justify-center min-h-[250px] border border-subtle">
        <div class="w-full h-48 bg-surface-container-highest rounded flex items-center justify-center border border-dashed border-outline-variant">
          <span class="material-symbols-outlined text-outline-variant text-3xl">ads_click</span>
        </div>
      </section>

      {{-- Newsletter Widget --}}
      <section class="bg-primary-container p-stack-lg rounded-xl text-center">
        <h3 class="font-headline-md text-headline-md text-white mb-2">খবরের আপডেট পেতে</h3>
        <p class="font-body-sm text-body-sm text-on-primary-container mb-stack-lg opacity-80">
          প্রতিদিন সেরা সব খবর সরাসরি আপনার ইমেইলে পেতে সাবস্ক্রাইব করুন।
        </p>
        <div class="flex flex-col gap-2">
          <input class="w-full px-stack-md py-2 rounded border border-white/20 bg-white/10 text-white placeholder:text-white/60 outline-none focus:bg-white/20 transition-all font-body-sm"
                 placeholder="আপনার ইমেইল এড্রেস" type="email"/>
          <button class="w-full py-2 bg-secondary text-white font-label-caps text-label-caps rounded hover:bg-news-red-accent transition-colors">
            সাবস্ক্রাইব করুন
          </button>
        </div>
      </section>

    </aside>
  </div>
</main>

{{-- ══════════════════════════════════════════
     LATEST NEWS SECTION (below article)
═══════════════════════════════════════════ --}}
@php
  $latestNews = \App\Models\News::where('status','published')
    ->where('id','!=',$news->id)
    ->latest('updated_at')
    ->limit(4)->get();
@endphp
@if($latestNews->count() > 0)
<section class="max-w-container-max mx-auto px-gutter py-section-padding">
  <div class="flex items-center gap-3 mb-6">
    <span class="w-1 h-8 bg-secondary rounded-full"></span>
    <h2 class="font-headline-md text-2xl text-on-surface" style="font-family:'SolaimanLipi',serif;">সর্বশেষ আপডেট</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($latestNews as $ln)
    <a href="{{ route('news.show', $ln->slug) }}" class="group block bg-surface-container-lowest border border-subtle rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
      <div class="aspect-[16/10] overflow-hidden bg-surface-container">
        <img src="{{ $ln->featured_image ? (str_starts_with($ln->featured_image,'http') ? $ln->featured_image : asset('storage/'.$ln->featured_image)) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
             alt="{{ $ln->title }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy"/>
      </div>
      <div class="p-4">
        @if($ln->category)
        <span class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">{{ $ln->category->name }}</span>
        @endif
        <h3 class="font-headline-md text-[15px] leading-snug text-on-surface mt-1 line-clamp-2 group-hover:text-primary transition-colors" style="font-family:'SolaimanLipi',serif;">{{ $ln->title }}</h3>
        <p class="font-meta-data text-[11px] text-outline mt-2">{{ $ln->updated_at->diffForHumans() }}</p>
      </div>
    </a>
    @endforeach
  </div>
</section>
@endif

{{-- ══════════════════════════════════════════
     MOBILE: Anchor ad above bottom nav
═══════════════════════════════════════════ --}}
<div class="fixed bottom-[64px] left-0 w-full z-40 flex justify-center items-center bg-surface/90 backdrop-blur-sm border-t border-subtle py-1 md:hidden">
  <div class="relative w-[320px] h-[50px] bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-caps text-[10px] border border-outline-variant">
    <button onclick="this.parentElement.parentElement.remove()"
            class="absolute -top-2 -right-2 w-5 h-5 bg-on-surface text-surface rounded-full flex items-center justify-center text-xs shadow-sm">×</button>
    ANCHOR AD SLOT
  </div>
</div>

{{-- ══════════════════════════════════════════
     PROSE STYLES
═══════════════════════════════════════════ --}}
<style>
/* Article prose styling */
.article-prose {
  font-family: 'SolaimanLipi', serif;
  font-size: 18px;
  line-height: 1.8;
  max-width: 780px;
}
.article-prose h2 {
  font-family: 'SolaimanLipi', serif;
  font-size: 24px; font-weight: 700;
  margin: 2rem 0 0.75rem; color: #000;
}
.article-prose h3 {
  font-family: 'SolaimanLipi', serif;
  font-size: 20px; font-weight: 600;
  margin: 1.5rem 0 0.5rem; color: #000;
}
.article-prose p { margin-bottom: 1rem; }
.article-prose blockquote {
  border-left: 4px solid #bb0112;
  background: #F8FAFC;
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  font-family: 'SolaimanLipi', serif;
  font-size: 20px;
  font-weight: 600;
  color: #000;
  font-style: italic;
}
.article-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
.article-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
.article-prose li { margin-bottom: 0.25rem; }
.article-prose img { border-radius: 0.5rem; max-width: 100%; height: auto; margin: 1.5rem auto; display: block; }
.article-prose a { color: #bb0112; text-decoration: underline; }
.article-prose strong { font-weight: 700; }

/* Dropcap first letter */
.article-prose > p:first-of-type::first-letter {
  font-family: 'SolaimanLipi', serif;
  font-size: 4.5rem;
  font-weight: 700;
  line-height: 0.8;
  float: left;
  margin-right: 0.5rem;
  margin-top: 0.1rem;
  color: #000;
}

/* Mobile font adjustments */
@media (max-width: 767px) {
  .article-prose { font-size: 16px; line-height: 1.75; }
  .article-prose > p:first-of-type::first-letter { font-size: 3.5rem; }
}
</style>

{{-- Bookmark script --}}
<script>
function toggleBookmark() {
  const slug = '{{ $news->slug }}';
  const icon = document.getElementById('bookmark-icon');
  let saved = JSON.parse(localStorage.getItem('sajeb_bookmarks') || '[]');
  if (saved.includes(slug)) {
    saved = saved.filter(s => s !== slug);
    icon.style.fontVariationSettings = "'FILL' 0";
  } else {
    saved.push(slug);
    icon.style.fontVariationSettings = "'FILL' 1";
  }
  localStorage.setItem('sajeb_bookmarks', JSON.stringify(saved));
}
// Init bookmark state
(function() {
  const slug = '{{ $news->slug }}';
  const icon = document.getElementById('bookmark-icon');
  const saved = JSON.parse(localStorage.getItem('sajeb_bookmarks') || '[]');
  if (icon && saved.includes(slug)) icon.style.fontVariationSettings = "'FILL' 1";
})();
</script>

@endsection
