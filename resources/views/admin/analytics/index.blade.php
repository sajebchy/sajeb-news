@extends('layouts.admin')

@section('page-title', 'অ্যানালিটিক্স')

@section('content')
@php
    $mobile  = $deviceBreakdown->get('Mobile')?->total  ?? 0;
    $desktop = $deviceBreakdown->get('Desktop')?->total ?? 0;
    $tablet  = $deviceBreakdown->get('Tablet')?->total  ?? 0;
    $mobPct  = $totalDevices > 0 ? round($mobile  / $totalDevices * 100) : 0;
    $dskPct  = $totalDevices > 0 ? round($desktop / $totalDevices * 100) : 0;
    $tabPct  = $totalDevices > 0 ? round($tablet  / $totalDevices * 100) : 0;

    $catTotal = $categoryAnalytics->sum('total_views') ?: 1;
@endphp

{{-- ── Header ─────────────────────────────────────────────── --}}
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h2 class="font-display text-2xl font-bold text-primary">অ্যানালিটিক্স ওভারভিউ</h2>
        <p class="text-on-surface-variant text-sm mt-1">আপনার নিউজ পোর্টালের পারফরম্যান্স বিশ্লেষণ</p>
    </div>
    <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-xl border border-outline-variant text-sm text-on-surface-variant">
        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
        <span>আজকের তারিখ: {{ now()->locale('bn')->isoFormat('D MMMM, YYYY') }}</span>
    </div>
</div>

{{-- ── Stat Cards ─────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-surface p-5 rounded-xl border border-outline-variant hover:shadow-md transition-all group">
        <div class="flex justify-between items-start mb-3">
            <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px]">visibility</span>
            <span class="text-tertiary text-xs font-bold flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> লাইভ
            </span>
        </div>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">মোট ভিউ</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ number_format($totalViews) }}</p>
    </div>

    <div class="bg-surface p-5 rounded-xl border border-outline-variant hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-3">
            <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px]">article</span>
            <span class="text-tertiary text-xs font-bold flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
            </span>
        </div>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">মোট পোস্ট</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ number_format($totalNews ?? 0) }}</p>
    </div>

    <div class="bg-surface p-5 rounded-xl border border-outline-variant hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-3">
            <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px]">group</span>
        </div>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">মোট ব্যবহারকারী</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ number_format($totalUsers ?? 0) }}</p>
    </div>

    <div class="bg-surface p-5 rounded-xl border border-outline-variant hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-3">
            <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px]">person_pin</span>
        </div>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">মোট ভিজিটর</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ number_format($totalVisitors) }}</p>
    </div>
</div>

