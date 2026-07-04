@extends('public.layout')

@section('title', 'আমার প্রোফাইল — ' . (\App\Models\SeoSetting::first()?->site_name ?: 'সজীব নিউজ'))

@section('content')
<div class="max-w-4xl mx-auto px-gutter py-8">

    {{-- Profile Card --}}
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <div class="w-20 h-20 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-2xl flex-shrink-0 uppercase overflow-hidden">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    {{ mb_substr($user->name, 0, 1) }}
                @endif
            </div>
            <div class="text-center sm:text-left flex-1">
                <h1 class="text-xl font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">{{ $user->name }}</h1>
                <p class="text-sm text-on-surface-variant mt-1">{{ $user->email }}</p>
                <div class="flex flex-wrap gap-2 mt-3 justify-center sm:justify-start">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">
                        <span class="material-symbols-outlined text-[14px]">person</span>
                        {{ ucfirst($user->roles->first()?->name ?? 'user') }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container text-on-surface-variant text-xs">
                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                        যোগদান: {{ $user->created_at->format('d M, Y') }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-error/10 text-error text-sm font-medium hover:bg-error/20 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span> লগআউট
                </button>
            </form>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/30 flex items-center justify-between">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2" style="font-family:'SolaimanLipi',serif;">
                <span class="material-symbols-outlined text-primary">forum</span>
                আমার মন্তব্যসমূহ
            </h2>
            <span class="text-xs text-on-surface-variant bg-surface-container px-3 py-1 rounded-full">
                মোট {{ $comments->total() }} টি
            </span>
        </div>

        @forelse($comments as $comment)
        <div class="px-6 py-4 border-b border-outline-variant/10 hover:bg-surface-container-low/50 transition-colors">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    @if($comment->news)
                    <a href="{{ route('news.show', $comment->news->slug) }}" class="text-sm font-semibold text-primary hover:underline line-clamp-1" style="font-family:'SolaimanLipi',serif;">
                        {{ $comment->news->title }}
                    </a>
                    @else
                    <span class="text-sm text-on-surface-variant italic">(সংবাদটি মুছে ফেলা হয়েছে)</span>
                    @endif
                    <p class="text-sm text-on-surface mt-1" style="font-family:'SolaimanLipi',serif;">{{ $comment->comment_text }}</p>
                </div>
                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                    <span class="text-[11px] text-on-surface-variant">{{ $comment->created_at->diffForHumans() }}</span>
                    @if($comment->approved)
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-medium">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span> অনুমোদিত
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-medium">
                            <span class="material-symbols-outlined text-[12px]">pending</span> অপেক্ষমাণ
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center">
            <span class="material-symbols-outlined text-[48px] text-outline/40">chat_bubble_outline</span>
            <p class="text-on-surface-variant mt-2" style="font-family:'SolaimanLipi',serif;">আপনি এখনও কোনো মন্তব্য করেননি</p>
        </div>
        @endforelse

        @if($comments->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/30">
            {{ $comments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
