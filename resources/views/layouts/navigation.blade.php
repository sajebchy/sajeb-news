<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-lg">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
            <x-application-logo />
        </a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            @php
                $activeLiveStream = \App\Models\LiveStream::where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where(function($query) {
                        $query->whereNull('end_time')
                              ->orWhere('end_time', '>', now());
                    })
                    ->first();
            @endphp
            
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('manage_news'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i> {{ __('Dashboard') }}
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('manage_news'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="newsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-newspaper"></i> News
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="newsDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.news.index') }}">All News</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.news.create') }}">Add News</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Categories</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tags.index') }}">Tags</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::user()->hasRole('admin'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear"></i> Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.activities') }}">Activities</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings') }}">Settings</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.analytics') }}">Analytics</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth

                <!-- Live TV Button -->
                <li class="nav-item ms-2">
                    <a href="{{ $activeLiveStream ? route('live.watch', $activeLiveStream->slug) : route('live.index') }}" class="live-tv-btn">
                        <span class="live-dot"></span>
                        <span>লাইভ টিভি</span>
                    </a>
                </li>
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('Log in') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
</div>
</nav>

<style>
    /* Live TV Button Styling */
    .navbar .live-tv-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem !important;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        color: white !important;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        white-space: nowrap;
        margin-left: 0.5rem;
    }

    .navbar .live-tv-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        background: linear-gradient(135deg, #c82333 0%, #b01e2a 100%) !important;
        color: white !important;
        text-decoration: none;
    }

    .navbar .live-tv-btn:active {
        transform: translateY(0);
        color: white !important;
    }

    /* Live dot animation */
    .navbar .live-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: pulse-live 1.5s infinite;
    }

    @keyframes pulse-live {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .navbar .live-tv-btn {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.85rem;
        }

        .navbar .live-tv-btn span:last-child {
            display: none;
        }

        .navbar .live-tv-btn {
            width: 48px;
            justify-content: center;
            text-align: center;
        }

        .navbar .live-tv-btn::after {
            content: 'LIVE';
            display: inline-block;
            font-size: 0.7rem;
            font-weight: bold;
        }
    }
</style>