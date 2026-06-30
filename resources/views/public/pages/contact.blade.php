@extends('public.layout')

@section('title', $metaTags['title'] ?? 'যোগাযোগ করুন - সজীব নিউজ')
@section('meta_description', $metaTags['description'] ?? 'সজীব নিউজের সাথে যোগাযোগ করুন।')

@section('content')

<!-- Page Hero -->
<div class="bg-primary text-white py-12 px-gutter">
  <div class="max-w-container-max mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-3 block tracking-widest uppercase">সংযোগ</span>
    <h1 class="font-headline-lg text-4xl mb-4 leading-tight">আমাদের সাথে যোগাযোগ করুন</h1>
    <p class="font-body-main text-white/80 max-w-2xl">সংবাদ টিপস, মতামত বা অন্য যেকোনো বিষয়ে আমাদের দলের সাথে যোগাযোগ করুন।</p>
  </div>
</div>

<main class="max-w-container-max mx-auto px-gutter py-section-padding">

  @if(session('success'))
  <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-800 rounded flex items-center gap-3">
    <span class="material-symbols-outlined text-green-600">check_circle</span>
    <p class="font-body-main">{{ session('success') }}</p>
  </div>
  @endif

  @if(session('error'))
  <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 rounded flex items-center gap-3">
    <span class="material-symbols-outlined text-red-600">error</span>
    <p class="font-body-main">{{ session('error') }}</p>
  </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-12 gap-stack-lg items-start">

    <!-- Contact Form (70%) -->
    <div class="md:col-span-8 bg-white border border-border-subtle p-stack-lg shadow-sm">
      <h2 class="font-headline-md text-xl mb-6 text-primary">বার্তা পাঠান</h2>
      <form class="space-y-6" method="POST" action="{{ route('contact.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-2">
            <label class="font-label-caps text-label-caps text-on-surface-variant" for="name">পূর্ণ নাম <span class="text-secondary">*</span></label>
            <input class="border border-border-subtle focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 font-body-sm outline-none transition-all @error('name') border-secondary @enderror"
                   id="name" name="name" placeholder="আপনার নাম লিখুন" required type="text" value="{{ old('name') }}"/>
            @error('name')<p class="text-secondary text-sm mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="flex flex-col gap-2">
            <label class="font-label-caps text-label-caps text-on-surface-variant" for="email">ইমেইল অ্যাড্রেস <span class="text-secondary">*</span></label>
            <input class="border border-border-subtle focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 font-body-sm outline-none transition-all @error('email') border-secondary @enderror"
                   id="email" name="email" placeholder="email@example.com" required type="email" value="{{ old('email') }}"/>
            @error('email')<p class="text-secondary text-sm mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
        <div class="flex flex-col gap-2">
          <label class="font-label-caps text-label-caps text-on-surface-variant" for="subject">বিষয় <span class="text-secondary">*</span></label>
          <select class="border border-border-subtle focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 font-body-sm outline-none transition-all appearance-none bg-white"
                  id="subject" name="subject" required>
            <option value="">— বিষয় নির্বাচন করুন —</option>
            <option value="সংবাদ টিপস" {{ old('subject')=='সংবাদ টিপস'?'selected':'' }}>সংবাদ টিপস / স্কুপ</option>
            <option value="সংশোধন অনুরোধ" {{ old('subject')=='সংশোধন অনুরোধ'?'selected':'' }}>সংশোধন অনুরোধ</option>
            <option value="বিজ্ঞাপন" {{ old('subject')=='বিজ্ঞাপন'?'selected':'' }}>বিজ্ঞাপন সংক্রান্ত</option>
            <option value="প্রযুক্তিগত সহায়তা" {{ old('subject')=='প্রযুক্তিগত সহায়তা'?'selected':'' }}>প্রযুক্তিগত সহায়তা</option>
            <option value="অন্যান্য" {{ old('subject')=='অন্যান্য'?'selected':'' }}>অন্যান্য</option>
          </select>
          @error('subject')<p class="text-secondary text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-2">
          <label class="font-label-caps text-label-caps text-on-surface-variant" for="message">আপনার বার্তা <span class="text-secondary">*</span></label>
          <textarea class="border border-border-subtle focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 font-body-sm outline-none transition-all resize-none @error('message') border-secondary @enderror"
                    id="message" name="message" placeholder="আমরা আপনাকে কীভাবে সাহায্য করতে পারি?" required rows="6">{{ old('message') }}</textarea>
          @error('message')<p class="text-secondary text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <button class="bg-primary text-white font-label-caps text-label-caps py-4 px-10 hover:bg-news-blue-dark transition-colors duration-300 active:scale-95 flex items-center gap-2 group" type="submit">
          বার্তা পাঠান
          <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">send</span>
        </button>
      </form>
    </div>

    <!-- Contact Info (30%) -->
    <aside class="md:col-span-4 flex flex-col gap-stack-lg">
      <div class="bg-surface-muted border border-border-subtle p-stack-md">
        <h3 class="font-headline-md text-headline-md text-primary mb-4">সজীব নিউজ সদর দফতর</h3>
        <div class="space-y-4">
          <div class="flex gap-4">
            <span class="material-symbols-outlined text-secondary flex-shrink-0">location_on</span>
            <div class="font-body-sm text-on-surface-variant">
              <strong class="text-on-surface">ঠিকানা:</strong><br/>
              ১২২/এ মতিঝিল বাণিজ্যিক এলাকা,<br/>
              ঢাকা ১০০০, বাংলাদেশ
            </div>
          </div>
          <div class="flex gap-4">
            <span class="material-symbols-outlined text-secondary flex-shrink-0">call</span>
            <div class="font-body-sm text-on-surface-variant">
              <strong class="text-on-surface">ফোন:</strong><br/>
              +৮৮০ ২ ৯৫৫৫১২৩
            </div>
          </div>
          <div class="flex gap-4">
            <span class="material-symbols-outlined text-secondary flex-shrink-0">mail</span>
            <div class="font-body-sm text-on-surface-variant">
              <strong class="text-on-surface">ইমেইল:</strong><br/>
              editor@sajebnews.com<br/>
              ads@sajebnews.com
            </div>
          </div>
        </div>
      </div>

      <!-- Office Hours -->
      <div class="bg-surface-container-low p-stack-md border border-subtle rounded">
        <h4 class="font-label-caps text-label-caps text-on-surface-variant mb-3">অফিস সময়সূচি</h4>
        <div class="space-y-2 font-body-sm text-on-surface-variant">
          <div class="flex justify-between"><span>রবি – বৃহস্পতি</span><span class="font-bold text-on-surface">সকাল ৯টা – রাত ৮টা</span></div>
          <div class="flex justify-between"><span>শুক্র – শনি</span><span class="font-bold text-on-surface">সকাল ১০টা – বিকাল ৫টা</span></div>
        </div>
        <p class="text-meta-data font-meta-data text-secondary mt-3">অনলাইন নিউজরুম ২৪/৭ চালু থাকে।</p>
      </div>

      <!-- Social Links -->
      <div class="p-stack-md">
        <h4 class="font-label-caps text-label-caps text-on-surface-variant border-b border-border-subtle pb-2 mb-4">আমাদের অনুসরণ করুন</h4>
        <div class="flex gap-3">
          <a class="w-10 h-10 flex items-center justify-center bg-news-blue-dark text-white rounded-full hover:bg-secondary transition-colors" href="#" title="Facebook">
            <span class="material-symbols-outlined text-[20px]">face_nod</span>
          </a>
          <a class="w-10 h-10 flex items-center justify-center bg-news-blue-dark text-white rounded-full hover:bg-secondary transition-colors" href="#" title="Twitter">
            <span class="material-symbols-outlined text-[20px]">share</span>
          </a>
          <a class="w-10 h-10 flex items-center justify-center bg-news-blue-dark text-white rounded-full hover:bg-secondary transition-colors" href="#" title="YouTube">
            <span class="material-symbols-outlined text-[20px]">play_circle</span>
          </a>
          <a class="w-10 h-10 flex items-center justify-center bg-news-blue-dark text-white rounded-full hover:bg-secondary transition-colors" href="#" title="LinkedIn">
            <span class="material-symbols-outlined text-[20px]">work</span>
          </a>
        </div>
      </div>
    </aside>
  </div>

</main>

@endsection
