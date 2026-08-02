@extends('layouts.admin')

@section('page-title', isset($source) && $source->exists ? 'সোর্স সম্পাদনা' : 'নতুন সোর্স')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.news-sources.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> সোর্স তালিকা
    </a>
</div>

@if($errors->any())
<div class="mb-5 bg-error/10 border border-error/30 text-error px-4 py-3 rounded-xl text-sm">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-on-surface mb-1" style="font-family:'SolaimanLipi',serif;">
        {{ isset($source) && $source->exists ? 'সোর্স সম্পাদনা' : 'নতুন সোর্স যুক্ত করুন' }}
    </h2>
    <p class="text-sm text-on-surface-variant mb-6">সাইটের ঠিকানা দিন — সিস্টেম RSS ফিড অটো খুঁজে নেবে। চাইলে সরাসরি ফিড URL-ও দিতে পারেন।</p>

    <form method="POST" action="{{ isset($source) && $source->exists ? route('admin.news-sources.update', $source) : route('admin.news-sources.store') }}" class="space-y-5">
        @csrf
        @if(isset($source) && $source->exists) @method('PUT') @endif

        <div>
            <label for="name" class="block text-sm font-semibold text-on-surface mb-1.5">সোর্সের নাম <span class="text-error">*</span></label>
            <input type="text" id="name" name="name" required
                   value="{{ old('name', $source->name ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                   placeholder="যেমন: প্রথম আলো">
        </div>

        <div>
            <label for="site_url" class="block text-sm font-semibold text-on-surface mb-1.5">ওয়েবসাইট URL <span class="text-error">*</span></label>
            <input type="url" id="site_url" name="site_url" required
                   value="{{ old('site_url', $source->site_url ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                   placeholder="https://example.com">
        </div>

        <div>
            <label for="feed_url" class="block text-sm font-semibold text-on-surface mb-1.5">RSS ফিড URL <span class="text-on-surface-variant font-normal">(ঐচ্ছিক — খালি রাখলে অটো খুঁজবে)</span></label>
            <input type="url" id="feed_url" name="feed_url"
                   value="{{ old('feed_url', $source->feed_url ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface border border-outline-variant rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                   placeholder="https://example.com/feed">
        </div>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $source->is_active ?? true))
                   class="w-5 h-5 rounded accent-primary">
            <span class="text-sm text-on-surface">সক্রিয় (এই সোর্স থেকে খবর টানা হবে)</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                {{ isset($source) && $source->exists ? 'আপডেট করুন' : 'যুক্ত করুন' }}
            </button>
            <a href="{{ route('admin.news-sources.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-sm text-on-surface-variant hover:bg-surface-variant transition-all">বাতিল</a>
        </div>
    </form>
</div>

@endsection
