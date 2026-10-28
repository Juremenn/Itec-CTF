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
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $user = $request->user();

        // If platform is in competition mode:
        if ($activeMode === 'competition') {
            // Admin can bypass for testing
            if ($user && $user->isAdmin()) {
                return $next($request);
            }

            // For students/participants, check if competition is strictly active
            if ($competitionStatus !== 'active') {
                $statusLabel = match ($competitionStatus) {
                    'upcoming' => 'has not started yet',
                    'paused' => 'is currently paused',
                    'ended' => 'has concluded',
                    default => 'is currently locked',
                };

                $message = "Competition flag submissions are locked because the competition {$statusLabel}.";

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                    ], 403);
                }

                return back()->with('error', $message);
            }
        }

        return $next($request);
    }
}

