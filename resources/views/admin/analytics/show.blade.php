@extends('layouts.admin')

@section('page-title', 'ভিজিটর বিশ্লেষণ')

@section('content')

{{-- Back --}}
<div class="mb-4">
    <a href="{{ route('admin.analytics') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> অ্যানালিটিক্সে ফিরে যান
    </a>
</div>

{{-- Header --}}
<div class="mb-6">
    <p class="text-xs font-semibold text-primary uppercase tracking-wide mb-1">ভিজিটর বিশ্লেষণ</p>
    <h2 class="text-xl md:text-2xl font-bold text-on-surface leading-snug" style="font-family:'SolaimanLipi',serif;">{{ $news->title }}</h2>
</div>

{{-- ── Summary Cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface p-5 rounded-xl border border-outline-variant">
        <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px] mb-3 inline-block">group</span>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">মোট ভিজিটর</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ number_format($visitors->total()) }}</p>
    </div>
    <div class="bg-surface p-5 rounded-xl border border-outline-variant">
        <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px] mb-3 inline-block">schedule</span>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">গড় পঠন সময়</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ $avgReadingTime }}<span class="text-base"> মিনিট</span></p>
    </div>
    <div class="bg-surface p-5 rounded-xl border border-outline-variant">
        <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px] mb-3 inline-block">task_alt</span>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">সম্পূর্ণ পড়েছে</p>
        <p class="font-display text-2xl font-bold text-on-surface">{{ $completedReading }}%</p>
    </div>
    <div class="bg-surface p-5 rounded-xl border border-outline-variant">
        <span class="material-symbols-outlined text-primary bg-primary-fixed p-2 rounded-lg text-[22px] mb-3 inline-block">share</span>
        <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-wide mb-1">শীর্ষ সোর্স</p>
        <p class="font-display text-xl font-bold text-on-surface capitalize">{{ $topSource }}</p>
    </div>
</div>

{{-- ── Filter ── --}}
<form method="GET" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center">
    <div class="relative w-full md:w-72">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary"
               placeholder="IP, দেশ বা শহর খুঁজুন...">
    </div>
    <select name="source" class="bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary w-full md:w-auto">
        <option value="">সব সোর্স</option>
        @foreach(['google'=>'Google','facebook'=>'Facebook','twitter'=>'Twitter/X','chatgpt'=>'ChatGPT','bing'=>'Bing','whatsapp'=>'WhatsApp','linkedin'=>'LinkedIn','direct'=>'Direct','other'=>'অন্যান্য'] as $val=>$lbl)
        <option value="{{ $val }}" @selected(request('source') === $val)>{{ $lbl }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-all whitespace-nowrap">
        <span class="material-symbols-outlined text-[16px] align-middle">filter_alt</span> ফিল্টার
    </button>
</form>

{{-- ── Visitor Table ── --}}
<div class="bg-surface rounded-xl border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">IP</th>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide hidden md:table-cell">লোকেশন ও ডিভাইস</th>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">সোর্স</th>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide">পঠন</th>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide text-right">সময়</th>
                    <th class="px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wide text-center hidden lg:table-cell">বিস্তারিত</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($visitors as $visitor)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3">
                        <code class="text-primary text-xs bg-primary-fixed px-2 py-0.5 rounded">{{ $visitor->visitor_ip ?? '—' }}</code>
                    </td>
                    <td class="px-5 py-3 hidden md:table-cell">
                        <p class="text-xs text-on-surface">
                            <span class="material-symbols-outlined text-[14px] align-middle">location_on</span>
                            {{ $visitor->visitor_city ?? '—' }}, {{ $visitor->visitor_country ?? '—' }}
                        </p>
                        <p class="text-xs text-outline mt-0.5">{{ $visitor->visitor_device ?? '—' }} · {{ $visitor->browser ?? '' }} · {{ $visitor->os ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg bg-surface-container text-on-surface">
                            <i class="bi {{ $visitor->source_icon }} text-[12px]"></i>
                            {{ ucfirst($visitor->referrer_source ?? 'direct') }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-on-surface font-semibold">{{ $visitor->reading_time }}</span>
                        <span class="block text-xs text-outline">{{ $visitor->scroll_percentage }}% স্ক্রল
                            @if($visitor->completed_reading)<span class="text-tertiary">· সম্পূর্ণ</span>@endif
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right text-xs text-on-surface-variant whitespace-nowrap">
                        {{ $visitor->visit_date?->diffForHumans() ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-center hidden lg:table-cell">
                        <a href="{{ route('admin.analytics.visitor-detail', [$news->id, $visitor->id]) }}"
                           class="inline-flex items-center gap-1 text-xs text-primary font-semibold hover:underline">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[40px] block mb-2">sensors_off</span>
                        এই সংবাদটির জন্য এখনো কোনো ভিজিটর ডেটা নেই।
                        <p class="text-xs mt-1">পাঠক এই খবরটি পড়লে এখানে বিস্তারিত (লোকেশন, ডিভাইস, সোর্স, পঠন সময়) দেখাবে।</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($visitors->hasPages())
<div class="mt-6">{{ $visitors->appends(request()->only(['search','source']))->links() }}</div>
@endif

@endsection
