@extends('layouts.admin')

@section('page-title', 'বিজ্ঞাপন সম্পাদনা')

@push('styles')
<style>
    .form-section { transition: box-shadow .2s; }
    .form-section:hover { box-shadow: 0 4px 16px rgba(0,78,159,.07); }
    .field-label { font-size:.8125rem; font-weight:600; color:#4a5568; margin-bottom:.35rem; display:block; }
    .field-input {
        width:100%; padding:.625rem .875rem;
        border: 1.5px solid #d0d5dd;
        border-radius:.75rem;
        font-size:.875rem;
        background:#fff;
        outline:none;
        transition: border-color .15s, box-shadow .15s;
    }
    .field-input:focus { border-color:#004e9f; box-shadow:0 0 0 3px rgba(0,78,159,.12); }
    .field-input.is-invalid { border-color:#c5221f; }
    .invalid-msg { font-size:.75rem; color:#c5221f; margin-top:.25rem; }
    .section-icon { width:36px; height:36px; border-radius:.625rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
</style>
@endpush

@section('content')

{{-- Header --}}
<header class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.advertisements.index') }}"
       class="w-9 h-9 flex items-center justify-center rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-on-surface" style="font-family:'Noto Serif Bengali',serif;">বিজ্ঞাপন সম্পাদনা</h2>
        <p class="text-sm text-on-surface-variant mt-0.5">{{ $ad->name }}</p>
    </div>
</header>

{{-- Alerts --}}
@if($errors->any())
<div class="mb-6 bg-error/10 border border-error/30 text-error px-4 py-3 rounded-xl text-sm">
    <p class="font-bold mb-1 flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">error</span> ফর্মে কিছু ত্রুটি আছে</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.advertisements.update', $ad) }}" method="POST" enctype="multipart/form-data" id="ad-form">
@csrf
@method('PUT')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ===== LEFT COLUMN (main) ===== --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Basic Info --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-primary/10">
                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                </div>
                <h3 class="font-bold text-on-surface">মৌলিক তথ্য</h3>
            </div>
            <div class="p-6 space-y-4">

                <div>
                    <label class="field-label" for="name">বিজ্ঞাপনের নাম <span class="text-error">*</span></label>
                    <input class="field-input @error('name') is-invalid @enderror"
                           type="text" id="name" name="name"
                           value="{{ old('name', $ad->name) }}"
                           placeholder="যেমন: হোমপেজ ব্যানার - ABC কোম্পানি" required>
                    @error('name')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="placement">প্লেসমেন্ট <span class="text-error">*</span></label>
                        <select class="field-input @error('placement') is-invalid @enderror" id="placement" name="placement" required>
                            <option value="">প্লেসমেন্ট বেছে নিন</option>
                            @foreach ($placements as $value => $label)
                            <option value="{{ $value }}" @selected(old('placement', $ad->placement) == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('placement')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="type">টাইপ <span class="text-error">*</span></label>
                        <select class="field-input @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">টাইপ বেছে নিন</option>
                            @foreach ($types as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $ad->type) == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="device_target">ডিভাইস টার্গেট <span class="text-error">*</span></label>
                        <select class="field-input @error('device_target') is-invalid @enderror" id="device_target" name="device_target" required>
                            @foreach ($deviceTargets as $value => $label)
                            <option value="{{ $value }}" @selected(old('device_target', $ad->device_target) == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('device_target')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="ad_source">বিজ্ঞাপনের উৎস <span class="text-error">*</span></label>
                        <select class="field-input @error('ad_source') is-invalid @enderror" id="ad_source" name="ad_source" required>
                            <option value="">উৎস বেছে নিন</option>
                            @foreach ($adSources as $value => $label)
                            <option value="{{ $value }}" @selected(old('ad_source', $ad->ad_source) == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('ad_source')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="ad_type">বিজ্ঞাপনের ধরণ <span class="text-error">*</span></label>
                        <select class="field-input @error('ad_type') is-invalid @enderror" id="ad_type" name="ad_type" required>
                            <option value="">ধরণ বেছে নিন</option>
                            @foreach ($adTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('ad_type', $ad->ad_type) == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('ad_type')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="display_order">প্রদর্শনের ক্রম</label>
                        <input class="field-input @error('display_order') is-invalid @enderror"
                               type="number" id="display_order" name="display_order"
                               value="{{ old('display_order', $ad->display_order) }}" min="0">
                        @error('display_order')<p class="invalid-msg">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Image & Link --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-secondary/10">
                    <span class="material-symbols-outlined text-secondary text-[18px]">image</span>
                </div>
                <h3 class="font-bold text-on-surface">ছবি ও লিংক</h3>
            </div>
            <div class="p-6 space-y-5">

                {{-- Image Upload --}}
                <div class="pb-5 border-b border-outline-variant">
                    <p class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-primary">cloud_upload</span>
                        ছবি আপলোড
                    </p>
                    <div class="flex gap-3 items-start">
                        <div class="flex-1">
                            <input class="field-input @error('image_file') is-invalid @enderror"
                                   type="file" id="image_file" name="image_file" accept="image/*">
                            @error('image_file')<p class="invalid-msg">{{ $message }}</p>@enderror
                            <p class="text-xs text-on-surface-variant mt-1.5">JPG, PNG, GIF, WebP — সর্বোচ্চ ৫MB</p>
                        </div>
                        <button type="button" id="upload-btn"
                                class="px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity flex items-center gap-1.5 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">upload</span>
                            আপলোড
                        </button>
                    </div>
                    <div id="upload-progress" class="mt-3 hidden">
                        <div class="w-full bg-surface-container-high rounded-full h-2 overflow-hidden">
                            <div id="progress-bar" class="h-full bg-primary rounded-full transition-all duration-300" style="width:0%"></div>
                        </div>
                    </div>
                    <div id="upload-message" class="mt-2"></div>
                </div>

                <input type="hidden" id="image_url" name="image_url" value="{{ old('image_url', $ad->image_url) }}">

                {{-- Preview --}}
                <div id="image-preview-wrap">
                    <label class="field-label">বর্তমান ছবি</label>
                    <div class="rounded-xl border-2 border-dashed border-outline-variant bg-surface-container p-4 flex items-center justify-center min-h-[180px]">
                        <img id="preview-img"
                             src="{{ $ad->image_url ? asset($ad->image_url) : '' }}"
                             alt="Preview"
                             class="{{ $ad->image_url ? '' : 'hidden' }} max-h-48 rounded-lg object-contain">
                        <div id="no-image-placeholder" class="{{ $ad->image_url ? 'hidden' : '' }} text-center text-on-surface-variant">
                            <span class="material-symbols-outlined" style="font-size:48px">image_not_supported</span>
                            <p class="text-sm mt-1">কোনো ছবি নেই</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="field-label" for="alt_text">ছবির Alt টেক্সট</label>
                    <input class="field-input @error('alt_text') is-invalid @enderror"
                           type="text" id="alt_text" name="alt_text"
                           value="{{ old('alt_text', $ad->alt_text) }}"
                           placeholder="অ্যাক্সেসিবিলিটির জন্য বর্ণনামূলক টেক্সট">
                    @error('alt_text')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>

                <div class="pt-5 border-t border-outline-variant">
                    <p class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-primary">link</span>
                        গন্তব্য লিংক
                    </p>
                    <div>
                        <label class="field-label" for="ad_url">ক্লিক URL <span class="text-error">*</span></label>
                        <input class="field-input @error('ad_url') is-invalid @enderror"
                               type="url" id="ad_url" name="ad_url"
                               value="{{ old('ad_url', $ad->ad_url) }}"
                               placeholder="https://example.com" required>
                        @error('ad_url')<p class="invalid-msg">{{ $message }}</p>@enderror
                        <div class="mt-2 bg-surface-container rounded-lg px-3 py-2 text-xs font-mono text-on-surface-variant break-all" id="link-preview">
                            {{ $ad->ad_url ?? 'কোনো URL নেই' }}
                        </div>
                    </div>
                    <label class="flex items-center gap-2.5 mt-3 cursor-pointer">
                        <input type="checkbox" name="link_open_new_tab" value="1" class="w-4 h-4 rounded accent-primary"
                               {{ old('link_open_new_tab', $ad->link_open_new_tab ?? false) ? 'checked' : '' }}>
                        <span class="text-sm text-on-surface">নতুন ট্যাবে খুলুন</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Monetization Networks --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden" id="networks-section">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-tertiary/10">
                    <span class="material-symbols-outlined text-tertiary text-[18px]">monetization_on</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface">মনিটাইজেশন নেটওয়ার্ক</h3>
                    <p class="text-xs text-on-surface-variant">AdSense ও বিকল্প নেটওয়ার্ক</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-primary/5 border border-primary/20 rounded-xl px-4 py-3 text-sm text-primary flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] mt-0.5">info</span>
                    Google AdSense সহ ১১+ বিকল্প নেটওয়ার্ক থেকে বেছে নিন।
                </div>
                <div>
                    <label class="field-label" for="ad_network">নেটওয়ার্ক বেছে নিন</label>
                    <select class="field-input @error('ad_network') is-invalid @enderror" id="ad_network" name="ad_network">
                        <option value="">-- নেটওয়ার্ক বেছে নিন --</option>
                        @foreach ($adNetworks as $key => $label)
                        <option value="{{ $key }}" @selected(old('ad_network', $ad->ad_network) == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('ad_network')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div id="network-fields-container"></div>
                <div id="adsense-warning" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-sm text-yellow-800">
                    <p class="font-bold flex items-center gap-1.5 mb-1.5"><span class="material-symbols-outlined text-[16px]">warning</span> Google AdSense নীতি</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        <li>প্রতি পেজে সর্বোচ্চ ৩টি বিজ্ঞাপন</li>
                        <li>পেজে ন্যূনতম ৩০০ শব্দের কন্টেন্ট থাকতে হবে</li>
                        <li>Publisher ID ফরম্যাট: pub-XXXXXXXXXXXXXXXX (১৬ ডিজিট)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- UTM Parameters --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-primary/10">
                    <span class="material-symbols-outlined text-primary text-[18px]">analytics</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface">UTM প্যারামিটার</h3>
                    <p class="text-xs text-on-surface-variant">ক্যাম্পেইন ট্র্যাকিং</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="utm_source">UTM Source</label>
                        <input class="field-input" type="text" id="utm_source" name="utm_source"
                               value="{{ old('utm_source', $ad->utm_source) }}"
                               placeholder="facebook, google, newsletter">
                        <p class="text-xs text-on-surface-variant mt-1">ট্র্যাফিকের উৎস</p>
                    </div>
                    <div>
                        <label class="field-label" for="utm_medium">UTM Medium</label>
                        <input class="field-input" type="text" id="utm_medium" name="utm_medium"
                               value="{{ old('utm_medium', $ad->utm_medium) }}"
                               placeholder="cpc, banner, email">
                        <p class="text-xs text-on-surface-variant mt-1">মার্কেটিং মাধ্যম</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="utm_campaign">UTM Campaign</label>
                        <input class="field-input" type="text" id="utm_campaign" name="utm_campaign"
                               value="{{ old('utm_campaign', $ad->utm_campaign) }}"
                               placeholder="winter_sale_2026">
                        <p class="text-xs text-on-surface-variant mt-1">ক্যাম্পেইনের নাম</p>
                    </div>
                    <div>
                        <label class="field-label" for="utm_term">UTM Term</label>
                        <input class="field-input" type="text" id="utm_term" name="utm_term"
                               value="{{ old('utm_term', $ad->utm_term) }}"
                               placeholder="soft drinks, beverage">
                        <p class="text-xs text-on-surface-variant mt-1">কীওয়ার্ড</p>
                    </div>
                </div>
                <div>
                    <label class="field-label" for="utm_content">UTM Content</label>
                    <input class="field-input" type="text" id="utm_content" name="utm_content"
                           value="{{ old('utm_content', $ad->utm_content) }}"
                           placeholder="sidebar_banner, footer_link">
                    <p class="text-xs text-on-surface-variant mt-1">বিজ্ঞাপন পার্থক্য করতে</p>
                </div>
                <div class="bg-surface-container rounded-xl p-3">
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">সম্পূর্ণ URL প্রিভিউ</p>
                    <p class="text-xs font-mono text-on-surface break-all" id="url-preview">{{ $ad->ad_url }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== RIGHT COLUMN (sidebar) ===== --}}
    <div class="space-y-5">

        {{-- Submit Actions --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 space-y-3">
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-primary text-white px-5 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity active:scale-95">
                <span class="material-symbols-outlined text-[18px]">save</span>
                পরিবর্তন সংরক্ষণ করুন
            </button>
            <a href="{{ route('admin.advertisements.show', $ad) }}"
               class="w-full flex items-center justify-center gap-2 border border-outline-variant text-on-surface-variant px-5 py-3 rounded-xl font-bold text-sm hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
                বাতিল করুন
            </a>
            <a href="{{ route('admin.advertisements.index') }}"
               class="w-full flex items-center justify-center gap-2 text-on-surface-variant px-5 py-2.5 rounded-xl text-sm hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[16px]">list</span>
                তালিকায় ফিরে যান
            </a>
        </div>

        {{-- Status & Schedule --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-tertiary/10">
                    <span class="material-symbols-outlined text-tertiary text-[18px]">schedule</span>
                </div>
                <h3 class="font-bold text-on-surface text-sm">সময়সূচি ও স্ট্যাটাস</h3>
            </div>
            <div class="p-5 space-y-4">
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-outline-variant hover:bg-surface-container transition-colors">
                    <input type="checkbox" name="is_active" value="1"
                           class="w-4 h-4 rounded accent-primary"
                           {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-bold text-on-surface block">বিজ্ঞাপন সক্রিয়</span>
                        <span class="text-xs text-on-surface-variant">চেক করলে বিজ্ঞাপনটি দৃশ্যমান হবে</span>
                    </div>
                </label>
                <div>
                    <label class="field-label" for="start_date">শুরুর তারিখ <span class="text-error">*</span></label>
                    <input class="field-input @error('start_date') is-invalid @enderror"
                           type="datetime-local" id="start_date" name="start_date"
                           value="{{ old('start_date', $ad->start_date?->format('Y-m-d\TH:i')) }}" required>
                    @error('start_date')<p class="invalid-msg">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label" for="end_date">শেষের তারিখ</label>
                    <input class="field-input @error('end_date') is-invalid @enderror"
                           type="datetime-local" id="end_date" name="end_date"
                           value="{{ old('end_date', $ad->end_date?->format('Y-m-d\TH:i')) }}">
                    @error('end_date')<p class="invalid-msg">{{ $message }}</p>@enderror
                    <p class="text-xs text-on-surface-variant mt-1">ফাঁকা রাখলে মেয়াদহীন</p>
                </div>
            </div>
        </div>

        {{-- Advertiser Info --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-primary/10">
                    <span class="material-symbols-outlined text-primary text-[18px]">person_pin</span>
                </div>
                <h3 class="font-bold text-on-surface text-sm">বিজ্ঞাপনদাতার তথ্য</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="field-label" for="advertiser_name">নাম</label>
                    <input class="field-input" type="text" id="advertiser_name" name="advertiser_name"
                           value="{{ old('advertiser_name', $ad->advertiser_name) }}"
                           placeholder="কোম্পানির নাম">
                </div>
                <div>
                    <label class="field-label" for="advertiser_email">ইমেইল</label>
                    <input class="field-input" type="email" id="advertiser_email" name="advertiser_email"
                           value="{{ old('advertiser_email', $ad->advertiser_email) }}"
                           placeholder="contact@company.com">
                </div>
                <div>
                    <label class="field-label" for="advertiser_phone">ফোন</label>
                    <input class="field-input" type="text" id="advertiser_phone" name="advertiser_phone"
                           value="{{ old('advertiser_phone', $ad->advertiser_phone) }}"
                           placeholder="+880-XXXX-XXXXXX">
                </div>
            </div>
        </div>

        {{-- Budget Settings --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-secondary/10">
                    <span class="material-symbols-outlined text-secondary text-[18px]">payments</span>
                </div>
                <h3 class="font-bold text-on-surface text-sm">বাজেট সেটিংস</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="field-label" for="cpc_amount">CPC (প্রতি ক্লিক)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-sm">৳</span>
                        <input class="field-input pl-7" type="number" step="0.01" id="cpc_amount" name="cpc_amount"
                               value="{{ old('cpc_amount', $ad->cpc_amount) }}" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="field-label" for="cpm_amount">CPM (প্রতি ১০০০ ইম্প্রেশন)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-sm">৳</span>
                        <input class="field-input pl-7" type="number" step="0.01" id="cpm_amount" name="cpm_amount"
                               value="{{ old('cpm_amount', $ad->cpm_amount) }}" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="field-label" for="daily_impression_limit">দৈনিক ইম্প্রেশন সীমা</label>
                    <input class="field-input" type="number" id="daily_impression_limit" name="daily_impression_limit"
                           value="{{ old('daily_impression_limit', $ad->daily_impression_limit) }}"
                           min="1" placeholder="সীমাহীন">
                </div>
                <div>
                    <label class="field-label" for="max_clicks_per_day">দৈনিক সর্বোচ্চ ক্লিক</label>
                    <input class="field-input" type="number" id="max_clicks_per_day" name="max_clicks_per_day"
                           value="{{ old('max_clicks_per_day', $ad->max_clicks_per_day) }}"
                           min="1" placeholder="সীমাহীন">
                </div>
            </div>
        </div>

        {{-- Performance Stats --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-primary/10">
                    <span class="material-symbols-outlined text-primary text-[18px]">bar_chart</span>
                </div>
                <h3 class="font-bold text-on-surface text-sm">পারফরম্যান্স</h3>
            </div>
            <div class="p-5 grid grid-cols-3 gap-3 text-center">
                <div>
                    <p class="text-2xl font-bold text-primary">{{ number_format($ad->views) }}</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">ভিউ</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-secondary">{{ number_format($ad->clicks) }}</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">ক্লিক</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-tertiary">{{ $ad->ctr }}%</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">CTR</p>
                </div>
            </div>
        </div>

        {{-- Meta info --}}
        <div class="bg-surface-container rounded-xl p-4 text-xs text-on-surface-variant space-y-1.5">
            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">schedule</span> তৈরি: {{ $ad->created_at->format('d M Y, H:i') }}</p>
            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">update</span> আপডেট: {{ $ad->updated_at->format('d M Y, H:i') }}</p>
            @if($ad->creator ?? null)
            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">person</span> {{ $ad->creator->name }}</p>
            @endif
        </div>

        {{-- Notes --}}
        <div class="form-section bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant bg-surface-container">
                <div class="section-icon bg-outline/10">
                    <span class="material-symbols-outlined text-outline text-[18px]">note</span>
                </div>
                <h3 class="font-bold text-on-surface text-sm">নোট</h3>
            </div>
            <div class="p-5">
                <textarea class="field-input resize-none" id="notes" name="notes" rows="4"
                          placeholder="এই বিজ্ঞাপন সম্পর্কে অভ্যন্তরীণ নোট">{{ old('notes', $ad->notes) }}</textarea>
            </div>
        </div>

    </div>
</div>

</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Ad source → show/hide networks section
    const adSourceSelect = document.getElementById('ad_source');
    const networksSection = document.getElementById('networks-section');
    function toggleNetworks() {
        networksSection.style.display = adSourceSelect.value === 'online' ? 'block' : 'none';
    }
    adSourceSelect.addEventListener('change', toggleNetworks);
    toggleNetworks();

    // Network dynamic fields
    const networkFieldsConfig = @json($networkFields ?? []);
    const adNetworkSelect = document.getElementById('ad_network');
    const networkFieldsContainer = document.getElementById('network-fields-container');
    const adsenseWarning = document.getElementById('adsense-warning');
    const existingConfig = @json($ad->network_config ?? []);

    function renderNetworkFields() {
        const net = adNetworkSelect.value;
        networkFieldsContainer.innerHTML = '';
        adsenseWarning.classList.toggle('hidden', net !== 'adsense');
        if (!net || !networkFieldsConfig[net]) return;

        Object.entries(networkFieldsConfig[net]).forEach(([key, label]) => {
            const oldVal = existingConfig[key] || '';
            const isCode = key === 'code';
            networkFieldsContainer.insertAdjacentHTML('beforeend', `
                <div class="mb-3">
                    <label class="field-label" for="network_${key}">${label} <span class="text-error">*</span></label>
                    ${isCode
                        ? `<textarea class="field-input resize-none" id="network_${key}" name="network_${key}" rows="5">${oldVal}</textarea>`
                        : `<input class="field-input" type="text" id="network_${key}" name="network_${key}" value="${oldVal}" placeholder="${label}">`
                    }
                </div>
            `);
        });
    }
    adNetworkSelect.addEventListener('change', renderNetworkFields);
    if (adNetworkSelect.value) renderNetworkFields();

    // Image upload
    const imageFileInput = document.getElementById('image_file');
    const uploadBtn     = document.getElementById('upload-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar   = document.getElementById('progress-bar');
    const uploadMessage = document.getElementById('upload-message');
    const previewImg    = document.getElementById('preview-img');
    const noImagePlaceholder = document.getElementById('no-image-placeholder');
    const imageUrlField = document.getElementById('image_url');

    imageFileInput.addEventListener('change', function () {
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                noImagePlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    uploadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const file = imageFileInput.files[0];
        if (!file) {
            uploadMessage.innerHTML = '<p class="text-xs text-error mt-1">প্রথমে একটি ছবি বেছে নিন।</p>';
            return;
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        uploadProgress.classList.remove('hidden');
        progressBar.style.width = '40%';
        uploadBtn.disabled = true;
        uploadMessage.innerHTML = '';

        fetch('{{ route("admin.upload-advertisement-image") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            progressBar.style.width = '100%';
            setTimeout(() => uploadProgress.classList.add('hidden'), 500);
            if (data.success) {
                imageUrlField.value = data.url;
                previewImg.src = '/' + data.url;
                previewImg.classList.remove('hidden');
                noImagePlaceholder.classList.add('hidden');
                uploadMessage.innerHTML = '<p class="text-xs text-tertiary mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">check_circle</span> ছবি আপলোড সফল হয়েছে।</p>';
            } else {
                uploadMessage.innerHTML = `<p class="text-xs text-error mt-1">${data.message || 'আপলোড ব্যর্থ হয়েছে।'}</p>`;
            }
            uploadBtn.disabled = false;
        })
        .catch(err => {
            uploadProgress.classList.add('hidden');
            uploadMessage.innerHTML = `<p class="text-xs text-error mt-1">ত্রুটি: ${err.message}</p>`;
            uploadBtn.disabled = false;
        });
    });

    // URL preview & UTM
    function updateUrlPreview() {
        const base = document.getElementById('ad_url').value;
        const params = [];
        ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(id => {
            const v = document.getElementById(id)?.value;
            if (v) params.push(`${id}=${encodeURIComponent(v)}`);
        });
        document.getElementById('url-preview').textContent =
            base + (params.length ? (base.includes('?') ? '&' : '?') + params.join('&') : '');
        document.getElementById('link-preview').textContent = base || 'কোনো URL নেই';
    }
    ['ad_url','utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', updateUrlPreview);
    });
    updateUrlPreview();

    // Form: clear SVG placeholder before submit
    document.getElementById('ad-form').addEventListener('submit', function () {
        if (imageUrlField.value.startsWith('data:image/svg')) imageUrlField.value = '';
    });
});
</script>
@endpush
