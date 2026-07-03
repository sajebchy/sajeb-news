@extends('public.layout')

@section('title', 'লাইভ স্ট্রিম - সজীব নিউজ')
@section('meta_description', 'সজীব নিউজের সরাসরি সম্প্রচার দেখুন। লাইভ টকশো, বিশেষ প্রতিবেদন ও সংবাদ।')

@section('content')

@php
  $activeStream = $featured_streams->firstWhere('status', 'live')
    ?? $live_streams->first()
    ?? $featured_streams->first();

  $relatedNews = \App\Models\News::where('status','published')
    ->latest('published_at')->limit(5)->get();

  // Format viewer count
  $viewerCount = $activeStream ? number_format($activeStream->viewer_count ?? 0) : '০';
  $startedAgo  = $activeStream && $activeStream->started_at
    ? $activeStream->started_at->locale('bn')->diffForHumans()
    : 'শীঘ্রই শুরু হবে';
@endphp

{{-- ═══════════════════════════════════════
     BREAKING NEWS TICKER
═══════════════════════════════════════ --}}
<div class="w-full bg-primary py-2 overflow-hidden border-b border-outline-variant">
  <div class="flex items-center">
    <span class="bg-secondary text-white px-3 py-1 font-label-caps text-label-caps z-10 shadow-md flex-shrink-0">ব্রেকিং নিউজ</span>
    <div class="overflow-hidden flex-1">
      <span class="animate-ticker text-white font-body-sm text-body-sm px-4">
        @foreach($relatedNews->take(4) as $tn){{ $tn->title }} • @endforeach
        নির্বাচনী প্রচারণায় সরগরম রাজধানী • আন্তর্জাতিক বাজারে তেলের দামের ব্যাপক পতন
      </span>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════
     MAIN CONTENT (mobile-first, max-w-md center)
