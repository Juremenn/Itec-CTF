<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.index');
            }
            abort(404);
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptData = [
            $loginField => $credentials['login'],
            'password'  => $credentials['password'],
        ];

        if (Auth::attempt($attemptData)) {
            $user = Auth::user();

            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                abort(404);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.index')->with('success', 'Berhasil masuk ke Pusat Kontrol Administrator.');
        }

        return back()->withErrors([
            'login' => 'Kredensial administrator tidak valid.',
        ])->onlyInput('login');
    }

    public function index(): View
    {
        SystemSetting::clearCache();

        $competitionStatus = SystemSetting::competitionStatus();
        $activeMode = SystemSetting::activeMode();
        $broadcast = SystemSetting::get('broadcast_message', '');

        $totalParticipants = User::where('role', '!=', 'admin')->count();
        $totalChallenges = Challenge::count();
        $totalSolves = Solve::count();
        $competitionSolves = Solve::where('mode', 'competition')->count();
        $trainingSolves = Solve::where('mode', 'training')->count();

        $participants = User::where('role', '!=', 'admin')
            ->withCount(['solves as total_solves', 'solves as competition_solves' => function ($q) {
                $q->where('mode', 'competition');
            }])
            ->withSum(['solves as total_points' => function ($query) {
                $query->join('challenges', 'solves.challenge_id', '=', 'challenges.id')
                    ->selectRaw('COALESCE(SUM(challenges.points), 0)');
            }], 'challenges.points')
            ->orderByDesc('total_points')
            ->get();

        $challenges = Challenge::orderBy('category')->orderBy('points')->get();

        return view('admin.index', compact(
            'competitionStatus',
            'activeMode',
            'broadcast',
            'totalParticipants',
            'totalChallenges',
            'totalSolves',
            'competitionSolves',
            'trainingSolves',
            'participants',
            'challenges'
        ));
    }

    public function toggleMode(Request $request): RedirectResponse
    {
        $request->validate([
            'active_mode'        => ['nullable', 'in:competition,training'],
            'competition_status' => ['nullable', 'in:upcoming,active,paused,ended'],
        ]);

        if ($request->filled('active_mode')) {
            $mode = $request->input('active_mode');
            SystemSetting::set('active_mode', $mode);
            SystemSetting::set('platform_mode', $mode);
        }

        if ($request->filled('competition_status')) {
            $status = $request->input('competition_status');
            SystemSetting::set('competition_status', $status);
        }

        SystemSetting::clearCache();

        return back()->with('success', 'Status operasional sistem berhasil diperbarui.');
    }

    public function setBroadcast(Request $request): RedirectResponse
    {
        $request->validate([
            'broadcast_message' => ['nullable', 'string', 'max:500'],
        ]);

        $message = trim((string)$request->input('broadcast_message', ''));
        SystemSetting::set('broadcast_message', $message);
        SystemSetting::clearCache();

        return back()->with('success', 'Banner pengumuman global berhasil diperbarui.');
    }

    public function purgeScores(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_reset' => ['required', 'string'],
        ]);

        if ($request->input('confirm_reset') !== 'RESET') {
            return back()->with('error', 'Frasa konfirmasi reset tidak cocok. Silakan ketik "RESET" untuk mengonfirmasi penghapusan skor.');
        }

        Solve::truncate();

        return back()->with('success', 'Data papan skor berhasil direset. Akun peserta tetap aman.');
    }
}
