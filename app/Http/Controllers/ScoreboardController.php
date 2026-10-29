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
        '#f97316', // Orange
        '#22c55e', // Emerald
        '#38bdf8', // Cyan
        '#eab308', // Amber
        '#a855f7', // Purple
        '#ec4899', // Pink
        '#14b8a6', // Teal
        '#6366f1', // Indigo
        '#f43f5e', // Rose
        '#94a3b8', // Slate
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
        
        $datasets = [];

        foreach ($topUsers as $index => $u) {
            $color = $this->colorPalette[$index % count($this->colorPalette)];
            $points = [];
            $accum = 0;

            $solves = $u->solves;

            if ($solves->isNotEmpty()) {
                $firstTimestamp = ($solves->first()->solved_at->timestamp - 60) * 1000;
                $points[] = ['x' => $firstTimestamp, 'y' => 0];

                foreach ($solves as $s) {
                    $accum += ($s->challenge?->points ?? 0);
                    $points[] = [
                        'x' => $s->solved_at->timestamp * 1000,
                        'y' => $accum,
                    ];
                }

                // Current plateau
                $points[] = [
                    'x' => now()->timestamp * 1000,
                    'y' => $accum,
                ];

                $datasets[] = [
                    'label' => $u->name,
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'data' => $points,
                    'tension' => 0.2,
                    'borderWidth' => 2,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
                ];
            }
        }

        return [
            'hasRealData' => !empty($datasets),
            'datasets' => $datasets,
        ];
    }
}
