@extends('layouts.admin')

@section('page-title', 'ব্যবহারকারী ব্যবস্থাপনা')

@push('styles')
<style>
    .user-row:hover { background-color: #f0eded; }
    .user-row .row-actions { opacity: 0; transition: opacity .15s; }
    .user-row:hover .row-actions { opacity: 1; }
    .avatar-initials {
        width:40px; height:40px; border-radius:9999px;
        display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:15px; flex-shrink:0;
        color:#fff;
    }
    .stat-card { transition: transform .2s, box-shadow .2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.09); }
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
            <span class="text-primary">ব্যবহারকারী ব্যবস্থাপনা</span>
        </nav>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">ব্যবহারকারী ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">সজীব নিউজের সকল ব্যবহারকারী পরিচালনা করুন।</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md hover:opacity-90 transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">person_add</span>
        নতুন ব্যবহারকারী যোগ করুন
    </a>
</header>

{{-- ===== Filter Bar ===== --}}
<form method="GET" action="{{ route('admin.users.index') }}">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center justify-between">
    <div class="flex items-center gap-3 flex-1 flex-wrap">
        <div class="relative flex-1 min-w-[220px] max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                   placeholder="নাম বা ইমেইলে খুঁজুন...">
        </div>
        <div class="relative">
            <select name="role"
                    class="appearance-none pl-4 pr-9 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary cursor-pointer min-w-[150px]">
                <option value="">সব রোল</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[18px]">expand_more</span>
        </div>
        <div class="relative">
            <select name="status"
                    class="appearance-none pl-4 pr-9 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary cursor-pointer min-w-[140px]">
                <option value="">সব স্ট্যাটাস</option>
                <option value="active"   @selected(request('status') === 'active')>সক্রিয়</option>
                <option value="inactive" @selected(request('status') === 'inactive')>নিষ্ক্রিয়</option>
            </select>
            <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[18px]">expand_more</span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button type="submit"
                class="flex items-center gap-1.5 px-4 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-[16px]">filter_list</span>
            ফিল্টার
        </button>
        @if(request()->hasAny(['search','role','status']))
        <a href="{{ route('admin.users.index') }}"
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
            <thead>
                <tr class="bg-surface-container border-b border-outline-variant">
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">ব্যবহারকারী</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">রোল</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">যোগদান</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">কার্যক্রম</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্ট্যাটাস</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($users as $user)
                @php
                    $roleColors = [
                        'admin'      => 'bg-primary/10 text-primary',
                        'super-admin'=> 'bg-primary/10 text-primary',
                        'editor'     => 'bg-tertiary/10 text-tertiary',
                        'author'     => 'bg-secondary/10 text-secondary',
                        'subscriber' => 'bg-surface-container-high text-on-surface-variant',
                    ];
                    $primaryRole = $user->roles->first()?->name ?? 'subscriber';
                    $roleClass   = $roleColors[$primaryRole] ?? 'bg-surface-container-high text-on-surface-variant';
                    $avatarColors= ['#004e9f','#ab3500','#005e2c','#6750A4','#B5460F'];
                    $avatarBg    = $avatarColors[crc32($user->email) % count($avatarColors)];
                    $initials    = strtoupper(substr($user->name, 0, 1)) . (str_contains($user->name, ' ') ? strtoupper(substr(strrchr($user->name,' '),1,1)) : '');
                @endphp
                <tr class="user-row transition-colors">
                    {{-- User --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($user->profile_photo_path ?? $user->avatar ?? null)
                            <img src="{{ asset($user->profile_photo_path ?? $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                            @else
                            <div class="avatar-initials" style="background-color: {{ $avatarBg }}">{{ $initials }}</div>
                            @endif
                            <div>
                                <p class="font-bold text-sm text-on-surface">{{ $user->name }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td class="px-6 py-4">
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight {{ $roleClass }} mb-0.5">
                                {{ ucfirst($role->name) }}
                            </span>
                            @endforeach
                        @else
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight bg-surface-container-high text-on-surface-variant">
                            Subscriber
                        </span>
                        @endif
                    </td>

                    {{-- Join Date --}}
                    <td class="px-6 py-4 text-sm text-on-surface-variant whitespace-nowrap">
                        {{ $user->created_at?->format('d M, Y') ?? '—' }}
                    </td>

                    {{-- Activity --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px]">article</span>
                                {{ number_format($user->news_articles_count ?? 0) }} আর্টিকেল
                            </div>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @php $isActive = $user->is_active ?? true; @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold
                            {{ $isActive ? 'bg-tertiary/10 text-tertiary' : 'bg-error/10 text-error' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-tertiary' : 'bg-error' }}"></span>
                            {{ $isActive ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="row-actions flex justify-end gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-primary hover:bg-primary/10 transition-colors"
                               title="সম্পাদনা">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('এই ব্যবহারকারীকে মুছে ফেলতে চান? এই কাজ পূর্বাবস্থায় ফেরানো যাবে না।')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors"
                                        title="মুছে ফেলুন">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-20">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:64px;">group_off</span>
                        <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'SolaimanLipi',serif;">কোনো ব্যবহারকারী পাওয়া যায়নি</p>
                        @if(!request()->hasAny(['search','role','status']))
                        <a href="{{ route('admin.users.create') }}"
                           class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            প্রথম ব্যবহারকারী তৈরি করুন
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
    @php $pager = $users->appends(request()->only('search','role','status')); @endphp
    <footer class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between gap-4 flex-wrap">
        <span class="text-sm text-on-surface-variant">
            মোট <span class="font-bold text-on-surface">{{ $users->total() }}</span> জনের মধ্যে
            <span class="font-bold text-on-surface">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span> জন দেখানো হচ্ছে
        </span>
        <div class="flex items-center gap-1">
            @if($pager->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant opacity-40 cursor-not-allowed">
                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
            </span>
            @else
            <a href="{{ $pager->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors">
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

            @if($pager->lastPage() > $pager->currentPage() + 2)
            <span class="px-1 text-on-surface-variant text-sm">...</span>
            <a href="{{ $pager->url($pager->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container-high text-on-surface font-bold text-sm transition-colors">{{ $pager->lastPage() }}</a>
            @endif

            @if($pager->hasMorePages())
            <a href="{{ $pager->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors">
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
</div>

{{-- ===== Bottom Stats ===== --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary" style="font-size:28px">group</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-bold">মোট ব্যবহারকারী</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format($totalUsers) }}</h3>
        </div>
    </div>
    <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-tertiary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-tertiary" style="font-size:28px">verified_user</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-bold">সক্রিয় ব্যবহারকারী</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format($activeUsers) }}</h3>
        </div>
    </div>
    <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-secondary" style="font-size:28px">person_add</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider font-bold">আজকের নতুন</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format($todayUsers) }}</h3>
        </div>
    </div>
</section>

@endsection
