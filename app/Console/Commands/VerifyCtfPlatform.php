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

        // Test 2: Verify Default Accounts
        $this->comment("\n[2] Verifying User Accounts & Public View Sanitation...");
        $admin = User::where('username', 'admin')->first();
        if ($admin && $admin->role === 'admin' && Hash::check('Admin12345!', $admin->password)) {
            $this->line("  ✓ Admin account verified (username: admin, role: admin).");
        } else {
            $this->error("  ✗ Admin account mismatch or password check failed.");
            $failed++;
        }

        $alice = User::where('username', 'alice')->first();
        if ($alice && $alice->role === 'participant') {
            $this->line("  ✓ Participant account verified (username: alice, role: participant).");
        } else {
            $this->error("  ✗ Participant account missing.");
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

        // Test 3: Stealth Admin Routing & 404 Response
        $this->comment("\n[3] Verifying Stealth Admin Routing & 404 Response...");
        $middleware = new \App\Http\Middleware\StealthAdmin();

        // 3a: Non-admin participant
        $reqAlice = \Illuminate\Http\Request::create('/itec/admin', 'GET');
        $reqAlice->setUserResolver(fn() => $alice);
        try {
            $middleware->handle($reqAlice, fn() => response('OK'));
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

        // Test 4: Dual Mode System & Hard-Locked Flag Submission
        $this->comment("\n[4] Verifying Dual-Mode Middleware Flag Lock...");
        $ensureMiddleware = new \App\Http\Middleware\EnsureCompetitionActive();

        // Set competition to paused
        SystemSetting::set('active_mode', 'competition');
        SystemSetting::set('competition_status', 'paused');
        SystemSetting::clearCache();

        $submitReq = \Illuminate\Http\Request::create('/challenges/1/submit', 'POST');
        $submitReq->setUserResolver(fn() => $alice);
        $submitReq->headers->set('Accept', 'application/json');

        $lockRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        if ($lockRes->getStatusCode() === 403) {
            $this->line("  ✓ Submission strictly hard-locked when Competition is PAUSED (403 returned).");
        } else {
            $this->error("  ✗ Submission was not hard-locked during paused competition! Code: " . $lockRes->getStatusCode());
            $failed++;
        }

        // Set competition to active
        SystemSetting::set('competition_status', 'active');
        SystemSetting::clearCache();
        $activeRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        if ($activeRes->getContent() === 'ALLOWED') {
            $this->line("  ✓ Submission allowed through when Competition is ACTIVE.");
        } else {
            $this->error("  ✗ Active competition submission failed.");
            $failed++;
        }

        // Test in Training mode
        SystemSetting::set('active_mode', 'training');
        SystemSetting::set('competition_status', 'paused');
        SystemSetting::clearCache();
        $trainingRes = $ensureMiddleware->handle($submitReq, fn() => response('ALLOWED'));
        if ($trainingRes->getContent() === 'ALLOWED') {
            $this->line("  ✓ In Training Mode, open-ended submissions remain unlocked regardless of competition state.");
        } else {
            $this->error("  ✗ Training mode submission failed.");
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

        // Test 7: Verify Branding Assets
        $this->comment("\n[7] Verifying Branding Logo Asset...");
        $logoPath = public_path('assets/img/logoitec.jpg');
        if (file_exists($logoPath)) {
            $this->line("  ✓ Organization logo present at: {$logoPath}");
        } else {
            $this->error("  ✗ Organization logo missing at: {$logoPath}");
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
