<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>পাসওয়ার্ড পুনরুদ্ধার - {{ config('app.name', 'Sajeb News') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi.ttf') format('truetype'); font-weight: 400; font-display: swap; }
        @font-face { font-family: 'SolaimanLipi'; src: url('/fonts/SolaimanLipi-Bold.ttf') format('truetype'); font-weight: 700; font-display: swap; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "surface-dim": "#dcd9d9",
                        "on-surface": "#1c1b1b",
                        "on-surface-variant": "#414753",
                        "outline": "#727784",
                        "outline-variant": "#c1c6d5",
                        "tertiary": "#005e2c",
                        "tertiary-container": "#00793b",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "inverse-primary": "#aac7ff",
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
        .card-elevation { box-shadow: 0px 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-background text-on-surface">

    <!-- Top App Bar -->
    <header class="fixed top-0 w-full z-50 bg-surface border-b border-outline-variant flex items-center justify-between px-4 h-16">
        <div class="flex items-center gap-2">
            <a href="{{ route('login') }}" class="flex items-center justify-center p-2 hover:bg-surface-container-high transition-colors rounded-full active:opacity-70">
                <span class="material-symbols-outlined text-primary">arrow_back</span>
            </a>
            <span class="text-[20px] font-bold text-primary bengali leading-tight">{{ config('app.name', 'Sajeb News') }}</span>
        </div>
    </header>

    <!-- Main -->
    <main class="min-h-screen pt-24 pb-12 px-4 flex flex-col items-center justify-center">

        <!-- Session Status -->
        @if (session('status'))
            <div class="w-full max-w-md mb-4 bg-tertiary-container/20 border border-tertiary/30 text-tertiary rounded-xl px-4 py-3 flex items-center gap-2 text-sm bengali">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <!-- Card -->
        <div class="w-full max-w-md bg-surface-container-lowest p-8 rounded-xl card-elevation">

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-primary-fixed flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined text-[32px] text-primary" style="font-variation-settings:'FILL' 1">lock_reset</span>
                </div>
            </div>

            <!-- Headline -->
            <div class="text-center mb-8">
                <h2 class="bengali font-bold text-[24px] leading-tight text-on-surface mb-2">পাসওয়ার্ড ভুলে গেছেন?</h2>
                <p class="bengali text-[14px] leading-relaxed text-on-surface-variant max-w-[280px] mx-auto">
                    আপনার ইমেইল ঠিকানাটি প্রদান করুন। আমরা আপনাকে একটি পাসওয়ার্ড পুনরুদ্ধারের লিঙ্ক পাঠাব।
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="bengali text-[12px] font-bold tracking-wider uppercase text-on-surface-variant block ml-1">
                        ইমেইল ঠিকানা
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">mail</span>
                        </div>
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="example@email.com"
                            required autofocus
                            class="block w-full pl-11 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all text-[16px] @error('email') border-error @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="bengali text-[13px] text-error flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-primary-container text-on-primary py-4 rounded-lg font-bold flex items-center justify-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all">
                    <span class="bengali text-[16px]" id="btnText">চালিয়ে যান</span>
                    <span class="material-symbols-outlined" id="btnIcon">arrow_forward</span>
                </button>
            </form>

            <!-- Back to login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-primary hover:underline transition-all group">
                    <span class="material-symbols-outlined text-[20px] transition-transform group-hover:-translate-x-1">keyboard_backspace</span>
                    <span class="bengali text-[14px] font-semibold">লগইন-এ ফিরে যান</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center opacity-40">
            <p class="bengali text-[13px] text-on-surface-variant">© ২০২৪ সাজেব নিউজ | নির্ভরযোগ্য সংবাদের ঠিকানা</p>
        </div>
    </main>

    <script>
        const form = document.querySelector('form');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btnIcon.innerHTML = '<span class="animate-spin">progress_activity</span>';
            btnText.textContent = 'প্রক্রিয়াকরণ হচ্ছে...';
        });
    </script>
</body>
</html>
