<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompetitionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $competitionStatus = SystemSetting::competitionStatus();
        $user = $request->user();

        // Admin can always bypass for testing and administrative previews
        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        // When competition status is NOT active (i.e. paused, upcoming, ended):
        // All flag submissions and interactive lab access are hard-locked
        if ($competitionStatus !== 'active') {
            $statusLabel = match ($competitionStatus) {
                'upcoming' => 'belum dimulai',
                'paused'   => 'sedang dijeda oleh instruktur',
                'ended'    => 'telah berakhir',
                default    => 'sedang dikunci',
            };

            $message = "Akses pengerjaan tantangan dan pengiriman flag dikunci karena status kompetisi {$statusLabel}.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            if ($request->isMethod('post')) {
                return back()->with('error', $message);
            }

            return redirect()->route('challenges.index')->with('error', $message);
        }

        return $next($request);
    }
}

