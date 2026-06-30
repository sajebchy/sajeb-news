<!DOCTYPE html>
<html class="light" lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রোফাইল সেটিংস — সজীব নিউজ</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Serif:wght@400;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#004e9f",
            "primary-container": "#0066cc",
            "primary-fixed": "#d7e3ff",
            "on-primary-container": "#dfe8ff",
            "on-primary-fixed": "#001b3e",
            "secondary": "#ab3500",
            "secondary-container": "#fe6a34",
            "on-secondary-container": "#5d1900",
            "tertiary": "#005e2c",
            "tertiary-container": "#00793b",
            "on-tertiary-container": "#98ffaf",
            "error": "#ba1a1a",
            "error-container": "#ffdad6",
            "background": "#fcf9f8",
            "on-background": "#1c1b1b",
            "surface": "#fcf9f8",
            "on-surface": "#1c1b1b",
            "on-surface-variant": "#414753",
            "surface-container-low": "#f6f3f2",
            "surface-container": "#f0eded",
            "surface-container-high": "#eae7e7",
            "surface-container-lowest": "#ffffff",
            "outline": "#727784",
            "outline-variant": "#c1c6d5",
          },
          spacing: {
            "xs": "4px",
            "sm": "8px",
            "md": "16px",
            "lg": "24px",
            "xl": "32px",
            "xxl": "48px",
            "xxxl": "64px",
          },
        }
      }
    }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #fcf9f8;
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .font-serif-bn {
            font-family: 'Noto Serif', serif;
        }
        .form-input-focus:focus {
            border-color: #004e9f;
            box-shadow: 0 0 0 2px rgba(0, 78, 159, 0.1);
            outline: none;
        }
        .nav-item {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="text-on-surface">

{{-- Header --}}
<header class="sticky top-0 z-50 w-full bg-surface border-b border-outline-variant px-6 py-4 shadow-sm">
    <div class="max-w-6xl mx-auto flex items-center justify-between">
        <h1 class="font-serif-bn text-[28px] font-bold text-primary">সজীব নিউজ</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" target="_blank" class="text-on-surface-variant hover:text-primary transition-colors text-[14px]">সাইট দেখুন</a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="text-error hover:opacity-70 text-[14px] font-bold">লগআউট</button>
            </form>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-8 min-h-[calc(100vh-120px)]">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar --}}
        <aside class="w-full lg:w-72 flex-shrink-0">
            <div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant sticky top-24">
                <div class="mb-6">
                    <h2 class="font-serif-bn text-[22px] font-bold text-on-surface">সেটিংস</h2>
                    <p class="text-[14px] text-on-surface-variant">আপনার অ্যাকাউন্ট পরিচালনা করুন</p>
                </div>
                <nav class="space-y-2">
                    <button type="button" onclick="switchTab('account')" class="nav-item w-full flex items-center gap-4 p-4 rounded-lg font-bold text-[14px] bg-primary-container text-on-primary-container transition-all">
                        <span class="material-symbols-outlined">person</span>
                        প্রোফাইল তথ্য
                    </button>
                    <button type="button" onclick="switchTab('password')" class="nav-item w-full flex items-center gap-4 p-4 rounded-lg font-bold text-[14px] text-on-surface-variant hover:bg-surface-container transition-all">
                        <span class="material-symbols-outlined">security</span>
                        পাসওয়ার্ড পরিবর্তন
                    </button>
                </nav>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex-grow">
            {{-- Account Info Tab --}}
            <section id="tab-account" class="bg-white p-8 rounded-xl shadow-md border border-outline-variant">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="font-serif-bn text-[28px] font-bold text-on-surface">প্রোফাইল তথ্য</h2>
                </div>

                {{-- Success Message --}}
                @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                    <i class="bi bi-check-circle"></i> প্রোফাইল সফলভাবে আপডেট হয়েছে।
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Profile Picture --}}
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-4 block">প্রোফাইল ছবি</label>
                        <div class="flex flex-col gap-4">
                            <!-- Current Avatar Preview -->
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-full overflow-hidden bg-surface-container flex-shrink-0 border-2 border-primary-container">
                                    @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary-container text-on-primary-container">
                                        <span class="material-symbols-outlined text-[36px]">person</span>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[14px] text-on-surface font-bold">বর্তমান ছবি</p>
                                    <p class="text-[12px] text-on-surface-variant">আপনার প্রোফাইল ছবি</p>
                                </div>
                            </div>

                            <!-- Upload Input -->
                            <div class="relative border-2 border-dashed border-outline-variant rounded-lg p-6 text-center hover:bg-surface-container-low transition-colors cursor-pointer" id="uploadArea">
                                <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant">image</span>
                                    <div>
                                        <p class="text-[14px] font-bold text-primary">ছবি নির্বাচন করুন</p>
                                        <p class="text-[12px] text-on-surface-variant">JPG, PNG, GIF (সর্বোচ্চ ২ MB)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview -->
                            <img id="previewImage" class="hidden w-full max-w-sm rounded-lg border border-outline-variant" alt="Preview">

                            @error('avatar')<p class="text-error text-[12px]">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">নাম</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full p-4 rounded-lg border border-outline-variant form-input-focus text-[16px]"
                               placeholder="আপনার নাম">
                        @error('name')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">ইমেইল</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full p-4 rounded-lg border border-outline-variant bg-surface-container-low text-[16px]">
                        {{-- Hidden field so email is submitted with the form --}}
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <p class="text-[12px] text-on-surface-variant mt-1">ইমেইল পরিবর্তন করা যাবে না</p>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">জীবনী (ঐচ্ছিক)</label>
                        <textarea name="bio" rows="4"
                                  class="w-full p-4 rounded-lg border border-outline-variant form-input-focus text-[16px]"
                                  placeholder="নিজের সম্পর্কে কিছু লিখুন...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary-container text-on-primary-container rounded-lg font-bold shadow-sm hover:opacity-90 active:scale-95 transition-all">
                            পরিবর্তন সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </section>

            {{-- Password Tab --}}
            <section id="tab-password" class="hidden bg-white p-8 rounded-xl shadow-md border border-outline-variant">
                <h2 class="font-serif-bn text-[28px] font-bold text-on-surface mb-8">পাসওয়ার্ড পরিবর্তন করুন</h2>

                @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                    <i class="bi bi-check-circle"></i> পাসওয়ার্ড সফলভাবে আপডেট হয়েছে।
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6 max-w-md">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">বর্তমান পাসওয়ার্ড</label>
                        <input type="password" name="current_password" required
                               class="w-full p-4 rounded-lg border border-outline-variant form-input-focus text-[16px]">
                        @error('current_password')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">নতুন পাসওয়ার্ড</label>
                        <input type="password" name="password" required
                               class="w-full p-4 rounded-lg border border-outline-variant form-input-focus text-[16px]">
                        @error('password')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wide mb-2 block">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full p-4 rounded-lg border border-outline-variant form-input-focus text-[16px]">
                        @error('password_confirmation')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-8 py-3 bg-primary text-on-primary rounded-lg font-bold shadow-sm hover:opacity-90 active:scale-95 transition-all">
                            পাসওয়ার্ড আপডেট করুন
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show target tab
        document.getElementById('tab-' + tabId).classList.remove('hidden');

        // Update button styles
        document.querySelectorAll('.nav-item').forEach(btn => {
            btn.classList.remove('bg-primary-container', 'text-on-primary-container');
            btn.classList.add('text-on-surface-variant', 'hover:bg-surface-container');
        });

        // Highlight active button
        event.currentTarget.classList.add('bg-primary-container', 'text-on-primary-container');
        event.currentTarget.classList.remove('text-on-surface-variant', 'hover:bg-surface-container');
    }

    // Avatar Upload Handler
    const uploadArea = document.getElementById('uploadArea');
    const avatarInput = document.getElementById('avatarInput');
    const previewImage = document.getElementById('previewImage');

    // Click to upload
    uploadArea.addEventListener('click', () => avatarInput.click());

    // File selection handler
    avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            displayPreview(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-surface-container-low');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-surface-container-low');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-surface-container-low');

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            avatarInput.files = e.dataTransfer.files;
            displayPreview(file);
        }
    });

    function displayPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewImage.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>

</body>
</html>
