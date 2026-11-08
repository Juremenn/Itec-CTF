<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LandingController extends Controller
{
    /**
     * Display the public landing / get started gateway before login.
     * If user is already authenticated, redirect to the dashboard unless requesting get-started specifically.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // If visiting root URL '/' and already authenticated, direct participant straight to dashboard
        if ($request->routeIs('home') && Auth::check()) {
            return redirect()->route('dashboard');
        }

        $activeMode = SystemSetting::activeMode();
        $competitionStatus = SystemSetting::competitionStatus();
        $broadcast = SystemSetting::get('broadcast_message', '');

        // Fetch active challenges to compute genuine platform statistics
        $challenges = Challenge::where('is_active', true)
            ->select('id', 'title', 'slug', 'category', 'difficulty', 'points', 'description')
            ->get();

        $totalChallenges = $challenges->count();
        $totalSolves = Solve::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // Distinct category mapping with authentic descriptions and challenge counts
        $categories = [
            'web' => [
                'name' => 'Web Exploitation',
                'description' => 'Eksplorasi celah keamanan aplikasi web seperti SQL Injection, IDOR, dan Reflected XSS.',
                'count' => $challenges->where('category', 'web')->count(),
                'points' => $challenges->where('category', 'web')->sum('points'),
                'icon' => 'globe',
            ],
            'crypto' => [
                'name' => 'Kriptografi',
                'description' => 'Analisis sandi klasik, cipher substitusi, frekuensi karakter, dan perlindungan kunci data.',
                'count' => $challenges->where('category', 'crypto')->count(),
                'points' => $challenges->where('category', 'crypto')->sum('points'),
                'icon' => 'key',
            ],
            'recon' => [
                'name' => 'Reconnaissance & OSINT',
                'description' => 'Pengintaian jejak digital, pemindaian rute tersembunyi, dan analisis kebijakan perayap web.',
                'count' => $challenges->where('category', 'recon')->count(),
                'points' => $challenges->where('category', 'recon')->sum('points'),
                'icon' => 'search',
            ],
            'general' => [
                'name' => 'General Skills',
                'description' => 'Navigasi lingkungan shell Linux, manipulasi berkas biner, dan inspeksi opsi bantuan perkakas.',
                'count' => $challenges->where('category', 'general')->count(),
                'points' => $challenges->where('category', 'general')->sum('points'),
                'icon' => 'terminal',
            ],
            'binary' => [
                'name' => 'Binary & Reverse',
                'description' => 'Dekompilasi logika program, reverse engineering file kompilasi, dan inspeksi struktur biner.',
                'count' => $challenges->where('category', 'binary')->count(),
                'points' => $challenges->where('category', 'binary')->sum('points'),
                'icon' => 'cpu',
            ],
            'forensics' => [
                'name' => 'Digital Forensics',
                'description' => 'Investigasi artefak memori, analisis paket transmisi jaringan, dan ekstraksi data tersembunyi.',
                'count' => $challenges->where('category', 'forensics')->count(),
                'points' => $challenges->where('category', 'forensics')->sum('points'),
                'icon' => 'shield',
            ],
        ];

        return view('landing.index', compact(
            'challenges',
            'categories',
            'totalChallenges',
            'totalSolves',
            'totalUsers',
            'activeMode',
            'competitionStatus',
            'broadcast'
        ));
    }
}

