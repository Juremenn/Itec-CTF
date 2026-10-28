<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $selectedCategory = $request->query('category', 'all');

        $query = Challenge::where('is_active', true);

        if ($activeMode === 'competition') {
            $query->whereIn('mode_target', ['competition', 'both']);
        }

        if ($selectedCategory !== 'all') {
            $query->where('category', $selectedCategory);
        }

        $challenges = $query->orderBy('category')->orderBy('points')->get();
        $userSolves = Solve::where('user_id', $user->id)->pluck('challenge_id')->toArray();

        return view('challenges.index', compact(
            'challenges',
            'userSolves',
            'activeMode',
            'competitionStatus',
            'selectedCategory'
        ));
    }

    public function show(string $slug): View
    {
        $challenge = Challenge::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = Auth::user();
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $isSolved = $challenge->isSolvedBy($user);
        $solveRecord = $challenge->solveFor($user);

        // Submissions locked state for competition mode
        $isSubmissionLocked = false;
        $lockReason = '';
        if ($activeMode === 'competition' && !$user->isAdmin()) {
            if ($competitionStatus !== 'active') {
                $isSubmissionLocked = true;
                $lockReason = match ($competitionStatus) {
                    'upcoming' => 'Competition has not started yet. Flag submissions are disabled.',
                    'paused'   => 'Competition is currently paused by the instructor. Flag submissions are locked.',
                    'ended'    => 'Competition has concluded. Flag submissions are permanently closed.',
                    default    => 'Competition mode is inactive. Flag submissions are locked.',
                };
            }
        }

        // Leaderboard preview snippet for challenge sidebar
        $topSolvers = Solve::where('challenge_id', $challenge->id)
            ->whereHas('user', fn($q) => $q->where('role', '!=', 'admin'))
            ->with('user')
            ->orderBy('solved_at')
            ->limit(5)
            ->get();

        return view('challenges.show', compact(
            'challenge',
            'isSolved',
            'solveRecord',
            'activeMode',
            'competitionStatus',
            'isSubmissionLocked',
            'lockReason',
            'topSolvers'
        ));
    }

    public function submitFlag(Request $request, int $id): RedirectResponse
    {
        $challenge = Challenge::findOrFail($id);
        $user = Auth::user();
        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();

        $request->validate([
            'flag' => ['required', 'string'],
        ]);

        $submittedFlag = trim($request->input('flag'));
        $correctFlag = trim($challenge->flag);

        // Check if already solved
        if ($challenge->isSolvedBy($user)) {
            return back()->with('info', 'You have already captured this flag!');
        }

        if (hash_equals($correctFlag, $submittedFlag)) {
            // Determine solve mode
            $mode = ($activeMode === 'competition') ? 'competition' : 'training';

            Solve::create([
                'user_id'      => $user->id,
                'challenge_id' => $challenge->id,
                'mode'         => $mode,
                'solved_at'    => now(),
            ]);

            return back()->with('success', "Correct flag captured! +{$challenge->points} points awarded to your record.");
        }

        return back()->with('error', 'Incorrect flag token. Verify your findings and try again.')->withInput();
    }
}

