<?php
declare(strict_types=1);

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/includes/challenge_view.php';

// ══════════════════════════════════════════════════════════════════════════════
//  Challenge 3 – IDOR: Insecure Direct Object Reference
//  VULNERABILITY: Unauthenticated direct primary key lookup.
//  Attack: ?id=99 (privileged administrator record)
// ══════════════════════════════════════════════════════════════════════════════

$db = get_db();
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_SESSION['user_id'] ?? 1);

$stmt = $db->prepare("SELECT * FROM profiles WHERE id = ?");
$stmt->execute([$id]);
$profile = $stmt->fetch();

$all_profiles = $db->query(
    "SELECT id, username, role FROM profiles WHERE role != 'admin' ORDER BY id ASC"
)->fetchAll();

// ── Scenario HTML ─────────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-scenario-content">
    <p class="scenario-intro">
        Can you uncover the hidden administrator profile and its privileged secrets?
        The identity endpoint directly dereferences a user-supplied numeric identifier without verifying the caller's authorization or ownership context.
    </p>

    <div class="cylab-info-box">
        <h4 class="info-box-title">Execution Objective</h4>
        <ul class="cylab-checklist">
            <li>Inspect the enumerated public directory table in the workspace console.</li>
            <li>Probe the <code class="mono-inline">?id=</code> parameter beyond the listed public identifiers.</li>
            <li>Locate the elevated administrative record and extract the stored flag token.</li>
        </ul>
    </div>

    <div class="cylab-theory-box">
        <h4 class="info-box-title">Theoretical Mechanics</h4>
        <p class="text-secondary text-sm">
            IDOR occurs when an application exposes database primary keys in URL parameters without validating caller ownership:
        </p>
        <pre><code class="language-http">GET /profile?id=1   -- Standard Public Record
GET /profile?id=99  -- Privileged High-Order Record</code></pre>
    </div>
</div>
<?php
$scenario_html = ob_get_clean();

// ── Playground HTML ───────────────────────────────────────────────────────────
ob_start();
?>
<div class="cylab-playground-card" id="vuln-interactive">
    <div class="playground-card-header">
        <h4 class="playground-card-title">Profile Record Inspector</h4>
        <span class="playground-card-endpoint">Active Query: <code class="mono-inline">?id=<?= e((string)$id) ?></code></span>
    </div>

    <form method="GET" class="cylab-vuln-form" id="idor-form">
        <input type="hidden" name="page" value="challenge_idor">
        <div class="button-row">
            <div class="input-container flex-1">
                <span class="input-prefix-icon"><?= svg_icon('terminal', '', 15) ?></span>
                <input
                    type="number"
                    name="id"
                    id="idor-id-input"
                    class="form-control"
                    placeholder="Target Object ID"
                    value="<?= e((string)$id) ?>"
                    min="1"
                >
            </div>
            <button type="submit" class="btn btn-secondary" id="idor-go-btn">Inspect</button>
        </div>
    </form>

    <?php if ($profile): ?>
    <div class="cylab-profile-box mt-3 <?= ($profile['role'] === 'admin') ? 'is-admin-profile' : '' ?>">
        <div class="profile-avatar-lg">
            <?= strtoupper(substr($profile['username'], 0, 2)) ?>
        </div>
        <div class="profile-details-main">
            <div class="profile-name-row">
                <span class="profile-name"><?= e($profile['username']) ?></span>
                <span class="badge <?= $profile['role'] === 'admin' ? 'badge-admin' : 'badge-student' ?>">
                    <?= ucfirst(e($profile['role'])) ?>
                </span>
                <span class="badge badge-subtle">ID: #<?= e((string)$profile['id']) ?></span>
            </div>
            <div class="profile-bio-text"><?= e($profile['bio'] ?? '') ?></div>
            <?php if ($profile['role'] === 'admin' && !empty($profile['secret'])): ?>
            <div class="flag-reveal mt-2">
                <div class="flag-label">Privileged Record Found &mdash; Flag Unlocked:</div>
                <code class="flag-code" id="idor-flag"><?= e($profile['secret']) ?></code>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="result-box result-error mt-3" id="no-profile">
        Record not found: Object identifier <code class="mono-inline">id=<?= e((string)$id) ?></code> yielded no results.
    </div>
    <?php endif; ?>

    <!-- User Directory -->
    <div class="mt-4">
        <h5 class="text-sm font-semibold mb-2 text-primary">Public Directory Listing</h5>
        <div class="table-surface-card">
            <table class="saas-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_profiles as $p): ?>
                    <tr>
                        <td class="text-muted text-xs mono">#<?= sprintf('%02d', (int)$p['id']) ?></td>
                        <td class="font-medium text-primary"><?= e($p['username']) ?></td>
                        <td><span class="badge badge-<?= e($p['role']) ?>"><?= ucfirst(e($p['role'])) ?></span></td>
                        <td>
                            <a href="/index.php?page=challenge_idor&id=<?= (int)$p['id'] ?>"
                               class="btn btn-ghost btn-sm"
                               id="view-profile-<?= (int)$p['id'] ?>">
                               Inspect
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <span class="field-hint mt-1">Sequence note: Primary keys are discontinuous. Check elevated ID numbers.</span>
    </div>
</div>
<?php
$playground_html = ob_get_clean();

$hint = "The administrator profile is hidden from the directory list. Try elevated ID values like id=99.";
$artifacts = [
    'public_directory' => '#vuln-interactive',
    'profile_endpoint' => '?page=challenge_idor&id=1',
];

render_cylab_challenge_view('idor', $scenario_html, $playground_html, $hint, $artifacts);
