<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has super-admin role only
        if (!auth()->check() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'এই বৈশিষ্ট্য শুধুমাত্র সুপার এডমিনদের জন্য উপলব্ধ। (Only super-admin can access this feature.)');
        }

        return $next($request);
    }
}