═══════════════════════════════════════ --}}
<main class="flex-grow flex flex-col w-full max-w-2xl mx-auto bg-surface overflow-hidden pb-20">

  @if($activeStream)
  {{-- ─── VIDEO PLAYER SECTION ─── --}}
  <section class="relative aspect-video bg-black w-full group overflow-hidden">
    @if($activeStream->thumbnail)
      <img class="w-full h-full object-cover opacity-90"
           src="{{ asset('storage/' . $activeStream->thumbnail) }}"
           alt="{{ $activeStream->title }}"/>
    @else
      <div class="w-full h-full flex items-center justify-center bg-primary-container">
        <span class="material-symbols-outlined text-white/40" style="font-size:80px;">live_tv</span>
      </div>
    @endif

    {{-- Live Badge + Viewers --}}
    <div class="absolute top-3 left-3 flex items-center gap-2">
      @if($activeStream->status === 'live')
      <span class="bg-news-red-accent text-white px-2 py-0.5 rounded-sm font-label-caps text-[10px] flex items-center gap-1 animate-pulse">
        <span class="w-1.5 h-1.5 bg-white rounded-full"></span> LIVE
      </span>
      @elseif($activeStream->status === 'pending')
      <span class="bg-amber-500 text-white px-2 py-0.5 rounded-sm font-label-caps text-[10px] flex items-center gap-1">
        <span class="material-symbols-outlined text-[12px]">schedule</span> আসন্ন
      </span>
      @endif
      <span class="bg-black/50 text-white px-2 py-0.5 rounded-sm font-label-caps text-[10px] flex items-center gap-1 backdrop-blur-sm">
        <span class="material-symbols-outlined text-[12px]">visibility</span> {{ $viewerCount }}
      </span>
    </div>

    {{-- Play Button (hover) --}}
    <a href="{{ route('live.watch', $activeStream->slug) }}"
       class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
      <div class="bg-white/20 backdrop-blur-md rounded-full p-4 cursor-pointer hover:bg-white/30 transition-colors">
        <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings:'FILL' 1;">play_arrow</span>
      </div>
    </a>

    {{-- Progress bar --}}
    <div class="absolute bottom-0 left-0 w-full h-1 bg-white/20">
      <div class="h-full bg-secondary {{ $activeStream->status === 'live' ? 'w-full' : 'w-0' }}"></div>
    </div>
  </section>

  {{-- ─── STREAM INFO ─── --}}
  <section class="p-4 border-b border-subtle">
    <h2 class="font-headline-md text-headline-md text-on-surface mb-1">{{ $activeStream->title }}</h2>
    <div class="flex items-center justify-between text-on-surface-variant">
      <p class="font-meta-data text-meta-data">প্রচারিত হচ্ছে: {{ $startedAgo }}</p>
      <div class="flex gap-3">
        <button onclick="navigator.share ? navigator.share({title:'{{ addslashes($activeStream->title) }}',url:window.location.href}) : null"
                class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary transition-colors">share</button>
        <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-secondary transition-colors">thumb_up</span>
      </div>
    </div>
  </section>

  @else
  {{-- No live stream --}}
  <div class="py-16 px-gutter text-center">
    <span class="material-symbols-outlined text-[80px] text-outline-variant">live_tv</span>
    <h2 class="font-headline-md text-xl mt-4 text-on-surface-variant">এখন কোনো লাইভ স্ট্রিম নেই</h2>
    <p class="font-body-sm text-on-surface-variant mt-2">শীঘ্রই আসছে। নিচে আসন্ন স্ট্রিম দেখুন।</p>
  </div>
  @endif

  {{-- ─── TABS ─── --}}
  <nav class="flex border-b border-subtle bg-surface-container-low sticky top-[56px] z-30">
    <button id="tab-chat" onclick="switchTab('chat')"
            class="flex-1 py-3 font-label-caps text-label-caps text-secondary border-b-2 border-secondary transition-all">
      লাইভ চ্যাট
    </button>
    <button id="tab-related" onclick="switchTab('related')"
            class="flex-1 py-3 font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all">
      সম্পর্কিত সংবাদ
    </button>
    <button id="tab-schedule" onclick="switchTab('schedule')"
            class="flex-1 py-3 font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all">
      শিডিউল
    </button>
  </nav>

  {{-- ─── TAB CONTENT ─── --}}
  <div class="flex-grow flex flex-col bg-white" id="tab-content">

    {{-- CHAT TAB --}}
    <div class="flex flex-col" id="content-chat" style="min-height:340px">
      <div class="flex-grow p-4 space-y-4 overflow-y-auto" id="chat-messages" style="max-height:380px">
        {{-- Placeholder messages (will be replaced by real-time updates) --}}
        <div class="flex gap-3 items-start">
          <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-bold text-xs flex-shrink-0">AS</div>
          <div class="flex flex-col">
            <span class="font-label-caps text-[11px] text-on-surface-variant mb-0.5">আরিফুল ইসলাম</span>
            <p class="bg-surface-container p-2 rounded-lg rounded-tl-none font-body-sm text-body-sm text-on-surface">খুবই চমৎকার আলোচনা হচ্ছে। বক্তাদের যুক্তিতে নতুন অনেক কিছু জানতে পারছি।</p>
          </div>
        </div>
        <div class="flex gap-3 items-start">
          <div class="w-8 h-8 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed font-bold text-xs flex-shrink-0">NK</div>
          <div class="flex flex-col">
            <span class="font-label-caps text-[11px] text-on-surface-variant mb-0.5">নুসরাত জাহান</span>
            <p class="bg-surface-container p-2 rounded-lg rounded-tl-none font-body-sm text-body-sm text-on-surface">দেশের অর্থনীতির ওপর এই পরিস্থিতির প্রভাব নিয়ে কি কোনো কথা হবে?</p>
          </div>
        </div>
        <div class="flex gap-3 items-start">
          <div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed font-bold text-xs flex-shrink-0">RH</div>
          <div class="flex flex-col">
            <span class="font-label-caps text-[11px] text-on-surface-variant mb-0.5">রাকিব হাসান</span>
            <p class="bg-surface-container p-2 rounded-lg rounded-tl-none font-body-sm text-body-sm text-on-surface">লাইভটি শেয়ার করার জন্য ধন্যবাদ।</p>
          </div>
        </div>
        <div id="dynamic-messages"></div>
      </div>

      {{-- Chat Input --}}
      <div class="p-3 border-t border-subtle bg-surface flex items-center gap-2">
        <input id="chat-input" type="text"
               class="flex-grow bg-surface-container-high border-none rounded-full px-4 py-2 focus:ring-2 focus:ring-primary outline-none text-body-sm font-body-sm"
               placeholder="মতামত লিখুন..."/>
        <button onclick="sendChatMessage()"
                class="bg-primary text-white p-2 rounded-full flex items-center justify-center active:scale-95 transition-transform hover:bg-secondary">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">send</span>
        </button>
      </div>
    </div>

    {{-- RELATED NEWS TAB (hidden) --}}
    <div class="hidden p-4 space-y-3" id="content-related">
      @forelse($relatedNews as $rn)
      <a href="{{ route('news.show', $rn->slug) }}"
         class="flex gap-3 p-2 bg-surface-muted rounded border border-subtle hover:bg-surface-container-low transition-colors group">
        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded">
          <img class="w-full h-full object-cover group-hover:scale-105 transition-transform"
               src="{{ $rn->featured_image ? (Str::startsWith($rn->featured_image,'http') ? $rn->featured_image : asset('storage/'.$rn->featured_image)) : ($defaultFeaturedImage ?? asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? ''))) }}"
               alt="{{ $rn->title }}" loading="lazy"/>
        </div>
        <div class="flex flex-col justify-between py-1">
          <h3 class="font-headline-md text-sm leading-tight text-on-surface group-hover:text-secondary transition-colors line-clamp-3">
            {{ $rn->title }}
          </h3>
          <p class="font-meta-data text-xs text-secondary mt-1">{{ $rn->category->name ?? 'সংবাদ' }}</p>
        </div>
      </a>
      @empty
      <p class="text-center py-8 font-body-sm text-on-surface-variant">কোনো সংবাদ পাওয়া যায়নি।</p>
      @endforelse
    </div>

    {{-- SCHEDULE TAB (hidden) --}}
    <div class="hidden p-4" id="content-schedule">
      <div class="space-y-5">
        {{-- Currently Live --}}
        @if($activeStream && $activeStream->status === 'live')
        <div class="flex gap-4 items-center">
          <span class="font-label-caps text-on-surface-variant w-20 text-[11px] flex-shrink-0">
            {{ $activeStream->started_at ? $activeStream->started_at->format('h:i A') : 'এখনই' }}
          </span>
          <div class="flex-grow p-3 bg-primary-fixed text-on-primary-fixed rounded border-l-4 border-secondary">
            <p class="font-label-caps text-[10px] flex items-center gap-1">
              <span class="w-1.5 h-1.5 bg-news-red-accent rounded-full animate-pulse inline-block"></span> সরাসরি সম্প্রচার
            </p>
            <h4 class="font-headline-md text-sm mt-0.5">{{ Str::limit($activeStream->title, 60) }}</h4>
          </div>
        </div>
        @endif

        {{-- Upcoming --}}
        @forelse($upcoming_streams as $us)
        <div class="flex gap-4 items-center">
          <span class="font-label-caps text-on-surface-variant w-20 text-[11px] flex-shrink-0">
            {{ $us->scheduled_at ? $us->scheduled_at->format('h:i A') : '—' }}
          </span>
          <a href="{{ route('live.watch', $us->slug) }}"
             class="flex-grow p-3 bg-surface-container rounded border-l-4 border-outline-variant hover:border-secondary transition-colors">
            <p class="font-label-caps text-[10px] text-on-surface-variant">আসন্ন</p>
            <h4 class="font-headline-md text-sm mt-0.5">{{ Str::limit($us->title, 60) }}</h4>
          </a>
        </div>
        @empty
        {{-- Placeholder schedule --}}
        <div class="flex gap-4 items-center">
          <span class="font-label-caps text-on-surface-variant w-20 text-[11px]">০৫:৩০ PM</span>
          <div class="flex-grow p-3 bg-surface-container rounded border-l-4 border-outline-variant">
            <p class="font-label-caps text-[10px] text-on-surface-variant">আসন্ন</p>
            <h4 class="font-headline-md text-sm">শীঘ্রই নতুন স্ট্রিম আসছে</h4>
          </div>
        </div>
        <div class="flex gap-4 items-center">
          <span class="font-label-caps text-on-surface-variant w-20 text-[11px]">০৮:০০ PM</span>
          <div class="flex-grow p-3 bg-surface-container rounded border-l-4 border-outline-variant">
            <p class="font-label-caps text-[10px] text-on-surface-variant">আসন্ন</p>
            <h4 class="font-headline-md text-sm">রাতের প্রধান সংবাদ ও বিশ্লেষণ</h4>
          </div>
        </div>
        @endforelse
      </div>
    </div>

  </div>{{-- /tab-content --}}

  {{-- ─── OTHER LIVE STREAMS (below tabs) ─── --}}
  @if($live_streams->count() > 1 || $featured_streams->count() > 1)
  <div class="border-t border-subtle px-4 py-stack-lg bg-surface-container-low">
    <h3 class="font-label-caps text-label-caps text-secondary mb-stack-md border-b-2 border-secondary inline-block pb-1">আরও লাইভ স্ট্রিম</h3>
    <div class="space-y-3 mt-stack-md">
      @foreach($live_streams->where('id', '!=', optional($activeStream)->id)->take(3) as $ls)
      <a href="{{ route('live.watch', $ls->slug) }}"
         class="flex gap-3 group hover:bg-surface-container transition-colors p-2 rounded">
        <div class="w-24 h-16 flex-shrink-0 rounded overflow-hidden bg-primary-container relative">
          @if($ls->thumbnail)
          <img class="w-full h-full object-cover" src="{{ asset('storage/'.$ls->thumbnail) }}" alt="{{ $ls->title }}"/>
          @else
          <div class="w-full h-full flex items-center justify-center">
            <span class="material-symbols-outlined text-white/40">live_tv</span>
          </div>
          @endif
          <span class="absolute top-1 left-1 bg-news-red-accent text-white px-1 font-label-caps text-[9px] flex items-center gap-0.5">
            <span class="w-1 h-1 bg-white rounded-full animate-pulse"></span> LIVE
          </span>
        </div>
        <div>
          <h4 class="font-headline-md text-sm leading-tight group-hover:text-secondary transition-colors line-clamp-2">{{ $ls->title }}</h4>
          <span class="font-meta-data text-[11px] text-on-surface-variant flex items-center gap-1 mt-1">
            <span class="material-symbols-outlined text-[13px]">visibility</span> {{ number_format($ls->viewer_count ?? 0) }}
          </span>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif

