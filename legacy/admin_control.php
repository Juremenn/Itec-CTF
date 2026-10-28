<?php
declare(strict_types=1);

require_once ROOT_PATH . '/layout.php';

// Enforce admin permission
if (!is_admin()) {
    header('Location: /index.php?page=dashboard');
    exit;
}

$db = get_db();
$flash_msg  = '';
$flash_type = '';

// ── Handle Admin Actions ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    match ($action) {
        // Start CTF
        'start_ctf' => (function () use (&$flash_msg, &$flash_type) {
            set_setting('ctf_status', 'running');
            $flash_msg  = 'Competition started. Student flag submissions are now open.';
            $flash_type = 'success';
        })(),

        // Stop / Pause CTF
        'stop_ctf' => (function () use (&$flash_msg, &$flash_type) {
            set_setting('ctf_status', 'stopped');
            $flash_msg  = 'Competition paused. Student flag submissions are now locked.';
            $flash_type = 'success';
        })(),

        // Toggle CTF start / stop
        'toggle_status' => (function () use (&$flash_msg, &$flash_type) {
            $current = get_setting('ctf_status', 'running');
            $new     = ($current === 'running') ? 'stopped' : 'running';
            set_setting('ctf_status', $new);
            $flash_msg  = ($new === 'running')
                ? 'Competition started. Student flag submissions are now open.'
                : 'Competition paused. Student flag submissions are now locked.';
            $flash_type = 'success';
        })(),

        // Set start and end schedule
        'save_schedule' => (function () use (&$flash_msg, &$flash_type) {
            $start = trim($_POST['start_time'] ?? '');
            $end   = trim($_POST['end_time'] ?? '');

            set_setting('ctf_start_time', $start);
            set_setting('ctf_end_time', $end);

            $flash_msg  = 'Automated schedule settings have been saved.';
            $flash_type = 'success';
        })(),

        // Clear schedule
        'clear_schedule' => (function () use (&$flash_msg, &$flash_type) {
            set_setting('ctf_start_time', '');
            set_setting('ctf_end_time', '');
            $flash_msg  = 'Schedule cleared. Competition switched to manual control mode.';
            $flash_type = 'info';
        })(),

        // Broadcast announcement
        'save_announcement' => (function () use (&$flash_msg, &$flash_type) {
            $ann = trim($_POST['announcement'] ?? '');
            set_setting('ctf_announcement', $ann);
            $flash_msg  = 'Global announcement has been published to all active dashboards.';
            $flash_type = 'success';
        })(),

        // Reset all challenge solves / scores
        'reset_scores' => (function () use ($db, &$flash_msg, &$flash_type) {
            $confirm = $_POST['confirm_reset'] ?? '';
            if ($confirm === 'RESET') {
                $db->exec("DELETE FROM solved;");
                $flash_msg  = 'All student scores and challenge submissions have been reset to 0.';
                $flash_type = 'success';
            } else {
                $flash_msg  = 'Reset confirmation failed. Please type "RESET" to confirm.';
                $flash_type = 'error';
            }
        })(),

        default => null,
    };
}

$ctf = get_ctf_state();

// Fetch all registered students for monitoring
$students = $db->query(
    "SELECT u.id, u.username, u.email, u.role, u.created_at,
            COALESCE(SUM(s.points), 0) AS total_points,
            COUNT(s.id) AS solved_count
     FROM   users u
     LEFT JOIN solved s ON s.user_id = u.id
     GROUP BY u.id
     ORDER BY total_points DESC, u.id ASC"
)->fetchAll();

layout_head('CTF Control Center');
layout_sidebar('admin_control');
?>

