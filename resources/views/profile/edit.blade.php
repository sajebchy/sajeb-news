@extends('public.layout')

@section('title', 'My Profile - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Navigation (Desktop) -->
        <div class="col-lg-3 d-none d-lg-block mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-circle"></i> Profile Menu
                    </h5>
                    <nav class="nav flex-column">
                        <a class="nav-link ps-0 active" href="#profile-info" data-bs-toggle="tab">
                            <i class="bi bi-person"></i> Profile Information
                        </a>
                        <a class="nav-link ps-0" href="#password-form" data-bs-toggle="tab">
                            <i class="bi bi-key"></i> Change Password
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                        <a class="nav-link ps-0 text-danger" href="#delete-account" data-bs-toggle="tab">
                            <i class="bi bi-trash"></i> Delete Account
                        </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Mobile Tabs (Mobile View) -->
            <div class="d-lg-none mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-info" type="button">
                            <i class="bi bi-person"></i> Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-form" type="button">
                            <i class="bi bi-key"></i> Password
                        </button>
                    </li>
                    @if(auth()->user()->hasRole('admin'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete-account" type="button">
                            <i class="bi bi-trash text-danger"></i> Delete
                        </button>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="tab-content">
                <!-- Profile Information Tab -->
                <div class="tab-pane fade show active" id="profile-info" role="tabpanel">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-fill"></i> Profile Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Password Change Tab -->
                <div class="tab-pane fade" id="password-form" role="tabpanel">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-key-fill"></i> Change Password
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Delete Account Tab (Admin Only) -->
                @if(auth()->user()->hasRole('admin'))
                <div class="tab-pane fade" id="delete-account" role="tabpanel">
                    <div class="card shadow-sm border-danger mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle-fill"></i> Delete Account
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        border-radius: 8px 8px 0 0;
        padding: 1.25rem;
        font-weight: 600;
    }

    .nav-link {
        color: #6c757d;
        border-radius: 5px;
        padding: 10px 12px !important;
        transition: all 0.3s ease;
        margin-bottom: 5px;
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .nav-link.active {
        background-color: #0d6efd;
        color: white !important;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        color: #6c757d;
        padding-bottom: 10px;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #0d6efd;
        background-color: transparent;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background-color: transparent;
    }

    /* Form styling */
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 10px 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
            font-size: 14px;
        }

        .card-body {
            padding: 1rem;
        }

        .form-control,
        .form-select {
            font-size: 14px;
            min-height: 38px;
        }

        .nav-link {
            padding: 8px 10px !important;
            font-size: 13px;
        }
    }
</style>
@endsection
