<?php
declare(strict_types=1);

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/includes/challenge_view.php';

// ══════════════════════════════════════════════════════════════════════════════
//  Challenge 4 – Reflected XSS
//  VULNERABILITY: Raw unescaped reflection of ?q= parameter.
// ══════════════════════════════════════════════════════════════════════════════

$raw_query = $_GET['q'] ?? '';

if (!isset($_COOKIE['xss_demo_session'])) {
    setrawcookie('xss_demo_session', FLAGS['xss_reflect'], [
        'expires'  => time() + 3600,
        'path'     => '/',
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
}

// ── Scenario HTML ─────────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-scenario-content">
    <p class="scenario-intro">
        Can you execute client-side JavaScript within the application context to inspect sensitive browser storage?
        The article query endpoint reflects user search strings directly into the DOM response without contextual HTML character entity escaping.
    </p>

    <div class="cylab-info-box">
        <h4 class="info-box-title">Execution Objective</h4>
        <ul class="cylab-checklist">
            <li>Formulate an inline or reflected XSS execution payload targeting the search input.</li>
            <li>Access <code class="mono-inline">document.cookie</code> to read the demonstration session cookie.</li>
            <li>The flag token is stored inside <code class="mono-inline">xss_demo_session</code>. Submit it in the flag form.</li>
        </ul>
    </div>

    <div class="cylab-theory-box">
        <h4 class="info-box-title">Sample Vectors</h4>
        <pre><code class="language-html">&lt;!-- Script tag injection --&gt;
&lt;script&gt;alert(document.cookie)&lt;/script&gt;

&lt;!-- Image error handler --&gt;
&lt;img src=x onerror="alert(document.cookie)"&gt;</code></pre>
    </div>
</div>
<?php
$scenario_html = ob_get_clean();

// ── Playground HTML ───────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-playground-card" id="vuln-interactive">
    <div class="playground-card-header">
        <h4 class="playground-card-title">Target Search Dispatcher</h4>
        <span class="playground-card-endpoint">Target Endpoint: <code class="mono-inline">TechBlog Search Controller</code></span>
    </div>

    <form method="GET" novalidate id="xss-form" class="cylab-vuln-form">
        <input type="hidden" name="page" value="challenge_xss">
        <div class="button-row">
            <div class="input-container flex-1">
                <span class="input-prefix-icon"><?= svg_icon('search', '', 15) ?></span>
                <input
                    type="text"
                    name="q"
                    id="xss-input"
                    class="form-control"
                    placeholder="Search articles or inject script..."
                    value="<?= htmlspecialchars($raw_query, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn btn-secondary" id="xss-search-btn">Search</button>
        </div>
    </form>

    <?php if ($raw_query !== ''): ?>
    <div class="result-box result-info mt-3" id="xss-reflection">
        <div class="text-xs text-muted mb-1 font-medium">Reflected Query Output:</div>
        <div class="xss-output-echo" id="reflected-output">
            <!-- INTENTIONALLY VULNERABLE: DO NOT ADD htmlspecialchars HERE -->
            <?= $raw_query ?>
        </div>
        <div class="text-xs text-muted mt-2">Zero matching catalog articles discovered.</div>
    </div>
    <?php endif; ?>

    <!-- Cookie Inspector Box -->
    <div class="cylab-cookie-box mt-3">
        <div class="cookie-box-header">
            <span class="font-semibold text-xs text-primary">Browser Context Storage Inspector</span>
            <span class="badge badge-subtle">Key: xss_demo_session</span>
        </div>
        <div class="cookie-box-body" id="cookie-display">
            <span class="text-muted text-xs">Execute XSS to extract cookie from DOM context, or inspect below.</span>
        </div>
        <button type="button" class="btn btn-ghost btn-sm mt-2" id="reveal-cookie-btn" onclick="
            document.getElementById('cookie-display').innerHTML = '<code class=\'mono-inline\'>' + document.cookie + '</code>';
            this.textContent = 'Storage Inspected';
        ">
            Read document.cookie
        </button>
    </div>
</div>
<?php
$playground_html = ob_get_clean();

$hint = "Try injecting: <script>alert(document.cookie)</script> into the search input. The flag is stored inside xss_demo_session.";
$artifacts = [
    'TechBlog search endpoint' => '#vuln-interactive',
    'xss_demo_session cookie'  => '#vuln-interactive',
];

render_cylab_challenge_view('xss_reflect', $scenario_html, $playground_html, $hint, $artifacts);
