@extends('layouts.admin')

@section('page-title', 'বিভাগ ব্যবস্থাপনা')

@push('styles')
<style>
    .toggle-track {
        width: 44px; height: 24px;
        background: #c1c6d5;
        border-radius: 9999px;
        position: relative;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-block;
        flex-shrink: 0;
    }
    .toggle-track.on { background: #004e9f; }
    .toggle-track::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 20px; height: 20px;
        background: white;
        border-radius: 9999px;
        transition: transform 0.2s;
    }
    .toggle-track.on::after { transform: translateX(20px); }
    tr.row-hover:hover { background-color: #f0eded; }
</style>
@endpush

@section('content')

{{-- ===== Success / Error Alert ===== --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-tertiary/10 border border-tertiary/30 text-tertiary px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 flex items-center gap-3 bg-error/10 border border-error/30 text-error px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">error</span>
    {{ session('error') }}
</div>
@endif

{{-- ===== Page Header ===== --}}
<header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'Noto Serif Bengali',serif;">বিভাগ ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">সজীব নিউজের সংবাদ বিভাগসমূহ পরিচালনা করুন।</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 shadow-sm transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        নতুন বিভাগ যোগ করুন
    </a>
</header>

{{-- ===== Search & Filter Bar ===== --}}
<form method="GET" action="{{ route('admin.categories.index') }}" id="searchForm">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center justify-between">
    <div class="relative w-full md:w-96">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
               placeholder="বিভাগ খুঁজুন...">
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto">
        <select name="status"
                class="bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active"   @selected(request('status') === 'active')>সক্রিয়</option>
            <option value="inactive" @selected(request('status') === 'inactive')>নিষ্ক্রিয়</option>
        </select>
        <button type="submit"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-[18px]">filter_list</span>
            ফিল্টার
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-1 px-3 py-2.5 border border-outline-variant rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[16px]">close</span>
            রিসেট
        </a>
        @endif
    </div>
</div>
</form>

{{-- ===== Data Table ===== --}}
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">বিভাগের নাম</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্লাগ</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">সংবাদ সংখ্যা</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্ট্যাটাস</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($categories as $category)
                <tr class="row-hover transition-colors">
                    {{-- Name --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            @if($category->color)
                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $category->color }}"></div>
                            @endif
                            <div>
                                <span class="font-semibold text-primary text-base" style="font-family:'Noto Serif Bengali',serif;">
                                    {{ $category->name }}
                                </span>
                                @if($category->featured_order)
                                <span class="ml-2 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase">
                                    ⭐ #{{ $category->featured_order }}
                                </span>
                                @endif
                                @if($category->description)
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ Str::limit($category->description, 50) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Slug --}}
                    <td class="px-6 py-5">
                        <code class="font-mono text-sm text-on-surface-variant bg-surface-container px-2 py-1 rounded">{{ $category->slug }}</code>
                    </td>

                    {{-- News Count --}}
                    <td class="px-6 py-5">
                        <span class="bg-surface-container-high px-3 py-1 rounded-full text-xs font-bold text-on-surface">
                            {{ number_format($category->news_count ?? 0) }}
                        </span>
                    </td>

                    {{-- Status Toggle --}}
                    <td class="px-6 py-5">
                        <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 group" title="স্ট্যাটাস পরিবর্তন করুন">
                                <div class="toggle-track {{ $category->is_published ? 'on' : '' }}"></div>
                                <span class="text-xs font-bold {{ $category->is_published ? 'text-on-surface' : 'text-on-surface-variant' }} uppercase">
                                    {{ $category->is_published ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-5">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded-lg transition-all"
                               title="সম্পাদনা">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('এই বিভাগটি মুছে ফেলতে চান?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-2 text-on-surface-variant hover:text-error hover:bg-error-container rounded-lg transition-all"
                                        title="মুছে ফেলুন">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-20">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:64px;">category</span>
                        <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'Noto Serif Bengali',serif;">কোনো বিভাগ পাওয়া যায়নি</p>
                        <a href="{{ route('admin.categories.create') }}"
                           class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            প্রথম বিভাগ তৈরি করুন
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
    @php $pager = $categories->appends(request()->only('search','status')); @endphp
    <footer class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between gap-4 flex-wrap">
        <span class="text-sm text-on-surface-variant">
            মোট {{ $categories->total() }}টির মধ্যে
            {{ $categories->firstItem() }}–{{ $categories->lastItem() }}টি দেখানো হচ্ছে
        </span>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            @if($pager->onFirstPage())
            <span class="p-2 rounded text-on-surface-variant opacity-40 cursor-not-allowed">
                <span class="material-symbols-outlined">chevron_left</span>
            </span>
            @else
            <a href="{{ $pager->previousPageUrl() }}" class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </a>
            @endif

            {{-- Pages --}}
            @foreach($pager->getUrlRange(max(1,$pager->currentPage()-2), min($pager->lastPage(),$pager->currentPage()+2)) as $page => $url)
            @if($page == $pager->currentPage())
            <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold text-sm">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-high text-on-surface-variant font-bold text-sm transition-colors">{{ $page }}</a>
            @endif
            @endforeach

            @if($pager->lastPage() > $pager->currentPage() + 2)
            <span class="px-2 text-on-surface-variant">...</span>
            <a href="{{ $pager->url($pager->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-high text-on-surface-variant font-bold text-sm transition-colors">{{ $pager->lastPage() }}</a>
            @endif

            {{-- Next --}}
            @if($pager->hasMorePages())
            <a href="{{ $pager->nextPageUrl() }}" class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
            @else
            <span class="p-2 rounded text-on-surface-variant opacity-40 cursor-not-allowed">
                <span class="material-symbols-outlined">chevron_right</span>
            </span>
            @endif
        </div>
    </footer>
    @endif
</div>

{{-- ===== Quick Stats Cards ===== --}}
@php
    $totalCats  = \App\Models\Category::count();
    $activeCats = \App\Models\Category::where('is_published', true)->count();
    $totalViews = \App\Models\News::where('status','published')->sum('views');
@endphp
<section class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">inventory_2</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট বিভাগ</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $totalCats }}</h3>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-tertiary-fixed flex items-center justify-center text-tertiary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">সক্রিয় বিভাগ</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $activeCats }}</h3>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">visibility</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট ভিউ</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">
                {{ $totalViews > 1000000 ? number_format($totalViews/1000000,1).'M' : ($totalViews > 1000 ? number_format($totalViews/1000,1).'K' : number_format($totalViews)) }}
            </h3>
        </div>
    </div>
</section>

@endsection
