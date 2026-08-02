@extends('layouts.admin')

@section('page-title', 'খবর বিস্তারিত')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.external-news.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> তালিকায় ফিরে যান
    </a>
</div>

<article class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 md:p-8 max-w-4xl">

    {{-- Meta --}}
    <div class="flex items-center gap-2 flex-wrap mb-3">
        <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full">
            <span class="material-symbols-outlined text-[13px]">rss_feed</span>{{ $item->source->name ?? 'সোর্স' }}
        </span>
        @if($item->published_at)
        <span class="text-xs text-on-surface-variant">{{ $item->published_at->format('d M, Y • h:i A') }}</span>
        @endif
        @if($item->author)
        <span class="text-xs text-on-surface-variant">✍ {{ $item->author }}</span>
        @endif
    </div>

    {{-- Title --}}
    <h1 class="text-2xl md:text-3xl font-bold text-on-surface leading-tight mb-4" style="font-family:'SolaimanLipi',serif;">
        {{ $item->title }}
    </h1>

    {{-- Keywords --}}
    @if(count($item->keywords_array))
    <div class="flex items-center gap-1.5 flex-wrap mb-5 pb-5 border-b border-outline-variant">
        <span class="material-symbols-outlined text-[16px] text-outline">sell</span>
        <span class="text-xs text-on-surface-variant font-semibold mr-1">কীওয়ার্ড:</span>
        @foreach($item->keywords_array as $kw)
        <span class="text-[11px] bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full">{{ $kw }}</span>
        @endforeach
    </div>
    @endif

    {{-- Featured image --}}
    @if($item->image_url)
    <img src="{{ $item->image_url }}" alt="" class="w-full max-h-[420px] object-cover rounded-lg border border-outline-variant mb-5" onerror="this.style.display='none'">
    @endif

    {{-- Full content --}}
    <div class="prose max-w-none text-on-surface leading-relaxed ext-content" style="font-family:'SolaimanLipi',serif;">
        @if($item->content)
            {!! $item->content !!}
        @elseif($item->excerpt)
            <p>{{ $item->excerpt }}</p>
        @else
            <p class="text-on-surface-variant">এই খবরের পূর্ণ কনটেন্ট পাওয়া যায়নি। মূল সাইটে দেখুন।</p>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 flex-wrap mt-8 pt-5 border-t border-outline-variant">
        <a href="{{ $item->url }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 bg-surface-container border border-outline-variant text-on-surface px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-surface-variant transition-all">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span> মূল সাইটে পড়ুন
        </a>

        {{-- Phase 2 placeholder: AI rewrite → import as draft --}}
        <button type="button" disabled title="শীঘ্রই আসছে"
                class="inline-flex items-center gap-1.5 bg-primary-container/50 text-on-primary-container/60 px-4 py-2.5 rounded-xl font-bold text-sm cursor-not-allowed">
            <span class="material-symbols-outlined text-[18px]">auto_awesome</span> AI দিয়ে রিরাইট <span class="text-[10px] bg-white/40 rounded px-1.5 py-0.5">শীঘ্রই</span>
        </button>
    </div>
</article>

<style>
    .ext-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; }
    .ext-content p { margin-bottom: 1rem; }
    .ext-content a { color: #004e9f; text-decoration: underline; }
    .ext-content h1, .ext-content h2, .ext-content h3 { font-weight: 700; margin: 1.25rem 0 0.5rem; }
    .ext-content ul, .ext-content ol { margin: 0 0 1rem 1.5rem; list-style: revert; }
    .ext-content iframe { max-width: 100%; }
</style>

@endsection
