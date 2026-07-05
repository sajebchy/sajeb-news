@extends('public.layout')

@section('title', '#' . $tag . ' — সজীব নিউজ')
@section('meta_description', $tag . ' ট্যাগের সর্বশেষ সংবাদ পড়ুন সজীব নিউজে।')
@section('robots', 'index, follow')

@push('styles')
<meta property="og:type" content="website">
<meta property="og:title" content="#{{ $tag }} — সজীব নিউজ">
<meta property="og:description" content="{{ $tag }} ট্যাগের সংবাদ — সজীব নিউজ">
@if($news->previousPageUrl())
<link rel="prev" href="{{ $news->previousPageUrl() }}">
@endif
@if($news->nextPageUrl())
<link rel="next" href="{{ $news->nextPageUrl() }}">
@endif
@endpush

@push('scripts')
@php
  $__seoSvc = app(\App\Services\SeoService::class);
  $__breadSchema = $__seoSvc->getBreadcrumbSchema([$tag => url()->current()]);
@endphp
<script type="application/ld+json">{!! json_encode($__breadSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">

  <div class="mb-stack-lg border-b border-subtle pb-4">
    <div class="flex items-center gap-2 mb-2">
      <span class="material-symbols-outlined text-secondary">sell</span>
      <h1 class="font-headline-lg text-headline-lg text-primary">{{ $tag }}</h1>
    </div>
    <p class="text-meta-data font-meta-data text-on-surface-variant">
      এই ট্যাগে {{ $news->total() }} টি সংবাদ পাওয়া গেছে
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">
    <div class="lg:col-span-8 space-y-stack-lg">
      @forelse($news as $article)
      <article class="flex flex-col md:flex-row gap-6 p-4 border border-transparent hover:bg-surface-container-low rounded-lg group hover-lift">
        <div class="md:w-1/3 flex-shrink-0">
          <a href="{{ route('news.show', $article->slug) }}">
            <img class="w-full h-48 md:h-44 object-cover rounded-md"
                 src="{{ $article->featured_image ? storage_image_url($article->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                 alt="{{ $article->title }}" loading="lazy"/>
          </a>
        </div>
        <div class="flex-1 flex flex-col">
          @if($article->category)
          <span class="text-secondary font-label-caps text-label-caps mb-1">{{ $article->category->name }}</span>
          @endif
          <a href="{{ route('news.show', $article->slug) }}">
            <h2 class="font-headline-md text-headline-md text-primary mb-2 group-hover:text-secondary transition-colors">{{ $article->title }}</h2>
          </a>
          @if($article->excerpt)
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-4 line-clamp-3">{{ $article->excerpt }}</p>
          @endif
          <div class="mt-auto flex items-center justify-between text-meta-data font-meta-data text-outline">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">person</span>
              {{ $article->author->name ?? 'সংবাদদাতা' }}
            </span>
            @if($article->published_at)
            <span>{{ $article->published_at->diffForHumans() }}</span>
            @endif
          </div>
        </div>
      </article>
      @empty
      <div class="text-center py-12 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl text-outline-variant">article</span>
        <p class="font-headline-md mt-4">এই ট্যাগে কোনো সংবাদ নেই</p>
      </div>
      @endforelse

      @if($news->hasPages())
      <div class="flex justify-center pt-stack-md">
        <nav class="flex gap-2">
          @for($p = 1; $p <= $news->lastPage(); $p++)
          <a href="{{ $news->url($p) }}" class="w-10 h-10 flex items-center justify-center border border-subtle rounded hover:bg-primary hover:text-white transition-colors {{ $p === $news->currentPage() ? 'bg-primary text-white' : '' }}">{{ $p }}</a>
          @endfor
        </nav>
      </div>
      @endif
    </div>

    <aside class="lg:col-span-4 space-y-stack-lg">
      @php
        $popularTagged = \App\Models\News::published()
          ->whereHas('tags', fn($q) => $q->where('name', $tag)->orWhere('slug', $tag))
          ->orderByDesc('views')
          ->limit(5)->get();
      @endphp
      @if($popularTagged->count() > 0)
      <div class="bg-surface-muted p-stack-md rounded-lg border border-subtle">
        <h3 class="font-headline-md text-headline-md text-primary mb-4 border-l-4 border-secondary pl-3">জনপ্রিয়</h3>
        <div class="space-y-4">
          @foreach($popularTagged as $idx => $cp)
          <a href="{{ route('news.show', $cp->slug) }}" class="group flex gap-4 items-start {{ $idx > 0 ? 'border-t border-subtle/50 pt-4' : '' }} block">
            <span class="font-display-breaking text-3xl text-outline-variant font-bold leading-none group-hover:text-secondary transition-colors">{{ $idx + 1 }}.</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-secondary transition-colors">{{ $cp->title }}</p>
          </a>
          @endforeach
        </div>
      </div>
      @endif
    </aside>
  </div>
</main>
@endsection
