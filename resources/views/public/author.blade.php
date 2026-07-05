@extends('public.layout')

@section('title', $author->name . ' - লেখক প্রোফাইল | সজীব নিউজ')
@section('meta_description', $author->bio ?? $author->name . ' এর প্রকাশিত সব সংবাদ পড়ুন সজীব নিউজে।')

@push('styles')
<style>
    .tab-active {
        border-bottom: 3px solid #004e9f;
        color: #004e9f;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

{{-- ========== PROFILE HEADER ========== --}}
<section class="relative">
    {{-- Cover Photo --}}
    <div class="h-56 md:h-80 w-full overflow-hidden">
        <div class="w-full h-full bg-cover bg-center bg-gradient-to-br from-primary to-primary-container"
             style="background-image: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1400&auto=format&fit=crop&q=80')">
        </div>
    </div>

    {{-- Avatar (overlaps cover photo) --}}
    <div class="max-w-[1200px] mx-auto px-4 md:px-6 relative z-10">
        <div class="-mt-14 md:-mt-20">
            <div class="w-28 h-28 md:w-40 md:h-40 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                @if($author->avatar)
                    <img class="w-full h-full object-cover"
                         src="{{ storage_image_url($author->avatar) }}"
                         alt="{{ $author->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-primary-container">
                        <span class="material-symbols-outlined text-white" style="font-size: 64px; font-variation-settings: 'FILL' 1;">person</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Name & Bio (below the cover, after avatar) --}}
<div class="max-w-[1200px] mx-auto px-4 md:px-6 pt-4 pb-2">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl md:text-4xl font-bold text-on-surface" style="font-family: 'SolaimanLipi', serif;">
                {{ $author->name }}
            </h1>
            <p class="text-sm md:text-base text-on-surface-variant max-w-2xl mt-1">
                {{ $author->bio ?? $author->name . ' সজীব নিউজের একজন লেখক।' }}
            </p>
        </div>
        @auth
            @if(auth()->id() === $author->id)
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-1 bg-white border border-outline px-5 py-3 rounded-xl font-bold text-sm text-on-surface hover:bg-surface-container-low transition-colors shadow-sm self-start mt-2 md:mt-0 flex-shrink-0">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                প্রোফাইল সম্পাদনা
            </a>
            @endif
        @endauth
    </div>
</div>

