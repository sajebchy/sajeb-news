@extends('layouts.admin')

@section('page-title', 'সোর্স ম্যানেজ')

@section('content')

@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-tertiary/10 border border-tertiary/30 text-tertiary px-4 py-3 rounded-xl text-sm font-bold">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>
    {{ session('success') }}
</div>
@endif

<header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">সোর্স ম্যানেজ</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">যে ওয়েবসাইটগুলো থেকে খবর টেনে আনতে চান তাদের লিঙ্ক যুক্ত করুন (RSS ফিড)।</p>
    </div>
    <a href="{{ route('admin.news-sources.create') }}"
       class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 shadow-sm transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        নতুন সোর্স
    </a>
</header>

@if($sources->count() > 0)
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container text-on-surface-variant text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">নাম</th>
                    <th class="px-4 py-3 font-semibold">খবর</th>
                    <th class="px-4 py-3 font-semibold">সর্বশেষ আপডেট</th>
                    <th class="px-4 py-3 font-semibold">অবস্থা</th>
                    <th class="px-4 py-3 font-semibold text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @foreach($sources as $source)
                <tr class="hover:bg-surface-container/40">
                    <td class="px-4 py-3">
                        <div class="font-bold text-on-surface">{{ $source->name }}</div>
                        <a href="{{ $source->site_url }}" target="_blank" rel="noopener" class="text-xs text-on-surface-variant hover:text-primary break-all">{{ $source->site_url }}</a>
                        @if($source->last_error)
                        <div class="text-[11px] text-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">error</span> {{ Str::limit($source->last_error, 80) }}
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-on-surface-variant">{{ $source->items_count }}</td>
                    <td class="px-4 py-3 text-on-surface-variant text-xs">
                        {{ $source->last_fetched_at ? $source->last_fetched_at->diffForHumans() : 'কখনো নয়' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($source->is_active)
                        <span class="text-[11px] font-bold bg-tertiary/10 text-tertiary px-2 py-1 rounded-full">সক্রিয়</span>
                        @else
                        <span class="text-[11px] font-bold bg-surface-container text-on-surface-variant px-2 py-1 rounded-full">নিষ্ক্রিয়</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2 justify-end">
                            <form method="POST" action="{{ route('admin.news-sources.toggle', $source) }}">
                                @csrf
                                <button type="submit" title="সক্রিয়/নিষ্ক্রিয়" class="text-on-surface-variant hover:text-primary">
                                    <span class="material-symbols-outlined text-[20px]">{{ $source->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                </button>
                            </form>
                            <a href="{{ route('admin.news-sources.edit', $source) }}" title="সম্পাদনা" class="text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form method="POST" action="{{ route('admin.news-sources.destroy', $source) }}"
                                  onsubmit="return confirm('এই সোর্স ও এর সব খবর মুছে ফেলবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="মুছুন" class="text-error/70 hover:text-error">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $sources->links() }}</div>

@else
<div class="text-center py-16 bg-surface-container-lowest border border-outline-variant rounded-xl">
    <span class="material-symbols-outlined text-on-surface-variant/30" style="font-size:64px;">settings_input_antenna</span>
    <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'SolaimanLipi',serif;">কোনো সোর্স যুক্ত করা হয়নি</p>
    <a href="{{ route('admin.news-sources.create') }}" class="inline-flex items-center gap-1 mt-4 text-primary font-bold text-sm hover:underline">
        <span class="material-symbols-outlined text-[18px]">add_circle</span> প্রথম সোর্স যুক্ত করুন
    </a>
</div>
@endif

@endsection
