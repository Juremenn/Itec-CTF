<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScoreboardController extends Controller
{
    public function index(Request $request): View
    {
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $tab = $request->query('tab', $activeMode === 'competition' ? 'competition' : 'practice');

        // Ensure valid tab
        if (!in_array($tab, ['competition', 'practice'], true)) {
            $tab = 'competition';
        }

        // 1. Competition Leaderboard Query
        // Strictly filters out administrative accounts and sums points only for competition solves
        $competitionUsers = User::where('role', '!=', 'admin')
            ->withSum(['solves as total_points' => function ($query) {
                $query->where('mode', 'competition')
                    ->join('challenges', 'solves.challenge_id', '=', 'challenges.id')
                    ->selectRaw('COALESCE(SUM(challenges.points), 0)');
            }], 'challenges.points')
            ->withCount(['solves as solved_count' => function ($query) {
                $query->where('mode', 'competition');
            }])
            ->with(['solves' => function ($q) {
                $q->where('mode', 'competition')->with('challenge')->latest('solved_at');
            }])
            ->get()
            ->sortByDesc(function ($user) {
                // Secondary sort: earliest last solve for tie-break
                $lastSolve = $user->solves->first()?->solved_at?->timestamp ?? PHP_INT_MAX;
                return [
                    $user->total_points ?? 0,
                    -1 * $lastSolve,
                ];
            })
            ->values();

        // 2. All-Time / Practice Board Query
        // Tracks cumulative progress across all modes (competition + training) across all categories
        $practiceUsers = User::where('role', '!=', 'admin')
            ->withSum(['solves as total_points' => function ($query) {
                $query->join('challenges', 'solves.challenge_id', '=', 'challenges.id')
                    ->selectRaw('COALESCE(SUM(challenges.points), 0)');
            }], 'challenges.points')
            ->withCount('solves as solved_count')
            ->with(['solves' => function ($q) {
                $q->with('challenge')->latest('solved_at');
            }])
            ->get()
            ->sortByDesc(function ($user) {
                $lastSolve = $user->solves->first()?->solved_at?->timestamp ?? PHP_INT_MAX;
                return [
                    $user->total_points ?? 0,
                    -1 * $lastSolve,
                ];
            })
            ->values();

        return view('scoreboard.index', compact(
            'competitionUsers',
            'practiceUsers',
            'tab',
            'activeMode',
            'competitionStatus'
        ));
    }
}

