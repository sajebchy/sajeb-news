<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>নতুন পাসওয়ার্ড সেট করুন - {{ config('app.name', 'Sajeb News') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi.ttf') format('truetype'); font-weight: 400; font-display: swap; }
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi-Bold.ttf') format('truetype'); font-weight: 700; font-display: swap; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=snap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#004e9f",
                        "on-primary": "#ffffff",
                        "primary-fixed": "#d7e3ff",
                        "primary-container": "#0066cc",
                        "on-primary-container": "#dfe8ff",
                        "secondary": "#ab3500",
                        "surface": "#fcf9f8",
                        "background": "#fcf9f8",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f6f3f2",
                        "surface-container": "#f0eded",
                        "surface-container-high": "#eae7e7",
                        "surface-container-highest": "#e5e2e1",
                        "on-surface": "#1c1b1b",
                        "on-surface-variant": "#414753",
                        "outline": "#727784",
                        "outline-variant": "#c1c6d5",
                        "tertiary": "#005e2c",
                        "tertiary-container": "#00793b",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem",
                    },
                    fontFamily: {
                        "bengali": ["SolaimanLipi", "serif"],
                        "sans": ["Inter", "sans-serif"],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; min-height: max(884px, 100dvh); }
        .bengali { font-family: 'SolaimanLipi', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .input-focus-ring:focus-within { box-shadow: 0 0 0 2px rgba(0,78,159,0.1); border-color: #004e9f; }
        .password-strength-bar { transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-background text-on-surface flex flex-col min-h-screen">

    <!-- Top App Bar -->
    <header class="fixed top-0 w-full z-50 bg-surface border-b border-outline-variant flex items-center justify-between px-4 h-16">
        <a href="{{ route('login') }}" class="p-2 hover:bg-surface-container-high transition-colors rounded-full active:opacity-70 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
        <span class="text-[18px] font-bold text-primary bengali">{{ config('app.name', 'সাজেব নিউজ') }}</span>
        <div class="w-10"></div>
    </header>

    <!-- Main -->
    <main class="flex-grow flex flex-col items-center justify-start px-4 pt-24 pb-6 max-w-md mx-auto w-full">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-fixed rounded-full mb-4">
                <span class="material-symbols-outlined text-primary text-[32px]" style="font-variation-settings:'FILL' 1">lock_reset</span>
            </div>
            <h2 class="bengali font-bold text-[24px] leading-tight text-on-surface mb-2">নতুন পাসওয়ার্ড সেট করুন</h2>
            <p class="text-[14px] text-on-surface-variant px-2 bengali">আপনার অ্যাকাউন্টের নিরাপত্তার জন্য একটি শক্তিশালী পাসওয়ার্ড তৈরি করুন।</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('password.store') }}" id="resetForm" class="w-full space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email (hidden visually, required by Laravel) -->
            <div class="space-y-2">
                <label for="email" class="bengali text-[12px] font-bold tracking-wider uppercase text-on-surface-variant block ml-1">ইমেইল ঠিকানা</label>
                <div class="relative group input-focus-ring border border-outline-variant rounded-lg transition-all duration-200">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline">mail</span>
                    </div>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', $request->email) }}"
                        required autocomplete="username"
                        class="w-full py-4 pl-11 pr-4 bg-transparent border-none rounded-lg focus:ring-0 text-on-surface text-[16px] @error('email') border-error @enderror"
                    />
                </div>
                @error('email')
                    <p class="bengali text-[13px] text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <label for="password" class="bengali text-[12px] font-bold tracking-wider uppercase text-on-surface-variant block ml-1">নতুন পাসওয়ার্ড</label>
                <div class="relative group input-focus-ring border border-outline-variant rounded-lg transition-all duration-200">
                    <input id="password" name="password" type="password"
                        placeholder="••••••••"
                        required autocomplete="new-password"
                        class="w-full py-4 pl-4 pr-12 bg-transparent border-none rounded-lg focus:ring-0 text-on-surface text-[16px] @error('password') border-error @enderror"
                    />
                    <button type="button" onclick="togglePassword('password', 'eye_new')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined" id="eye_new">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="bengali text-[13px] text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password Strength -->
            <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant/30 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="bengali text-[12px] font-bold tracking-wider uppercase text-on-surface-variant">পাসওয়ার্ডের শক্তি</span>
                    <span class="bengali text-[12px] font-bold text-error" id="strength_text">দুর্বল</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container-highest rounded-full overflow-hidden">
                    <div class="h-full w-0 bg-error password-strength-bar" id="strength_bar"></div>
                </div>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-[13px] text-on-surface-variant bengali" id="req_length">
                        <span class="material-symbols-outlined text-[16px]">circle</span>
                        কমপক্ষে ৮টি অক্ষর
                    </li>
                    <li class="flex items-center gap-2 text-[13px] text-on-surface-variant bengali" id="req_upper">
                        <span class="material-symbols-outlined text-[16px]">circle</span>
                        একটি বড় হাতের ও একটি ছোট হাতের অক্ষর
                    </li>
                    <li class="flex items-center gap-2 text-[13px] text-on-surface-variant bengali" id="req_num">
                        <span class="material-symbols-outlined text-[16px]">circle</span>
                        অন্তত একটি সংখ্যা
                    </li>
                </ul>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="bengali text-[12px] font-bold tracking-wider uppercase text-on-surface-variant block ml-1">পাসওয়ার্ড নিশ্চিত করুন</label>
                <div class="relative group input-focus-ring border border-outline-variant rounded-lg transition-all duration-200">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="••••••••"
                        required autocomplete="new-password"
                        class="w-full py-4 pl-4 pr-12 bg-transparent border-none rounded-lg focus:ring-0 text-on-surface text-[16px] @error('password_confirmation') border-error @enderror"
                    />
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye_confirm')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined" id="eye_confirm">visibility</span>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="bengali text-[13px] text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" id="submitBtn"
                class="w-full bg-primary text-on-primary bengali text-[18px] font-semibold py-4 rounded-lg shadow-sm hover:bg-primary-container transition-all active:scale-[0.98]">
                পাসওয়ার্ড আপডেট করুন
            </button>

            <!-- Back to Login -->
            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-[14px] text-primary hover:underline underline-offset-4 group">
                    <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                    <span class="bengali">লগইন পেজে ফিরে যান</span>
                </a>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="py-8 text-center opacity-40">
        @php $__rpSeo = \App\Models\SeoSetting::first(); @endphp
        <p class="bengali text-[12px] text-on-surface-variant">© {{ date('Y') }} {{ $__rpSeo->site_name ?? 'Sajeb News' }}। সকল স্বত্ব সংরক্ষিত।</p>
    </footer>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength_bar');
        const strengthText = document.getElementById('strength_text');
        const reqLength = document.getElementById('req_length');
        const reqUpper = document.getElementById('req_upper');
        const reqNum = document.getElementById('req_num');

        function updateReq(el, isValid) {
            const icon = el.querySelector('.material-symbols-outlined');
            if (isValid) {
                icon.innerText = 'check_circle';
                icon.style.fontVariationSettings = "'FILL' 1";
                icon.classList.remove('text-on-surface-variant');
                icon.classList.add('text-[#005e2c]');
                el.classList.remove('text-on-surface-variant');
                el.classList.add('text-[#005e2c]');
            } else {
                icon.innerText = 'circle';
                icon.style.fontVariationSettings = "'FILL' 0";
                icon.classList.add('text-on-surface-variant');
                icon.classList.remove('text-[#005e2c]');
                el.classList.add('text-on-surface-variant');
                el.classList.remove('text-[#005e2c]');
            }
        }

        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;

            if (val.length >= 8) { score++; updateReq(reqLength, true); } else { updateReq(reqLength, false); }
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) { score++; updateReq(reqUpper, true); } else { updateReq(reqUpper, false); }
            if (/\d/.test(val)) { score++; updateReq(reqNum, true); } else { updateReq(reqNum, false); }

            if (val.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.backgroundColor = '#e5e2e1';
                strengthText.textContent = 'অপেক্ষা করুন';
                strengthText.style.color = '#414753';
            } else if (score < 2) {
                strengthBar.style.width = '33%';
                strengthBar.style.backgroundColor = '#ba1a1a';
                strengthText.textContent = 'দুর্বল';
                strengthText.style.color = '#ba1a1a';
            } else if (score === 2) {
                strengthBar.style.width = '66%';
                strengthBar.style.backgroundColor = '#ab3500';
                strengthText.textContent = 'মাঝারি';
                strengthText.style.color = '#ab3500';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = '#005e2c';
                strengthText.textContent = 'শক্তিশালী';
                strengthText.style.color = '#005e2c';
            }
        });

        document.getElementById('resetForm').addEventListener('submit', (e) => {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<span class="flex items-center justify-center gap-2 bengali"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> আপডেট হচ্ছে...</span>';
            btn.disabled = true;
        });
    </script>
</body>
</html>
