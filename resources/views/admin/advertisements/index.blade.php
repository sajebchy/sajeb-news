@extends('layouts.admin')

@section('page-title', 'বিজ্ঞাপন ব্যবস্থাপনা')

@push('styles')
<style>
    .stat-card { transition: transform .2s, box-shadow .2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.10); }
    .ad-row:hover { background-color: #f0eded; }
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
</style>
@endpush

@section('content')

{{-- ===== Alerts ===== --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-tertiary/10 border border-tertiary/30 text-tertiary px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-5 bg-error/10 border border-error/30 text-error px-4 py-3 rounded-xl text-sm">
    <p class="font-bold mb-1 flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">error</span> ত্রুটি পাওয়া গেছে</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

{{-- ===== Header ===== --}}
<header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">বিজ্ঞাপন ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">সজীব নিউজের সকল বিজ্ঞাপন প্রচারণা পরিচালনা করুন।</p>
    </div>
    <a href="{{ route('admin.advertisements.create') }}"
       class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md hover:opacity-90 transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        নতুন বিজ্ঞাপন তৈরি করুন
    </a>
</header>

{{-- ===== Stats Cards ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="stat-card bg-surface-container-lowest rounded-xl border border-outline-variant p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary" style="font-size:24px;">ads_click</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট বিজ্ঞাপন</p>
            <p class="text-2xl font-bold text-on-surface">{{ number_format($totalAds) }}</p>
        </div>
    </div>

    <div class="stat-card bg-surface-container-lowest rounded-xl border border-outline-variant p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-tertiary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-tertiary" style="font-size:24px;">play_circle</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">সক্রিয়</p>
            <p class="text-2xl font-bold text-on-surface">{{ number_format($activeAds) }}</p>
        </div>
    </div>

    <div class="stat-card bg-surface-container-lowest rounded-xl border border-outline-variant p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary-container" style="font-size:24px;">visibility</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট ভিউ</p>
            <p class="text-2xl font-bold text-on-surface">
                {{ $totalImpressions > 1000000 ? number_format($totalImpressions/1000000,1).'M' : ($totalImpressions > 1000 ? number_format($totalImpressions/1000,1).'K' : number_format($totalImpressions)) }}
            </p>
        </div>
    </div>

    <div class="stat-card bg-surface-container-lowest rounded-xl border border-outline-variant p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-secondary" style="font-size:24px;">touch_app</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট ক্লিক</p>
            <p class="text-2xl font-bold text-on-surface">
                {{ $totalClicks > 1000 ? number_format($totalClicks/1000,1).'K' : number_format($totalClicks) }}
            </p>
        </div>
    </div>

</div>

{{-- ===== Search Bar ===== --}}
<form method="GET" action="{{ route('admin.advertisements.index') }}">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center justify-between">
    <div class="relative w-full md:w-96">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
               placeholder="বিজ্ঞাপন খুঁজুন...">
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto">
        <select name="placement"
                class="bg-surface border border-outline-variant rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব প্লেসমেন্ট</option>
            @foreach(['header','sidebar','footer','in_article','popup','banner'] as $p)
            <option value="{{ $p }}" @selected(request('placement') === $p)>{{ ucfirst(str_replace('_',' ',$p)) }}</option>
            @endforeach
        </select>
        <select name="status"
                class="bg-surface border border-outline-variant rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active"   @selected(request('status') === 'active')>সক্রিয়</option>
            <option value="inactive" @selected(request('status') === 'inactive')>নিষ্ক্রিয়</option>
        </select>
        <button type="submit"
                class="flex items-center gap-1.5 px-4 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">filter_list</span>
            ফিল্টার
        </button>
        @if(request()->hasAny(['search','placement','status']))
        <a href="{{ route('admin.advertisements.index') }}"
           class="flex items-center gap-1 px-3 py-2.5 border border-outline-variant rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">close</span>
            রিসেট
        </a>
        @endif
    </div>
</div>
</form>

{{-- ===== Table ===== --}}
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">বিজ্ঞাপনের নাম</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">প্লেসমেন্ট</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">ধরণ</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্ট্যাটাস</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">ভিউ</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">ক্লিক</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">CTR</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">মেয়াদ</th>
                    <th class="px-5 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($ads as $ad)
                <tr class="ad-row transition-colors">

                    {{-- Name --}}
                    <td class="px-5 py-4">
                        <div>
                            <p class="font-semibold text-sm text-on-surface">{{ $ad->name }}</p>
                            @if($ad->ad_network)
                            <span class="text-[11px] text-on-surface-variant">{{ ucfirst($ad->ad_network) }}</span>
                            @endif
                        </div>
                    </td>

                    {{-- Placement --}}
                    <td class="px-5 py-4">
                        <span class="bg-primary/10 text-primary text-[11px] font-bold px-2.5 py-1 rounded-full uppercase">
                            {{ ucfirst(str_replace('_', ' ', $ad->placement ?? '—')) }}
                        </span>
                    </td>

                    {{-- Type --}}
                    <td class="px-5 py-4">
                        <span class="bg-surface-container-high text-on-surface-variant text-[11px] font-bold px-2.5 py-1 rounded-full uppercase">
                            {{ ucfirst($ad->ad_type ?? $ad->type ?? '—') }}
                        </span>
                    </td>

                    {{-- Status toggle --}}
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admin.advertisements.toggle-status', $ad) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 group" title="স্ট্যাটাস পরিবর্তন">
                                <div class="toggle-track {{ $ad->is_active ? 'on' : '' }}"></div>
                                <span class="text-xs font-bold {{ $ad->is_active ? 'text-tertiary' : 'text-on-surface-variant' }} uppercase">
                                    {{ $ad->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </button>
                        </form>
                    </td>

                    {{-- Views --}}
                    <td class="px-5 py-4 text-right">
                        <span class="text-sm font-semibold text-on-surface">
                            {{ $ad->views > 1000 ? number_format($ad->views/1000,1).'K' : number_format($ad->views) }}
                        </span>
                    </td>

                    {{-- Clicks --}}
                    <td class="px-5 py-4 text-right">
                        <span class="text-sm font-semibold text-on-surface">{{ number_format($ad->clicks) }}</span>
                    </td>

                    {{-- CTR --}}
                    <td class="px-5 py-4 text-right">
                        @if($ad->views > 0)
                        <span class="text-sm font-bold text-tertiary">{{ $ad->ctr }}%</span>
                        @else
                        <span class="text-sm text-on-surface-variant">—</span>
                        @endif
                    </td>

                    {{-- Period --}}
                    <td class="px-5 py-4">
                        <p class="text-xs text-on-surface-variant whitespace-nowrap">
                            {{ $ad->start_date?->format('d M Y') ?? '—' }}
                        </p>
                        <p class="text-xs text-on-surface-variant">
                            {{ $ad->end_date ? '→ '.$ad->end_date->format('d M Y') : '→ ∞' }}
                        </p>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('admin.advertisements.show', $ad) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-primary-container hover:bg-primary/10 transition-colors"
                               title="বিস্তারিত দেখুন">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                            <a href="{{ route('admin.advertisements.edit', $ad) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-primary hover:bg-primary/10 transition-colors"
                               title="সম্পাদনা">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form method="POST" action="{{ route('admin.advertisements.destroy', $ad) }}"
                                  onsubmit="return confirm('এই বিজ্ঞাপনটি মুছে ফেলতে চান?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors"
                                        title="মুছে ফেলুন">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-20">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:64px;">campaign</span>
                        <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'SolaimanLipi',serif;">
                            কোনো বিজ্ঞাপন পাওয়া যায়নি
                        </p>
                        @if(!request()->hasAny(['search','placement','status']))
                        <a href="{{ route('admin.advertisements.create') }}"
                           class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            প্রথম বিজ্ঞাপন তৈরি করুন
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination footer --}}
    @if($ads instanceof \Illuminate\Pagination\LengthAwarePaginator && $ads->hasPages())
    @php $pager = $ads->appends(request()->only('search','placement','status')); @endphp
    <footer class="px-5 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between gap-4 flex-wrap">
        <span class="text-sm text-on-surface-variant">
            মোট {{ $ads->total() }}টির মধ্যে {{ $ads->firstItem() }}–{{ $ads->lastItem() }}টি দেখানো হচ্ছে
        </span>
        <div class="flex items-center gap-1">
            @if($pager->onFirstPage())
            <span class="p-2 rounded text-on-surface-variant opacity-40 cursor-not-allowed"><span class="material-symbols-outlined">chevron_left</span></span>
            @else
            <a href="{{ $pager->previousPageUrl() }}" class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors"><span class="material-symbols-outlined">chevron_left</span></a>
            @endif

            @foreach($pager->getUrlRange(max(1,$pager->currentPage()-2), min($pager->lastPage(),$pager->currentPage()+2)) as $page => $url)
                @if($page == $pager->currentPage())
                <span class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold text-sm">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-high text-on-surface-variant font-bold text-sm transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($pager->hasMorePages())
            <a href="{{ $pager->nextPageUrl() }}" class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors"><span class="material-symbols-outlined">chevron_right</span></a>
            @else
            <span class="p-2 rounded text-on-surface-variant opacity-40 cursor-not-allowed"><span class="material-symbols-outlined">chevron_right</span></span>
            @endif
        </div>
    </footer>
    @endif
</div>

{{-- ===== Bottom Performance Bento ===== --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-5">

    {{-- Top performing ad --}}
    @php $topAd = $ads->sortByDesc('clicks')->first(); @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-secondary text-[20px]">emoji_events</span>
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">সেরা পারফর্মার</h3>
        </div>
        @if($topAd)
        <p class="font-semibold text-on-surface text-base" style="font-family:'SolaimanLipi',serif;">{{ $topAd->name }}</p>
        <div class="flex items-center gap-4 mt-2">
            <span class="text-xs text-on-surface-variant flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">touch_app</span>
                {{ number_format($topAd->clicks) }} ক্লিক
            </span>
            @if($topAd->views > 0)
            <span class="text-xs font-bold text-tertiary">{{ $topAd->ctr }}% CTR</span>
            @endif
        </div>
        @else
        <p class="text-sm text-on-surface-variant">কোনো ডেটা নেই</p>
        @endif
    </div>

    {{-- Active vs Inactive --}}
    @php
        $inactiveAds = \App\Models\Advertisement::where('is_active', false)->count();
        $activeCount = \App\Models\Advertisement::where('is_active', true)->count();
        $total = $activeCount + $inactiveAds;
        $activePct = $total > 0 ? round(($activeCount/$total)*100) : 0;
    @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-primary text-[20px]">pie_chart</span>
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">সক্রিয়তার হার</h3>
        </div>
        <div class="flex items-end justify-between mb-2">
            <span class="text-3xl font-bold text-on-surface">{{ $activePct }}%</span>
            <span class="text-xs text-on-surface-variant">{{ $activeCount }}/{{ $total }}</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden">
            <div class="h-full bg-primary rounded-full transition-all duration-700" style="width: {{ $activePct }}%"></div>
        </div>
        <p class="text-xs text-on-surface-variant mt-2">{{ $activeCount }}টি সক্রিয়, {{ $inactiveAds }}টি নিষ্ক্রিয়</p>
    </div>

    {{-- Overall CTR --}}
    @php
        $overallCtr = $totalImpressions > 0 ? round(($totalClicks/$totalImpressions)*100, 2) : 0;
    @endphp
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-tertiary text-[20px]">trending_up</span>
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">সামগ্রিক CTR</h3>
        </div>
        <p class="text-3xl font-bold text-on-surface mb-1">{{ $overallCtr }}%</p>
        <div class="flex items-center gap-4 mt-2 text-xs text-on-surface-variant">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">visibility</span>
                {{ $totalImpressions > 1000 ? number_format($totalImpressions/1000,1).'K' : number_format($totalImpressions) }} ভিউ
            </span>
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">touch_app</span>
                {{ number_format($totalClicks) }} ক্লিক
            </span>
        </div>
    </div>

</section>

@endsection