<div class="cylab-page-container">
    <!-- ── Page Header ─────────────────────────────────────────────────────── -->
    <div class="admin-page-header">
        <div>
            <h1 class="page-main-heading">CTF Control Center</h1>
            <p class="page-main-subheading">Manage competition state, automated schedule, announcements, and participant standings.</p>
        </div>
        <div class="header-status-pill">
            <?php if ($ctf['state'] === 'running'): ?>
                <span class="status-indicator status-live">
                    <span class="status-dot"></span> Competition Active
                </span>
            <?php elseif ($ctf['state'] === 'stopped'): ?>
                <span class="status-indicator status-stopped">
                    <span class="status-dot"></span> Competition Paused
                </span>
            <?php elseif ($ctf['state'] === 'not_started'): ?>
                <span class="status-indicator status-scheduled">
                    <span class="status-dot"></span> Scheduled
                </span>
            <?php else: ?>
                <span class="status-indicator status-ended">
                    <span class="status-dot"></span> Concluded
                </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($flash_msg): ?>
        <div class="alert alert-<?= $flash_type ?>" role="alert">
            <span class="alert-icon-svg">
                <?= $flash_type === 'success' ? svg_icon('check', '', 15) : svg_icon('alert', '', 15) ?>
            </span>
            <div><?= $flash_msg ?></div>
        </div>
    <?php endif; ?>

    <!-- ── Admin Controls Grid ─────────────────────────────────────────────── -->
    <div class="admin-dashboard-grid">

        <!-- ── Card 1: Direct Status Override ──────────────────────────────── -->
        <div class="card admin-control-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Direct Status Override</h3>
                    <p class="admin-card-desc">Manually open or close the flag submission gateway.</p>
                </div>
            </div>

            <div class="admin-status-box <?= $ctf['is_active'] ? 'status-box-running' : 'status-box-stopped' ?>">
                <div class="status-box-label">Current Status</div>
                <div class="status-box-value">
                    <span class="status-dot"></span>
                    <?= $ctf['is_active'] ? 'Running — Submissions Active' : 'Paused — Submissions Locked' ?>
                </div>
                <p class="status-box-desc"><?= e($ctf['message']) ?></p>
            </div>

            <form method="POST" class="mt-auto">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <?php if ($ctf['is_active']): ?>
                    <input type="hidden" name="action" value="stop_ctf">
                    <button type="submit" class="btn btn-danger-outline btn-full" id="btn-stop-ctf">
                        Pause Competition
                    </button>
                    <p class="field-hint text-center mt-2">
                        Locks flag submissions for all students immediately.
                    </p>
                <?php else: ?>
                    <input type="hidden" name="action" value="start_ctf">
                    <button type="submit" class="btn btn-primary btn-full" id="btn-start-ctf">
                        Resume Competition
                    </button>
                    <p class="field-hint text-center mt-2">
                        Enables flag submissions for all student accounts.
                    </p>
                <?php endif; ?>
            </form>
        </div>

        <!-- ── Card 2: Automated Schedule ──────────────────────────────────── -->
        <div class="card admin-control-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Automated Scheduling</h3>
                    <p class="admin-card-desc">Set target start and conclusion timestamps for the lab.</p>
                </div>
            </div>

            <form method="POST" id="schedule-form" class="admin-form-group">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="save_schedule">

                <div class="form-field">
                    <label class="form-field-label" for="start_time">Start Time</label>
                    <input
                        type="datetime-local"
                        id="start_time"
                        name="start_time"
                        class="form-control"
                        value="<?= e($ctf['start_time']) ?>"
                    >
                    <span class="field-hint">Leave blank to control start manually.</span>
                </div>

                <div class="form-field">
                    <label class="form-field-label" for="end_time">End Time</label>
                    <input
                        type="datetime-local"
                        id="end_time"
                        name="end_time"
                        class="form-control"
                        value="<?= e($ctf['end_time']) ?>"
                    >
                    <span class="field-hint">Leave blank for unlimited duration.</span>
                </div>

                <div class="button-row">
                    <button type="submit" class="btn btn-primary" id="btn-save-schedule">
                        Save Schedule
                    </button>
                    <?php if (!empty($ctf['start_time']) || !empty($ctf['end_time'])): ?>
                    <button type="button" class="btn btn-ghost" onclick="
                        document.getElementById('start_time').value = '';
                        document.getElementById('end_time').value = '';
                        document.getElementById('schedule-form').submit();
                    ">
                        Clear
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ── Card 3: Global Announcement ─────────────────────────────────── -->
        <div class="card admin-control-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Global Announcement</h3>
                    <p class="admin-card-desc">Broadcast a message banner to all active student screens.</p>
                </div>
            </div>

            <form method="POST" id="announcement-form" class="admin-form-group">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="save_announcement">

                <div class="form-field">
                    <label class="form-field-label" for="announcement">Announcement Message</label>
                    <textarea
                        id="announcement"
                        name="announcement"
                        class="form-control form-textarea"
                        rows="3"
                        placeholder="e.g. Challenge 2 has been updated. The competition will conclude at 21:00 WIB."
                    ><?= e($ctf['announcement']) ?></textarea>
                </div>

                <div class="button-row">
                    <button type="submit" class="btn btn-primary" id="btn-save-announcement">
                        Publish Announcement
                    </button>
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('announcement').value='';">
                        Clear Text
                    </button>
                </div>
            </form>
        </div>

        <!-- ── Card 4: Reset Competition Scores ────────────────────────────── -->
        <div class="card admin-control-card card-destructive">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title text-danger">Reset Platform Scores</h3>
                    <p class="admin-card-desc">Clear all student submissions and solved flags.</p>
                </div>
            </div>

            <p class="destructive-warning-text">
                Warning: This action clears all records from the <code>solved</code> table. Registered user accounts will not be affected.
            </p>

            <form method="POST" onsubmit="return confirm('Are you sure you want to reset all challenge scores?');" class="admin-form-group mt-auto">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="reset_scores">

                <div class="form-field">
                    <label class="form-field-label" for="confirm_reset">Type <strong>RESET</strong> to confirm:</label>
                    <input
                        type="text"
                        id="confirm_reset"
                        name="confirm_reset"
                        class="form-control"
                        placeholder="RESET"
                        required
                        autocomplete="off"
                    >
                </div>

                <button type="submit" class="btn btn-danger-outline btn-full" id="btn-reset-scores">
                    Reset All Scores
                </button>
            </form>
        </div>

    </div>

    <!-- ── Table: Registered Students & Solves ─────────────────────────────── -->
    <div class="content-section" style="margin-top: 2rem;">
        <div class="section-title-bar">
            <div>
                <h2 class="section-heading">Registered Participants</h2>
                <p class="section-subheading">Total of <?= count($students) ?> user account(s) registered in the system.</p>
            </div>
        </div>

        <div class="table-surface-card">
            <table class="saas-data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Solved</th>
                        <th>Total Score</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td class="text-muted text-xs mono">#<?= (int)$s['id'] ?></td>
                            <td>
                                <span class="font-medium text-primary"><?= e($s['username']) ?></span>
                            </td>
                            <td class="text-secondary text-sm"><?= e($s['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= e($s['role']) ?>"><?= ucfirst(e($s['role'])) ?></span>
                            </td>
                            <td>
                                <span class="text-accent font-medium"><?= (int)$s['solved_count'] ?></span>
                                <span class="text-muted text-xs">/ <?= count(CHALLENGE_META) ?></span>
                            </td>
                            <td>
                                <span class="font-medium"><?= (int)$s['total_points'] ?> pts</span>
                            </td>
                            <td class="text-muted text-xs">
                                <?= date('M d, Y H:i', strtotime($s['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php layout_foot(); ?>
