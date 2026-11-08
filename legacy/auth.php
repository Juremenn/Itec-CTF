<?php
declare(strict_types=1);

// config.php already loaded by index.php
require_once ROOT_PATH . '/layout.php';

$page    = $_GET['page'] ?? 'login';
$error   = '';
$success = '';

if (is_logged_in()) {
    header('Location: /index.php?page=dashboard');
    exit;
}

// ── Handle Registration ───────────────────────────────────────────────────────
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';
    $email    = trim($_POST['email'] ?? '');

    // Validation
    if (strlen($username) < 3 || strlen($username) > 32) {
        $error = 'Username must be between 3 and 32 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username may only contain alphanumeric characters and underscores.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address syntax.';
    } else {
        $db   = get_db();
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Specified username is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins  = $db->prepare(
                "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'student')"
            );
            $ins->execute([$username, $hash, $email]);
            $success = 'Account created successfully. Please sign in below.';
            $page    = 'login';
        }
    }
}

// ── Handle Login ──────────────────────────────────────────────────────────────
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']      ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $db   = get_db();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // Persistent remember-me token (30 days)
            $token = bin2hex(random_bytes(32));
            $hash  = hash('sha256', $token);
            $db->prepare("UPDATE users SET remember_token = ? WHERE id = ?")->execute([$hash, $user['id']]);
            setcookie('remember_user', $user['id'] . ':' . $token, [
                'expires'  => time() + (86400 * 30),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            header('Location: /index.php?page=dashboard');
            exit;
        } else {
            usleep(250_000);
            $error = 'Authentication failed. Invalid operator credentials.';
        }
    }
}

// ── Render ────────────────────────────────────────────────────────────────────
$is_register = ($page === 'register');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_register ? 'Register' : 'Sign In' ?> &mdash; ITEC CyberLab</title>
    <meta name="description" content="ITEC CyberLab – Security Academy & Hands-on CTF Exploitation Platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body class="cylab-auth-body">
    <div class="cylab-auth-container">
        
        <!-- Prominent Centered Logo & Branding -->
        <div class="cylab-auth-header">
            <div class="cylab-auth-logo-wrap">
                <img src="/assets/img/logoitec.jpg" alt="ITEC CyberLab Logo" class="cylab-auth-logo-img">
            </div>
            <h1 class="cylab-auth-title">ITEC CyberLab</h1>
            <p class="cylab-auth-subtitle">SECURITY ACADEMY</p>
        </div>

        <!-- Clean Auth Card -->
        <div class="cylab-auth-card">
            <!-- Navigation Tabs with Orange Indicator -->
            <div class="cylab-auth-tabs">
                <a href="/index.php?page=login"
                   class="cylab-auth-tab <?= !$is_register ? 'active' : '' ?>">
                    Sign In
                </a>
                <a href="/index.php?page=register"
                   class="cylab-auth-tab <?= $is_register ? 'active' : '' ?>">
                    Register
                </a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error" role="alert">
                    <span class="alert-icon-svg"><?= svg_icon('alert', '', 15) ?></span>
                    <div><?= e($error) ?></div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <span class="alert-icon-svg"><?= svg_icon('check', '', 15) ?></span>
                    <div><?= e($success) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!$is_register): ?>
            <!-- ─── Login Form ─────────────────────────────────────────────── -->
            <form method="POST" action="/index.php?page=login" novalidate id="login-form" class="cylab-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                
                <div class="form-field">
                    <label for="login-username" class="form-field-label">Username</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('user', '', 15) ?></span>
                        <input
                            type="text"
                            id="login-username"
                            name="username"
                            class="form-control"
                            placeholder="e.g. operator_alex"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="login-password" class="form-field-label">Password</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('lock', '', 15) ?></span>
                        <input
                            type="password"
                            id="login-password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••••••"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-orange btn-full mt-2" id="login-btn">
                    <span>Sign In</span>
                    <span class="btn-arrow">&rarr;</span>
                </button>
            </form>

            <?php else: ?>
            <!-- ─── Register Form ──────────────────────────────────────────── -->
            <form method="POST" action="/index.php?page=register" novalidate id="register-form" class="cylab-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                
                <div class="form-field">
                    <label for="reg-username" class="form-field-label">Username</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('user', '', 15) ?></span>
                        <input
                            type="text"
                            id="reg-username"
                            name="username"
                            class="form-control"
                            placeholder="e.g. cadet_security"
                            autocomplete="username"
                            required
                            minlength="3"
                            maxlength="32"
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="reg-email" class="form-field-label">Email Address</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('terminal', '', 15) ?></span>
                        <input
                            type="email"
                            id="reg-email"
                            name="email"
                            class="form-control"
                            placeholder="cadet@campus.edu"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="reg-password" class="form-field-label">Password</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('lock', '', 15) ?></span>
                        <input
                            type="password"
                            id="reg-password"
                            name="password"
                            class="form-control"
                            placeholder="At least 6 characters"
                            autocomplete="new-password"
                            required
                            minlength="6"
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="reg-confirm" class="form-field-label">Confirm Password</label>
                    <div class="input-container">
                        <span class="input-prefix-icon"><?= svg_icon('lock', '', 15) ?></span>
                        <input
                            type="password"
                            id="reg-confirm"
                            name="confirm"
                            class="form-control"
                            placeholder="Re-enter password"
                            autocomplete="new-password"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-orange btn-full mt-2" id="register-btn">
                    <span>Create Account</span>
                    <span class="btn-arrow">&rarr;</span>
                </button>
            </form>
            <?php endif; ?>
        </div>

        <!-- Auxiliary Action: Public Standings -->
        <div class="cylab-auth-aux">
            <a href="/index.php?page=scoreboard" class="btn btn-ghost btn-full" id="btn-public-scoreboard">
                <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M6 4h12a2 2 0 0 1 2 2v3a6 6 0 0 1-12 0V6a2 2 0 0 1 2-2z"/></svg>
                <span>View Live Scoreboard &rarr;</span>
            </a>
        </div>

        <footer class="cylab-auth-footer">
            ITEC CyberLab &bull; Security Academy
        </footer>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>
