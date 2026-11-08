<?php
declare(strict_types=1);

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/includes/challenge_view.php';

// ══════════════════════════════════════════════════════════════════════════════
//  Challenge 5 – Information Disclosure & Reconnaissance
//  VULNERABILITY: Unauthenticated administrative debug console at /admin/panel.php
// ══════════════════════════════════════════════════════════════════════════════

// ── Scenario HTML ─────────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-scenario-content">
    <p class="scenario-intro">
        Can you locate the unauthenticated staging console left behind during deployment?
        Development artifacts and a privileged debug interface were accidentally exposed in the webroot. Discover the hidden endpoint using standard reconnaissance enumeration.
    </p>

    <div class="cylab-info-box">
        <h4 class="info-box-title">Execution Objective</h4>
        <ul class="cylab-checklist">
            <li>Leverage reconnaissance vectors (robots.txt, source comments, directory probing).</li>
            <li>Access the exposed console (zero authentication required).</li>
            <li>Extract the revealed flag token from the interface and submit below.</li>
        </ul>
    </div>

    <div class="cylab-theory-box">
        <h4 class="info-box-title">Reconnaissance Vectors</h4>
        <pre><code class="language-text">GET /robots.txt HTTP/1.1
---
User-agent: *
Disallow: /admin/
Disallow: /backup/</code></pre>
    </div>
</div>
<?php
$scenario_html = ob_get_clean();

// ── Playground HTML ───────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-playground-card" id="vuln-interactive">
    <div class="playground-card-header">
        <h4 class="playground-card-title">Reconnaissance Dispatch Console</h4>
        <span class="playground-card-endpoint">Target Host: <code class="mono-inline">http://127.0.0.1:8080</code></span>
    </div>

    <div class="recon-endpoint-grid mt-3">
        <a href="/robots.txt" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" id="link-robots">
            <span class="mono-inline">/robots.txt</span>
            <span class="btn-arrow">&rarr;</span>
        </a>
        <a href="/" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" id="link-root">
            <span>/ (View Page Source)</span>
            <span class="btn-arrow">&rarr;</span>
        </a>
        <a href="/admin/" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" id="link-admin">
            <span class="mono-inline">/admin/</span>
            <span class="btn-arrow">&rarr;</span>
        </a>
        <a href="/admin/panel.php" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" id="link-panel">
            <span class="mono-inline">/admin/panel.php</span>
            <span class="btn-arrow">&rarr;</span>
        </a>
    </div>

    <!-- Direct Route Prober -->
    <div class="mt-4">
        <label for="path-input" class="form-field-label">Direct Route Prober</label>
        <div class="button-row">
            <div class="input-container flex-1">
                <span class="input-prefix-icon"><?= svg_icon('search', '', 15) ?></span>
                <input
                    type="text"
                    id="path-input"
                    class="form-control"
                    placeholder="e.g. admin/panel.php"
                    value=""
                >
            </div>
            <button class="btn btn-secondary" id="path-test-btn" onclick="
                var p = document.getElementById('path-input').value.replace(/^\//,'');
                window.open('/' + p, '_blank');
            ">
                Probe Route
            </button>
        </div>
    </div>

    <!-- Discovered DOM Hint -->
    <div class="mt-3">
        <span class="text-xs text-muted font-medium mb-1 d-block">Discovered DOM Comment:</span>
        <pre><code class="language-html">&lt;!-- Developer Staging Note:
     Admin debug interface exposed at /admin/panel.php
     TODO: Implement authentication middleware before production deployment.
     Staging credential reference: Adm1n@Secr3t!2024
--&gt;</code></pre>
    </div>
</div>
<?php
$playground_html = ob_get_clean();

$hint = "Inspect /robots.txt or browse directly to /admin/panel.php to find the secret console.";
$artifacts = [
    '/robots.txt'       => '/robots.txt',
    '/admin/panel.php'  => '/admin/panel.php',
];

render_cylab_challenge_view('info_disclosure', $scenario_html, $playground_html, $hint, $artifacts);
