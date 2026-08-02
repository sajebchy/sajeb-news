@extends('layouts.admin')

@section('page-title', 'এখন প্রকাশিত খবর')

@section('content')

@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-tertiary/10 border border-tertiary/30 text-tertiary px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- ===== Header ===== --}}
<header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">এখন প্রকাশিত খবর</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">আপনার যুক্ত করা সোর্স ওয়েবসাইটগুলোর সর্বশেষ প্রকাশিত খবর।</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.news-sources.index') }}"
           class="bg-surface-container border border-outline-variant text-on-surface px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-surface-variant transition-all whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px]">settings_input_antenna</span>
            সোর্স ম্যানেজ
        </a>
        <form method="POST" action="{{ route('admin.external-news.refresh') }}">
            @csrf
            <button type="submit"
                    class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 shadow-sm transition-all active:scale-95 whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
                রিফ্রেশ
            </button>
        </form>
    </div>
</header>

{{-- ===== Filter Bar ===== --}}
<form method="GET" action="{{ route('admin.external-news.index') }}">
<input type="hidden" name="nofetch" value="1">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center justify-between">
    <div class="relative w-full md:w-80">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
               placeholder="খবর বা কীওয়ার্ড খুঁজুন...">
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
        <select name="source"
                class="bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব সোর্স</option>
            @foreach($sources as $src)
            <option value="{{ $src->id }}" @selected(request('source') == $src->id)>{{ $src->name }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-2 text-sm text-on-surface-variant bg-surface border border-outline-variant rounded-xl px-4 py-2.5 cursor-pointer">
            <input type="checkbox" name="unread" value="1" @checked(request('unread')) onchange="this.form.submit()">
            শুধু অপঠিত @if($unreadCount > 0)<span class="bg-secondary text-white text-[11px] font-bold rounded-full px-2 py-0.5">{{ $unreadCount }}</span>@endif
        </label>
        <button type="submit"
                class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-all whitespace-nowrap">
            খুঁজুন
        </button>
    </div>
</div>
</form>

{{-- ===== Items ===== --}}
@if($items->count() > 0)
<div class="space-y-3">
    @foreach($items as $item)
    <div class="bg-surface-container-lowest border {{ $item->is_read ? 'border-outline-variant' : 'border-primary/30' }} rounded-xl p-4 hover:shadow-md transition-all">
        <div class="flex flex-col md:flex-row gap-4">
            @if($item->image_url)
            <a href="{{ route('admin.external-news.show', $item) }}" class="flex-shrink-0">
                <img src="{{ $item->image_url }}" alt="" loading="lazy"
                     class="w-full md:w-40 h-32 md:h-24 object-cover rounded-lg border border-outline-variant"
                     onerror="this.style.display='none'">
            </a>
            @endif
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full">
                        <span class="material-symbols-outlined text-[13px]">rss_feed</span>{{ $item->source->name ?? 'সোর্স' }}
                    </span>
                    @unless($item->is_read)
                    <span class="text-[11px] font-bold bg-secondary/10 text-secondary px-2 py-0.5 rounded-full">নতুন</span>
                    @endunless
                    <span class="text-xs text-on-surface-variant">{{ optional($item->published_at)->diffForHumans() ?? optional($item->fetched_at)->diffForHumans() }}</span>
                </div>
                <a href="{{ route('admin.external-news.show', $item) }}"
                   class="block font-bold text-on-surface hover:text-primary transition-colors leading-snug line-clamp-2" style="font-family:'SolaimanLipi',serif;">
                    {{ $item->title }}
                </a>
                @if($item->excerpt)
                <p class="text-sm text-on-surface-variant mt-1 line-clamp-2">{{ $item->excerpt }}</p>
                @endif
                @if(count($item->keywords_array))
                <div class="flex items-center gap-1.5 flex-wrap mt-2">
                    <span class="material-symbols-outlined text-[15px] text-outline">sell</span>
                    @foreach(array_slice($item->keywords_array, 0, 6) as $kw)
                    <span class="text-[11px] bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full">{{ $kw }}</span>
                    @endforeach
                </div>
                @endif
                <div class="flex items-center gap-3 mt-3">
                    <a href="{{ route('admin.external-news.show', $item) }}"
                       class="text-primary font-bold text-sm hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">article</span> সম্পূর্ণ দেখুন
                    </a>
                    <a href="{{ $item->url }}" target="_blank" rel="noopener"
                       class="text-on-surface-variant text-sm hover:text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span> মূল সাইট
                    </a>
                    <form method="POST" action="{{ route('admin.external-news.destroy', $item) }}"
                          onsubmit="return confirm('এই খবরটি তালিকা থেকে মুছে ফেলবেন?')" class="ml-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-error/70 hover:text-error text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">{{ $items->links() }}</div>

@else
<div class="text-center py-16 bg-surface-container-lowest border border-outline-variant rounded-xl">
    <span class="material-symbols-outlined text-on-surface-variant/30" style="font-size:64px;">newspaper</span>
    <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'SolaimanLipi',serif;">এখনো কোনো খবর নেই</p>
    <p class="text-sm text-on-surface-variant mt-1">সোর্স ওয়েবসাইট যুক্ত করুন, তারপর রিফ্রেশ করুন।</p>
    <a href="{{ route('admin.news-sources.create') }}" class="inline-flex items-center gap-1 mt-4 text-primary font-bold text-sm hover:underline">
        <span class="material-symbols-outlined text-[18px]">add_circle</span> নতুন সোর্স যুক্ত করুন
    </a>
</div>
@endif

@endsection
