<?php
declare(strict_types=1);

/**
 * CyLab Academy Challenge View Template (Two-Column Layout)
 * Path: includes/challenge_view.php
 */

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/layout.php';

function render_cylab_challenge_view(
    string $challenge_id,
    string $scenario_html,
    string $playground_html,
    string $hint = '',
    array $artifacts = []
): void {
    $db = get_db();
    $user = current_user();
    $user_id = (int)($_SESSION['user_id'] ?? 0);
    $ctf = get_ctf_state();
    $meta = CHALLENGE_META[$challenge_id] ?? [];

    $title      = $meta['title'] ?? 'Challenge';
    $category   = $meta['category'] ?? 'General Skills';
    $difficulty = $meta['difficulty'] ?? 'Easy';
    $points     = (int)($meta['points'] ?? 100);

    // Check if current challenge is solved
    $stmt = $db->prepare("SELECT id FROM solved WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$user_id, $challenge_id]);
    $already_solved = (bool)$stmt->fetch();

    // Handle flag submission
    $flag_msg = '';
    $flag_type = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_flag'])) {
        verify_csrf();

        if (!$ctf['is_active'] && !is_admin()) {
            $flag_msg  = 'Competition is currently paused. Flag submissions are disabled.';
            $flag_type = 'error';
        } else {
            $submitted = urldecode(trim($_POST['flag'] ?? ''));
            $correct   = FLAGS[$challenge_id] ?? '';

            if ($submitted === $correct) {
                if (!$already_solved) {
                    $ins = $db->prepare(
                        "INSERT OR IGNORE INTO solved (user_id, challenge_id, points) VALUES (?, ?, ?)"
                    );
                    $ins->execute([$user_id, $challenge_id, $points]);
                    $flag_msg = "Flag verified successfully! <strong>+{$points} points</strong> credited.";
                    $flag_type = 'success';
                    $already_solved = true;
                } else {
                    $flag_msg = "You have already completed this lab.";
                    $flag_type = 'info';
                }
            } else {
                $flag_msg = "Incorrect flag token. Check your payload and syntax.";
                $flag_type = 'error';
            }
        }
    }

    // Determine Previous and Next challenge keys
    $keys = array_keys(CHALLENGE_META);
    $curr_idx = array_search($challenge_id, $keys, true);
    $prev_key = ($curr_idx !== false && $curr_idx > 0) ? $keys[$curr_idx - 1] : null;
    $next_key = ($curr_idx !== false && $curr_idx < count($keys) - 1) ? $keys[$curr_idx + 1] : null;

    $page_map = [
        'sqli_login'      => 'challenge_sqli_login',
        'sqli_search'     => 'challenge_sqli_search',
        'idor'            => 'challenge_idor',
        'xss_reflect'     => 'challenge_xss',
        'info_disclosure' => 'challenge_info',
    ];

    $prev_url = $prev_key ? '/index.php?page=' . ($page_map[$prev_key] ?? 'dashboard') : null;
    $next_url = $next_key ? '/index.php?page=' . ($page_map[$next_key] ?? 'dashboard') : null;

    // Leaderboard data for the Right Column Scoreboard widget
    $leaders = $db->query(
        "SELECT u.id, u.username, u.role,
                COALESCE(SUM(s.points), 0) AS total_points,
                COUNT(s.id) AS solved_count
         FROM   users u
         LEFT JOIN solved s ON s.user_id = u.id
         WHERE  u.role = 'student'
         GROUP BY u.id
         ORDER BY total_points DESC, u.id ASC
         LIMIT 8"
    )->fetchAll();

    $my_solves = solved_challenges($user_id);
    $total_modules = count(CHALLENGE_META);
    $progress_pct = $total_modules > 0 ? min(100, round((count($my_solves) / $total_modules) * 100)) : 0;

    // Start rendering with includes/header.php
    $header_title = $title;
    $include_highlight = true;
    require ROOT_PATH . '/includes/header.php';
    ?>

    <div class="cylab-page-container">

        <!-- ── Top Header Navigation Bar ──────────────────────────────────────── -->
        <div class="cylab-challenge-header-bar">
            <!-- Breadcrumbs trail -->
            <nav class="cylab-breadcrumbs" aria-label="Breadcrumb">
                <a href="/index.php?page=dashboard" class="crumb-link">Learning Paths</a>
                <span class="crumb-separator">&rsaquo;</span>
                <a href="/index.php?page=dashboard#challenges" class="crumb-link"><?= e($category) ?></a>
                <span class="crumb-separator">&rsaquo;</span>
                <span class="crumb-current"><?= e($title) ?></span>
            </nav>

            <!-- Navigation Controls: Overview, Previous, Next -->
            <div class="cylab-header-actions">
                <a href="/index.php?page=dashboard" class="cylab-btn-back">
                    <svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    <span>Overview</span>
                </a>

                <div class="cylab-prev-next-group">
                    <?php if ($prev_url): ?>
                        <a href="<?= $prev_url ?>" class="cylab-btn-nav" title="Previous Challenge">Previous</a>
                    <?php else: ?>
                        <span class="cylab-btn-nav disabled">Previous</span>
                    <?php endif; ?>

                    <?php if ($next_url): ?>
                        <a href="<?= $next_url ?>" class="cylab-btn-nav" title="Next Challenge">Next</a>
                    <?php else: ?>
                        <span class="cylab-btn-nav disabled">Next</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Big Challenge Title ────────────────────────────────────────────── -->
        <h1 class="cylab-challenge-title"><?= e($title) ?></h1>

        <!-- ── Main Two-Column Layout (70% Left, 30% Right) ──────────────────── -->
        <div class="cylab-two-column-layout">

            <!-- ══ LEFT COLUMN (70% width): Main Challenge Card ═════════════════ -->
            <div class="cylab-left-column">
                <article class="cylab-challenge-card" id="challenge-card-<?= e($challenge_id) ?>">

                    <!-- Card Header Meta -->
                    <div class="cylab-card-meta-row">
                        <div class="cylab-badges-wrap">
                            <span class="cylab-badge cylab-badge-category"><?= e($category) ?></span>
                            <span class="cylab-badge cylab-badge-<?= strtolower($difficulty) ?>"><?= e($difficulty) ?></span>
                        </div>
                        <div class="cylab-author-meta">
                            <span class="author-label">by ITEC CyberLab</span>
                            <span class="meta-dot">•</span>
                            <span class="meta-points"><?= $points ?> points</span>
                            <span class="meta-dot">•</span>
                            <span class="meta-source">picoCTF style</span>
                        </div>
                    </div>

                    <!-- Problem Narrative & Scenario -->
                    <div class="cylab-card-body">
                        <?= $scenario_html ?>

                        <?php if (!empty($artifacts)): ?>
                        <div class="cylab-artifacts-block">
                            <span class="artifacts-label">Downloadable Artifacts &amp; Direct Endpoints:</span>
                            <div class="artifacts-links">
                                <?php foreach ($artifacts as $art_name => $art_url): ?>
                                    <a href="<?= e($art_url) ?>" target="_blank" rel="noopener" class="cylab-artifact-link">
                                        <span><?= e($art_name) ?></span>
                                        <svg class="svg-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Interactive Target Playground -->
                    <?php if ($playground_html): ?>
                    <div class="cylab-playground-container">
                        <?= $playground_html ?>
                    </div>
                    <?php endif; ?>

                    <!-- ── Flag Submission Form (Integrated Input + Orange Submit) ── -->
                    <div class="cylab-flag-section">
                        <div class="cylab-flag-label-row">
                            <label for="flag-input-<?= e($challenge_id) ?>" class="cylab-flag-label">
                                <span>FLAG</span>
                                <span class="cylab-info-circle" title="Format: FLAG{...} &mdash; Search or exploit target to reveal the token">i</span>
                            </label>
                            <?php if ($already_solved): ?>
                                <span class="badge-solved-inline">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>Solved &mdash; <?= $points ?> pts</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($flag_msg): ?>
                            <div class="alert alert-<?= $flag_type ?> mt-2 mb-2" role="alert">
                                <div><?= $flag_msg ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Integrated Input Group with Inline Warm Amber/Orange Submit -->
                        <form method="POST" class="cylab-flag-form" novalidate id="flag-form-<?= e($challenge_id) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="submit_flag" value="1">

                            <div class="cylab-flag-input-group <?= $already_solved ? 'is-solved' : '' ?>">
                                <input
                                    type="text"
                                    name="flag"
                                    id="flag-input-<?= e($challenge_id) ?>"
                                    class="cylab-flag-input mono"
                                    placeholder="FLAG{...}"
                                    autocomplete="off"
                                    required
                                >
                                <button type="submit" class="cylab-flag-submit-btn" id="flag-submit-btn">
                                    Submit
                                </button>
                            </div>
                        </form>

                        <div class="cylab-flag-debug-info">
                            <span>debug info: [u:<?= e($user['username'] ?? 'guest') ?> challenge:<?= e($challenge_id) ?> pts:<?= $points ?>]</span>
                        </div>
                    </div>

                    <!-- ── Bottom Action Bar ──────────────────────────────────── -->
                    <div class="cylab-card-bottom-bar">
                        <div class="bottom-bar-left">
                            <button type="button" class="cylab-action-pill" id="btn-bookmark" onclick="
                                this.classList.toggle('active');
                                this.querySelector('span').textContent = this.classList.contains('active') ? 'Bookmarked' : 'Bookmark';
                            ">
                                <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                <span>Bookmark</span>
                            </button>

                            <div class="cylab-stat-badge">
                                <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span>104,842 solves</span>
                                <span class="meta-dot">•</span>
                                <span>87%</span>
                            </div>

                            <?php if ($hint): ?>
                            <details class="cylab-hint-dropdown">
                                <summary class="cylab-hint-summary">
                                    <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    <span>Hint</span>
                                </summary>
                                <div class="cylab-hint-popover">
                                    <p><?= e($hint) ?></p>
                                </div>
                            </details>
                            <?php endif; ?>
                        </div>

                        <div class="bottom-bar-right">
                            <a href="#vuln-interactive" class="cylab-workspace-btn" onclick="
                                const target = document.querySelector('.cylab-playground-container');
                                if (target) target.scrollIntoView({ behavior: 'smooth' });
                            ">
                                <svg class="svg-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                <span>Open in Workspace</span>
                            </a>
                        </div>
                    </div>

                </article>
            </div>

            <!-- ══ RIGHT COLUMN (30% width): Live Scoreboard Widget ════════════ -->
            <aside class="cylab-right-column" aria-label="Scoreboard Standings">
                <div class="cylab-scoreboard-card">

                    <!-- Header -->
                    <div class="scoreboard-card-header">
                        <h2 class="scoreboard-heading">Scoreboard</h2>
                        <span class="scoreboard-counter"><?= count($my_solves) ?>/<?= $total_modules ?> modules</span>
                    </div>

                    <!-- Warm Orange Progress Bar (CyLab Signature) -->
                    <div class="cylab-progress-track">
                        <div class="cylab-progress-fill" style="width: <?= $progress_pct ?>%"></div>
                    </div>

                    <!-- Participant List -->
                    <div class="scoreboard-user-list">
                        <?php if (empty($leaders)): ?>
                            <div class="scoreboard-empty-state">
                                <p class="text-muted text-xs">No participant records recorded yet.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($leaders as $idx => $lead): ?>
                                <?php
                                    $rank = $idx + 1;
                                    $is_me = ($user_id === (int)$lead['id']);
                                    $has_solved = ((int)$lead['solved_count'] > 0);
                                ?>
                                <div class="scoreboard-row <?= $is_me ? 'row-is-user' : '' ?>">
                                    <!-- Status indicator (Green checkmark if solved, hollow circle if pending) -->
                                    <div class="row-status-icon">
                                        <?php if ($has_solved): ?>
                                            <span class="status-check-circle" title="Has solved modules">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-hollow-circle" title="In progress">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Participant Details -->
                                    <div class="row-user-details">
                                        <div class="row-user-title">
                                            <span class="row-rank-num">#<?= $rank ?></span>
                                            <span class="row-username <?= $is_me ? 'text-accent-orange font-semibold' : '' ?>">
                                                <?= e($lead['username']) ?>
                                            </span>
                                            <?php if ($is_me): ?>
                                                <span class="badge-you-pill">You</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row-user-sub">
                                            <?= (int)$lead['solved_count'] ?>/<?= $total_modules ?> modules completed
                                        </div>
                                    </div>

                                    <!-- Score Pill -->
                                    <div class="row-score-pill">
                                        <?= (int)$lead['total_points'] ?> pts
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Link -->
                    <div class="scoreboard-card-footer">
                        <a href="/index.php?page=scoreboard" class="scoreboard-footer-link">
                            <span>View Complete Standings</span>
                            <span class="arrow">&rarr;</span>
                        </a>
                    </div>

                </div>
            </aside>

        </div><!-- /.cylab-two-column-layout -->

    </div><!-- /.cylab-page-container -->

    <?php
    require ROOT_PATH . '/includes/footer.php';
}
