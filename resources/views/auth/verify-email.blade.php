<x-guest-layout>
    <h4 class="mb-4 text-center fw-bold">Verify Email</h4>

    <div class="mb-4 text-muted small">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mt-4 d-flex align-items-center justify-content-between gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="btn btn-primary">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-link text-decoration-none text-muted">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
