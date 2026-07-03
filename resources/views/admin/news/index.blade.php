@extends('layouts.admin')

@section('page-title', 'সংবাদ ব্যবস্থাপনা')

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .filter-chip.active-chip {
        background-color: #004e9f;
        color: #ffffff;
        border-color: #004e9f;
    }
    .news-card { transition: box-shadow 0.2s, transform 0.2s; position: relative; }
    .news-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.10); transform: translateY(-2px); }
    .news-card.menu-active { z-index: 60; transform: none; }
    .action-menu { display: none; }
    .action-menu.open { display: block; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

{{-- ===== Page Header ===== --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">সংবাদ ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">মোট {{ $news->total() ?? $news->count() }}টি সংবাদ</p>
    </div>
    <a href="{{ route('admin.news.create') }}"
       class="bg-primary text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md hover:opacity-90 transition-opacity active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>
        নতুন সংবাদ
    </a>
</div>

{{-- ===== Search + Filter Chips ===== --}}
<form method="GET" action="{{ route('admin.news.index') }}" id="filterForm">
<div class="flex flex-col gap-3 mb-6">

    {{-- Search --}}
    <div class="relative w-full">
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"
               placeholder="সংবাদ শিরোনাম খুঁজুন...">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        @if(request('search'))
        <a href="{{ route('admin.news.index', array_merge(request()->except('search','page'), [])) }}"
           class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-full hover:bg-surface-container text-outline hover:text-error transition-colors">
            <span class="material-symbols-outlined text-[16px]">close</span>
        </a>
        @endif
    </div>

    {{-- Status Chips + Filter Toggle --}}
    <div class="flex gap-2 overflow-x-auto hide-scrollbar py-1 items-center">
        <button type="button" id="filterToggle" onclick="toggleFilters()"
                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 border border-outline-variant rounded-full text-on-surface-variant font-bold text-xs bg-surface-container-low hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined text-[16px]">filter_list</span>
            ফিল্টার
        </button>
        <div class="h-6 w-px bg-outline-variant mx-1 flex-shrink-0"></div>
        @foreach([
            [''          , 'সব'],
            ['published' , 'প্রকাশিত'],
            ['draft'     , 'খসড়া'],
            ['scheduled' , 'নির্ধারিত'],
        ] as [$val, $label])
        <a href="{{ route('admin.news.index', array_merge(request()->except('status','page'), $val ? ['status'=>$val] : [])) }}"
           class="filter-chip flex-shrink-0 px-3 py-2 border rounded-full font-bold text-xs transition-all {{ request('status',$val===''?'':null) === $val ? 'active-chip' : 'border-outline-variant text-on-surface-variant bg-surface-container-low hover:bg-surface-container' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Collapsible Advanced Filters --}}
    <div id="filterMenu" class="{{ (request('category') || request('date_from') || request('date_to')) ? 'flex' : 'hidden' }} flex-col gap-4 bg-surface-container-low p-4 rounded-xl border border-outline-variant">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">বিভাগ</span>
                <select name="category"
                        class="bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
                    <option value="">সব বিভাগ</option>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">শুরুর তারিখ</span>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">শেষের তারিখ</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                    class="px-5 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:opacity-90 transition-opacity">
                প্রয়োগ করুন
            </button>
            <a href="{{ route('admin.news.index') }}"
               class="px-5 py-2 border border-outline-variant text-on-surface-variant rounded-lg font-bold text-sm hover:bg-surface-container transition-colors">
                রিসেট
            </a>
        </div>
    </div>

</div>
</form>

{{-- ===== News Cards Grid ===== --}}
@if($news->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
    @foreach($news as $item)
    <article class="news-card bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm flex flex-col relative">

        {{-- Thumbnail --}}
        <div class="relative h-48 w-full overflow-hidden flex-shrink-0 rounded-t-xl">
            <img class="w-full h-full object-cover {{ $item->status === 'draft' ? 'grayscale opacity-70' : '' }}"
                 src="{{ $item->featured_image ? Storage::url($item->featured_image) : 'https://picsum.photos/seed/'.$item->id.'/600/400' }}"
                 alt="{{ $item->title }}">

            {{-- Status Badge --}}
            <div class="absolute top-3 left-3">
                @if($item->status === 'published')
                <span class="bg-tertiary-container text-on-tertiary-container px-2.5 py-1 rounded font-bold text-[10px] uppercase tracking-wider shadow-sm">প্রকাশিত</span>
                @elseif($item->status === 'scheduled')
                <span class="bg-secondary-container text-on-secondary-container px-2.5 py-1 rounded font-bold text-[10px] uppercase tracking-wider shadow-sm">নির্ধারিত</span>
                @else
                <span class="bg-outline text-surface-bright px-2.5 py-1 rounded font-bold text-[10px] uppercase tracking-wider shadow-sm">খসড়া</span>
                @endif
            </div>

            {{-- Badges (Featured / Breaking) --}}
            <div class="absolute top-3 right-3 flex flex-col gap-1">
                @if($item->is_featured)
                <span class="bg-yellow-400 text-yellow-900 px-2 py-0.5 rounded text-[10px] font-bold uppercase shadow-sm">
                    ⭐ ফিচার্ড
                </span>
                @endif
                @if($item->is_breaking)
                <span class="bg-error text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase shadow-sm">
                    🔥 ব্রেকিং
                </span>
                @endif
            </div>
        </div>

        {{-- Content --}}
        <div class="p-4 flex flex-col gap-3 flex-1">
            <div class="flex justify-between items-start gap-2">
                <h3 class="font-semibold text-[15px] leading-snug text-on-surface line-clamp-2 flex-1 {{ $item->status === 'draft' ? 'italic text-on-surface-variant' : '' }}"
                    style="font-family:'SolaimanLipi',serif;">
                    {{ $item->title }}
                </h3>

                {{-- Three-dot action menu --}}
                <div class="relative flex-shrink-0">
                    <button type="button" onclick="toggleMenu(this)"
                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-outline text-[20px]">more_vert</span>
                    </button>
                    <div class="action-menu absolute right-0 top-9 w-44 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl z-50 py-1 overflow-hidden">
                        <a href="{{ route('admin.news.edit', $item) }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-[18px] text-primary">edit</span>
                            সম্পাদনা
                        </a>
                        <a href="{{ route('news.show', $item->slug) }}" target="_blank"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-[18px] text-tertiary">open_in_new</span>
                            দেখুন
                        </a>
                        <div class="h-px bg-outline-variant mx-3 my-1"></div>
                        <form method="POST" action="{{ route('admin.news.destroy', $item) }}"
                              onsubmit="return confirm('এই সংবাদটি মুছে ফেলতে চান?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-error hover:bg-error/5 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                মুছে ফেলুন
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Meta info --}}
            <div class="flex items-center gap-4 text-xs text-on-surface-variant mt-auto">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">person</span>
                    {{ $item->author->name ?? 'অজানা' }}
                </span>
                <span class="flex items-center gap-1">
                    @if($item->status === 'draft')
                    <span class="material-symbols-outlined text-[15px]">edit</span>
                    {{ $item->updated_at->diffForHumans() }}
                    @elseif($item->status === 'scheduled')
                    <span class="material-symbols-outlined text-[15px]">schedule</span>
                    {{ $item->published_at?->format('d M, H:i') ?? '-' }}
                    @else
                    <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                    {{ $item->published_at?->format('d M, Y') ?? '-' }}
                    @endif
                </span>
                @if($item->status === 'published')
                <span class="flex items-center gap-1 ml-auto">
                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                    {{ number_format($item->views ?? 0) }}
                </span>
                @endif
            </div>
        </div>
    </article>
    @endforeach
</div>

{{-- ===== Pagination ===== --}}
@if($news instanceof \Illuminate\Pagination\LengthAwarePaginator && $news->hasPages())
@php $paginator = $news->appends(request()->only('search','status','category','date_from','date_to')); @endphp
<nav class="flex items-center justify-between bg-surface-container-low p-2 rounded-2xl border border-outline-variant">
    {{-- Prev --}}
    @if($paginator->onFirstPage())
    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container-lowest border border-outline-variant text-outline opacity-40 cursor-not-allowed">
        <span class="material-symbols-outlined">chevron_left</span>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}"
       class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:bg-primary-container hover:text-white transition-all">
        <span class="material-symbols-outlined">chevron_left</span>
    </a>
    @endif

    {{-- Page numbers --}}
    <div class="flex gap-1">
        @foreach($paginator->getUrlRange(max(1,$paginator->currentPage()-2), min($paginator->lastPage(),$paginator->currentPage()+2)) as $page => $url)
        @if($page == $paginator->currentPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-sm">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-lowest text-on-surface font-bold text-sm hover:bg-primary-container hover:text-white transition-all">{{ $page }}</a>
        @endif
        @endforeach
        @if($paginator->lastPage() > $paginator->currentPage() + 2)
        <span class="w-10 h-10 flex items-center justify-center text-outline">...</span>
        @endif
    </div>

    {{-- Next --}}
    @if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}"
       class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:bg-primary-container hover:text-white transition-all">
        <span class="material-symbols-outlined">chevron_right</span>
    </a>
    @else
    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container-lowest border border-outline-variant text-outline opacity-40 cursor-not-allowed">
        <span class="material-symbols-outlined">chevron_right</span>
    </span>
    @endif
</nav>
@endif

@else
{{-- Empty state --}}
<div class="text-center py-24 bg-surface-container-lowest rounded-xl border border-outline-variant">
    <span class="material-symbols-outlined text-outline-variant" style="font-size:72px;">article</span>
    <h3 class="text-lg font-semibold text-on-surface-variant mt-4" style="font-family:'SolaimanLipi',serif;">
        @if(request('search') || request('status') || request('category'))
            কোনো সংবাদ পাওয়া যায়নি
        @else
            এখনো কোনো সংবাদ নেই
        @endif
    </h3>
    <p class="text-sm text-on-surface-variant mt-1 mb-6">
        @if(request('search') || request('status') || request('category'))
            ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন
        @else
            প্রথম সংবাদ তৈরি করুন
        @endif
    </p>
    @if(!request('search') && !request('status') && !request('category'))
    <a href="{{ route('admin.news.create') }}"
       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-[18px]">add</span>
        নতুন সংবাদ তৈরি করুন
    </a>
    @endif
</div>
@endif

@push('scripts')
<script>
function toggleFilters() {
    const menu = document.getElementById('filterMenu');
    const btn  = document.getElementById('filterToggle');
    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        menu.classList.add('flex');
        btn.classList.add('bg-primary-container', 'text-on-primary-container', 'border-primary');
        btn.classList.remove('border-outline-variant', 'text-on-surface-variant', 'bg-surface-container-low');
    } else {
        menu.classList.add('hidden');
        menu.classList.remove('flex');
        btn.classList.remove('bg-primary-container', 'text-on-primary-container', 'border-primary');
        btn.classList.add('border-outline-variant', 'text-on-surface-variant', 'bg-surface-container-low');
    }
}

function toggleMenu(btn) {
    const menu = btn.nextElementSibling;
    const card = btn.closest('.news-card');
    // Close all open menus first
    document.querySelectorAll('.action-menu.open').forEach(m => {
        if (m !== menu) {
            m.classList.remove('open');
            m.closest('.news-card')?.classList.remove('menu-active');
        }
    });
    menu.classList.toggle('open');
    card.classList.toggle('menu-active', menu.classList.contains('open'));
}

// Close menus on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('.action-menu.open').forEach(m => {
            m.classList.remove('open');
            m.closest('.news-card')?.classList.remove('menu-active');
        });
    }
});

// Auto-submit on chip click already uses links; search on Enter
document.querySelector('[name="search"]')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') document.getElementById('filterForm').submit();
});
</script>
@endpush

@endsection
