@extends('layouts.admin')

@section('page-title', 'চাকরি ব্যবস্থাপনা')

@push('styles')
<style>
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
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">চাকরি ব্যবস্থাপনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">চাকরির বিজ্ঞাপন তৈরি, সম্পাদনা ও পরিচালনা করুন।</p>
    </div>
    <a href="{{ route('admin.job-posts.create') }}"
       class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 shadow-sm transition-all active:scale-95 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        নতুন চাকরি পোস্ট
    </a>
</header>

{{-- ===== Search & Filter Bar ===== --}}
<form method="GET" action="{{ route('admin.job-posts.index') }}">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-3 items-center justify-between">
    <div class="relative w-full md:w-80">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
               placeholder="চাকরি বা প্রতিষ্ঠান খুঁজুন...">
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
        <select name="status"
                class="bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব স্ট্যাটাস</option>
            <option value="published" @selected(request('status') === 'published')>প্রকাশিত</option>
            <option value="draft" @selected(request('status') === 'draft')>ড্রাফট</option>
        </select>
        <select name="sector"
                class="bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary">
            <option value="">সব সেক্টর</option>
            @foreach(\App\Models\JobPost::jobSectors() as $sector)
            <option value="{{ $sector }}" @selected(request('sector') === $sector)>{{ $sector }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-[18px]">filter_list</span>
            ফিল্টার
        </button>
        @if(request('search') || request('status') || request('sector'))
        <a href="{{ route('admin.job-posts.index') }}"
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
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">পদের নাম</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">প্রতিষ্ঠান</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">সেক্টর</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">ডেডলাইন</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">স্ট্যাটাস</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($jobs as $job)
                <tr class="row-hover transition-colors">
                    <td class="px-6 py-5">
                        <div>
                            <span class="font-semibold text-primary text-base" style="font-family:'SolaimanLipi',serif;">{{ $job->title }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                @if($job->is_featured)
                                <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase">ফিচার্ড</span>
                                @endif
                                @if($job->is_urgent)
                                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase">জরুরি</span>
                                @endif
                                <span class="text-xs text-on-surface-variant">{{ $job->job_type_label }} • {{ $job->workplace_type_label }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="text-sm text-on-surface">{{ $job->company_name }}</span>
                        @if($job->location)
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $job->location }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <span class="bg-surface-container-high px-3 py-1 rounded-full text-xs font-bold text-on-surface">{{ $job->job_sector }}</span>
                    </td>
                    <td class="px-6 py-5">
                        @if($job->application_deadline)
                            @if($job->isExpired())
                            <span class="text-error text-sm font-bold">মেয়াদ শেষ</span>
                            @else
                            <span class="text-sm text-on-surface">{{ $job->application_deadline->format('d M, Y') }}</span>
                            @endif
                        @else
                        <span class="text-xs text-on-surface-variant">নির্ধারিত নয়</span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $job->status === 'published' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline/10 text-on-surface-variant' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $job->status === 'published' ? 'bg-tertiary' : 'bg-outline' }}"></span>
                            {{ $job->status === 'published' ? 'প্রকাশিত' : 'ড্রাফট' }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.job-posts.edit', $job) }}"
                               class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded-lg transition-all"
                               title="সম্পাদনা">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <a href="{{ route('jobs.show', $job) }}" target="_blank"
                               class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed rounded-lg transition-all"
                               title="দেখুন">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </a>
                            <form method="POST" action="{{ route('admin.job-posts.destroy', $job) }}"
                                  onsubmit="return confirm('এই চাকরি পোস্টটি মুছে ফেলতে চান?')">
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
                    <td colspan="6" class="text-center py-20">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:64px;">work</span>
                        <p class="text-on-surface-variant font-semibold mt-3" style="font-family:'SolaimanLipi',serif;">কোনো চাকরি পোস্ট পাওয়া যায়নি</p>
                        <a href="{{ route('admin.job-posts.create') }}"
                           class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            প্রথম চাকরি পোস্ট তৈরি করুন
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    @if($jobs instanceof \Illuminate\Pagination\LengthAwarePaginator && $jobs->hasPages())
    @php $pager = $jobs; @endphp
    <footer class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between gap-4 flex-wrap">
        <span class="text-sm text-on-surface-variant">
            মোট {{ $pager->total() }}টির মধ্যে
            {{ $pager->firstItem() }}–{{ $pager->lastItem() }}টি দেখানো হচ্ছে
        </span>
        <div class="flex items-center gap-1">
            @if($pager->onFirstPage())
            <span class="p-2 rounded text-on-surface-variant opacity-40 cursor-not-allowed">
                <span class="material-symbols-outlined">chevron_left</span>
            </span>
            @else
            <a href="{{ $pager->previousPageUrl() }}" class="p-2 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </a>
            @endif
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
<section class="grid grid-cols-1 md:grid-cols-4 gap-5">
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">work</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মোট পোস্ট</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-tertiary-fixed flex items-center justify-center text-tertiary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">প্রকাশিত</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $stats['published'] }}</h3>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">edit_note</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">ড্রাফট</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $stats['draft'] }}</h3>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant flex items-center gap-5">
        <div class="w-14 h-14 rounded-full bg-error/10 flex items-center justify-center text-error flex-shrink-0">
            <span class="material-symbols-outlined" style="font-size:28px;">schedule</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wider">মেয়াদ শেষ</p>
            <h3 class="text-2xl font-bold text-on-surface mt-0.5">{{ $stats['expired'] }}</h3>
        </div>
    </div>
</section>

@endsection