{{-- ── 3-col secondary grid ───────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

    {{-- Category Performance --}}
    <div class="bg-surface p-6 rounded-xl border border-outline-variant">
        <h4 class="font-display text-base font-bold text-on-surface mb-5">সেরা বিভাগ</h4>
        @if($categoryAnalytics->count())
        <div class="space-y-4">
            @foreach($categoryAnalytics->take(5) as $cat)
            @php $pct = $catTotal > 0 ? round($cat->total_views / $catTotal * 100) : 0; @endphp
            <div class="space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface truncate max-w-[70%]">{{ $cat->name }}</span>
                    <span class="font-bold text-primary">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-on-surface-variant text-center py-8">কোনো ডেটা নেই</p>
        @endif
    </div>

    {{-- Traffic Sources --}}
    <div class="bg-surface p-6 rounded-xl border border-outline-variant">
        <h4 class="font-display text-base font-bold text-on-surface mb-5">ট্র্যাফিক সোর্স</h4>
        @if($sourceBreakdown->count())
        <div class="space-y-3">
            @foreach($sourceBreakdown as $src)
            @php $pct = $totalVisitors > 0 ? round($src->total / $totalVisitors * 100, 1) : 0; @endphp
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-sm"
                     style="background:{{ $src->color }}22; color:{{ $src->color }}">
                    <i class="bi {{ $src->icon }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-on-surface">{{ $src->label }}</span>
                        <span class="text-on-surface-variant">{{ number_format($src->total) }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all duration-500"
                             style="width:{{ $pct }}%; background:{{ $src->color }}"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-on-surface-variant text-center py-8">ভিজিটর ডেটা নেই</p>
        @endif
    </div>

    {{-- Device Breakdown --}}
    <div class="bg-surface p-6 rounded-xl border border-outline-variant">
        <h4 class="font-display text-base font-bold text-on-surface mb-5">ডিভাইস ব্রেকডাউন</h4>
        <div class="space-y-5">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">smartphone</span>
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-semibold">মোবাইল</span>
                        <span class="font-display font-bold text-primary">{{ $mobPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-2.5">
                        <div class="bg-secondary-container h-2.5 rounded-full" style="width: {{ $mobPct }}%"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">laptop</span>
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-semibold">ডেস্কটপ</span>
                        <span class="font-display font-bold text-primary">{{ $dskPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full" style="width: {{ $dskPct }}%"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">tablet</span>
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-semibold">ট্যাবলেট</span>
                        <span class="font-display font-bold text-primary">{{ $tabPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-2.5">
                        <div class="bg-tertiary h-2.5 rounded-full" style="width: {{ $tabPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @if($totalDevices <= 1)
        <p class="text-xs text-on-surface-variant text-center mt-4">ভিজিটর ডেটা জমা হলে এখানে দেখাবে</p>
        @endif
    </div>
</div>

{{-- ── Top News Table ─────────────────────────────────────── --}}
<div class="bg-surface rounded-xl border border-outline-variant overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h4 class="font-display text-base font-bold text-on-surface">সবচেয়ে জনপ্রিয় সংবাদ</h4>
        <a href="{{ route('admin.news.index') }}"
           class="text-primary text-sm font-semibold flex items-center gap-1 hover:underline transition-all">
            সবগুলো দেখুন
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">#</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">শিরোনাম</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide hidden sm:table-cell">বিভাগ</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide text-right">ভিউ</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide text-center hidden md:table-cell">বিস্তারিত</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($topNews as $i => $news)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-display font-bold text-outline">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-on-surface line-clamp-1 max-w-xs">{{ $news->title }}</p>
                        @if($news->published_at)
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $news->published_at->diffForHumans() }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        @if($news->category)
                        <span class="bg-primary-fixed text-on-primary-fixed px-2 py-0.5 rounded text-[11px] font-bold">
                            {{ $news->category->name }}
                        </span>
                        @else
                        <span class="text-outline text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-display font-bold text-primary">
                        {{ number_format($news->views ?? 0) }}
                    </td>
                    <td class="px-6 py-4 text-center hidden md:table-cell">
                        <a href="{{ route('admin.analytics.show', $news->id) }}"
                           class="inline-flex items-center gap-1 text-xs bg-primary text-on-primary px-3 py-1.5 rounded-lg hover:opacity-90 transition-all font-semibold">
                            <span class="material-symbols-outlined text-[14px]">people</span> ভিজিটর
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[40px] block mb-2">inbox</span>
                        কোনো প্রকাশিত পোস্ট নেই
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Real-time Visitors ──────────────────────────────────── --}}
<div class="bg-surface rounded-xl border border-outline-variant overflow-hidden">
    <div class="px-6 py-4 border-b border-outline-variant">
        <h4 class="font-display text-base font-bold text-on-surface">সাম্প্রতিক ভিজিটর অ্যাক্টিভিটি</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">আইপি</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide hidden md:table-cell">লোকেশন ও ডিভাইস</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">সোর্স</th>
                    <th class="px-6 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide text-right">সময়</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($recentVisitors as $visitor)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-3">
                        <code class="text-primary text-xs bg-primary-fixed px-2 py-0.5 rounded">{{ $visitor->visitor_ip }}</code>
                    </td>
                    <td class="px-6 py-3 hidden md:table-cell">
                        <p class="text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-[14px] align-middle">location_on</span>
                            {{ $visitor->visitor_city ?? '—' }}, {{ $visitor->visitor_country ?? '—' }}
                        </p>
                        <p class="text-xs text-outline mt-0.5">{{ $visitor->visitor_device }} · {{ $visitor->browser }}</p>
                    </td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg bg-surface-container text-on-surface">
                            <i class="bi {{ $visitor->source_icon }} text-[12px]"></i>
                            {{ ucfirst($visitor->referrer_source) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right text-xs text-on-surface-variant whitespace-nowrap">
                        {{ $visitor->visit_date?->diffForHumans() ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[40px] block mb-2">sensors_off</span>
                        এখনো কোনো ভিজিটর ডেটা নেই
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
