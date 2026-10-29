<?php

use App\Http\Controllers\AdminChallengeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\ScoreboardController;
use Illuminate\Support\Facades\Route;

// Public crawler file for Challenge 5 Recon
Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow: /itec/secret-debug-panel\nDisallow: /itec/admin\n", 200)
        ->header('Content-Type', 'text/plain');
});

// Authentication & Registration Routes for Student Cadets
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Scoreboard & Real-time Live Data (Accessible by Participants and Guests)
Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard');
Route::get('/scoreboard/live-data', [ScoreboardController::class, 'liveData'])->name('scoreboard.live_data');

// Challenge Files Download (Public & Wget Accessible)
Route::get('/files/{filename}', function ($filename) {
    $cleanFilename = basename($filename);
    $filePath = public_path('files/' . $cleanFilename);
    if (!file_exists($filePath)) {
        $filePath = base_path('challenges/files/' . $cleanFilename);
    }
    if (!file_exists($filePath)) {
        abort(404, 'File tantangan tidak ditemukan.');
    }
    return response()->download($filePath, $cleanFilename);
})->name('files.download');

// Authenticated Participant Portal
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/{slug}', [ChallengeController::class, 'show'])->name('challenges.show');

    // Flag Submission Guarded by Competition State Middleware
    Route::post('/challenges/{id}/submit', [ChallengeController::class, 'submitFlag'])
        ->middleware('ensure.competition.active')
        ->name('challenges.submit');

    // Interactive Lab Environments Guarded by Competition State Middleware
    Route::middleware(['ensure.competition.active'])->group(function () {
        Route::match(['get', 'post'], '/lab/sqli-login', [LabController::class, 'sqliLogin'])->name('lab.sqli_login');
        Route::get('/lab/sqli-search', [LabController::class, 'sqliSearch'])->name('lab.sqli_search');
        Route::get('/lab/idor', [LabController::class, 'idor'])->name('lab.idor');
        Route::get('/lab/xss', [LabController::class, 'xss'])->name('lab.xss');
        Route::get('/itec/secret-debug-panel', [LabController::class, 'hiddenDebugConsole'])->name('lab.hidden_console');
    });
});

// Hidden Admin Panel (/itec/admin)
Route::prefix('itec/admin')->middleware(['stealth.admin'])->group(function () {
    // Stealth Admin Authentication Gate
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);

    // Protected Administrative Operations
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/toggle-mode', [AdminController::class, 'toggleMode'])->name('admin.toggle_mode');
        Route::post('/broadcast', [AdminController::class, 'setBroadcast'])->name('admin.broadcast');
        Route::resource('challenges', AdminChallengeController::class)->names('admin.challenges');
        Route::post('/reset-scores', [AdminController::class, 'purgeScores'])->name('admin.reset_scores');
        Route::post('/sync-database', [AdminController::class, 'syncDatabase'])->name('admin.sync_database');
    });
});
