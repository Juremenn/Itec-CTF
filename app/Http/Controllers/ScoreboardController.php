<?php

namespace App\Http\Controllers;

use App\Models\Solve;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScoreboardController extends Controller
{
    /**
     * Palette of distinct colors for timeline graphs.
     */
    private array $colorPalette = [
        '#f59e0b', // Amber / Orange (Standout primary line)
        '#38bdf8', // Sky Cyan
        '#10b981', // Emerald
        '#818cf8', // Indigo
        '#ec4899', // Pink
        '#94a3b8', // Slate
        '#14b8a6', // Teal
        '#a78bfa', // Purple
        '#fb7185', // Rose
        '#64748b', // Deep Slate
    ];

    public function index(Request $request): View
    {
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $tab = $request->query('tab', $activeMode === 'competition' ? 'competition' : 'practice');

        if (!in_array($tab, ['competition', 'practice'], true)) {
            $tab = 'competition';
        }

        $competitionUsers = $this->getRankedUsers('competition');
        $practiceUsers = $this->getRankedUsers('practice');

        $activeUsers = ($tab === 'competition') ? $competitionUsers : $practiceUsers;
        $chartData = $this->buildTimelineChartData($activeUsers, $tab);

        return view('scoreboard.index', compact(
            'competitionUsers',
            'practiceUsers',
            'tab',
            'activeMode',
            'competitionStatus',
            'chartData'
        ));
    }

    /**
     * Endpoint API JSON for Live Scoreboard silent real-time refresh.
     */
    public function liveData(Request $request): JsonResponse
    {
        $tab = $request->query('tab', 'competition');
        if (!in_array($tab, ['competition', 'practice'], true)) {
            $tab = 'competition';
        }

        $rankedUsers = $this->getRankedUsers($tab);
        $chartData = $this->buildTimelineChartData($rankedUsers, $tab);

        $usersPayload = $rankedUsers->map(function ($u, $idx) {
            return [
                'rank' => $idx + 1,
                'id' => $u->id,
                'name' => $u->name,
                'username' => $u->username,
                'solved_count' => $u->solved_count ?? 0,
                'total_points' => (int) ($u->total_points ?? 0),
            ];
        });

        return response()->json([
            'status' => 'success',
            'tab' => $tab,
            'competition_status' => SystemSetting::competitionStatus(),
            'users' => $usersPayload,
            'chart' => $chartData,
        ]);
    }

    /**
     * Query ranked participants (strictly excluding admins).
     */
    private function getRankedUsers(string $mode)
    {
        $query = User::where('role', '!=', 'admin');

        $query->withSum(['solves as total_points' => function ($q) {
            $q->join('challenges', 'solves.challenge_id', '=', 'challenges.id')
                ->selectRaw('COALESCE(SUM(challenges.points), 0)');
        }], 'challenges.points')
        ->withCount('solves as solved_count')
        ->with(['solves' => function ($q) {
            $q->with('challenge')->orderBy('solved_at', 'asc');
        }]);

        return $query->get()->sortByDesc(function ($user) {
            $lastSolve = $user->solves->last()?->solved_at?->timestamp ?? PHP_INT_MAX;
            return [
                (int) ($user->total_points ?? 0),
                -1 * $lastSolve,
            ];
        })->values();
    }

    /**
     * Build score progression chart datasets strictly from real database solves.
     */
    private function buildTimelineChartData($users, string $mode): array
    {
        $topUsers = $users->filter(fn($u) => ($u->total_points ?? 0) > 0)->take(10)->values();
        
        if ($topUsers->isEmpty()) {
            return [
                'hasRealData' => false,
                'datasets' => [],
            ];
        }

        // 1. Gather all actual solve timestamps to calculate a realistic, uncompressed window
        $allSolveTimestamps = [];
        foreach ($topUsers as $u) {
            foreach ($u->solves as $s) {
                if ($s->solved_at) {
                    $allSolveTimestamps[] = $s->solved_at->timestamp;
                }
            }
        }

        if (empty($allSolveTimestamps)) {
            return [
                'hasRealData' => false,
                'datasets' => [],
            ];
        }

        $minTime = min($allSolveTimestamps);
        $maxTime = max($allSolveTimestamps);

        // Dynamic boundaries:
        // If last solve happened over 2 hours ago, do not stretch graph across idle days (which squashes real activity into a sliver!)
        // Instead, give 15-30 minutes of breathing room on each side
        $isLiveRecent = (now()->timestamp - $maxTime) < 3600 && SystemSetting::competitionStatus() === 'active';
        $startTime = ($minTime - 900) * 1000; // 15 min before first solve
        $endTime = $isLiveRecent ? (now()->timestamp * 1000) : (($maxTime + 1800) * 1000); // 30 min after last solve

        if ($endTime <= $startTime) {
            $endTime = $startTime + (1800 * 1000);
        }

        $datasets = [];

        foreach ($topUsers as $index => $u) {
            $color = $this->colorPalette[$index % count($this->colorPalette)];
            $points = [];
            $pointRadii = [];
            $pointHoverRadii = [];
            $accum = 0;

            $solves = $u->solves;

            if ($solves->isNotEmpty()) {
                // Baseline origin point (radius 0 so it stays clean at the wall)
                $points[] = [
                    'x' => $startTime,
                    'y' => 0,
                ];
                $pointRadii[] = 0;
                $pointHoverRadii[] = 0;

                foreach ($solves as $s) {
                    $accum += ($s->challenge?->points ?? 0);
                    $points[] = [
                        'x' => $s->solved_at->timestamp * 1000,
                        'y' => $accum,
                    ];
                    $pointRadii[] = 4.5;
                    $pointHoverRadii[] = 7;
                }

                // Final plateau at timeline boundary (radius 0 so it finishes cleanly)
                $points[] = [
                    'x' => $endTime,
                    'y' => $accum,
                ];
                $pointRadii[] = 0;
                $pointHoverRadii[] = 0;

                // Curated visibility: Top 5 visible by default to eliminate spaghetti overlap
                $isVisible = ($index < 5);

                $datasets[] = [
                    'label' => $u->name,
                    'username' => $u->username,
                    'rank' => $index + 1,
                    'totalPoints' => (int) $accum,
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'data' => $points,
                    'hidden' => !$isVisible,
                    'tension' => 0.35,
                    'borderWidth' => ($index === 0) ? 3 : 2.2,
                    'pointRadius' => $pointRadii,
                    'pointHoverRadius' => $pointHoverRadii,
                    'pointBackgroundColor' => '#0c0e14',
                    'pointBorderColor' => $color,
                    'pointBorderWidth' => 2.5,
                ];
            }
        }

        return [
            'hasRealData' => !empty($datasets),
            'datasets' => $datasets,
            'timeBounds' => [
                'min' => $startTime,
                'max' => $endTime,
            ],
        ];
    }
}
