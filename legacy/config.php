<?php
declare(strict_types=1);

// ─── Timezone ────────────────────────────────────────────────────────────────
date_default_timezone_set('Asia/Bangkok');

// ─── Path constants ───────────────────────────────────────────────────────────
define('ROOT_PATH',    __DIR__);
define('DB_PATH',      ROOT_PATH . '/database.sqlite');
define('CHALLENGES_PATH', ROOT_PATH . '/challenges');

// ─── Session bootstrap (Persistent 30-day lifetime) ───────────────────────────
$session_lifetime = 86400 * 30; // 30 days
ini_set('session.gc_maxlifetime', (string)$session_lifetime);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => $session_lifetime,
        'path'     => '/',
        'secure'   => false,   // set true when behind HTTPS in production
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ─── PDO singleton ────────────────────────────────────────────────────────────
function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA journal_mode=WAL;');
        $pdo->exec('PRAGMA foreign_keys=ON;');
    }
    return $pdo;
}

// ─── Remember-me session restoration ──────────────────────────────────────────
function restore_remember_session(): void {
    if (isset($_SESSION['user_id'])) return;
    if (empty($_COOKIE['remember_user'])) return;

    $cookie = $_COOKIE['remember_user'];
    $parts  = explode(':', $cookie, 2);
    if (count($parts) !== 2) return;

    [$uid, $token] = $parts;
    if (!ctype_digit($uid) || empty($token)) return;

    try {
        $hash = hash('sha256', $token);
        $stmt = get_db()->prepare("SELECT * FROM users WHERE id = ? AND remember_token = ? LIMIT 1");
        $stmt->execute([(int)$uid, $hash]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
        }
    } catch (\Throwable $e) {
        // fail silently
    }
}

// Restore session from remember token if applicable
restore_remember_session();

// ─── Auth helpers ─────────────────────────────────────────────────────────────
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function is_admin(): bool {
    $user = current_user();
    return ($user !== null && ($user['role'] ?? '') === 'admin');
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /index.php?page=login');
        exit;
    }
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    static $cache = null;
    if ($cache === null) {
        $stmt = get_db()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $cache = $stmt->fetch() ?: null;
    }
    return $cache;
}

// ─── Platform Settings & CTF State ────────────────────────────────────────────
$GLOBAL_SETTINGS_CACHE = [];

function get_setting(string $key, string $default = ''): string {
    global $GLOBAL_SETTINGS_CACHE;
    if (isset($GLOBAL_SETTINGS_CACHE[$key])) return $GLOBAL_SETTINGS_CACHE[$key];
    try {
        $stmt = get_db()->prepare("SELECT value FROM settings WHERE key = ? LIMIT 1");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        $GLOBAL_SETTINGS_CACHE[$key] = ($val !== false) ? (string)$val : $default;
        return $GLOBAL_SETTINGS_CACHE[$key];
    } catch (\Throwable $e) {
        return $default;
    }
}

function set_setting(string $key, string $value): void {
    global $GLOBAL_SETTINGS_CACHE;
    $GLOBAL_SETTINGS_CACHE[$key] = $value;
    $stmt = get_db()->prepare(
        "INSERT INTO settings (key, value) VALUES (?, ?)
         ON CONFLICT(key) DO UPDATE SET value = excluded.value"
    );
    $stmt->execute([$key, $value]);
}

function get_ctf_state(): array {
    $status       = get_setting('ctf_status', 'running');
    $start_time   = get_setting('ctf_start_time', '');
    $end_time     = get_setting('ctf_end_time', '');
    $announcement = get_setting('ctf_announcement', '');

    $now = time();
    $state = $status;
    $message = '';

    if ($status === 'stopped') {
        $state   = 'stopped';
        $message = 'Kompetisi CTF saat ini sedang DIHENTIKAN sementara oleh Instruktur.';
    } elseif (!empty($start_time) && $now < strtotime($start_time)) {
        $state   = 'not_started';
        $message = 'Kompetisi CTF BELUM DIMULAI. Jadwal mulai: ' . date('d M Y, H:i', strtotime($start_time)) . ' WIB';
    } elseif (!empty($end_time) && $now > strtotime($end_time)) {
        $state   = 'ended';
        $message = 'Kompetisi CTF TELAH BERAKHIR pada: ' . date('d M Y, H:i', strtotime($end_time)) . ' WIB';
    } else {
        $state   = 'running';
        $message = 'Kompetisi CTF sedang AKTIF dan BERJALAN.';
    }

    return [
        'state'        => $state, // 'running', 'stopped', 'not_started', 'ended'
        'is_active'    => ($state === 'running'),
        'status_raw'   => $status,
        'start_time'   => $start_time,
        'end_time'     => $end_time,
        'announcement' => $announcement,
        'message'      => $message,
    ];
}

