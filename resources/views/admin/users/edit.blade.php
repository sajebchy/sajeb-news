@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12 col-lg-10 offset-lg-1">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-content" type="button" role="tab">
                    <i class="bi bi-person"></i> User Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-content" type="button" role="tab">
                    <i class="bi bi-key"></i> Change Password
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile-content" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit User Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="bi bi-person"></i> Full Name *
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope"></i> Email Address *
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone"></i> Phone Number
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">
                                    <i class="bi bi-pencil"></i> Bio
                                </label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-shield-check"></i> Roles & Permissions *
                                </label>
                                <div class="card border">
                                    <div class="card-body">
                                        @forelse ($roles as $role)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}" @checked($user->hasRole($role->name))>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    <strong>{{ ucfirst($role->name) }}</strong>
                                                    <small class="text-muted d-block" style="margin-left: 1.5rem;">
                                                        @if($role->name === 'admin')
                                                            Full access to admin panel
                                                        @elseif($role->name === 'editor')
                                                            Can create, edit and publish news
                                                        @elseif($role->name === 'author')
                                                            Can create and submit news for review
                                                        @elseif($role->name === 'contributor')
                                                            Can only view and comment on news
                                                        @endif
                                                    </small>
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-muted">No roles available</p>
                                        @endforelse
                                    </div>
                                </div>
                                @error('roles')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                                    <label class="form-check-label" for="is_active">
                                        <i class="bi bi-check-circle"></i> User is Active
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">Inactive users cannot login to the system</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Tab -->
            <div class="tab-pane fade" id="password-content" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-key-fill"></i> Change User Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> Use this form to reset or change the password for this user. The user will need to use the new password on their next login.
                        </div>

                        <form method="POST" action="{{ route('admin.users.change-password', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> New Password *
                                </label>
                                <input type="password" class="form-control @error('password', 'changePassword') is-invalid @enderror" id="password" name="password" required placeholder="Enter new password">
                                <small class="text-muted d-block mt-1">Minimum 8 characters recommended</small>
                                @error('password', 'changePassword')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-check-circle"></i> Confirm Password *
                                </label>
                                <input type="password" class="form-control @error('password_confirmation', 'changePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required placeholder="Confirm new password">
                                @error('password_confirmation', 'changePassword')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Warning:</strong> Changing this password will log the user out of all active sessions.
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-check-circle"></i> Change Password
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                                </button>
                            </div>

                            @if (session('status') === 'password-changed')
                                <div class="alert alert-success mt-3">
                                    <i class="bi bi-check-circle-fill"></i> Password changed successfully!
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border-bottom: 3px solid transparent;
        margin-bottom: -3px;
        font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #0d6efd;
        color: #0d6efd;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background-color: transparent;
    }

    .card {
        border: none;
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    @media (max-width: 768px) {
        .nav-tabs {
            flex-wrap: wrap;
        }

        .nav-link {
            font-size: 13px;
        }
    }
</style>
@endsection
