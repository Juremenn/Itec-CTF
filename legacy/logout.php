<?php
declare(strict_types=1);

if (isset($_SESSION['user_id'])) {
    try {
        $db = get_db();
        $db->prepare("UPDATE users SET remember_token = NULL WHERE id = ?")->execute([$_SESSION['user_id']]);
    } catch (\Throwable $e) {}
}

// Clear remember cookie
setcookie('remember_user', '', [
    'expires' => time() - 3600,
    'path'    => '/',
]);

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header('Location: /index.php?page=login');
exit;
