<!DOCTYPE html>
<html class="light" lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নিবন্ধন — সজীব নিউজ</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Serif:wght@600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#004e9f",
            "on-primary": "#ffffff",
            "primary-container": "#0066cc",
            "on-primary-container": "#dfe8ff",
            "secondary": "#ab3500",
            "on-secondary": "#ffffff",
            "secondary-container": "#fe6a34",
            "on-secondary-container": "#5d1900",
            "error": "#ba1a1a",
            "on-error": "#ffffff",
            "error-container": "#ffdad6",
            "background": "#fcf9f8",
            "on-background": "#1c1b1b",
            "surface": "#fcf9f8",
            "on-surface": "#1c1b1b",
            "surface-variant": "#e5e2e1",
            "on-surface-variant": "#414753",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f6f3f2",
            "surface-container": "#f0eded",
            "surface-container-high": "#eae7e7",
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
          fontFamily: {
            "display": ["Noto Serif", "serif"],
            "sans": ["Inter", "sans-serif"],
          },
        }
      }
    }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .focused-input {
            transition: all 0.2s ease;
        }
        .focused-input:focus {
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.1);
            outline: none;
            border-color: #0066cc;
        }
        body {
            min-height: max(884px, 100dvh);
            background-color: #fcf9f8;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="text-on-surface flex flex-col min-h-screen">

<header class="sticky top-0 z-50 w-full bg-surface border-b border-outline-variant px-4 py-4 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" class="material-symbols-outlined text-primary cursor-pointer hover:opacity-70">arrow_back</a>
        <h1 class="font-display text-[20px] font-bold text-primary">সজীব নিউজ</h1>
    </div>
    <span class="material-symbols-outlined text-on-surface-variant">language</span>
</header>

<main class="flex-grow flex flex-col px-4 pt-6 pb-12">
    <div class="max-w-md mx-auto w-full">
        {{-- Hero --}}
        <div class="mb-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-container/10 mb-4">
                <span class="material-symbols-outlined text-primary text-[32px]" style="font-variation-settings: 'FILL' 1;">person_add</span>
            </div>
            <h2 class="font-display text-[24px] font-bold text-on-surface mb-1">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
            <p class="text-[14px] text-on-surface-variant">সর্বশেষ খবরের সাথে যুক্ত থাকতে আজই নিবন্ধন করুন</p>
        </div>

        {{-- Register Form --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div class="space-y-1">
                <label class="text-[12px] font-bold text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">person</span>
                    পুরো নাম
                </label>
                <input type="text" name="name" required
                       class="w-full h-14 px-4 rounded-lg border border-outline-variant bg-surface-container-lowest focused-input text-[16px]"
                       placeholder="আপনার নাম লিখুন" value="{{ old('name') }}">
                @error('name')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1">
                <label class="text-[12px] font-bold text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">mail</span>
                    ইমেইল ঠিকানা
                </label>
                <input type="email" name="email" required
                       class="w-full h-14 px-4 rounded-lg border border-outline-variant bg-surface-container-lowest focused-input text-[16px]"
                       placeholder="example@mail.com" value="{{ old('email') }}">
                @error('email')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div class="space-y-1">
                <label class="text-[12px] font-bold text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">lock</span>
                    পাসওয়ার্ড
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                           class="w-full h-14 px-4 rounded-lg border border-outline-variant bg-surface-container-lowest focused-input text-[16px]"
                           placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড">
                    <button type="button" onclick="togglePassword('password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                @error('password')<p class="text-error text-[12px] mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1">
                <label class="text-[12px] font-bold text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">verified_user</span>
                    পাসওয়ার্ড নিশ্চিত করুন
                </label>
                <input type="password" name="password_confirmation" required
                       class="w-full h-14 px-4 rounded-lg border border-outline-variant bg-surface-container-lowest focused-input text-[16px]"
                       placeholder="আবার পাসওয়ার্ড লিখুন">
            </div>

            {{-- Terms --}}
            <div class="flex items-start gap-2 pt-2">
                <input type="checkbox" name="terms" id="terms" required class="mt-1 w-5 h-5 rounded border-outline-variant">
                <label for="terms" class="text-[14px] text-on-surface-variant">
                    আমি <a href="{{ route('terms') }}" class="text-primary font-bold hover:underline">শর্তাবলী</a> এবং
                    <a href="{{ route('privacy') }}" class="text-primary font-bold hover:underline">গোপনীয়তা নীতি</a> মেনে নিতে সম্মত।
                </label>
            </div>

            {{-- Register Button --}}
            <button type="submit"
                    class="w-full h-14 bg-primary text-on-primary font-display font-bold text-[18px] rounded-lg shadow-md active:scale-95 transition-transform mt-6 hover:opacity-90">
                নিবন্ধন করুন
            </button>
        </form>

        {{-- Divider --}}
        <div class="relative flex items-center gap-4 my-6">
            <div class="flex-grow border-t border-outline-variant"></div>
            <span class="text-[12px] font-bold text-outline">অথবা</span>
            <div class="flex-grow border-t border-outline-variant"></div>
        </div>

        {{-- Social Registration --}}
        <div class="grid grid-cols-2 gap-4">
            <button type="button"
                    class="flex items-center justify-center h-12 border border-outline-variant rounded-lg bg-surface-container-lowest hover:shadow-sm transition-all active:bg-surface-container">
                <span class="material-symbols-outlined text-[20px]">account_circle</span>
            </button>
            <button type="button"
                    class="flex items-center justify-center h-12 border border-outline-variant rounded-lg bg-surface-container-lowest hover:shadow-sm transition-all active:bg-surface-container">
                <span class="material-symbols-outlined text-[20px]">share</span>
            </button>
        </div>

        {{-- Login Link --}}
        <div class="mt-12 text-center pb-6">
            <p class="text-[16px] text-on-surface-variant">
                ইতিমধ্যে একটি অ্যাকাউন্ট আছে?
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">
                    লগইন করুন
                </a>
            </p>
        </div>
    </div>
</main>

{{-- Footer --}}
<footer class="w-full py-4 px-4 text-center border-t border-outline-variant bg-surface-container-low">
    <p class="text-[14px] text-outline-variant">© ২০२४ সজীব নিউজ। সর্বস্বত্ব সংরক্ষিত।</p>
</footer>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const btn = event.currentTarget;
        const span = btn.querySelector('span');
        if (input.type === 'password') {
            input.type = 'text';
            span.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            span.textContent = 'visibility';
        }
    }
</script>

</body>
</html>
