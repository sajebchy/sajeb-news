<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        \Log::info('🔐 Login attempt started', ['email' => $request->input('email')]);
        
        $request->authenticate();
        
        \Log::info('✅ Authentication passed');

        $request->session()->regenerate();
        
        \Log::info('✅ Session regenerated', ['session_id' => session()->getId()]);

        $user = auth()->user();
        \Log::info('✅ User authenticated', [
            'user_id' => $user->id, 
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray()
        ]);

        // Redirect admins to admin dashboard, others to regular dashboard
        if ($user->hasRole(['super-admin', 'admin'])) {
            \Log::info('🎯 User is admin, redirecting to admin.dashboard');
            return redirect()->route('admin.dashboard');
        }

        \Log::info('🎯 User is regular user, redirecting to dashboard');
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
