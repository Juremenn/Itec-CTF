<?php
declare(strict_types=1);

require_once ROOT_PATH . '/layout.php';

$user    = current_user();
$user_id = (int)($_SESSION['user_id'] ?? 0);
$solved  = solved_challenges($user_id);
$score   = total_score($user_id);
$total   = array_sum(array_column(CHALLENGE_META, 'points'));
$ctf     = get_ctf_state();

layout_head('Dashboard');
layout_sidebar('dashboard');
?>

<div class="cylab-page-container">
    <!-- ── Overview Summary Header ─────────────────────────────────────────── -->
    <section class="overview-header-card" aria-label="Overview">
        <div class="overview-info">
            <h1 class="overview-title">Security Lab Environment</h1>
            <p class="overview-description">
                Hands-on cybersecurity challenges designed for web application security testing.
                Find and exploit application flaws to retrieve flags in the format <code class="mono-inline">FLAG{...}</code>.
            </p>
        </div>

        <div class="overview-stats-grid">
            <div class="stat-box">
                <span class="stat-label">Labs Solved</span>
                <span class="stat-value text-accent"><?= count($solved) ?> <span class="stat-total">/ <?= count(CHALLENGE_META) ?></span></span>
            </div>
            <div class="stat-box">
                <span class="stat-label">Total Points</span>
                <span class="stat-value"><?= $score ?> <span class="stat-total">/ <?= $total ?></span></span>
            </div>
            <div class="stat-box">
                <span class="stat-label">Remaining</span>
                <span class="stat-value"><?= count(CHALLENGE_META) - count($solved) ?></span>
            </div>
            <div class="stat-box">
                <span class="stat-label">Competition</span>
                <span class="stat-value stat-status <?= $ctf['is_active'] ? 'text-accent' : 'text-danger' ?>">
                    <?= $ctf['is_active'] ? 'Active' : 'Paused' ?>
                </span>
            </div>
        </div>
    </section>

    <!-- ── Challenge Grid ─────────────────────────────────────────────────── -->
    <section class="content-section" aria-label="Available Challenges">
        <div class="section-title-bar">
            <div>
                <h2 class="section-heading">Available Challenges</h2>
                <p class="section-subheading">Select a target environment to begin analysis and exploitation.</p>
            </div>
        </div>

        <div class="challenge-cards-grid" id="challenge-grid">
            <?php foreach (CHALLENGE_META as $id => $ch): ?>
                <?php
                    $is_solved  = in_array($id, $solved, true);
                    $page_key   = match($id) {
                        'sqli_login'      => 'challenge_sqli_login',
                        'sqli_search'     => 'challenge_sqli_search',
                        'idor'            => 'challenge_idor',
                        'xss_reflect'     => 'challenge_xss',
                        'info_disclosure' => 'challenge_info',
                        default           => 'dashboard'
                    };
                    $category   = $ch['category'] ?? 'Web Exploitation';
                    $difficulty = $ch['difficulty'] ?? 'Easy';
                    $points     = (int)($ch['points'] ?? 100);
                ?>
                <article class="card challenge-item-card <?= $is_solved ? 'card-solved' : '' ?>" id="card-<?= e($id) ?>">
                    <!-- Card Header -->
                    <div class="challenge-item-header">
                        <span class="badge badge-category"><?= e($category) ?></span>
                        <div class="challenge-header-tags">
                            <?php if ($is_solved): ?>
                                <span class="badge badge-success"><?= svg_icon('check', '', 12) ?> Solved</span>
                            <?php else: ?>
                                <span class="badge badge-difficulty"><?= e($difficulty) ?></span>
                                <span class="badge badge-points"><?= $points ?> pts</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="challenge-item-body">
                        <h3 class="challenge-item-title"><?= e($ch['title']) ?></h3>
                        <p class="challenge-item-desc"><?= e($ch['description']) ?></p>
                    </div>

                    <!-- Card Footer -->
                    <div class="challenge-item-footer">
                        <div class="challenge-footer-meta">
                            <?php if ($is_solved): ?>
                                <span class="text-accent text-sm font-medium">Completed (+<?= $points ?> pts)</span>
                            <?php else: ?>
                                <span class="text-muted text-sm"><?= $points ?> points available</span>
                            <?php endif; ?>
                        </div>
                        <a href="/index.php?page=<?= $page_key ?>"
                           class="btn <?= $is_solved ? 'btn-ghost' : 'btn-primary' ?> btn-sm"
                           id="start-<?= e($id) ?>">
                            <span><?= $is_solved ? 'Revisit Lab' : 'Launch Lab' ?></span>
                            <span class="btn-arrow">&rarr;</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── Vulnerability Reference ────────────────────────────────────────── -->
    <section class="content-section" aria-label="Reference Guides">
        <div class="section-title-bar">
            <div>
                <h2 class="section-heading">Vulnerability Reference</h2>
                <p class="section-subheading">Overview of common security weaknesses and exploitation fundamentals.</p>
            </div>
        </div>

        <div class="reference-cards-grid">
            <div class="card ref-item-card">
                <div class="ref-card-category">SQL Injection</div>
                <h4 class="ref-card-title">Authentication Bypass &amp; UNION Extraction</h4>
                <p class="ref-card-text">
                    Arises when dynamic database queries concatenate unsanitized user inputs. Attackers bypass authentication with tautologies like <code class="mono-inline">' OR '1'='1</code> or leverage <code class="mono-inline">UNION SELECT</code> after probing column counts with <code class="mono-inline">ORDER BY</code> to read hidden database tables.
                </p>
                <div class="ref-card-meta">
                    <span class="badge badge-subtle">CWE-89</span>
                    <span class="text-muted text-xs">High Severity</span>
                </div>
            </div>

            <div class="card ref-item-card">
                <div class="ref-card-category">Access Control</div>
                <h4 class="ref-card-title">Insecure Direct Object Reference (IDOR)</h4>
                <p class="ref-card-text">
                    Occurs when an application uses client-controllable input to directly access an object in storage without verifying permissions. By incrementing or modifying numeric parameters in URL queries, attackers access sensitive records belonging to other users or administrators.
                </p>
                <div class="ref-card-meta">
                    <span class="badge badge-subtle">CWE-639</span>
                    <span class="text-muted text-xs">High Severity</span>
                </div>
            </div>

            <div class="card ref-item-card">
                <div class="ref-card-category">Client-Side Security</div>
                <h4 class="ref-card-title">Reflected Cross-Site Scripting (XSS)</h4>
                <p class="ref-card-text">
                    Occurs when query parameters are reflected back into the HTTP response without contextual HTML encoding. When viewed in a victim's browser, injected script payloads execute in the user context, enabling session cookie theft through <code class="mono-inline">document.cookie</code>.
                </p>
                <div class="ref-card-meta">
                    <span class="badge badge-subtle">CWE-79</span>
                    <span class="text-muted text-xs">Medium Severity</span>
                </div>
            </div>

            <div class="card ref-item-card">
                <div class="ref-card-category">Reconnaissance</div>
                <h4 class="ref-card-title">Information Disclosure &amp; Staging Endpoints</h4>
                <p class="ref-card-text">
                    Happens when internal endpoints, development tools, or configuration details are left publicly accessible. Probing crawler indexing directives like <code class="mono-inline">/robots.txt</code>, reading HTML source comments, and testing common path conventions reveal forgotten interfaces.
                </p>
                <div class="ref-card-meta">
                    <span class="badge badge-subtle">CWE-200</span>
                    <span class="text-muted text-xs">Low-Medium Severity</span>
                </div>
            </div>
        </div>
    </section>
</div>

<?php layout_foot(); ?>
