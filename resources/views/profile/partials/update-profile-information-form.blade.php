<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="needs-validation">
        @csrf
        @method('patch')

        <!-- Profile Avatar Section -->
        <div class="mb-4">
            <label class="form-label">
                <i class="bi bi-image"></i> Profile Picture
            </label>
            <div class="row align-items-center">
                <div class="col-auto mb-3 mb-md-0">
                    <div class="avatar-preview" style="width: 100px; height: 100px; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <input 
                        id="avatar" 
                        name="avatar" 
                        type="file" 
                        class="form-control @error('avatar') is-invalid @enderror" 
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                    />
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> Max file size: 2MB. Formats: JPG, PNG, GIF
                    </small>
                    @error('avatar')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="bi bi-person"></i> Full Name
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Enter your full name"
            />
            @error('name')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope"></i> Email Address
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
                placeholder="Enter your email"
            />
            @error('email')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror

            <!-- Email Verification Status -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Email Not Verified!</strong>
                    <p class="mb-0 mt-2">Your email address is unverified. 
                        <button type="button" form="send-verification" class="btn btn-link p-0">
                            Click here to resend verification email.
                        </button>
                    </p>
                    
                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0">
                            <i class="bi bi-check-circle"></i> A new verification link has been sent to your email address.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Bio Field -->
        <div class="mb-3">
            <label for="bio" class="form-label">
                <i class="bi bi-pencil-square"></i> Bio (Optional)
            </label>
            <textarea 
                id="bio" 
                name="bio" 
                class="form-control @error('bio') is-invalid @enderror" 
                rows="3"
                placeholder="Write a short bio about yourself..."
            >{{ old('bio', $user->bio) }}</textarea>
            <small class="text-muted d-block mt-1">Max 500 characters</small>
            @error('bio')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Phone Field -->
        <div class="mb-3">
            <label for="phone" class="form-label">
                <i class="bi bi-telephone"></i> Phone Number (Optional)
            </label>
            <input 
                id="phone" 
                name="phone" 
                type="tel" 
                class="form-control @error('phone') is-invalid @enderror" 
                value="{{ old('phone', $user->phone) }}"
                placeholder="Enter your phone number"
            />
            @error('phone')
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 align-items-center mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle-fill"></i> Profile updated successfully!
                </div>
            @endif
        </div>
    </form>

    <style>
        .avatar-preview {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #avatar {
            cursor: pointer;
        }

        #avatar:focus + .avatar-preview,
        #avatar:hover + .avatar-preview {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</section>
