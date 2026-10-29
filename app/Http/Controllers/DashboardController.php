<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $broadcast = SystemSetting::get('broadcast_message', '');

        // Fetch challenges matching current mode filter
        $query = Challenge::where('is_active', true);
        if ($activeMode === 'competition') {
            $query->whereIn('mode_target', ['competition', 'both']);
        }

        $challenges = $query->orderBy('category')->orderBy('points')->get();
        $totalChallenges = $challenges->count();

        // User solve statistics
        $userSolves = Solve::where('user_id', $user->id)
            ->with('challenge')
            ->get();

        $solvedChallengeIds = $userSolves->pluck('challenge_id')->toArray();
        $solvedCount = count($solvedChallengeIds);

        // Calculate total points across all solved challenges
        $userPoints = $userSolves->sum(fn($s) => $s->challenge?->points ?? 0);

        $completionRate = $totalChallenges > 0 ? round(($solvedCount / $totalChallenges) * 100) : 0;

        // Category stats
        $categories = [
            'web' => [
                'name' => 'Web Exploitation',
                'icon' => 'globe',
                'total' => $challenges->where('category', 'web')->count(),
                'solved' => $challenges->where('category', 'web')->whereIn('id', $solvedChallengeIds)->count(),
            ],
            'crypto' => [
                'name' => 'Cryptography',
                'icon' => 'key',
                'total' => $challenges->where('category', 'crypto')->count(),
                'solved' => $challenges->where('category', 'crypto')->whereIn('id', $solvedChallengeIds)->count(),
            ],
            'recon' => [
                'name' => 'Reconnaissance',
                'icon' => 'search',
                'total' => $challenges->where('category', 'recon')->count(),
                'solved' => $challenges->where('category', 'recon')->whereIn('id', $solvedChallengeIds)->count(),
            ],
        ];

        // Recent solves across participants (excluding admins)
        $recentSolves = Solve::whereHas('user', function ($q) {
                $q->where('role', '!=', 'admin');
            })
            ->with(['user', 'challenge'])
            ->latest('solved_at')
            ->limit(7)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'challenges',
            'userSolves',
            'solvedChallengeIds',
            'solvedCount',
            'userPoints',
            'totalChallenges',
            'completionRate',
            'categories',
            'recentSolves',
            'activeMode',
            'competitionStatus',
            'broadcast'
        ));
    }
}

