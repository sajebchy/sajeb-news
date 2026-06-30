<section>
    <p class="text-muted mb-4">
        <i class="bi bi-info-circle"></i> Ensure your account is using a long, random password to stay secure.
    </p>

    <form method="post" action="{{ route('password.update') }}" class="needs-validation">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                <i class="bi bi-lock"></i> Current Password
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                autocomplete="current-password"
                placeholder="Enter your current password"
                required
            />
            @error('current_password', 'updatePassword')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                <i class="bi bi-key"></i> New Password
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                autocomplete="new-password"
                placeholder="Enter a new password"
                required
            />
            @error('password', 'updatePassword')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">
                <i class="bi bi-check-circle"></i> Confirm Password
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                autocomplete="new-password"
                placeholder="Confirm your new password"
                required
            />
            @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 align-items-center mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Update Password
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle-fill"></i> Password updated successfully!
                </div>
            @endif
        </div>
    </form>
</section>