{{-- ========== STATS + CONTENT ========== --}}
<div class="max-w-[1200px] mx-auto px-4 md:px-6 py-8">

    {{-- Stats Grid --}}
    @php
        $totalViews  = \App\Models\News::where('author_id', $author->id)->sum('views');
        $totalPublished = \App\Models\News::where('author_id', $author->id)->where('status', 'published')->count();
        $totalComments  = \App\Models\Comment::where('user_id', $author->id)->count();
        $memberSince = $author->created_at ? $author->created_at->translatedFormat('M Y') : 'N/A';
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-primary-container text-4xl mb-1" style="font-size: 40px;">menu_book</span>
            <span class="text-2xl md:text-3xl font-bold text-on-surface" style="font-family: 'SolaimanLipi', serif;">
                {{ $totalViews > 1000 ? number_format($totalViews/1000, 1).'K' : number_format($totalViews) }}
            </span>
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">মোট পাঠক</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-secondary-container text-4xl mb-1" style="font-size: 40px;">article</span>
            <span class="text-2xl md:text-3xl font-bold text-on-surface" style="font-family: 'SolaimanLipi', serif;">
                {{ $totalPublished }}
            </span>
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">প্রকাশিত নিবন্ধ</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-tertiary-container text-4xl mb-1" style="font-size: 40px;">comment</span>
            <span class="text-2xl md:text-3xl font-bold text-on-surface" style="font-family: 'SolaimanLipi', serif;">
                {{ $totalComments }}
            </span>
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">মন্তব্য</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-outline text-4xl mb-1" style="font-size: 40px;">calendar_today</span>
            <span class="text-lg md:text-xl font-bold text-on-surface" style="font-family: 'SolaimanLipi', serif;">
                {{ $author->created_at ? $author->created_at->format('M Y') : 'N/A' }}
            </span>
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">সদস্য হওয়ার তারিখ</span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-outline-variant">
        <div class="flex gap-8 overflow-x-auto pb-px no-scrollbar">
            <button class="tab-active py-4 px-1 font-bold text-base cursor-pointer whitespace-nowrap transition-all" id="tab-articles" onclick="switchTab('articles')">
                প্রকাশিত নিবন্ধ
            </button>
            <button class="py-4 px-1 font-bold text-base text-on-surface-variant cursor-pointer whitespace-nowrap hover:text-primary transition-all" id="tab-popular" onclick="switchTab('popular')">
                সর্বাধিক পঠিত
            </button>
        </div>
    </div>

    {{-- Tab: Latest Articles (3-column grid) --}}
    <div id="content-articles">
        @if($news->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
            <a href="{{ route('news.show', $item->slug) }}"
               class="bg-white rounded-xl overflow-hidden border border-outline-variant shadow-sm hover:shadow-md transition-shadow group cursor-pointer block">
                <div class="relative h-48">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         src="{{ $item->featured_image ? storage_image_url($item->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                         alt="{{ $item->title }}">
                    @if($item->category)
                    <div class="absolute top-3 left-3 bg-primary-container text-white text-[11px] px-2 py-1 rounded font-bold uppercase tracking-widest">
                        {{ $item->category->name }}
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-[17px] leading-snug text-on-surface group-hover:text-primary transition-colors line-clamp-2"
                        style="font-family: 'SolaimanLipi', serif;">
                        {{ $item->title }}
                    </h3>
                    @if($item->excerpt)
                    <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">{{ $item->excerpt }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xs text-outline flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">schedule</span>
                            {{ $item->published_at?->diffForHumans() }}
                        </span>
                        <span class="material-symbols-outlined text-primary-container" style="font-size: 20px; font-variation-settings: 'FILL' 1;">bookmark</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($news->hasPages())
        <div class="mt-8">{{ $news->links() }}</div>
        @endif

        @else
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-outline-variant" style="font-size: 80px;">article</span>
            <p class="text-lg text-on-surface-variant mt-4">এই লেখকের কোনো নিবন্ধ নেই।</p>
        </div>
        @endif
    </div>

    {{-- Tab: Popular Articles --}}
    <div id="content-popular" class="hidden flex flex-col gap-4">
        @php
            $popularByAuthor = \App\Models\News::where('author_id', $author->id)
                ->where('status', 'published')
                ->orderByDesc('views')
                ->limit(10)
                ->get();
        @endphp
        @forelse($popularByAuthor as $item)
        <a href="{{ route('news.show', $item->slug) }}"
           class="bg-white p-5 rounded-xl border border-outline-variant flex gap-5 items-center hover:bg-surface-container-low transition-colors cursor-pointer group">
            <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0">
                <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                     src="{{ $item->featured_image ? storage_image_url($item->featured_image) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
                     alt="{{ $item->title }}">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    @if($item->category)
                    <span class="bg-surface-variant text-on-surface-variant text-[10px] px-2 py-[2px] rounded font-bold uppercase tracking-tighter">
                        {{ $item->category->name }}
                    </span>
                    @endif
                    <span class="text-xs text-outline">{{ $item->published_at?->diffForHumans() }}</span>
                </div>
                <h4 class="font-semibold text-[16px] text-on-surface line-clamp-2 group-hover:text-primary transition-colors"
                    style="font-family: 'SolaimanLipi', serif;">
                    {{ $item->title }}
                </h4>
                <span class="text-xs text-outline flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined" style="font-size: 14px;">visibility</span>
                    {{ number_format($item->views ?? 0) }} পাঠক
                </span>
            </div>
            <span class="material-symbols-outlined text-outline flex-shrink-0">chevron_right</span>
        </a>
        @empty
        <div class="text-center py-16">
            <p class="text-on-surface-variant">কোনো নিবন্ধ পাওয়া যায়নি।</p>
        </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
    function switchTab(tabId) {
        const tabs = ['articles', 'popular'];
        tabs.forEach(t => {
            const btn     = document.getElementById('tab-' + t);
            const content = document.getElementById('content-' + t);
            if (t === tabId) {
                btn.classList.add('tab-active');
                btn.classList.remove('text-on-surface-variant');
                if (content) content.classList.remove('hidden');
            } else {
                btn.classList.remove('tab-active');
                btn.classList.add('text-on-surface-variant');
                if (content) content.classList.add('hidden');
            }
        });
    }
</script>
@endpush

@endsection
