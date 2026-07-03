<!DOCTYPE html>
<html class="light" lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন — সজীব নিউজ</title>
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
            "tertiary": "#005e2c",
            "on-tertiary": "#ffffff",
            "error": "#ba1a1a",
            "on-error": "#ffffff",
            "error-container": "#ffdad6",
            "on-error-container": "#93000a",
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
          fontSize: {
            "body-sm": ["14px", { lineHeight: "1.6", fontWeight: "400" }],
            "body-lg": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
            "label-bold": ["12px", { lineHeight: "1", letterSpacing: "0.05em", fontWeight: "700" }],
            "display-h1-mobile": ["24px", { lineHeight: "1.2", fontWeight: "700" }],
            "display-h2-mobile": ["20px", { lineHeight: "1.3", fontWeight: "700" }],
          }
        }
      }
    }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .input-focus-ring:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.1);
        }
        body {
            min-height: max(884px, 100dvh);
            background-color: #fcf9f8;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

<main class="flex-grow flex flex-col items-center justify-center px-4 py-16 md:py-32">
    <div class="w-full max-w-sm">
        {{-- Branding --}}
        @php $__loginSeo = \App\Models\SeoSetting::first(); @endphp
        <div class="text-center mb-12">
            @if($__loginSeo?->logo)
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/' . $__loginSeo->logo) }}" alt="{{ $__loginSeo->site_name ?? 'সজীব নিউজ' }}" class="h-16 md:h-20 object-contain mx-auto">
                </a>
            @else
                <h1 class="font-display text-[24px] md:text-[36px] font-bold text-primary tracking-tight">
                    সজীব নিউজ
                </h1>
            @endif
        </div>

        {{-- Login Card --}}
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant shadow-sm">
            <h2 class="font-display text-[20px] font-bold text-on-surface mb-6 text-center">লগইন করুন</h2>

            @if ($errors->any())
            <div class="mb-4 p-3 bg-error-container border border-error rounded-lg">
                <p class="font-body-sm text-on-error-container">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="space-y-1">
                    <label for="email" class="font-label-bold text-label-bold text-on-surface-variant ml-1">
                        ইমেইল বা ফোন নম্বর
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                        <input type="email" name="email" id="email" required autofocus
                               class="w-full h-14 pl-12 pr-4 bg-surface border border-outline-variant rounded-lg font-body-lg text-body-lg input-focus-ring transition-all"
                               placeholder="example@mail.com" value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-1">
                    <div class="flex justify-between items-center px-1 mb-1">
                        <label for="password" class="font-label-bold text-label-bold text-on-surface-variant">
                            পাসওয়ার্ড
                        </label>
                        <a href="{{ route('password.request') }}" class="font-label-bold text-label-bold text-primary hover:underline">
                            ভুলে গেছেন?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                        <input type="password" name="password" id="password" required
                               class="w-full h-14 pl-12 pr-12 bg-surface border border-outline-variant rounded-lg font-body-lg text-body-lg input-focus-ring transition-all"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword(this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary active:scale-90">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Login Button --}}
                <button type="submit"
                        class="w-full h-14 bg-primary-container text-on-primary-container font-display text-body-lg rounded-lg shadow-sm active:scale-95 transition-transform mt-6 font-bold hover:opacity-90">
                    লগইন করুন
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative flex items-center py-6">
                <div class="flex-grow border-t border-outline-variant"></div>
                <span class="flex-shrink mx-4 text-on-surface-variant font-label-bold text-label-bold">অথবা</span>
                <div class="flex-grow border-t border-outline-variant"></div>
            </div>

            {{-- Social Login --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('auth.facebook') }}"
                   class="flex items-center justify-center gap-2 h-12 border border-outline-variant rounded-lg bg-surface hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] transition-all">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="text-[13px] font-semibold">Facebook</span>
                </a>
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-2 h-12 border border-outline-variant rounded-lg bg-surface hover:bg-surface-container transition-all">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    <span class="text-[13px] font-semibold">Google</span>
                </a>
            </div>
        </div>

        {{-- Sign Up Link --}}
        <div class="mt-6 text-center">
            <p class="font-body-sm text-on-surface-variant">
                অ্যাকাউন্ট নেই?
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">
                    নতুন অ্যাকাউন্ট তৈরি করুন
                </a>
            </p>
        </div>
    </div>
</main>

{{-- Footer --}}
<footer class="w-full py-4 px-4 border-t border-outline-variant bg-surface-container-low text-center">
    <p class="font-body-sm text-outline-variant">
        © ২০২৪ সজীব নিউজ। সর্বস্বত্ব সংরক্ষিত।
    </p>
</footer>

<script>
    function togglePassword(btn) {
        const input = document.getElementById('password');
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
