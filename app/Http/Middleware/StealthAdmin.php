<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StealthAdmin
{
    /**
     * Handle an incoming request.
     * Stealth routing:
     * - If logged-in user is a student/participant, strictly return 404 Not Found
     *   to conceal the existence of the administrative portal.
     * - If guest, redirect to /itec/admin/login (isolated stealth login).
     * - If admin, proceed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Any non-admin participant trying to access /itec/admin gets a 404 Not Found
        if ($user && $user->role !== 'admin') {
            abort(404);
        }

        // If not logged in, enforce stealth admin login gateway
        if (!$user) {
            if (!$request->is('itec/admin/login')) {
                return redirect()->route('admin.login');
            }
        }

        return $next($request);
    }
}
