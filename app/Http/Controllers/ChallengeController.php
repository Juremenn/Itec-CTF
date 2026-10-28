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

        $isLocked = ($competitionStatus !== 'active');
        $lockMessage = match ($competitionStatus) {
            'paused'   => 'Sesi kompetisi sedang dijeda oleh instruktur. Seluruh akses lab, target eksploitasi, dan pengiriman flag dikunci sementara.',
            'ended'    => 'Sesi kompetisi telah berakhir. Seluruh akses lab, target eksploitasi, dan pengiriman flag telah dinonaktifkan.',
            'upcoming' => 'Sesi kompetisi belum dimulai. Seluruh tantangan masih terkunci hingga waktu turnamen resmi dimulai.',
            default    => '',
        };

        return view('challenges.index', compact(
            'challenges',
            'userSolves',
            'activeMode',
            'competitionStatus',
            'selectedCategory',
            'isLocked',
            'lockMessage'
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

        // Submissions & interactive labs locked state
        $isSubmissionLocked = ($competitionStatus !== 'active');
        $lockReason = match ($competitionStatus) {
            'upcoming' => 'Kompetisi belum dimulai. Seluruh akses lab dan pengiriman flag dinonaktifkan.',
            'paused'   => 'Kompetisi sedang dijeda oleh instruktur. Seluruh akses lab dan pengiriman flag dikunci sementara.',
            'ended'    => 'Kompetisi telah berakhir. Seluruh akses lab dan pengiriman flag ditutup secara permanen.',
            default    => 'Status kompetisi tidak aktif. Pengiriman flag dikunci.',
        };

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
            return back()->with('info', 'Anda sudah menyelesaikan tantangan ini sebelumnya!');
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

            return back()->with('success', "Flag valid berhasil disubmit! Selamat, +{$challenge->points} poin telah ditambahkan ke akun Anda.");
        }

        return back()->with('error', 'Flag tidak sesuai. Silakan verifikasi kembali temuan Anda dan coba lagi.')->withInput();
    }
}

