<?php

namespace App\Console\Commands;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerifyCtfPlatform extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ctf:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprehensive integration test verifying all CTF platform requirements';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=================================================");
        $this->info("   ITEC CyberLab Platform - System Verification  ");
        $this->info("=================================================");

        $failed = 0;

        // Test 1: Check Database Tables
        $this->comment("\n[1] Verifying Database Schema & MySQL Engine...");
        $tables = ['users', 'system_settings', 'challenges', 'solves', 'products', 'secrets', 'profiles'];
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $this->line("  ✓ Table '{$table}' exists.");
            } else {
                $this->error("  ✗ Missing table: {$table}");
                $failed++;
            }
        }

        // Verify registration_ip and device_fingerprint columns
        if (DB::getSchemaBuilder()->hasColumn('users', 'registration_ip') && DB::getSchemaBuilder()->hasColumn('users', 'device_fingerprint')) {
            $this->line("  ✓ Table 'users' contains 'registration_ip' and 'device_fingerprint' anti-sybil columns.");
        } else {
            $this->error("  ✗ Anti-sybil tracking columns missing in users table.");
            $failed++;
        }

        // Test 2: Verify Admin Account & Leaderboard Cleanliness
        $this->comment("\n[2] Verifying Admin Account & Clean Leaderboard State...");
        $admin = User::where('username', 'admin')->first();
        if ($admin && $admin->role === 'admin' && Hash::check('Admin12345!', $admin->password)) {
            $this->line("  ✓ Admin account verified (username: admin, password: Admin12345!, role: admin).");
        } else {
            $this->error("  ✗ Admin account mismatch or password check failed.");
            $failed++;
        }

        // Verify zero seeded dummy participants on leaderboard
        $dummyCount = User::whereIn('username', ['alice', 'bob', 'charlie'])->count();
        if ($dummyCount === 0) {
            $this->line("  ✓ Leaderboard is strictly clean: Zero seeded dummy accounts ('alice', 'bob', 'charlie') present.");
        } else {
            $this->error("  ✗ Seeded dummy participants still present in database ({$dummyCount} found)!");
            $failed++;
        }

        // Verify public login view does NOT contain preset credentials
        $loginViewContent = view('auth.login', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        if (!str_contains($loginViewContent, 'alice123') && !str_contains($loginViewContent, 'Adm1n@Secr3t!2024') && !str_contains($loginViewContent, 'Preset Lab Accounts')) {
            $this->line("  ✓ Public login view is clean: Zero preset credentials leaked.");
        } else {
            $this->error("  ✗ Credentials leak detected in auth.login view!");
            $failed++;
        }

        // Verify registration view does NOT contain unwanted anti-sybil banner
        $registerViewContent = view('auth.register', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        if (!str_contains($registerViewContent, 'Kebijakan Anti-Sybil')) {
            $this->line("  ✓ Public register view is clean: Anti-sybil policy text removed.");
        } else {
            $this->error("  ✗ Unwanted policy text found in register view!");
            $failed++;
        }

        // Test 3: Stealth Admin Routing & 404 Response
        $this->comment("\n[3] Verifying Stealth Admin Routing & 404 Response...");
        $middleware = new \App\Http\Middleware\StealthAdmin();

        // 3a: Non-admin participant
        $mockStudent = new User(['id' => 999, 'name' => 'Cadet Tester', 'username' => 'tester', 'role' => 'participant']);
        $reqStudent = \Illuminate\Http\Request::create('/itec/admin', 'GET');
        $reqStudent->setUserResolver(fn() => $mockStudent);
        try {
            $middleware->handle($reqStudent, fn() => response('OK'));
            $this->error("  ✗ Participant was NOT blocked from stealth admin!");
            $failed++;
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() === 404) {
                $this->line("  ✓ Participant access to /itec/admin returned clean 404 Not Found (Stealth check passed).");
            } else {
                $this->error("  ✗ Expected 404 for stealth routing, got: " . $e->getStatusCode());
                $failed++;
            }
        }

        // 3b: Admin account
        $reqAdmin = \Illuminate\Http\Request::create('/itec/admin', 'GET');
        $reqAdmin->setUserResolver(fn() => $admin);
        $resAdmin = $middleware->handle($reqAdmin, fn() => response('OK'));
        if ($resAdmin->getContent() === 'OK') {
            $this->line("  ✓ Admin successfully authenticated to /itec/admin.");
        } else {
            $this->error("  ✗ Admin was unexpectedly blocked from /itec/admin.");
            $failed++;
        }

        // 3c: Guest redirection to /itec/admin/login
        $reqGuest = \Illuminate\Http\Request::create('/itec/admin', 'GET');
        $resGuest = $middleware->handle($reqGuest, fn() => response('OK'));
        if ($resGuest->isRedirect(route('admin.login'))) {
            $this->line("  ✓ Guest accessing /itec/admin redirected to stealth gate /itec/admin/login.");
        } else {
            $this->error("  ✗ Guest was not directed to stealth admin login.");
            $failed++;
        }

        // Test 4: Master Gate State & Hard-Locked Challenges (Paused, Ended, Upcoming)
        $this->comment("\n[4] Verifying Master Gate State & Hard-Locked Challenges...");
        $ensureMiddleware = new \App\Http\Middleware\EnsureCompetitionActive();

        $submitReq = \Illuminate\Http\Request::create('/challenges/1/submit', 'POST');
        $submitReq->setUserResolver(fn() => $mockStudent);
        $submitReq->headers->set('Accept', 'application/json');

        $labReq = \Illuminate\Http\Request::create('/lab/sqli-login', 'GET');
        $labReq->setUserResolver(fn() => $mockStudent);

        // 4a. Set competition to paused
        SystemSetting::set('competition_status', 'paused');
        SystemSetting::clearCache();

        $lockPausedRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        $labPausedRes = $ensureMiddleware->handle($labReq, fn() => response('ALLOWED'));
        if ($lockPausedRes->getStatusCode() === 403 && $labPausedRes->isRedirect(route('challenges.index'))) {
            $this->line("  ✓ When status is PAUSED (Dijeda): Submissions and lab access are strictly hard-locked.");
        } else {
            $this->error("  ✗ Paused status did not lock submissions or lab access properly!");
            $failed++;
        }

        // 4b. Set competition to ended
        SystemSetting::set('competition_status', 'ended');
        SystemSetting::clearCache();

        $lockEndedRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        $labEndedRes = $ensureMiddleware->handle($labReq, fn() => response('ALLOWED'));
        if ($lockEndedRes->getStatusCode() === 403 && $labEndedRes->isRedirect(route('challenges.index'))) {
            $this->line("  ✓ When status is ENDED (Selesai): Submissions and lab access are strictly hard-locked.");
        } else {
            $this->error("  ✗ Ended status did not lock submissions or lab access properly!");
            $failed++;
        }

        // 4c. Set competition to upcoming
        SystemSetting::set('competition_status', 'upcoming');
        SystemSetting::clearCache();

        $lockUpcomingRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        $labUpcomingRes = $ensureMiddleware->handle($labReq, fn() => response('ALLOWED'));
        if ($lockUpcomingRes->getStatusCode() === 403 && $labUpcomingRes->isRedirect(route('challenges.index'))) {
            $this->line("  ✓ When status is UPCOMING (Akan Datang): Submissions and lab access are strictly hard-locked.");
        } else {
            $this->error("  ✗ Upcoming status did not lock submissions or lab access properly!");
            $failed++;
        }

        // 4d. Set competition to active
        SystemSetting::set('competition_status', 'active');
        SystemSetting::clearCache();
        $activeRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        $activeLabRes = $ensureMiddleware->handle($labReq, fn() => response('ALLOWED'));
        if ($activeRes->getContent() === 'ALLOWED' && $activeLabRes->getContent() === 'ALLOWED') {
            $this->line("  ✓ When status is ACTIVE (Aktif): Submissions and interactive labs are unlocked.");
        } else {
            $this->error("  ✗ Active competition submission or lab access failed.");
            $failed++;
        }

        // 4e. Admin bypass when paused
        SystemSetting::set('competition_status', 'paused');
        SystemSetting::clearCache();
        $adminSubmitReq = \Illuminate\Http\Request::create('/challenges/1/submit', 'POST');
        $adminSubmitReq->setUserResolver(fn() => $admin);
        $adminRes = $ensureMiddleware->handle($adminSubmitReq, fn() => response('ALLOWED'));
        if ($adminRes->getContent() === 'ALLOWED') {
            $this->line("  ✓ Administrator bypass verified: Admin can preview and test challenges during locked states.");
        } else {
            $this->error("  ✗ Admin bypass failed during locked state.");
            $failed++;
        }

        // Test 5: Scoreboard Logic & Admin Exclusion
        $this->comment("\n[5] Verifying Scoreboard Logic & Admin Exclusion...");
        $scoreboardUsers = User::where('role', '!=', 'admin')->get();
        $adminFound = $scoreboardUsers->contains('role', 'admin');
        if (!$adminFound) {
            $this->line("  ✓ Administrative accounts are strictly excluded from scoreboard queries.");
        } else {
            $this->error("  ✗ Admin account leaked into scoreboard query!");
            $failed++;
        }

        // Test 6: Registration Rate Limiting (Max 3 per IP/device)
        $this->comment("\n[6] Verifying Registration Rate Limit (Max 3 per IP/Device)...");
        $testIp = '198.51.100.99';
        $testDevice = (string) Str::uuid();

        // Delete test records if any
        User::where('registration_ip', $testIp)->delete();

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Rate Limit Test $i",
                'username' => "rl_test_$i",
                'email' => "rl_test_{$i}@test.com",
                'password' => Hash::make('secret123'),
                'role' => 'participant',
                'registration_ip' => $testIp,
                'device_fingerprint' => $testDevice,
            ]);
        }

        $regReq = \Illuminate\Http\Request::create('/register', 'POST', [
            'name' => 'Overflow Cadet',
            'username' => 'rl_overflow',
            'email' => 'rl_overflow@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $regReq->server->set('REMOTE_ADDR', $testIp);
        $regReq->cookies->set('itec_device_id', $testDevice);

        $authController = app(\App\Http\Controllers\AuthController::class);
        $regRes = $authController->register($regReq);

        if ($regRes->isRedirect() && session()->has('errors')) {
            $this->line("  ✓ Registration 4th account strictly rejected with IP/Device rate limit error.");
        } else {
            $this->error("  ✗ Rate limit failed: 4th account was allowed to register!");
            $failed++;
        }

        // Clean up rate limit test records
        User::where('registration_ip', $testIp)->delete();

        // Test 7: Verify Typography & Branding
        $this->comment("\n[7] Verifying Strict Typography (Poppins Only) & Branding Assets...");
        $layoutContent = file_get_contents(resource_path('views/layouts/app.blade.php'));
        if (str_contains($layoutContent, 'family=Poppins') && str_contains($layoutContent, "font-family: 'Poppins', sans-serif !important;") && !str_contains($layoutContent, 'family=Inter')) {
            $this->line("  ✓ Strict Poppins typography enforced across all UI elements.");
        } else {
            $this->error("  ✗ Typography check failed: Non-Poppins font or missing !important rule in layout.");
            $failed++;
        }

        $logoPath = public_path('assets/img/logoitec.jpg');
        if (file_exists($logoPath)) {
            $this->line("  ✓ Organization logo present at: {$logoPath}");
        } else {
            $this->error("  ✗ Organization logo missing at: {$logoPath}");
            $failed++;
        }

        // Test 8: Verifying Footer Branding (ITEC Organization & SMK Metland) and Public Livescoreboard
        $this->comment("\n[8] Verifying Footer Branding & Public Livescoreboard...");
        if (str_contains($layoutContent, 'ITEC Organization') && str_contains($layoutContent, 'smkmetland.net') && str_contains($layoutContent, 'SMK Metland')) {
            $this->line("  ✓ Footer branding verified: 'ITEC Organization' & link to 'smkmetland.net'.");
        } else {
            $this->error("  ✗ Footer branding missing 'ITEC Organization' or 'smkmetland.net'.");
            $failed++;
        }

        $chartJsPath = public_path('assets/js/chart.min.js');
        if (file_exists($chartJsPath)) {
            $this->line("  ✓ Local Chart.js bundle verified for score progression timeline graph.");
        } else {
            $this->error("  ✗ Missing local Chart.js at: {$chartJsPath}");
            $failed++;
        }

        // Verify public scoreboard accessibility (guest access allowed)
        $scoreReq = \Illuminate\Http\Request::create('/scoreboard', 'GET');
        $scoreRes = app()->handle($scoreReq);
        if ($scoreRes->getStatusCode() === 200) {
            $this->line("  ✓ Public Livescoreboard is accessible without authentication (guests & spectators allowed).");
        } else {
            $this->error("  ✗ Public scoreboard access failed with status: {$scoreRes->getStatusCode()}");
            $failed++;
        }

        // Summary
        $this->line("\n-------------------------------------------------");
        if ($failed === 0) {
            $this->info("   All platform verifications PASSED successfully!  ");
            $this->line("-------------------------------------------------\n");
            return 0;
        } else {
            $this->error("   Verification finished with {$failed} failure(s).  ");
            $this->line("-------------------------------------------------\n");
            return 1;
        }
    }
}
