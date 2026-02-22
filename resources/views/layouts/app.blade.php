<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Canonical URL -->
        <link rel="canonical" href="@yield('canonical', url()->current())">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">

        <!-- Fonts - Optimized for fast loading -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- DNS Prefetch for CDN -->
        <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
        
        <!-- Preload Critical Resources with higher priority -->
        <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" crossorigin>
        <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" as="style" crossorigin>

        <!-- Bootstrap CSS - Load async to prevent blocking -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'" crossorigin>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" media="print" onload="this.media='all'" crossorigin>
        <noscript>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" crossorigin>
        </noscript>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="d-flex flex-column min-vh-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm py-3 py-md-4">
                    <div class="container-lg">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow-1 py-4">
                <div class="container-lg">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-dark text-white text-center py-4 mt-5">
                <div class="container-lg">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                </div>
            </footer>
        </div>

        <!-- Bootstrap Bundle JS (includes Popper) - Load asynchronously -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer crossorigin></script>
        
        <!-- Performance optimization: Remove auto-refresh during initial load time -->
        <script>
            // Only auto-refresh after 30 seconds to allow initial page render without interruption
            setTimeout(function() {
                // Uncomment below if auto-refresh is actually needed
                // location.reload();
            }, 30000);
        </script>
    </body>
</html>
