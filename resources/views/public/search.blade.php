@extends('public.layout')

@section('title', $query ? 'ফলাফল: "' . $query . '" — সজীব নিউজ' : 'অনুসন্ধান — সজীব নিউজ')
@section('robots', 'noindex, follow')

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .active-filter {
        background-color: #0066cc;
        color: white;
    }
    .filter-btn.active {
        background-color: #004e9f;
        color: #ffffff;
    }
</style>
@endpush

@section('content')

<main class="max-w-[1200px] mx-auto px-4 md:px-6 py-8">

    {{-- ===== Search Header ===== --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-on-surface mb-1" style="font-family:'SolaimanLipi',serif;">
            @if($query)
                ফলাফল: "{{ $query }}"
            @else
                সংবাদ অনুসন্ধান করুন
            @endif
        </h1>
        @if($query)
        <p class="text-sm text-on-surface-variant">
            আমরা আপনার অনুসন্ধানের জন্য
            <strong>{{ $news instanceof \Illuminate\Pagination\LengthAwarePaginator ? $news->total() : $news->count() }}টি</strong>
            ফলাফল পেয়েছি।
        </p>
        @endif

        {{-- Search Form --}}
        <form action="{{ route('news.search') }}" method="GET"
              class="mt-5 flex flex-col sm:flex-row gap-3 items-center max-w-2xl">
            <div class="relative w-full">
                <input name="q" value="{{ $query }}" type="text" autofocus
                       class="w-full pl-12 pr-4 py-4 border border-outline rounded-xl bg-surface focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 outline-none text-base"
                       placeholder="অনুসন্ধান করুন...">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            </div>
            <button type="submit"
                    class="bg-primary text-white px-8 py-4 rounded-xl font-bold text-sm flex items-center gap-2 whitespace-nowrap hover:opacity-90 transition-opacity">
                আবার অনুসন্ধান করুন
            </button>
        </form>
    </div>

    <div class="flex flex-col md:flex-row gap-8">

        {{-- ===== Left Sidebar Filters ===== --}}
        <aside class="w-full md:w-64 flex-shrink-0 space-y-5">

            {{-- Sort --}}
            <div class="bg-surface-container-low p-5 rounded-xl">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">সাজানোর ধরণ</h3>
                <select name="sort" onchange="applySort(this.value)"
                        class="w-full p-3 border border-outline rounded-lg bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="relevance" {{ request('sort','relevance')==='relevance' ? 'selected' : '' }}>সবচেয়ে প্রাসঙ্গিক</option>
                    <option value="latest"    {{ request('sort')==='latest'    ? 'selected' : '' }}>সর্বশেষ সংবাদ</option>
                    <option value="oldest"    {{ request('sort')==='oldest'    ? 'selected' : '' }}>পুরানো সংবাদ</option>
                    <option value="popular"   {{ request('sort')==='popular'   ? 'selected' : '' }}>সর্বাধিক পঠিত</option>
                </select>
            </div>

            {{-- Category Filter --}}
            @php
                $allCategories = \App\Models\Category::where('is_active', true)->withCount(['news' => fn($q) => $q->where('status','published')])->orderByDesc('news_count')->get();
                $activeCategory = request('category');
            @endphp
            @if($allCategories->count())
            <div class="bg-surface-container-low p-5 rounded-xl">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">বিভাগসমূহ</h3>
                <div class="space-y-2">
                    @foreach($allCategories as $cat)
                    <a href="{{ route('news.search', array_merge(request()->only('q','sort'), ['category' => $cat->slug])) }}"
                       class="flex justify-between items-center group cursor-pointer {{ $activeCategory === $cat->slug ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }} transition-colors">
                        <span class="text-sm">{{ $cat->name }}</span>
                        <span class="text-xs px-2 rounded {{ $activeCategory === $cat->slug ? 'bg-primary-container text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">
                            {{ $cat->news_count }}
                        </span>
                    </a>
                    @endforeach
                </div>
                @if($activeCategory)
                <a href="{{ route('news.search', request()->only('q','sort')) }}"
                   class="mt-3 flex items-center gap-1 text-xs text-error hover:underline">
                    <span class="material-symbols-outlined text-[14px]">close</span> ফিল্টার সরান
                </a>
                @endif
            </div>
            @endif

            {{-- Date Filter --}}
            <div class="bg-surface-container-low p-5 rounded-xl">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">সময়কাল</h3>
                <div class="flex flex-col gap-1">
                    @php $activePeriod = request('period',''); @endphp
                    @foreach([
                        ['24h',   'গত ২৪ ঘণ্টা'],
                        ['week',  'গত এক সপ্তাহ'],
                        ['month', 'গত এক মাস'],
                        ['year',  'গত এক বছর'],
                    ] as [$val, $label])
                    <a href="{{ route('news.search', array_merge(request()->only('q','sort','category'), ['period' => $val])) }}"
                       class="text-left py-2 px-3 rounded-lg text-sm transition-colors
                              {{ $activePeriod === $val ? 'bg-primary text-white font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                    @if($activePeriod)
                    <a href="{{ route('news.search', request()->only('q','sort','category')) }}"
                       class="flex items-center gap-1 text-xs text-error hover:underline mt-1">
                        <span class="material-symbols-outlined text-[14px]">close</span> সরান
                    </a>
                    @endif
                </div>
            </div>

            {{-- Trending --}}
            @php $trending = \App\Models\News::where('status','published')->orderByDesc('views')->limit(5)->get(); @endphp
            @if($trending->count())
            <div class="bg-surface-container-low p-5 rounded-xl">
                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">ট্রেন্ডিং</h3>
                <div class="space-y-3">
                    @foreach($trending as $i => $t)
                    <a href="{{ route('news.show', $t->slug) }}" class="flex gap-3 items-start group {{ $i > 0 ? 'border-t border-outline-variant pt-3' : '' }}">
                        <span class="text-2xl font-bold text-outline-variant group-hover:text-primary transition-colors leading-none flex-shrink-0">
                            {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="text-sm text-on-surface-variant group-hover:text-primary transition-colors line-clamp-2" style="font-family:'SolaimanLipi',serif;">
                            {{ $t->title }}
                        </p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </aside>

        {{-- ===== Results Area ===== --}}
        <section class="flex-1 min-w-0">

            @if($query)
                @forelse($news as $article)
                <article class="flex flex-col sm:flex-row gap-5 p-4 mb-4 border border-outline-variant rounded-xl bg-surface hover:shadow-lg transition-shadow duration-300 group">

                    {{-- Thumbnail --}}
                    <div class="w-full sm:w-1/3 flex-shrink-0 overflow-hidden rounded-lg" style="min-height:160px;max-height:180px;">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             src="{{ $article->featured_image ? storage_image_url($article->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                             alt="{{ $article->title }}">
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            {{-- Category + Date --}}
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                @if($article->category)
                                <span class="px-3 py-1 bg-primary-container text-white text-xs rounded-lg font-bold uppercase">
                                    {{ $article->category->name }}
                                </span>
                                @endif
                                @if($article->published_at)
                                <span class="text-xs text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size:14px;">schedule</span>
                                    {{ $article->published_at->diffForHumans() }}
                                </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h2 class="text-lg md:text-xl font-semibold text-on-surface mb-2 leading-snug group-hover:text-primary transition-colors"
                                style="font-family:'SolaimanLipi',serif;">
                                {{ $article->title }}
                            </h2>

                            {{-- Excerpt --}}
                            @if($article->excerpt)
                            <p class="text-sm text-on-surface-variant line-clamp-3">{{ $article->excerpt }}</p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xs font-bold text-primary">
                                @if($article->author)
                                    লিখেছেন: {{ $article->author->name }}
                                @else
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                                        {{ number_format($article->views ?? 0) }}
                                    </span>
                                @endif
                            </span>
                            <a href="{{ route('news.show', $article->slug) }}"
                               class="flex items-center gap-1 text-primary text-sm font-bold hover:underline">
                                বিস্তারিত পড়ুন
                                <span class="material-symbols-outlined" style="font-size:18px;">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <div class="text-center py-24">
                    <span class="material-symbols-outlined text-outline-variant" style="font-size:80px;">search_off</span>
                    <h3 class="text-xl font-semibold text-on-surface-variant mt-4" style="font-family:'SolaimanLipi',serif;">
                        "{{ $query }}" এর জন্য কোনো ফলাফল পাওয়া যায়নি
                    </h3>
                    <p class="text-sm text-on-surface-variant mt-2">ভিন্ন কীওয়ার্ড দিয়ে চেষ্টা করুন</p>
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($news instanceof \Illuminate\Pagination\LengthAwarePaginator && $news->hasPages())
                @php $paginator = $news->appends(request()->only('q','sort','category','period')); @endphp
                <div class="flex justify-center items-center gap-2 py-12">
                    {{-- Prev --}}
                    @if($paginator->onFirstPage())
                    <span class="w-12 h-12 flex items-center justify-center rounded-full border border-outline-variant text-outline-variant cursor-not-allowed opacity-40">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </span>
                    @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="w-12 h-12 flex items-center justify-center rounded-full border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage()-2), min($paginator->lastPage(), $paginator->currentPage()+2)) as $page => $url)
                    @if($page == $paginator->currentPage())
                    <span class="w-12 h-12 flex items-center justify-center rounded-full bg-primary text-white font-bold">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}"
                       class="w-12 h-12 flex items-center justify-center rounded-full border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach

                    {{-- Next --}}
                    @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="w-12 h-12 flex items-center justify-center rounded-full border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    @else
                    <span class="w-12 h-12 flex items-center justify-center rounded-full border border-outline-variant text-outline-variant cursor-not-allowed opacity-40">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </span>
                    @endif
                </div>
                @endif

            @else
                {{-- Empty state - no query --}}
                <div class="text-center py-24">
                    <span class="material-symbols-outlined text-outline-variant" style="font-size:80px;">manage_search</span>
                    <h3 class="text-xl font-semibold text-on-surface-variant mt-4" style="font-family:'SolaimanLipi',serif;">
                        কী খুঁজছেন লিখুন
                    </h3>
                    <p class="text-sm text-on-surface-variant mt-1">উপরের সার্চ বক্সে লিখে অনুসন্ধান শুরু করুন</p>
                </div>
            @endif

        </section>
    </div>

</main>

@push('scripts')
<script>
    function applySort(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', val);
        window.location.href = url.toString();
    }
</script>
@endpush

@endsection
