<?php
declare(strict_types=1);

/**
 * CyLab Academy Design System – Live Scoreboard & Leaderboard
 * Path: scoreboard.php
 */

require_once ROOT_PATH . '/layout.php';

$db = get_db();
$ctf = get_ctf_state();

$filter = $_GET['filter'] ?? 'all';
if (!in_array($filter, ['all', 'students'], true)) {
    $filter = 'all';
}

$where_clause = ($filter === 'students') ? "WHERE u.role = 'student'" : "";

// Fetch leaderboard standings
$leaders = $db->query(
    "SELECT u.id, u.username, u.role, COALESCE(SUM(s.points), 0) AS total_points,
            COUNT(s.id) AS solved_count,
            MAX(s.solved_at) AS last_solve
     FROM   users u
     LEFT JOIN solved s ON s.user_id = u.id
     {$where_clause}
     GROUP BY u.id
     ORDER BY total_points DESC, last_solve ASC, u.id ASC
     LIMIT 50"
)->fetchAll();

// System statistics
$total_students = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_enrolled = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_solves   = (int)$db->query("SELECT COUNT(*) FROM solved")->fetchColumn();
$total_possible = array_sum(array_column(CHALLENGE_META, 'points'));
$top_scorer     = (!empty($leaders) && (int)$leaders[0]['total_points'] > 0)
    ? $leaders[0]['username'] . ' (' . $leaders[0]['total_points'] . 'p)'
    : 'None Yet';

$current_user = current_user();
$current_uid  = $current_user ? (int)$current_user['id'] : 0;
$my_solves    = $current_uid > 0 ? solved_challenges($current_uid) : [];
$is_auth      = is_logged_in();

layout_head('Scoreboard');
layout_sidebar('scoreboard');
?>

