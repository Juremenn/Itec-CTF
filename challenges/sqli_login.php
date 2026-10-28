<?php
declare(strict_types=1);

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/includes/challenge_view.php';

// ══════════════════════════════════════════════════════════════════════════════
//  Challenge 1 – SQL Injection: Login Bypass
//  VULNERABILITY: Intentionally unescaped raw string concatenation.
//  Payload: admin' --
// ══════════════════════════════════════════════════════════════════════════════

$db = get_db();
$vuln_msg = '';
$flag_revealed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vuln_login'])) {
    $username = $_POST['vuln_username'] ?? '';
    $password = $_POST['vuln_password'] ?? '';

    // Dangerous query for training demonstration:
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";

    $result_row = null;
    try {
        $result_row = $db->query($sql)->fetch();
    } catch (\PDOException $e) {
        $vuln_msg = 'SQL Exception: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    if ($result_row) {
        $vuln_msg = 'Authentication successful as <strong>' . e($result_row['username']) . '</strong>.';
        $flag_revealed = true;
    } elseif (!$vuln_msg) {
        $vuln_msg = 'Authentication failed: invalid credentials.';
    }
}

// ── Build Scenario & Theory HTML (Left Column) ────────────────────────────────
ob_start();
?>
<div class="cylab-scenario-content">
    <p class="scenario-intro">
        Can you authenticate into the administrative control gateway without possessing valid credentials?
        The backend concatenates raw operator inputs directly into dynamic SQL statements without parameterization.
    </p>

    <div class="cylab-info-box">
        <h4 class="info-box-title">Execution Objective</h4>
        <ul class="cylab-checklist">
            <li>Inject SQL logic syntax into the target authentication form below.</li>
            <li>Bypass password validation and authenticate as <code class="mono-inline">admin</code>.</li>
            <li>Capture the revealed flag token and submit it in the field below.</li>
        </ul>
    </div>

    <div class="cylab-theory-box">
        <h4 class="info-box-title">Theoretical Vector Analysis</h4>
        <p class="text-secondary text-sm">
            When input is directly concatenated, an attacker can terminate the string literal early and comment out the rest of the query:
        </p>
        <pre><code class="language-sql">-- Target query structure:
SELECT * FROM users WHERE username = 'alice' AND password = 'secret'

-- Injected payload: username = admin' --
SELECT * FROM users WHERE username = 'admin' --' AND password = '...'
-- The '--' sequence causes the database engine to ignore the password condition!</code></pre>
    </div>
</div>
<?php
$scenario_html = ob_get_clean();

// ── Build Playground HTML (Target Login Console) ──────────────────────────────
ob_start();
?>
<div class="cylab-playground-card" id="vuln-interactive">
    <div class="playground-card-header">
        <h4 class="playground-card-title">Target Authentication Gateway</h4>
        <span class="playground-card-endpoint">Target Endpoint: <code class="mono-inline">ShopEZ Enterprise Console</code></span>
    </div>

    <form method="POST" novalidate id="vuln-login-form" class="cylab-vuln-form">
        <input type="hidden" name="vuln_login" value="1">
        
        <div class="form-field">
            <label for="vuln-username" class="form-field-label">Username</label>
            <div class="input-container">
                <span class="input-prefix-icon"><?= svg_icon('user', '', 15) ?></span>
                <input
                    type="text"
                    id="vuln-username"
                    name="vuln_username"
                    class="form-control"
                    placeholder="admin"
                    autocomplete="off"
                >
            </div>
        </div>

        <div class="form-field">
            <label for="vuln-password" class="form-field-label">Password</label>
            <div class="input-container">
                <span class="input-prefix-icon"><?= svg_icon('lock', '', 15) ?></span>
                <input
                    type="text"
                    id="vuln-password"
                    name="vuln_password"
                    class="form-control"
                    placeholder="password"
                    autocomplete="off"
                >
            </div>
        </div>

        <button type="submit" class="btn btn-secondary btn-full mt-2" id="vuln-login-btn">
            <span>Authenticate</span>
            <span class="btn-arrow">&rarr;</span>
        </button>
    </form>

    <?php if ($vuln_msg): ?>
    <div class="result-box <?= $flag_revealed ? 'result-success' : 'result-error' ?> mt-3" id="sqli-result">
        <div class="result-msg"><?= $vuln_msg ?></div>
        <?php if ($flag_revealed): ?>
        <div class="flag-reveal mt-2">
            <div class="flag-label">Flag Unlocked:</div>
            <code class="flag-code" id="revealed-flag"><?= e(FLAGS['sqli_login']) ?></code>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Live SQL Preview -->
    <div class="sql-preview-box mt-3">
        <div class="preview-title">Live SQL Query Preview:</div>
        <pre class="preview-code">SELECT * FROM users WHERE username = '<span id="prev-user">...</span>' AND password = '<span id="prev-pass">...</span>'</pre>
    </div>
</div>

<script>
(function() {
    const u = document.getElementById('vuln-username');
    const p = document.getElementById('vuln-password');
    const pu = document.getElementById('prev-user');
    const pp = document.getElementById('prev-pass');
    function update() {
        if (pu) pu.textContent = u.value || '...';
        if (pp) pp.textContent = p.value || '...';
    }
    u && u.addEventListener('input', update);
    p && p.addEventListener('input', update);
})();
</script>
<?php
$playground_html = ob_get_clean();

$hint = "Try commenting out the password check using SQLite comment syntax: admin' --";
$artifacts = [
    'static query source' => '#vuln-interactive',
    'ShopEZ schema'       => '#vuln-interactive',
];

// Render using CyLab Two-Column layout
render_cylab_challenge_view('sqli_login', $scenario_html, $playground_html, $hint, $artifacts);
