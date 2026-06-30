@extends('layouts.admin')

@section('page-title', 'নিউজলেটার ব্যবস্থাপনা')

@push('styles')
<style>
    .stats-card { transition: transform .2s, box-shadow .2s; }
    .stats-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.09); }
    .sub-row:hover { background-color: #f0eded; }
    .sub-row .row-actions { opacity: 0; transition: opacity .15s; }
    .sub-row:hover .row-actions { opacity: 1; }
    .avatar-initials {
        width:40px; height:40px; border-radius:9999px;
        display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:14px; flex-shrink:0; color:#fff;
    }
</style>
@endpush

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-tertiary/10 border border-tertiary/30 text-tertiary px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 flex items-center gap-3 bg-error/10 border border-error/30 text-error px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">error</span> {{ session('error') }}
</div>
@endif

{{-- ===== Header ===== --}}
<header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
    <div>
        <nav class="flex items-center gap-1 text-xs text-on-surface-variant font-bold mb-1.5">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">ড্যাশবোর্ড</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary">নিউজলেটার ব্যবস্থাপনা</span>
        </nav>
        <h2 class="text-2xl font-bold text-primary" style="font-family:'Noto Serif Bengali',serif;">নিউজলেটার ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">আপনার পাঠকদের সাথে সংযুক্ত থাকুন এবং কার্যক্রম পর্যবেক্ষণ করুন।</p>
    </div>
    <a href="{{ route('admin.news.create') }}"
       class="bg-secondary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md hover:opacity-90 transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">add_circle</span>
        নতুন নিউজলেটার তৈরি করুন
    </a>
</header>

{{-- ===== Stats Cards ===== --}}
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="stats-card bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:24px">group</span>
            </div>
            <span class="flex items-center gap-0.5 text-xs font-bold text-tertiary">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>+১২%
            </span>
        </div>
        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">মোট সাবস্ক্রাইবার</p>
        <h3 class="text-3xl font-bold text-on-surface mt-1">{{ number_format($totalSubscribers) }}</h3>
    </div>

    <div class="stats-card bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-tertiary/10 text-tertiary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:24px">verified</span>
            </div>
            <span class="text-xs font-bold text-on-surface-variant">সক্রিয়</span>
        </div>
        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">যাচাইকৃত</p>
        <h3 class="text-3xl font-bold text-on-surface mt-1">{{ number_format($verifiedSubscribers) }}</h3>
    </div>

    <div class="stats-card bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-error/10 text-error rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:24px">unsubscribe</span>
            </div>
            <span class="flex items-center gap-0.5 text-xs font-bold text-error">
                <span class="material-symbols-outlined text-[14px]">trending_down</span>আন-সাব
            </span>
        </div>
        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">আন-সাবস্ক্রাইবড</p>
        <h3 class="text-3xl font-bold text-on-surface mt-1">{{ number_format($unsubscribed) }}</h3>
    </div>

    <div class="stats-card bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:24px">person_add</span>
            </div>
            <span class="flex items-center gap-0.5 text-xs font-bold text-tertiary">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>আজ
            </span>
        </div>
        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">আজকের নতুন</p>
        <h3 class="text-3xl font-bold text-on-surface mt-1">{{ number_format($todaySubscribers) }}</h3>
    </div>

</section>

{{-- ===== Main Content Grid ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ===== Subscribers Table (2/3) ===== --}}
    <section class="xl:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col">

        {{-- Table header + search --}}
        <div class="p-5 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="font-bold text-on-surface text-lg" style="font-family:'Noto Serif Bengali',serif;">সাবস্ক্রাইবার তালিকা</h3>
            <form method="GET" action="{{ route('admin.newsletters.index') }}" class="flex items-center gap-2 flex-wrap">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="pl-9 pr-3 py-2 text-sm bg-surface border border-outline-variant rounded-xl outline-none focus:ring-2 focus:ring-primary w-48"
                           placeholder="নাম বা ইমেইল...">
                </div>
                <div class="relative">
                    <select name="status"
                            class="appearance-none pl-3 pr-7 py-2 text-sm bg-surface border border-outline-variant rounded-xl outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                        <option value="">সব</option>
                        <option value="verified"     @selected(request('status')==='verified')>যাচাইকৃত</option>
                        <option value="unverified"   @selected(request('status')==='unverified')>অযাচাইকৃত</option>
                        <option value="unsubscribed" @selected(request('status')==='unsubscribed')>আন-সাব</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-1.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[16px]">expand_more</span>
                </div>
                <button type="submit"
                        class="px-3 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                </button>
                @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.newsletters.index') }}"
                   class="px-3 py-2 border border-outline-variant rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-surface-container border-b border-outline-variant">
                    <tr>
                        <th class="px-5 py-3.5 text-xs font-bold text-on-surface-variant uppercase tracking-wider">সাবস্ক্রাইবার</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-on-surface-variant uppercase tracking-wider hidden md:table-cell">ইমেইল</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-on-surface-variant uppercase tracking-wider hidden sm:table-cell">তারিখ</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($subscribers as $sub)
                    @php
                        $avatarColors = ['#004e9f','#ab3500','#005e2c','#6750A4','#B5460F'];
                        $avatarBg    = $avatarColors[crc32($sub->email) % count($avatarColors)];
                        $name        = $sub->name ?? explode('@', $sub->email)[0];
                        $initials    = strtoupper(substr($name, 0, 1)) . (str_contains($name,' ') ? strtoupper(substr(strrchr($name,' '),1,1)) : '');
                        $isUnsub     = (bool)$sub->unsubscribed_at;
                        $isActive    = $sub->is_verified && !$isUnsub;
                    @endphp
                    <tr class="sub-row transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="avatar-initials" style="background-color:{{ $avatarBg }}">{{ $initials }}</div>
                                <div>
                                    <p class="font-bold text-sm text-on-surface">{{ $name }}</p>
                                    <p class="text-xs text-on-surface-variant md:hidden">{{ $sub->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-on-surface-variant hidden md:table-cell">{{ $sub->email }}</td>
                        <td class="px-5 py-4">
                            @if($isUnsub)
                            <span class="inline-block px-2.5 py-1 bg-surface-container-high text-on-surface-variant rounded-full text-[11px] font-bold">আন-সাব</span>
                            @elseif($isActive)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-tertiary/10 text-tertiary rounded-full text-[11px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>সক্রিয়
                            </span>
                            @else
                            <span class="inline-block px-2.5 py-1 bg-secondary/10 text-secondary rounded-full text-[11px] font-bold">অযাচাই</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-on-surface-variant whitespace-nowrap hidden sm:table-cell">
                            {{ $sub->subscribed_at
                                ? \Carbon\Carbon::parse($sub->subscribed_at)->format('d M, Y')
                                : $sub->created_at?->format('d M, Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="row-actions flex justify-end gap-1">
                                <form method="POST" action="{{ route('admin.newsletters.destroy', $sub) }}"
                                      onsubmit="return confirm('এই সাবস্ক্রাইবারকে মুছে ফেলতে চান?')">
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
                        <td colspan="5" class="text-center py-16">
                            <span class="material-symbols-outlined text-outline-variant" style="font-size:56px">mail_off</span>
                            <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'Noto Serif Bengali',serif;">কোনো সাবস্ক্রাইবার পাওয়া যায়নি</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($subscribers instanceof \Illuminate\Pagination\LengthAwarePaginator && $subscribers->hasPages())
        @php $pager = $subscribers->appends(request()->only('search','status')); @endphp
        <footer class="px-5 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between gap-3 flex-wrap">
            <span class="text-sm text-on-surface-variant">
                মোট <span class="font-bold text-on-surface">{{ $subscribers->total() }}</span> জনের মধ্যে
                <span class="font-bold text-on-surface">{{ $subscribers->firstItem() }}–{{ $subscribers->lastItem() }}</span> জন
            </span>
            <div class="flex items-center gap-1">
                @if($pager->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
                @else
                <a href="{{ $pager->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
                @endif

                @foreach($pager->getUrlRange(max(1,$pager->currentPage()-2), min($pager->lastPage(),$pager->currentPage()+2)) as $page => $url)
                    @if($page == $pager->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-sm">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high text-on-surface font-bold text-sm transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if($pager->hasMorePages())
                <a href="{{ $pager->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
                @endif
            </div>
        </footer>
        @endif
    </section>

    {{-- ===== Right Sidebar (1/3) ===== --}}
    <div class="space-y-5">

        {{-- Growth Breakdown --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant">
                <h3 class="font-bold text-on-surface" style="font-family:'Noto Serif Bengali',serif;">প্রবৃদ্ধির হার</h3>
            </div>
            <div class="p-5 space-y-4">
                @php
                    $total        = max($totalSubscribers, 1);
                    $verifiedPct  = round($verifiedSubscribers / $total * 100);
                    $unsubPct     = round($unsubscribed / $total * 100);
                    $unverifiedPct = max(0, 100 - $verifiedPct - $unsubPct);
                @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="font-bold text-tertiary">যাচাইকৃত</span>
                        <span class="font-bold text-on-surface">{{ $verifiedPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden">
                        <div class="h-full bg-tertiary rounded-full duration-700" style="width:{{ $verifiedPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="font-bold text-secondary">অযাচাইকৃত</span>
                        <span class="font-bold text-on-surface">{{ $unverifiedPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden">
                        <div class="h-full bg-secondary rounded-full duration-700" style="width:{{ $unverifiedPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="font-bold text-error">আন-সাবস্ক্রাইবড</span>
                        <span class="font-bold text-on-surface">{{ $unsubPct }}%</span>
                    </div>
                    <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden">
                        <div class="h-full bg-error rounded-full duration-700" style="width:{{ $unsubPct }}%"></div>
                    </div>
                </div>
                <div class="pt-3 border-t border-outline-variant grid grid-cols-2 gap-3 text-center">
                    <div class="bg-surface-container rounded-xl p-3">
                        <p class="text-xl font-bold text-tertiary">{{ number_format($verifiedSubscribers) }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">সক্রিয়</p>
                    </div>
                    <div class="bg-surface-container rounded-xl p-3">
                        <p class="text-xl font-bold text-error">{{ number_format($unsubscribed) }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">আন-সাব</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Campaigns --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant">
                <h3 class="font-bold text-on-surface" style="font-family:'Noto Serif Bengali',serif;">সাম্প্রতিক ক্যাম্পেইন</h3>
            </div>
            <div class="p-5 space-y-5">
                @php
                    $campaigns = [
                        ['title'=>'আজকের শীর্ষ খবর: বাজেট ২০২৪-২৫ বিশেষ আপডেট',  'date'=>'১৫ মে', 'open'=>'৪২.৫%', 'click'=>'১২.৮%'],
                        ['title'=>'সাপ্তাহিক প্রযুক্তি সংবাদ: এআই বিপ্লব ও বাংলাদেশ',  'date'=>'১২ মে', 'open'=>'৩৮.২%', 'click'=>'৯.৫%'],
                        ['title'=>'বিনিয়োগ গাইড: নতুন অর্থবছরে কোথায় বিনিয়োগ?','date'=>'১০ মে', 'open'=>'৩১.৭%', 'click'=>'৬.২%'],
                    ];
                @endphp
                @foreach($campaigns as $c)
                <div class="group cursor-pointer">
                    <div class="flex justify-between items-start mb-1.5">
                        <span class="text-[10px] uppercase tracking-wider font-bold text-primary">পাঠানো: {{ $c['date'] }}</span>
                        <span class="material-symbols-outlined text-outline text-[18px] group-hover:text-primary transition-colors">open_in_new</span>
                    </div>
                    <h4 class="text-sm font-semibold text-on-surface leading-snug mb-3" style="font-family:'Noto Serif Bengali',serif;">{{ $c['title'] }}</h4>
                    <div class="grid grid-cols-2 gap-3 bg-surface-container-low p-3 rounded-xl">
                        <div>
                            <p class="text-[10px] text-on-surface-variant uppercase font-bold">ওপেন রেট</p>
                            <p class="text-base font-bold text-on-surface">{{ $c['open'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-on-surface-variant uppercase font-bold">ক্লিক রেট</p>
                            <p class="text-base font-bold text-on-surface">{{ $c['click'] }}</p>
                        </div>
                    </div>
                </div>
                @if(!$loop->last)<hr class="border-outline-variant">@endif
                @endforeach
            </div>
            <div class="px-5 pb-5">
                <a href="{{ route('admin.news.index') }}"
                   class="block w-full text-center py-2.5 border border-outline text-on-surface rounded-xl text-sm font-bold hover:bg-surface-container transition-colors">
                    সব নিউজ দেখুন
                </a>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5">
            <h3 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                দ্রুত অ্যাকশন
            </h3>
            <div class="space-y-2">
                <a href="{{ route('admin.newsletters.index') }}?status=unverified"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-outline-variant hover:bg-surface-container transition-colors text-sm font-bold text-on-surface">
                    <span class="material-symbols-outlined text-secondary text-[18px]">pending</span>
                    অযাচাইকৃত দেখুন
                </a>
                <a href="{{ route('admin.newsletters.index') }}?status=unsubscribed"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-outline-variant hover:bg-surface-container transition-colors text-sm font-bold text-on-surface">
                    <span class="material-symbols-outlined text-error text-[18px]">unsubscribe</span>
                    আন-সাবস্ক্রাইবড দেখুন
                </a>
                <a href="{{ route('admin.newsletters.index') }}?status=verified"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-outline-variant hover:bg-surface-container transition-colors text-sm font-bold text-on-surface">
                    <span class="material-symbols-outlined text-tertiary text-[18px]">verified</span>
                    শুধু সক্রিয় দেখুন
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