<div class="cylab-page-container">

    <!-- ══ Scoreboard Header & Key Metrics Bar ═══════════════════════════════ -->
    <div class="overview-header-card scoreboard-hero-card">
        <div class="overview-info">
            <h1 class="overview-title">Live Scoreboard</h1>
            <p class="overview-description">
                Real-time competitive standings and exploit capture metrics.
                Rankings update dynamically based on verified challenge flags.
                <?php if (!$is_auth): ?>
                    <a href="/index.php?page=register" class="text-accent-orange font-semibold ml-1">Create an account to join &rarr;</a>
                <?php endif; ?>
            </p>
        </div>

        <div class="overview-stats-grid">
            <div class="overview-stat-box">
                <span class="stat-box-label">Top Ranking</span>
                <span class="stat-box-value text-accent-orange" title="<?= e($top_scorer) ?>"><?= e($top_scorer) ?></span>
            </div>
            <div class="overview-stat-box">
                <span class="stat-box-label">Cadets Enrolled</span>
                <span class="stat-box-value"><?= $total_students ?></span>
            </div>
            <div class="overview-stat-box">
                <span class="stat-box-label">Flags Captured</span>
                <span class="stat-box-value"><?= $total_solves ?></span>
            </div>
            <div class="overview-stat-box">
                <span class="stat-box-label">CTF Status</span>
                <span class="stat-box-value <?= $ctf['is_active'] ? 'text-success' : 'text-danger' ?>">
                    <?= $ctf['is_active'] ? 'Active' : 'Paused' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ══ Leaderboard Section ══════════════════════════════════════════════ -->
    <section aria-label="Leaderboard Standings" class="scoreboard-section">
        <div class="section-header-row">
            <div>
                <h2 class="section-heading">Competitive Standings</h2>
                <p class="section-subheading">Official ranking table sorted by total points scored and earliest flag submission.</p>
            </div>

            <!-- Filter Tabs -->
            <div class="scoreboard-filter-tabs">
                <a href="/index.php?page=scoreboard&filter=all"
                   class="scoreboard-filter-btn <?= $filter === 'all' ? 'active' : '' ?>">
                    All Participants (<?= $total_enrolled ?>)
                </a>
                <a href="/index.php?page=scoreboard&filter=students"
                   class="scoreboard-filter-btn <?= $filter === 'students' ? 'active' : '' ?>">
                    Students Only (<?= $total_students ?>)
                </a>
            </div>
        </div>

        <div class="table-surface-card" id="scoreboard-table-wrap">
            <table class="saas-data-table" id="scoreboard-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Rank</th>
                        <th>Participant</th>
                        <th style="width: 130px;">Role</th>
                        <th style="width: 140px;">Labs Solved</th>
                        <th style="width: 200px;">Total Points</th>
                        <th style="width: 160px;">Last Solve</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leaders)): ?>
                        <?php foreach ($leaders as $i => $row): ?>
                            <?php
                                $rank    = $i + 1;
                                $is_me   = $is_auth && ($row['username'] === ($current_user['username'] ?? ''));
                                $total   = (int)$row['total_points'];
                                $bar_pct = $total_possible > 0 ? min(100, round(($total / $total_possible) * 100)) : 0;
                                $is_admin_user = ($row['role'] === 'admin');
                            ?>
                            <tr class="table-row <?= $is_me ? 'row-highlighted' : '' ?>" id="rank-row-<?= $rank ?>">
                                <!-- Rank Medal -->
                                <td>
                                    <?php if ($rank === 1 && $total > 0): ?>
                                        <span class="badge-rank badge-rank-gold" title="1st Place (Gold)">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            <span>#1</span>
                                        </span>
                                    <?php elseif ($rank === 2 && $total > 0): ?>
                                        <span class="badge-rank badge-rank-silver" title="2nd Place (Silver)">#2</span>
                                    <?php elseif ($rank === 3 && $total > 0): ?>
                                        <span class="badge-rank badge-rank-bronze" title="3rd Place (Bronze)">#3</span>
                                    <?php else: ?>
                                        <span class="badge-rank badge-rank-default">#<?= $rank ?></span>
                                    <?php endif; ?>
                                </td>

                                <!-- Participant Identity -->
                                <td>
                                    <div class="participant-identity">
                                        <div class="participant-avatar <?= $is_admin_user ? 'avatar-admin' : '' ?>">
                                            <?= strtoupper(substr($row['username'], 0, 2)) ?>
                                        </div>
                                        <div class="participant-info">
                                            <span class="participant-name <?= $is_me ? 'is-me' : '' ?>">
                                                <?= e($row['username']) ?>
                                            </span>
                                            <?php if ($is_me): ?>
                                                <span class="badge-you-pill">You</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role -->
                                <td>
                                    <span class="role-badge <?= $is_admin_user ? 'badge-admin' : 'badge-student' ?>">
                                        <?= $is_admin_user ? 'Instructor' : 'Cadet' ?>
                                    </span>
                                </td>

                                <!-- Labs Solved -->
                                <td>
                                    <div class="solved-metric-cell">
                                        <span class="solved-count-bold"><?= (int)$row['solved_count'] ?></span>
                                        <span class="solved-fraction-muted">/ <?= count(CHALLENGE_META) ?></span>
                                    </div>
                                </td>

                                <!-- Points & Progress Bar -->
                                <td>
                                    <div class="table-score-container">
                                        <div class="score-number-row">
                                            <span class="score-points-bold <?= $total > 0 ? 'text-accent-orange' : '' ?>"><?= $total ?> pts</span>
                                            <span class="score-pct-label"><?= $bar_pct ?>%</span>
                                        </div>
                                        <div class="mini-bar-track">
                                            <div class="mini-bar-fill" style="width: <?= $bar_pct ?>%;"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Last Solve Timestamp -->
                                <td class="last-solve-cell">
                                    <?php if ($row['last_solve']): ?>
                                        <div class="timestamp-wrap" title="<?= e($row['last_solve']) ?>">
                                            <svg class="svg-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span><?= e(date('M d, H:i', strtotime($row['last_solve']))) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-disabled">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state-cell">
                                <div class="empty-state-container">
                                    <div class="empty-state-icon-wrap">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M6 4h12a2 2 0 0 1 2 2v3a6 6 0 0 1-12 0V6a2 2 0 0 1 2-2z"/></svg>
                                    </div>
                                    <h3 class="empty-state-title">No Participant Records Yet</h3>
                                    <p class="empty-state-desc">
                                        The leaderboard is currently open for submissions. Solve any challenge in the lab to register your points and claim the top ranking.
                                    </p>
                                    <div class="empty-state-actions">
                                        <?php if ($is_auth): ?>
                                            <a href="/index.php?page=dashboard#challenges" class="btn btn-orange btn-sm">
                                                Solve Challenges &rarr;
                                            </a>
                                        <?php else: ?>
                                            <a href="/index.php?page=register" class="btn btn-orange btn-sm">
                                                Register Account &rarr;
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ══ Challenge Completion Statistics ═══════════════════════════════ -->
        <div class="content-section completion-section" style="margin-top: 3.5rem;">
            <div class="section-title-bar">
                <div>
                    <h2 class="section-heading">Challenge Completion Statistics</h2>
                    <p class="section-subheading">Aggregate solve rate and difficulty breakdown across the entire operator pool.</p>
                </div>
            </div>

            <div class="completion-metrics-grid">
                <?php foreach (CHALLENGE_META as $ch_id => $ch): ?>
                    <?php
                        $stmt = $db->prepare("SELECT COUNT(DISTINCT user_id) FROM solved WHERE challenge_id = ?");
                        $stmt->execute([$ch_id]);
                        $cnt = (int)$stmt->fetchColumn();
                        $student_pool = max(1, $total_enrolled);
                        $rate = round(($cnt / $student_pool) * 100);
                        $is_ch_solved = in_array($ch_id, $my_solves, true);
                    ?>
                    <div class="completion-stat-card <?= $is_ch_solved ? 'card-solved' : '' ?>">
                        <div class="stat-card-top">
                            <div class="stat-card-title-group">
                                <span class="stat-card-tag"><?= e($ch['tag'] ?? 'LAB') ?></span>
                                <h3 class="stat-card-title"><?= e($ch['title']) ?></h3>
                            </div>
                            <span class="stat-card-pts-badge"><?= (int)$ch['points'] ?> pts</span>
                        </div>

                        <div class="stat-card-body">
                            <div class="completion-progress-track">
                                <div class="completion-progress-fill" style="width: <?= $rate ?>%;"></div>
                            </div>
                        </div>

                        <div class="stat-card-footer">
                            <div class="stat-footer-left">
                                <span class="stat-solve-count"><?= $cnt ?> solved</span>
                                <span class="stat-dot">•</span>
                                <span class="stat-rate-text"><?= $rate ?>% completion</span>
                            </div>
                            <div class="stat-footer-right">
                                <?php if ($is_ch_solved): ?>
                                    <span class="user-solved-pill" title="You have captured this flag">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Solved by you</span>
                                    </span>
                                <?php else: ?>
                                    <span class="user-unsolved-pill"><?= e($ch['difficulty']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</div><!-- /.cylab-page-container -->

<?php
layout_foot();
?>
