<?php
declare(strict_types=1);

/**
 * Shared layout helpers used by dashboard, scoreboard, and challenge pages.
 * Clean, high-density enterprise cybersecurity interface (CyberLabs / HackTheBox / Linear style).
 */

function svg_icon(string $name, string $class = '', int $size = 16): string {
    $stroke = '1.75';
    $cls = 'svg-icon' . ($class !== '' ? ' ' . htmlspecialchars($class, ENT_QUOTES) : '');
    $w = (int)$size;

    return match ($name) {
        'shield' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
SVG,
        'terminal' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
SVG,
        'layout' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
SVG,
        'trophy' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M6 4h12a2 2 0 0 1 2 2v3a6 6 0 0 1-12 0V6a2 2 0 0 1 2-2z"/></svg>
SVG,
        'sliders' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
SVG,
        'database' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
SVG,
        'user' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
SVG,
        'code' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
SVG,
        'search' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
SVG,
        'flag' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
SVG,
        'check' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
SVG,
        'lock' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
SVG,
        'unlock' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
SVG,
        'logout' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
SVG,
        'alert' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
SVG,
        'info' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
SVG,
        'help' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
SVG,
        'arrow-right' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
SVG,
        'cpu' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
SVG,
        'menu' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
SVG,
        'bell' => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
SVG,
        default => <<<SVG
<svg class="{$cls}" width="{$w}" height="{$w}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
SVG,
    };
}

