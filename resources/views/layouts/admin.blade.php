<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="2000">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin Panel</title>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" as="style">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #0dcaf0;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Mobile overlay for sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991px) {
            .sidebar-overlay.show {
                display: block;
            }
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }

        .sidebar-brand i {
            margin-right: 10px;
            font-size: 24px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .sidebar-menu i {
            width: 25px;
            margin-right: 10px;
            text-align: center;
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Bar */
        .topbar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .dropdown-menu {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        /* Content Area */
        .content {
            padding: 30px;
            flex: 1;
            overflow-y: auto;
        }

        /* Cards */
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card.primary {
            border-left-color: var(--primary);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.info {
            border-left-color: var(--info);
        }

        .stat-card.warning {
            border-left-color: var(--warning);
        }

        .stat-card-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .stat-card-label {
            color: #999;
            font-size: 14px;
        }

        /* Tables */
        .table-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table th {
            border: none;
            color: #333;
            font-weight: 600;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Forms */
        .form-control,
        .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        /* Alerts */
        .alert {
            border-radius: 5px;
            border: none;
        }

        /* Hamburger Menu Button */
        .hamburger-btn {
            background: none;
            border: none;
            color: #333;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            margin-right: 15px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .hamburger-btn:hover {
            color: var(--primary);
        }

        /* Responsive - Tablet (992px and below) */
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 260px;
            }

            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
                transition: transform 0.3s ease;
                top: 0;
                left: 0;
                height: 100vh;
                width: var(--sidebar-width);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                padding: 15px 20px;
                gap: 10px;
            }

            .topbar-title {
                font-size: 18px;
                flex: 1;
            }

            .hamburger-btn {
                display: flex;
                min-width: 44px;
                min-height: 44px;
            }

            .sidebar-brand {
                margin-bottom: 25px;
                padding: 15px 10px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                font-size: 18px;
            }

            .sidebar-brand i {
                font-size: 22px;
            }

            .content {
                padding: 20px;
                font-size: 14px;
            }

            .stat-card {
                padding: 15px;
                margin-bottom: 15px;
            }

            .stat-card-value {
                font-size: 24px;
            }

            .stat-card-label {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 10px;
                font-size: 13px;
            }

            .btn {
                padding: 8px 14px;
                font-size: 13px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 11px;
            }

            .form-control,
            .form-select {
                padding: 10px 12px;
                font-size: 14px;
            }

            /* Responsive table wrapper */
            .table-wrapper {
                padding: 15px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive {
                min-width: 320px;
            }
        }

        /* Responsive - Mobile (768px and below) */
        @media (max-width: 768px) {
            .topbar {
                flex-wrap: wrap;
                gap: 8px;
                padding: 12px 15px;
            }

            .topbar-title {
                font-size: 16px;
                order: 2;
                width: 100%;
                margin: 0;
            }

            .topbar-right {
                order: 1;
                gap: 8px;
                width: 100%;
                justify-content: space-between;
                align-items: center;
            }

            .hamburger-btn {
                order: 0;
                min-width: 40px;
                min-height: 40px;
                font-size: 20px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .sidebar {
                width: 80vw;
                max-width: 260px;
            }

            .sidebar-brand {
                margin-bottom: 20px;
                padding: 12px 8px;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 6px;
                font-size: 16px;
            }

            .sidebar-brand i {
                font-size: 20px;
                margin-right: 8px;
            }

            .sidebar-menu a {
                padding: 12px 15px;
                font-size: 14px;
            }

            .sidebar-menu i {
                font-size: 18px;
            }

            .content {
                padding: 15px;
            }

            .stat-card {
                padding: 12px;
                margin-bottom: 12px;
                border-radius: 6px;
            }

            .stat-card-icon {
                font-size: 28px;
            }

            .stat-card-value {
                font-size: 20px;
            }

            .stat-card-label {
                font-size: 11px;
            }

            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px;
                font-size: 12px;
            }

            .table th {
                white-space: nowrap;
            }

            .btn {
                padding: 6px 12px;
                font-size: 12px;
                min-height: 36px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 10px;
                min-height: 32px;
            }

            .form-control,
            .form-select {
                padding: 8px 10px;
                font-size: 14px;
                min-height: 40px;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .dropdown-menu {
                min-width: 160px;
                font-size: 13px;
            }

            .card {
                border-radius: 6px;
                margin-bottom: 15px;
            }

            .row {
                margin: -8px;
            }

            .col, [class*='col-'] {
                padding: 8px;
            }
        }

        /* Responsive - Small Mobile (576px and below) */
        @media (max-width: 576px) {
            body {
                font-size: 14px;
            }

            .topbar {
                flex-direction: column;
                gap: 5px;
                padding: 10px 12px;
            }

            .topbar-title {
                order: 1;
                font-size: 15px;
                width: 100%;
                text-align: center;
                margin-bottom: 8px;
            }

            .topbar-right {
                order: 0;
                width: 100%;
                gap: 5px;
                justify-content: space-between;
            }

            .hamburger-btn {
                min-width: 36px;
                min-height: 36px;
                font-size: 18px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .user-menu span {
                display: none !important;
            }

            .breadcrumb {
                font-size: 12px;
                margin-bottom: 15px;
            }

            .content {
                padding: 10px;
            }

            .stat-card {
                padding: 10px;
                margin-bottom: 10px;
                border-left-width: 3px;
            }

            .stat-card-icon {
                font-size: 24px;
                margin-bottom: 5px;
            }

            .stat-card-value {
                font-size: 18px;
            }

            .stat-card-label {
                font-size: 10px;
            }

            .table-wrapper {
                padding: 10px;
                border-radius: 6px;
            }

            .table {
                font-size: 11px;
                margin-bottom: 0;
            }

            .table th {
                padding: 6px;
                font-size: 11px;
                font-weight: 600;
            }

            .table td {
                padding: 6px;
                font-size: 11px;
                word-break: break-word;
            }

            .table tbody tr:nth-child(odd) {
                background-color: #f9f9f9;
            }

            .btn {
                padding: 5px 10px;
                font-size: 11px;
                min-height: 32px;
                white-space: nowrap;
            }

            .btn-sm {
                padding: 3px 6px;
                font-size: 9px;
                min-height: 28px;
            }

            .btn i {
                margin-right: 3px;
            }

            .form-control,
            .form-select {
                padding: 8px;
                font-size: 13px;
                min-height: 38px;
            }

            .input-group .form-control {
                min-height: 36px;
            }

            .form-label {
                font-size: 12px;
                margin-bottom: 4px;
            }

            .input-group > span {
                font-size: 12px;
            }

            .card {
                border-radius: 4px;
                margin-bottom: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .card-header {
                padding: 12px;
                font-size: 13px;
                font-weight: 600;
            }

            .card-body {
                padding: 12px;
            }

            .row {
                margin: -5px;
            }

            .col, [class*='col-'] {
                padding: 5px;
            }

            .dropdown-menu {
                min-width: 140px;
                font-size: 12px;
                padding: 5px 0;
            }

            .dropdown-item {
                padding: 8px 12px;
                font-size: 12px;
            }

            .sidebar-menu a {
                padding: 10px 12px;
                font-size: 13px;
            }

            .sidebar-menu i {
                font-size: 16px;
            }

            .sidebar-brand {
                padding: 10px 8px;
                font-size: 15px;
                margin-bottom: 15px;
            }

            .sidebar-brand i {
                font-size: 18px;
                margin-right: 6px;
            }

            /* Optimize table for mobile horizontal scroll */
            .table thead {
                font-size: 10px;
            }

            .table tbody {
                font-size: 11px;
            }

            /* Badge and badge styling */
            .badge {
                padding: 3px 6px;
                font-size: 10px;
            }

            /* Alert styling */
            .alert {
                padding: 10px 12px;
                font-size: 12px;
                margin-bottom: 12px;
            }

            /* Modal improvements for mobile */
            @media (max-height: 600px) {
                .modal-body {
                    max-height: 70vh;
                    overflow-y: auto;
                }
            }
        }

        /* Extra small devices (Below 360px) */
        @media (max-width: 360px) {
            .topbar {
                padding: 8px 10px;
            }

            .topbar-title {
                font-size: 14px;
                margin-bottom: 6px;
            }

            .content {
                padding: 8px;
            }

            .btn {
                padding: 4px 8px;
                font-size: 10px;
            }

            .table th, .table td {
                padding: 4px;
                font-size: 10px;
            }
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-item.active {
            color: #999;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-newspaper"></i>
                <span>Sajeb NEWS</span>
            </a>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="@if (request()->routeIs('admin.dashboard')) active @endif">
                        <i class="bi bi-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.news.index') }}" class="@if (request()->routeIs('admin.news.*')) active @endif">
                        <i class="bi bi-file-text"></i>
                        <span>News</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.categories.index') }}" class="@if (request()->routeIs('admin.categories.*')) active @endif">
                        <i class="bi bi-folder"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.tags.index') }}" class="@if (request()->routeIs('admin.tags.*')) active @endif">
                        <i class="bi bi-tags"></i>
                        <span>Tags</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}" class="@if (request()->routeIs('admin.users.*')) active @endif">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.analytics') }}" class="@if (request()->routeIs('admin.analytics')) active @endif">
                        <i class="bi bi-graph-up"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.activities') }}" class="@if (request()->routeIs('admin.activities')) active @endif">
                        <i class="bi bi-clock-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>

                @if (auth()->user()->hasRole(['admin', 'super-admin']))
                <li>
                    <a href="{{ route('admin.live-streams.index') }}" class="@if (request()->routeIs('admin.live-streams.*')) active @endif">
                        <i class="bi bi-camera-video"></i>
                        <span>Live Streaming</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('admin.settings') }}" class="@if (request()->routeIs('admin.settings')) active @endif">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>

                <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 0;">

                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-globe"></i>
                        <span>View Site</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="w-100 text-start" style="border: none; background: none; color: rgba(255,255,255,0.8); padding: 12px 15px; display: flex; align-items: center; cursor: pointer;">
                            <i class="bi bi-box-arrow-left" style="width: 25px; margin-right: 10px; text-align: center;"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <button class="hamburger-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                <div class="topbar-right">
                    <div class="dropdown">
                        <div class="user-menu" id="userMenuBtn">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                        <i class="bi bi-box-arrow-left"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-circle"></i> Error!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <script defer>
        // Sidebar toggle on mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Function to show/hide overlay
        function updateSidebarOverlay() {
            if (sidebar.classList.contains('show')) {
                sidebarOverlay.classList.add('show');
            } else {
                sidebarOverlay.classList.remove('show');
            }
        }

        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            updateSidebarOverlay();
        });

        // Close sidebar when clicking on overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.remove('show');
                updateSidebarOverlay();
            });
        }

        // Close sidebar when clicking outside of it
        document.addEventListener('click', function(e) {
            const isClickInsideSidebar = sidebar.contains(e.target);
            const isClickOnToggle = sidebarToggle.contains(e.target);

            if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                updateSidebarOverlay();
            }
        });

        // Close sidebar when clicking on a menu link
        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Only close on mobile screens
                if (window.innerWidth <= 991) {
                    sidebar.classList.remove('show');
                    updateSidebarOverlay();
                }
            });
        });

        // Close sidebar when window is resized to larger screen
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                sidebar.classList.remove('show');
                updateSidebarOverlay();
            }
        });

        // User menu dropdown
        document.getElementById('userMenuBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            menu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userMenuBtn');
            const dropdown = userMenu.nextElementSibling;
            if (!userMenu.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Prevent page scroll when sidebar is open on mobile
        let scrollLock = false;
        const updateScrollLock = () => {
            if (window.innerWidth <= 991 && sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
                scrollLock = true;
            } else {
                document.body.style.overflow = '';
                scrollLock = false;
            }
        };

        sidebar.addEventListener('transitionend', updateScrollLock);
        window.addEventListener('resize', updateScrollLock);
    </script>

    @stack('scripts')
</body>
</html>
