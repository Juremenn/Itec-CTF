# 🛡️ ITEC CyberLab 101 – Cybersecurity Practice & CTF Platform

A production-grade, educational Capture-the-Flag (CTF) and cybersecurity practice platform developed for **ITEC CyberLab 101**, re-engineered with modern **Laravel 11.x**, **MySQL 8.x**, and Eloquent ORM.

Styled strictly with a clean, high-density dark UI inspired by **CyLab Academy** and **HackTheBox** (Obsidian `#0c0d12` base, subtle borders `1px solid #1f232e`, slate typography, and warm orange CTA `#f97316`).

---

## 🚀 Quick Start

### 1. Launch Platform Server
Run the startup script from the project root:
```bash
./start.sh
```
Or start via the PHP development server:
```bash
export PHP_INI_SCAN_DIR="/etc/php/8.4/cli/conf.d:$(pwd)/php_conf"
php -S 0.0.0.0:8080 -t public
```

Navigate in your browser to:
```
http://localhost:8080
```

### 2. Run Automated Verification Tests
We have implemented a comprehensive artisan verification command that validates schema integrity, dual-mode submission locking, stealth admin routing, and scoreboard queries:
```bash
./artisan ctf:verify
```

---

## 👥 Default Accounts

| Username | Password | Role | Description |
| :--- | :--- | :--- | :--- |
| `admin` | `Admin12345!` | `admin` | Platform instructor / operator account |
| `alice` | `alice123` | `participant` | Enrolled student cadet |
| `bob` | `bob456` | `participant` | Enrolled student cadet |
| `charlie` | `charlie789` | `participant` | Enrolled student cadet |

---

## 🏛️ Architecture & Key Features

### 1. Dual Mode Operating System (Competition vs Training)
Managed directly via `system_settings` table in the database and configured in the hidden Admin Control Center:
- **Competition Mode (`active_mode = competition`)**:
  - Time-sensitive tournament setting with live competition leaderboard.
  - When status is **`paused`**, **`upcoming`**, or **`ended`**:
    - Flag submissions are hard-locked via the `ensure.competition.active` middleware.
    - Submit buttons render disabled with informative status alerts.
    - Students cannot accumulate tournament points during locked hours.
    - Instructors have test bypass privileges.
- **Training/Practice Mode (`active_mode = training`)**:
  - Open-ended, non-punitive pedagogical practice.
  - Challenges remain permanently open for submission.
  - Hints and defense/remediation walkthroughs are accessible and toggleable.

### 2. Stealth Admin Routing (`/itec/admin`)
- Administrative features are mapped under `/itec/admin` with middleware `stealth.admin`.
- When non-admin users (or participants) attempt to access `/itec/admin`, the middleware returns a clean **`404 Not Found`** instead of `403 Forbidden`, concealing the route's existence.

### 3. Dynamic Scoreboard & Administrative Exclusion
- Scoreboard controller filters out administrative accounts (`User::where('role', '!=', 'admin')->withSum(...)`). Admins, instructors, and test operators never appear on public or student leaderboards.
- **Competition Board**: Ranks students based strictly on challenges solved during active competition windows.
- **All-Time / Practice Board**: Tracks ongoing cumulative member progress across all categories (Web, Crypto, Recon).

---

## 📂 Route Architecture (`routes/web.php`)

```php
// Authentication Routes (Centered clean login featuring assets/img/logoitec)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard');

    // Interactive Lab Environments
    Route::match(['get', 'post'], '/lab/sqli-login', [LabController::class, 'sqliLogin'])->name('lab.sqli_login');
    Route::get('/lab/sqli-search', [LabController::class, 'sqliSearch'])->name('lab.sqli_search');
    Route::get('/lab/idor', [LabController::class, 'idor'])->name('lab.idor');
    Route::get('/lab/xss', [LabController::class, 'xss'])->name('lab.xss');
    Route::get('/itec/secret-debug-panel', [LabController::class, 'hiddenDebugConsole'])->name('lab.hidden_console');
});

// Hidden Admin Panel (/itec/admin)
Route::prefix('itec/admin')->middleware(['auth', 'stealth.admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/toggle-mode', [AdminController::class, 'toggleMode'])->name('admin.toggle_mode');
    Route::post('/broadcast', [AdminController::class, 'setBroadcast'])->name('admin.broadcast');
    Route::resource('challenges', AdminChallengeController::class);
    Route::post('/reset-scores', [AdminController::class, 'purgeScores'])->name('admin.reset_scores');
});
```

---

## 🗄️ Database Migrations

- **`users`**: `id`, `name`, `username` (unique), `email` (unique), `password`, `role` (enum: 'participant', 'admin', default: 'participant'), timestamps.
- **`system_settings`**: `id`, `key` (unique), `value`, timestamps.
- **`challenges`**: `id`, `title`, `slug`, `category` (web, crypto, recon), `difficulty` (easy, medium, hard), `points`, `flag`, `description`, `hint`, `remediation`, `mode_target` (enum: 'competition', 'training', 'both'), `is_active`, timestamps.
- **`solves`**: `id`, `user_id`, `challenge_id`, `mode` (competition/training), `solved_at`, unique composite key `[user_id, challenge_id]`.
- **Vulnerable lab tables**: `products`, `secrets`, `profiles` for interactive laboratory targets.