function solved_challenges(int $user_id): array {
    $stmt = get_db()->prepare("SELECT challenge_id FROM solved WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return array_column($stmt->fetchAll(), 'challenge_id');
}

function total_score(int $user_id): int {
    $stmt = get_db()->prepare("SELECT COALESCE(SUM(points),0) FROM solved WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return (int)$stmt->fetchColumn();
}

function record_solve(int $user_id, string $challenge_id): bool {
    $meta = CHALLENGE_META[$challenge_id] ?? null;
    $points = $meta ? (int)$meta['points'] : 100;
    $stmt = get_db()->prepare(
        "INSERT OR IGNORE INTO solved (user_id, challenge_id, points) VALUES (?, ?, ?)"
    );
    return $stmt->execute([$user_id, $challenge_id, $points]);
}

function layout_head(string $title, bool $include_highlight = false): void {
    $GLOBALS['header_title'] = $title;
    $GLOBALS['include_highlight'] = $include_highlight;
}

function layout_sidebar(string $active = ''): void {
    $header_title = $GLOBALS['header_title'] ?? 'ITEC CyberLab';
    $include_highlight = $GLOBALS['include_highlight'] ?? false;
    $page_key = $active !== '' ? $active : ($_GET['page'] ?? 'dashboard');
    require ROOT_PATH . '/includes/header.php';
}

function layout_foot(bool $include_highlight = false): void {
    $include_highlight = $include_highlight || ($GLOBALS['include_highlight'] ?? false);
    require ROOT_PATH . '/includes/footer.php';
}

/**
 * Render the flag submission form + handle flag checking.
 */
function render_flag_form(string $challenge_id, string $hint = ''): void {
    $user_id = (int)($_SESSION['user_id'] ?? 0);
    $db      = get_db();
    $ctf     = get_ctf_state();

    $stmt = $db->prepare("SELECT id FROM solved WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$user_id, $challenge_id]);
    $already_solved = (bool)$stmt->fetch();

    $flag_msg  = '';
    $flag_type = '';

    if (!$ctf['is_active'] && !is_admin()) {
        ?>
        <div class="card flag-submission-card" id="flag-submission">
            <div class="card-header-clean">
                <div>
                    <h3 class="card-title">Submit Flag</h3>
                    <p class="card-subtitle">Verify your solution by submitting the secret token.</p>
                </div>
                <span class="badge badge-locked">Submissions Locked</span>
            </div>
            <div class="alert alert-error" role="alert">
                <span class="alert-icon-svg"><?= svg_icon('lock', '', 15) ?></span>
                <div>Competition is currently paused. Flag submissions are disabled.</div>
            </div>
            <?php if ($hint): ?>
            <details class="hint-accordion">
                <summary class="hint-summary"><?= svg_icon('help', '', 14) ?> View Challenge Hint</summary>
                <div class="hint-content"><?= e($hint) ?></div>
            </details>
            <?php endif; ?>
        </div>
        <?php
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_flag'])) {
        verify_csrf();

        if (!$ctf['is_active'] && !is_admin()) {
            $flag_msg  = 'Competition is currently paused. Submissions are closed.';
            $flag_type = 'error';
        } else {
            $submitted = urldecode(trim($_POST['flag'] ?? ''));
            $correct   = FLAGS[$challenge_id] ?? '';

            if ($submitted === $correct) {
                if (!$already_solved) {
                    $pts = POINTS[$challenge_id] ?? 0;
                    $ins = $db->prepare(
                        "INSERT OR IGNORE INTO solved (user_id, challenge_id, points) VALUES (?, ?, ?)"
                    );
                    $ins->execute([$user_id, $challenge_id, $pts]);
                    $flag_msg  = "Flag verified successfully. <strong>+{$pts} points</strong> credited.";
                    $flag_type = 'success';
                    $already_solved = true;
                } else {
                    $flag_msg  = 'You have already solved this challenge.';
                    $flag_type = 'info';
                }
            } else {
                $flag_msg  = 'Incorrect flag. Check your syntax and try again.';
                $flag_type = 'error';
            }
        }
    }
    ?>
    <div class="card flag-submission-card <?= $already_solved ? 'card-solved' : '' ?>" id="flag-submission">
        <div class="card-header-clean">
            <div>
                <h3 class="card-title">Submit Flag</h3>
                <p class="card-subtitle">Enter the captured flag token below to verify completion.</p>
            </div>
            <?php if ($already_solved): ?>
                <span class="badge badge-success"><?= svg_icon('check', '', 12) ?> Solved</span>
            <?php else: ?>
                <span class="badge badge-subtle"><?= (int)(POINTS[$challenge_id] ?? 0) ?> pts</span>
            <?php endif; ?>
        </div>

        <?php if (is_admin() && !$ctf['is_active']): ?>
            <div class="alert alert-info">
                <span class="alert-icon-svg"><?= svg_icon('info', '', 15) ?></span>
                <div>Instructor override active. You can test flag verification while competition is paused.</div>
            </div>
        <?php endif; ?>

        <?php if ($already_solved && !$flag_msg): ?>
            <div class="alert alert-success">
                <span class="alert-icon-svg"><?= svg_icon('check', '', 15) ?></span>
                <div>You have solved this challenge and earned <?= (int)(POINTS[$challenge_id] ?? 0) ?> points.</div>
            </div>
        <?php endif; ?>

        <?php if ($flag_msg): ?>
            <div class="alert alert-<?= $flag_type ?>" role="alert">
                <span class="alert-icon-svg">
                    <?= $flag_type === 'success' ? svg_icon('check', '', 15) : svg_icon('alert', '', 15) ?>
                </span>
                <div><?= $flag_msg ?></div>
            </div>
        <?php endif; ?>

        <?php if (!$already_solved || $flag_type === 'error'): ?>
        <form method="POST" novalidate id="flag-form-<?= e($challenge_id) ?>" class="flag-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="submit_flag" value="1">
            <div class="flag-input-group">
                <div class="input-container flex-1">
                    <span class="input-prefix-icon"><?= svg_icon('flag', '', 15) ?></span>
                    <input
                        type="text"
                        name="flag"
                        id="flag-input-<?= e($challenge_id) ?>"
                        class="form-control mono"
                        placeholder="FLAG{...}"
                        autocomplete="off"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary" id="submit-flag-<?= e($challenge_id) ?>">
                    Submit Flag
                </button>
            </div>
        </form>
        <?php endif; ?>

        <?php if ($hint): ?>
        <details class="hint-accordion">
            <summary class="hint-summary"><?= svg_icon('help', '', 14) ?> Challenge Hint</summary>
            <div class="hint-content"><?= e($hint) ?></div>
        </details>
        <?php endif; ?>
    </div>
    <?php
}
