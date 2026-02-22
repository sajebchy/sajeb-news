<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-lg d-flex align-items-center">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
            <x-application-logo />
        </a>

        <!-- Live TV Button (Desktop) - Always visible -->
        @php
            $activeLiveStream = \App\Models\LiveStream::where('status', 'active')
                ->where('start_time', '<=', now())
                ->where(function($query) {
                    $query->whereNull('end_time')
                          ->orWhere('end_time', '>', now());
                })
                ->first();
        @endphp
        
        <!-- Desktop Live TV Button -->
        <div class="d-none d-lg-block ms-3" style="flex-shrink: 0;">
            <a href="{{ $activeLiveStream ? route('live.watch', $activeLiveStream->slug) : route('live.index') }}" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                লাইভ টিভি
            </a>
        </div>

        <!-- Mobile Live TV Button -->
        <div class="d-lg-none ms-2" style="flex-shrink: 0;">
            <a href="{{ $activeLiveStream ? route('live.watch', $activeLiveStream->slug) : route('live.index') }}" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.7rem; height: 28px; width: 50px; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                LIVE
            </a>
        </div>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="flex-shrink: 0;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Navigation Links -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
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
    /* Live TV Button Container */
    .navbar > .container-lg {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }

    /* Live TV Button Styling */
    .navbar .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        border: none !important;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        white-space: nowrap;
        color: white !important;
        text-decoration: none !important;
    }

    .navbar .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        background: linear-gradient(135deg, #c82333 0%, #b01e2a 100%) !important;
        color: white !important;
        text-decoration: none;
    }

    .navbar .btn-danger:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        color: white !important;
    }

    /* Animate live dot */
    .navbar .btn-danger i {
        animation: pulse-dot 1.5s infinite;
        display: inline-block;
    }

    @keyframes pulse-dot {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    /* Desktop button */
    @media (min-width: 992px) {
        .navbar .btn-danger {
            padding: 0.5rem 1rem !important;
            font-size: 0.9rem;
        }
    }

    /* Mobile button */
    @media (max-width: 991.98px) {
        .navbar .btn-danger {
            width: 50px !important;
            height: 28px !important;
            padding: 0 !important;
            font-size: 0.7rem;
            border-radius: 4px;
            min-width: auto !important;
        }
    }

    /* Ensure navbar brand doesn't conflict */
    .navbar-brand {
        margin-right: auto;
    }

    /* Navbar collapse should work properly */
    .navbar-collapse {
        flex-basis: 100%;
        flex-grow: 1;
    }
</style>