// ─── CSRF helpers ─────────────────────────────────────────────────────────────
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}

// ─── Flash message helpers ────────────────────────────────────────────────────
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// ─── Sanitisation helpers ─────────────────────────────────────────────────────
/**
 * Safe HTML output – use for all user-controlled content in normal pages.
 */
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ─── Challenge flag registry ──────────────────────────────────────────────────
// Flags are deliberately stored here (not in DB) so students can't just
// dump the SQLite file; they must exploit the vulnerability to reveal them.
define('FLAGS', [
    'sqli_login'       => 'FLAG{sql1_byp4ss_m4st3r}',
    'sqli_search'      => 'FLAG{uni0n_s3l3ct_h3r0}',
    'idor'             => 'FLAG{id0r_p4th_tr4v3rs4l}',
    'xss_reflect'      => 'FLAG{xss_r3fl3ct3d_pwn3d}',
    'info_disclosure'  => 'FLAG{h1dd3n_p4n3l_f0und}',
]);

// Points per challenge
define('POINTS', [
    'sqli_login'      => 100,
    'sqli_search'     => 150,
    'idor'            => 100,
    'xss_reflect'     => 100,
    'info_disclosure' => 75,
]);

// Challenge metadata for the dashboard
define('CHALLENGE_META', [
    'sqli_login' => [
        'title'       => 'SQL Injection: Authentication Bypass',
        'category'    => 'Web Exploitation',
        'tag'         => 'SQLi',
        'icon'        => 'SQLi',
        'svg'         => 'database',
        'difficulty'  => 'Easy',
        'points'      => 100,
        'description' => 'The login form concatenates user input directly into an inline SQL statement. Bypass authentication controls without valid credentials.',
        'url'         => '/challenges/sqli_login.php',
    ],
    'sqli_search' => [
        'title'       => 'SQL Injection: UNION Data Exfiltration',
        'category'    => 'Web Exploitation',
        'tag'         => 'UNION',
        'icon'        => 'UNION',
        'svg'         => 'database',
        'difficulty'  => 'Medium',
        'points'      => 150,
        'description' => 'The catalog query endpoint is vulnerable to UNION injection. Determine result column counts and exfiltrate hidden records from the database.',
        'url'         => '/challenges/sqli_search.php',
    ],
    'idor' => [
        'title'       => 'Insecure Direct Object Reference (IDOR)',
        'category'    => 'Access Control',
        'tag'         => 'IDOR',
        'icon'        => 'IDOR',
        'svg'         => 'user',
        'difficulty'  => 'Easy',
        'points'      => 100,
        'description' => 'The profile view trusts a user-supplied numeric identifier without verifying caller authorization. Enumerate records to locate the administrator profile.',
        'url'         => '/challenges/idor.php',
    ],
    'xss_reflect' => [
        'title'       => 'Reflected Cross-Site Scripting (XSS)',
        'category'    => 'Client-Side',
        'tag'         => 'XSS',
        'icon'        => 'XSS',
        'svg'         => 'code',
        'difficulty'  => 'Easy',
        'points'      => 100,
        'description' => 'The search parameter reflects unsanitized payload strings into the DOM. Inject an execution vector to capture session tokens from the browser.',
        'url'         => '/challenges/xss_reflect.php',
    ],
    'info_disclosure' => [
        'title'       => 'Information Disclosure & Recon',
        'category'    => 'Reconnaissance',
        'tag'         => 'Recon',
        'icon'        => 'Recon',
        'svg'         => 'search',
        'difficulty'  => 'Easy',
        'points'      => 75,
        'description' => 'Exposed endpoint configurations and development comments reveal administrative routes. Probe indexing artifacts to discover the hidden console.',
        'url'         => '/challenges/info_disclosure.php',
    ],
]);