</main>

{{-- Ticker animation + tab switch script --}}
<style>
@keyframes ticker {
  0%   { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
.animate-ticker {
  display: inline-block;
  white-space: nowrap;
  animation: ticker 40s linear infinite;
}
</style>

<script>
function switchTab(tabName) {
  ['chat','related','schedule'].forEach(t => {
    const el = document.getElementById('content-' + t);
    const btn = document.getElementById('tab-' + t);
    if (el) el.classList.add('hidden');
    if (btn) {
      btn.classList.remove('text-secondary','border-b-2','border-secondary');
      btn.classList.add('text-on-surface-variant');
    }
  });
  const show = document.getElementById('content-' + tabName);
  const active = document.getElementById('tab-' + tabName);
  if (show) show.classList.remove('hidden');
  if (active) {
    active.classList.remove('text-on-surface-variant');
    active.classList.add('text-secondary','border-b-2','border-secondary');
  }
}

function sendChatMessage() {
  const input = document.getElementById('chat-input');
  const msg = input.value.trim();
  if (!msg) return;
  const container = document.getElementById('dynamic-messages');
  const div = document.createElement('div');
  div.className = 'flex gap-3 items-start mt-4';
  div.innerHTML = `
    <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center font-bold text-xs flex-shrink-0 text-on-surface">আপ</div>
    <div class="flex flex-col">
      <span class="font-label-caps text-[11px] text-on-surface-variant mb-0.5">আপনি</span>
      <p class="bg-surface-container p-2 rounded-lg rounded-tl-none font-body-sm text-body-sm text-on-surface">${msg.replace(/</g,'&lt;')}</p>
    </div>`;
  container.appendChild(div);
  input.value = '';
  document.getElementById('chat-messages').scrollTop = 9999;
}

document.getElementById('chat-input')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') sendChatMessage();
});

document.addEventListener('DOMContentLoaded', () => switchTab('chat'));
</script>

@endsection
