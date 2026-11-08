<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/layout.php';

// Auto-initialise database on first run
if (!file_exists(DB_PATH)) {
    init_database();
} else {
    // Re-run idempotent seed in case of schema changes
    init_database();
}

// ── Global API Endpoint (Navbar Quick Flag Submit & Sandbox Reset) ────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_action'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    if (!is_logged_in()) {
        echo json_encode(['success' => false, 'message' => 'Please sign in first.']);
        exit;
    }
    
    $action = (string)$_POST['api_action'];
    $current_u = current_user();
    $u_id = (int)$current_u['id'];
    
    if ($action === 'submit_flag') {
        $state = get_ctf_state();
        if (!$state['is_active'] && !is_admin()) {
            echo json_encode([
                'success' => false,
                'message' => 'Competition is paused by instructor. Flag submissions are locked.'
            ]);
            exit;
        }
        
        $submitted_flag = trim((string)($_POST['flag'] ?? ''));
        if ($submitted_flag === '') {
            echo json_encode(['success' => false, 'message' => 'Flag cannot be empty.']);
            exit;
        }
        
        $matched_cid = null;
        foreach (FLAGS as $cid => $correct_flag) {
            if (hash_equals($correct_flag, $submitted_flag)) {
                $matched_cid = $cid;
                break;
            }
        }
        
        if (!$matched_cid) {
            echo json_encode([
                'success' => false,
                'message' => 'Incorrect flag. Verify your exploit output format FLAG{...}.'
            ]);
            exit;
        }
        
        $solves = solved_challenges($u_id);
        if (in_array($matched_cid, $solves, true)) {
            $meta = CHALLENGE_META[$matched_cid];
            echo json_encode([
                'success' => false,
                'message' => 'Already solved! You have already claimed points for ' . $meta['title'] . '.'
            ]);
            exit;
        }
        
        // Record solve in database
        record_solve($u_id, $matched_cid);
        $meta = CHALLENGE_META[$matched_cid];
        $new_score = total_score($u_id);
        $new_solves = solved_challenges($u_id);
        
        echo json_encode([
            'success' => true,
            'message' => 'BOOM! Correct flag captured!',
            'challenge_id' => $matched_cid,
            'challenge_title' => $meta['title'],
            'points' => (int)$meta['points'],
            'new_score' => $new_score,
            'new_count' => count($new_solves),
            'total_possible' => array_sum(array_column(CHALLENGE_META, 'points')),
            'total_challenges' => count(CHALLENGE_META)
        ]);
        exit;
    }
    
    if ($action === 'reset_sandbox') {
        init_database();
        echo json_encode([
            'success' => true,
            'message' => 'Sandbox database tables (products & secrets) successfully restored to default state.'
        ]);
        exit;
    }
    
    echo json_encode(['success' => false, 'message' => 'Unknown API action.']);
    exit;
}

// ── Simple front-controller router ───────────────────────────────────────────
$allowed_pages = [
    'login', 'register', 'dashboard', 'scoreboard', 'logout',
    'admin_control',
    'challenge_sqli_login', 'challenge_sqli_search',
    'challenge_idor', 'challenge_xss', 'challenge_info',
];

$page = $_GET['page'] ?? (is_logged_in() ? 'dashboard' : 'login');

if (!in_array($page, $allowed_pages, true)) {
    $page = is_logged_in() ? 'dashboard' : 'login';
}

// Auth pages (login & register) redirect to dashboard if already authenticated
$auth_pages = ['login', 'register'];
if (in_array($page, $auth_pages, true) && is_logged_in()) {
    header('Location: /index.php?page=dashboard');
    exit;
}

// Public pages accessible by anyone without login
$public_pages = ['login', 'register', 'scoreboard'];
if (!in_array($page, $public_pages, true) && !is_logged_in()) {
    header('Location: /index.php?page=login');
    exit;
}

// Enforce admin permission for admin_control
if ($page === 'admin_control' && !is_admin()) {
    header('Location: /index.php?page=dashboard');
    exit;
}

// ── Page dispatch ─────────────────────────────────────────────────────────────
match ($page) {
    'login'                => require ROOT_PATH . '/auth.php',
    'register'             => require ROOT_PATH . '/auth.php',
    'logout'               => require ROOT_PATH . '/logout.php',
    'dashboard'            => require ROOT_PATH . '/dashboard.php',
    'scoreboard'           => require ROOT_PATH . '/scoreboard.php',
    'admin_control'        => require ROOT_PATH . '/admin_control.php',
    'challenge_sqli_login' => require ROOT_PATH . '/challenges/sqli_login.php',
    'challenge_sqli_search'=> require ROOT_PATH . '/challenges/sqli_search.php',
    'challenge_idor'       => require ROOT_PATH . '/challenges/idor.php',
    'challenge_xss'        => require ROOT_PATH . '/challenges/xss_reflect.php',
    'challenge_info'       => require ROOT_PATH . '/challenges/info_disclosure.php',
    default                => require ROOT_PATH . '/auth.php',
};
