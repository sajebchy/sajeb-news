<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Note: The login/register endpoints are protected by CSRF but handled through
     * the encrypted cookie-based session system (updated Feb 25, 2026).
     * 
     * The 419 error was fixed by:
     * - Switching to cookie-based sessions for reliability
     * - Enabling session encryption for security
     * - Setting SESSION_SAME_SITE=lax for proper CSRF handling
     *
     * @var array<int, string>
     */
    protected $except = [
        'test-simple-login',
        'api/*',
    ];
}

