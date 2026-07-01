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
@endphp
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
{{-- JSON-LD Schemas: NewsArticle + BreadcrumbList --}}
@foreach($schema as $s)
<script type="application/ld+json">{!! json_encode($s, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}</script>
@endforeach
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
@endphp

{{-- ══════════════════════════════════════════
     LEADERBOARD / HEADER AD
═══════════════════════════════════════════ --}}
@php $adHeader = \App\Helpers\AdHelper::getRandomAdByPlacement('header_top'); @endphp
@if($adHeader)
<div class="w-full bg-surface-container-low border-b border-subtle py-2 text-center">
  <p class="font-label-caps text-[10px] text-outline-variant uppercase tracking-widest mb-1">বিজ্ঞাপন</p>
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
    <article class="lg:col-span-8">

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
      <h1 class="font-headline-lg text-headline-lg md:text-4xl text-2xl mb-stack-md leading-tight text-primary">
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
            <a href="{{ route('author.show', $news->author->id) }}"
               class="font-label-caps text-label-caps font-bold text-primary hover:text-secondary transition-colors">
              {{ $news->author->name }}
            </a>
            @else
            <p class="font-label-caps text-label-caps font-bold">সজীব নিউজ ডেস্ক</p>
            @endif
            <p class="font-meta-data text-meta-data text-on-surface-variant">
              প্রকাশিত: {{ $news->published_at ? $news->published_at->locale('bn')->isoFormat('D MMMM, YYYY [|] h:mm A') : '' }}
            </p>
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
          : 'https://picsum.photos/seed/' . $news->id . '/800/450';
      @endphp
      <figure class="mb-stack-lg">
        <div class="aspect-video w-full overflow-hidden rounded-lg"
             style="box-shadow:0 4px 6px -1px rgba(0,0,0,.08),0 2px 4px -1px rgba(0,0,0,.04)">
          <img class="w-full h-full object-cover" src="{{ $heroImg }}" alt="{{ $news->title }}"/>
        </div>
        @if($news->og_description)
        <figcaption class="mt-stack-sm font-meta-data text-meta-data text-on-surface-variant italic text-center">
          {{ $news->og_description }}
        </figcaption>
        @endif
      </figure>

      {{-- Excerpt as lead paragraph --}}
      @if($news->excerpt)
      <p class="font-body-main text-body-main text-on-surface-variant text-lg leading-relaxed mb-6 border-l-4 border-secondary pl-4 bg-surface-muted py-3 pr-3 rounded-r-lg">
        {{ $news->excerpt }}
      </p>
      @endif

      {{-- Article Body --}}
      @php
        // Split content into paragraphs to inject mid-article ad
        $paragraphs = preg_split('/(<\/p>\s*)/i', $news->content, -1, PREG_SPLIT_DELIM_CAPTURE);
        $total = count($paragraphs);
        $midPoint = (int)($total / 2);
        $midInjected = false;
      @endphp
      <div class="article-prose space-y-stack-md font-body-main text-body-main leading-relaxed text-on-surface">
        @foreach($paragraphs as $i => $para)
          {!! $para !!}
          @if(!$midInjected && $i >= $midPoint && isset($adMiddle))
            @php $midInjected = true; @endphp
            <div style="margin:20px 0;text-align:center;">
              <p style="font-size:10px;color:#aaa;margin:0 0 4px;text-transform:uppercase;letter-spacing:.05em;">বিজ্ঞাপন</p>
              {!! \App\Helpers\AdHelper::renderAd($adMiddle) !!}
            </div>
          @endif
        @endforeach
      </div>

      {{-- Article Conclusion Ad --}}
      @if(isset($adConclusion) && $adConclusion)
      <div style="margin:20px 0;text-align:center;">
        <p style="font-size:10px;color:#aaa;margin:0 0 4px;text-transform:uppercase;letter-spacing:.05em;">বিজ্ঞাপন</p>
        {!! \App\Helpers\AdHelper::renderAd($adConclusion) !!}
      </div>
      @endif

      {{-- Below Article Ad --}}
      @if(isset($adBelowArticle) && $adBelowArticle)
      <div style="margin:24px 0;text-align:center;padding:12px 0;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
        <p style="font-size:10px;color:#aaa;margin:0 0 6px;text-transform:uppercase;letter-spacing:.05em;">বিজ্ঞাপন</p>
        {!! \App\Helpers\AdHelper::renderAd($adBelowArticle) !!}
      </div>
      @endif

      {{-- Tags: hidden from display, used only for SEO (meta keywords) --}}
      {{-- Tags are injected into <meta name="keywords"> and NewsArticle schema --}}
      @if($news->tags && $news->tags->count() > 0)
      <div class="pt-stack-lg border-t border-subtle" style="display:none;" aria-hidden="true">
        <div class="flex flex-wrap gap-2 items-center">
          <span class="font-label-caps text-label-caps text-on-surface-variant">ট্যাগ:</span>
          @foreach($news->tags as $tag)
          <a href="{{ route('tag.show', $tag->slug) }}"
             class="px-3 py-1 bg-surface-container-low border border-subtle rounded-full text-meta-data font-meta-data hover:bg-secondary hover:text-white hover:border-secondary transition-all">
            {{ $tag->name }}
          </a>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Social Sharing --}}
      <div class="mt-stack-lg pt-stack-lg border-t border-subtle">
        <h3 class="font-label-caps text-label-caps mb-stack-md text-on-surface-variant">এই সংবাদটি শেয়ার করুন</h3>
        <div class="flex flex-wrap gap-3">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}"
             target="_blank" rel="noopener"
             class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-[18px]">face_nod</span>
          </a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}"
             target="_blank" rel="noopener"
             class="w-10 h-10 rounded-full bg-sky-500 flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-[18px]">alternate_email</span>
          </a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}"
             target="_blank" rel="noopener"
             class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-[18px]">chat</span>
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
                   src="{{ $rn->featured_image ? (Str::startsWith($rn->featured_image,'http') ? $rn->featured_image : asset('storage/'.$rn->featured_image)) : 'https://picsum.photos/seed/'.$rn->id.'/200/200' }}"
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
                   src="{{ $rn->featured_image ? (Str::startsWith($rn->featured_image,'http') ? $rn->featured_image : asset('storage/'.$rn->featured_image)) : 'https://picsum.photos/seed/'.$rn->id.'/200/200' }}"
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
        <span class="font-label-caps text-on-surface-variant opacity-50 mb-2 text-xs">বিজ্ঞাপন</span>
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
.article-prose h2 {
  font-family: 'Noto Serif Bengali', serif;
  font-size: 24px; font-weight: 700;
  margin: 2rem 0 0.75rem; color: #000;
}
.article-prose h3 {
  font-family: 'Noto Serif Bengali', serif;
  font-size: 20px; font-weight: 600;
  margin: 1.5rem 0 0.5rem; color: #000;
}
.article-prose p { margin-bottom: 1rem; }
.article-prose blockquote {
  border-left: 4px solid #bb0112;
  background: #F8FAFC;
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  font-family: 'Noto Serif Bengali', serif;
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
  font-family: 'Noto Serif Bengali', serif;
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
