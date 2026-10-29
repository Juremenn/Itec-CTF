ww<?php
declare(strict_types=1);

/**
 * CyLab Academy Design System – Header & Top Navbar with Functional Tools
 * Path: includes/header.php
 */

require_once ROOT_PATH . '/config.php';
require_once ROOT_PATH . '/layout.php';

$current_user = current_user();
$ctf_state    = get_ctf_state();
$page_key     = $_GET['page'] ?? 'dashboard';

// Compute user statistics for functional navbar badges
$user_id        = $current_user ? (int)$current_user['id'] : 0;
$user_solves    = $user_id > 0 ? solved_challenges($user_id) : [];
$user_score     = $user_id > 0 ? total_score($user_id) : 0;
$total_possible = array_sum(array_column(CHALLENGE_META, 'points'));

// Determine page title
$header_title = $header_title ?? 'ITEC CyberLab';
$include_highlight = $include_highlight ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($header_title) ?> &mdash; ITEC CyberLab</title>
    <meta name="description" content="ITEC CyberLab – Security Academy & Hands-on CTF Exploitation Platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/style.css">
    <?php if ($include_highlight): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <?php endif; ?>
    <script>
        (function() {
            try {
                if (localStorage.getItem('cylab_theme') === 'light') {
                    document.documentElement.classList.add('light-mode');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body class="cylab-body">

    <!-- ══ CyLab Academy Top Navbar ═══════════════════════════════════════════ -->
    <header class="cylab-navbar" id="top-navbar">
        <div class="cylab-nav-container">
            
            <!-- Left: Logo + Platform Name & Subtitle -->
            <a href="/index.php?page=dashboard" class="cylab-brand" id="nav-brand">
                <img src="/assets/img/logoitec.jpg" alt="ITEC CyberLab Logo" class="cylab-logo">
                <div class="cylab-brand-text">
                    <span class="cylab-brand-title">ITEC CyberLab</span>
                    <span class="cylab-brand-subtitle">SECURITY ACADEMY</span>
                </div>
            </a>

            <!-- Center: Navigation Links with Real Dropdown -->
            <nav class="cylab-nav-links" aria-label="Main Navigation">
                <a href="/index.php?page=dashboard"
                   class="cylab-nav-item <?= $page_key === 'dashboard' ? 'active' : '' ?>">
                    Dashboard
                </a>
                
                <!-- Interactive Challenges Dropdown -->
                <div class="cylab-dropdown-wrapper" id="challenges-dropdown-wrap">
                    <button type="button" class="cylab-nav-item cylab-dropdown-trigger <?= str_starts_with($page_key, 'challenge_') ? 'active' : '' ?>" id="challenges-dropdown-btn" aria-haspopup="true" aria-expanded="false">
                        <span>Challenges</span>
                        <svg class="svg-icon nav-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="cylab-dropdown-panel" id="challenges-dropdown-panel" role="menu">
                        <div class="dropdown-header">
                            <span class="dropdown-title">Hands-on Labs</span>
                            <span class="dropdown-badge"><?= count($user_solves) ?>/<?= count(CHALLENGE_META) ?> Solved</span>
                        </div>
                        <div class="dropdown-list">
                            <?php foreach (CHALLENGE_META as $cid => $ch): ?>
                                <?php
                                    $is_ch_solved = in_array($cid, $user_solves, true);
                                    $ch_url = match($cid) {
                                        'sqli_login'      => '/index.php?page=challenge_sqli_login',
                                        'sqli_search'     => '/index.php?page=challenge_sqli_search',
                                        'idor'            => '/index.php?page=challenge_idor',
                                        'xss_reflect'     => '/index.php?page=challenge_xss',
                                        'info_disclosure' => '/index.php?page=challenge_info',
                                        default           => '/index.php?page=dashboard'
                                    };
                                    $ch_active = ($page_key === substr($ch_url, 16));
                                ?>
                                <a href="<?= $ch_url ?>" class="dropdown-item-link <?= $ch_active ? 'item-active' : '' ?>" role="menuitem">
                                    <div class="dropdown-item-info">
                                        <span class="dropdown-item-tag"><?= e($ch['tag'] ?? 'LAB') ?></span>
                                        <div class="dropdown-item-text">
                                            <div class="dropdown-item-name"><?= e($ch['title']) ?></div>
                                            <div class="dropdown-item-sub"><?= e($ch['category']) ?> &bull; <?= e($ch['difficulty']) ?></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-item-status">
                                        <?php if ($is_ch_solved): ?>
                                            <span class="dropdown-status-solved" title="Solved">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            </span>
                                        <?php else: ?>
                                            <span class="dropdown-status-pts"><?= (int)$ch['points'] ?>p</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="dropdown-footer">
                            <a href="/index.php?page=dashboard#challenges" class="dropdown-all-link">
                                <span>Browse All Categories</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="/index.php?page=scoreboard"
                   class="cylab-nav-item <?= $page_key === 'scoreboard' ? 'active' : '' ?>">
                    Scoreboard
                </a>

                <?php if (is_admin()): ?>
                <a href="/index.php?page=admin_control"
                   class="cylab-nav-item cylab-admin-badge-link <?= $page_key === 'admin_control' ? 'active' : '' ?>" title="CTF Competition Control Center">
                    <span>CTF Control</span>
                    <span class="cylab-admin-pill <?= $ctf_state['is_active'] ? 'pill-live' : 'pill-paused' ?>">
                        <?= $ctf_state['is_active'] ? 'Active' : 'Paused' ?>
                    </span>
                </a>
                <?php endif; ?>
            </nav>

            <!-- Right: Functional Utility Tools & Profile Dropdown -->
            <div class="cylab-nav-right">
                
                <!-- 1. Live Score Pill for Authenticated Operators -->
                <?php if ($current_user): ?>
                <a href="/index.php?page=dashboard" class="cylab-score-pill" id="nav-score-pill" title="Your Total Points: <?= $user_score ?> / <?= $total_possible ?>">
                    <svg class="svg-icon text-accent-orange" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M6 4h12a2 2 0 0 1 2 2v3a6 6 0 0 1-12 0V6a2 2 0 0 1 2-2z"/></svg>
                    <span class="score-points" id="nav-score-pts"><?= $user_score ?> pts</span>
                    <span class="score-fraction" id="nav-score-frac">(<?= count($user_solves) ?>/<?= count(CHALLENGE_META) ?>)</span>
                </a>

                <!-- 2. Universal Quick Flag Submission Pill -->
                <button type="button" class="cylab-flag-pill" id="btn-open-quick-flag" title="Quick Flag Submission (Submit any captured token)">
                    <svg class="svg-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    <span>Submit Flag</span>
                </button>
                <?php endif; ?>

                <!-- 3. Academy Support & Cheatsheet Button (Heart Icon) -->
                <button type="button" class="cylab-support-pill" id="btn-open-support" title="Academy Support, Field Manual &amp; Database Health">
                    <svg class="svg-icon heart-icon" width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    <span>Support</span>
                </button>

                <!-- 4. Functional CyberLab Toolkit Drawer Button (>_) -->
                <button type="button" class="cylab-icon-btn nav-terminal-btn" id="btn-open-toolkit" title="Open CyberLab Toolkit (Base64, URL, Hashes, Payloads, Notes)" aria-label="Open CyberLab Toolkit">
                    <span class="nav-console-prompt">&gt;_</span>
                </button>

                <!-- 5. High-Contrast Terminal Mode Toggle -->
                <button type="button" class="cylab-icon-btn" id="btn-toggle-theme" title="Toggle High-Contrast Terminal Mode" aria-label="Toggle High-Contrast Terminal Mode">
                    <svg class="svg-icon theme-sun-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>

                <!-- 6. User Profile Dropdown -->
                <?php if ($current_user): ?>
                    <div class="cylab-dropdown-wrapper" id="profile-dropdown-wrap">
                        <button type="button" class="cylab-avatar-trigger" id="profile-dropdown-btn" aria-haspopup="true" aria-expanded="false" title="Account Menu (<?= e($current_user['username']) ?>)">
                            <div class="cylab-avatar-circle">
                                <?= strtoupper(substr($current_user['username'], 0, 2)) ?>
                            </div>
                        </button>
                        <div class="cylab-dropdown-panel profile-panel" id="profile-dropdown-panel" role="menu">
                            <div class="profile-dropdown-header">
                                <div class="profile-header-avatar">
                                    <?= strtoupper(substr($current_user['username'], 0, 2)) ?>
                                </div>
                                <div class="profile-header-text">
                                    <div class="profile-header-name"><?= e($current_user['username']) ?></div>
                                    <div class="profile-header-role">
                                        <span class="role-badge <?= is_admin() ? 'badge-admin' : 'badge-student' ?>">
                                            <?= is_admin() ? 'Instructor / Admin' : 'Student Cadet' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-stats-bar">
                                <div class="pstat-item">
                                    <span class="pstat-label">Score</span>
                                    <span class="pstat-val" id="profile-score-val"><?= $user_score ?> pts</span>
                                </div>
                                <div class="pstat-item">
                                    <span class="pstat-label">Solved</span>
                                    <span class="pstat-val" id="profile-solved-val"><?= count($user_solves) ?>/<?= count(CHALLENGE_META) ?></span>
                                </div>
                            </div>
                            <div class="profile-dropdown-links">
                                <a href="/index.php?page=dashboard" class="profile-menu-link">
                                    <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                    <span>Dashboard</span>
                                </a>
                                <a href="/index.php?page=scoreboard" class="profile-menu-link">
                                    <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M6 4h12a2 2 0 0 1 2 2v3a6 6 0 0 1-12 0V6a2 2 0 0 1 2-2z"/></svg>
                                    <span>Live Scoreboard</span>
                                </a>
                                <?php if (is_admin()): ?>
                                <a href="/index.php?page=admin_control" class="profile-menu-link">
                                    <svg class="svg-icon text-accent-orange" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                                    <span>CTF Control Center</span>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="profile-dropdown-footer">
                                <a href="/index.php?page=logout" class="profile-logout-link" id="nav-signout">
                                    <svg class="svg-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    <span>Sign Out</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Quick Logout Button -->
                    <a href="/index.php?page=logout" class="cylab-icon-btn nav-logout-btn" title="Sign Out of CyberLab" aria-label="Sign Out">
                        <svg class="svg-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </a>
                <?php else: ?>
                    <a href="/index.php?page=login" class="btn btn-orange btn-sm" id="nav-login">Sign In</a>
                <?php endif; ?>

            </div>
        </div>

        <!-- Subtle Top Glow Accent (CyLab Signature) -->
        <div class="cylab-navbar-glow"></div>
    </header>

    <main class="cylab-main-viewport">

    <!-- Global Announcements & State Alerts (Clean Alert Banners) -->
    <?php if (!empty($ctf_state['announcement']) || !$ctf_state['is_active']): ?>
    <aside class="cylab-alerts-wrapper" aria-label="System Notifications">
        <div class="cylab-alerts-container">
            <?php if (!empty($ctf_state['announcement'])): ?>
            <div class="cylab-alert-banner alert-banner-info" role="status">
                <div class="alert-icon-wrap" aria-hidden="true">
                    <svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="alert-content-wrap">
                    <span class="alert-badge-pill badge-pill-announcement">Announcement</span>
                    <span class="alert-message-text"><?= e($ctf_state['announcement']) ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!$ctf_state['is_active']): ?>
            <div class="cylab-alert-banner alert-banner-paused" role="alert">
                <div class="alert-icon-wrap" aria-hidden="true">
                    <svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                </div>
                <div class="alert-content-wrap">
                    <span class="alert-badge-pill badge-pill-paused">Competition Paused</span>
                    <span class="alert-message-text"><?= e($ctf_state['message']) ?></span>
                </div>
                <?php if (is_admin()): ?>
                    <a href="/index.php?page=admin_control" class="alert-action-btn" title="Open CTF Control Center">
                        <span>Open CTF Control</span>
                        <span class="action-arrow">&rarr;</span>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </aside>
    <?php endif; ?>

    <!-- ══ MODAL 1: Universal Quick Flag Submission ═════════════════════════════ -->
    <div class="cylab-modal-overlay" id="modal-quick-flag" role="dialog" aria-modal="true" aria-labelledby="flag-modal-title" style="display: none;">
        <div class="cylab-modal-box flag-modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span class="modal-icon-badge badge-flag">
                        <svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    </span>
                    <div>
                        <h3 class="modal-heading" id="flag-modal-title">Submit Captured Flag</h3>
                        <p class="modal-subheading">Universal validator &mdash; automatically identifies challenge and awards points</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" id="btn-close-flag" aria-label="Close modal">&times;</button>
            </div>

            <div class="modal-body">
                <div id="quick-flag-alert" class="modal-alert-box" style="display: none;"></div>

                <div class="tool-field-group">
                    <label class="tool-field-label" for="quick-flag-input">Captured Flag Token</label>
                    <div class="flag-input-wrapper">
                        <input type="text" id="quick-flag-input" class="tool-input mono" placeholder="FLAG{...}" autocomplete="off" spellcheck="false">
                        <button type="button" class="btn btn-orange" id="btn-quick-flag-submit">Verify &amp; Submit</button>
                    </div>
                </div>

                <div class="flag-modal-tips">
                    <div class="fmt-item">
                        <span class="fmt-dot"></span>
                        <span>Full syntax required: <code>FLAG{...}</code> including curly brackets.</span>
                    </div>
                    <div class="fmt-item">
                        <span class="fmt-dot"></span>
                        <span>Auto-matching will identify whether the flag belongs to SQLi, IDOR, XSS, or Recon.</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <span class="modal-footer-tip">Press <kbd class="mono-inline">Enter</kbd> to submit or <kbd class="mono-inline">Esc</kbd> to exit.</span>
                <button type="button" class="btn btn-ghost btn-sm" id="btn-close-flag-foot">Cancel</button>
            </div>
        </div>
    </div>

    <!-- ══ MODAL 2: CyberLab Security Toolkit (>_ Drawer/Modal) ═════════════════ -->
    <div class="cylab-modal-overlay" id="modal-cyber-toolkit" role="dialog" aria-modal="true" aria-labelledby="toolkit-modal-title" style="display: none;">
        <div class="cylab-modal-box toolkit-modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span class="modal-icon-badge">
                        <span class="mono-prompt">&gt;_</span>
                    </span>
                    <div>
                        <h3 class="modal-heading" id="toolkit-modal-title">CyberLab Quick Toolkit</h3>
                        <p class="modal-subheading">In-browser encoding, hash calculation, payload vectors &amp; live scratchpad</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" id="btn-close-toolkit" aria-label="Close modal">&times;</button>
            </div>

            <!-- Toolkit Tab Buttons -->
            <div class="toolkit-tabs-bar">
                <button type="button" class="toolkit-tab-btn active" data-tab="tab-base64">Base64</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-url">URL Encode</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-hash">Hash Tool</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-payloads">Payloads</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-scratchpad">Scratchpad</button>
            </div>

            <div class="modal-body">
                <!-- Tab 1: Base64 -->
                <div class="toolkit-tab-pane active" id="tab-base64">
                    <div class="tool-field-group">
                        <label class="tool-field-label">Plaintext String</label>
                        <textarea id="b64-input" class="tool-textarea mono" rows="3" placeholder="Type or paste plaintext here..."></textarea>
                    </div>
                    <div class="tool-actions-row">
                        <button type="button" class="btn btn-orange btn-sm" id="btn-b64-encode">Encode &darr;</button>
                        <button type="button" class="btn btn-ghost btn-sm" id="btn-b64-decode">Decode &uarr;</button>
                        <button type="button" class="btn btn-ghost btn-sm" id="btn-b64-clear">Clear</button>
                    </div>
                    <div class="tool-field-group mt-2">
                        <div class="tool-label-row">
                            <label class="tool-field-label">Base64 Encoded Result</label>
                            <button type="button" class="tool-copy-btn" id="btn-copy-b64">Copy</button>
                        </div>
                        <textarea id="b64-output" class="tool-textarea mono" rows="3" placeholder="Base64 encoded string appears here..."></textarea>
                    </div>
                </div>

                <!-- Tab 2: URL Encode -->
                <div class="toolkit-tab-pane" id="tab-url" style="display: none;">
                    <div class="tool-field-group">
                        <label class="tool-field-label">Raw Parameter String</label>
                        <textarea id="url-input" class="tool-textarea mono" rows="3" placeholder="e.g. ' OR 1=1 -- or <script>alert(1)</script>"></textarea>
                    </div>
                    <div class="tool-actions-row">
                        <button type="button" class="btn btn-orange btn-sm" id="btn-url-encode">URL Encode &darr;</button>
                        <button type="button" class="btn btn-ghost btn-sm" id="btn-url-decode">URL Decode &uarr;</button>
                        <button type="button" class="btn btn-ghost btn-sm" id="btn-url-clear">Clear</button>
                    </div>
                    <div class="tool-field-group mt-2">
                        <div class="tool-label-row">
                            <label class="tool-field-label">Encoded / Decoded Output</label>
                            <button type="button" class="tool-copy-btn" id="btn-copy-url">Copy</button>
                        </div>
                        <textarea id="url-output" class="tool-textarea mono" rows="3" placeholder="Resulting encoded URL string..."></textarea>
                    </div>
                </div>

                <!-- Tab 3: Hash Tool -->
                <div class="toolkit-tab-pane" id="tab-hash" style="display: none;">
                    <div class="tool-field-group">
                        <label class="tool-field-label">Input String</label>
                        <input type="text" id="hash-input" class="tool-input mono" placeholder="Enter string to calculate cryptographic hash...">
                    </div>
                    <div class="hash-results-group mt-3">
                        <div class="hash-result-item">
                            <div class="hash-label-row">
                                <span class="hash-algo">SHA-256</span>
                                <button type="button" class="tool-copy-btn" id="btn-copy-sha256">Copy</button>
                            </div>
                            <input type="text" id="hash-output-sha256" class="tool-input mono text-sm" readonly placeholder="Waiting for input...">
                        </div>
                        <div class="hash-result-item mt-2">
                            <div class="hash-label-row">
                                <span class="hash-algo">SHA-1</span>
                                <button type="button" class="tool-copy-btn" id="btn-copy-sha1">Copy</button>
                            </div>
                            <input type="text" id="hash-output-sha1" class="tool-input mono text-sm" readonly placeholder="Waiting for input...">
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Payloads Cheatsheet -->
                <div class="toolkit-tab-pane" id="tab-payloads" style="display: none;">
                    <p class="tool-hint-text">Click any payload card to instantly copy it to your clipboard:</p>
                    <div class="payload-cards-list">
                        <div class="payload-card" data-payload="admin' --">
                            <span class="payload-category">SQLi Auth Bypass</span>
                            <code class="payload-code mono">admin' --</code>
                            <span class="payload-copy-hint">Copy</span>
                        </div>
                        <div class="payload-card" data-payload="' OR '1'='1' --">
                            <span class="payload-category">SQLi Tautology</span>
                            <code class="payload-code mono">' OR '1'='1' --</code>
                            <span class="payload-copy-hint">Copy</span>
                        </div>
                        <div class="payload-card" data-payload="' UNION SELECT 1, 2, 3, 4, 5 --">
                            <span class="payload-category">SQLi UNION Extraction</span>
                            <code class="payload-code mono">' UNION SELECT 1, 2, 3, 4, 5 --</code>
                            <span class="payload-copy-hint">Copy</span>
                        </div>
                        <div class="payload-card" data-payload="<script>alert(document.cookie)</script>">
                            <span class="payload-category">Reflected XSS Execution</span>
                            <code class="payload-code mono">&lt;script&gt;alert(document.cookie)&lt;/script&gt;</code>
                            <span class="payload-copy-hint">Copy</span>
                        </div>
                        <div class="payload-card" data-payload="1' OR id=1 --">
                            <span class="payload-category">IDOR Param Probe</span>
                            <code class="payload-code mono">1' OR id=1 --</code>
                            <span class="payload-copy-hint">Copy</span>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Hacker Scratchpad -->
                <div class="toolkit-tab-pane" id="tab-scratchpad" style="display: none;">
                    <div class="tool-field-group">
                        <div class="tool-label-row">
                            <label class="tool-field-label">Local Lab Notes &amp; Payload Staging</label>
                            <span class="scratchpad-status" id="scratchpad-status">Saved</span>
                        </div>
                        <textarea id="tool-scratchpad" class="tool-textarea mono" rows="8" placeholder="Type or draft your SQL injection statements, notes, or flags here. Saved automatically in your browser..."></textarea>
                    </div>
                    <div class="tool-actions-row">
                        <button type="button" class="btn btn-orange btn-sm" id="btn-copy-scratchpad">Copy All Notes</button>
                        <button type="button" class="btn btn-ghost btn-sm" id="btn-clear-scratchpad">Clear Scratchpad</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <span class="modal-footer-tip">Tip: Press <kbd class="mono-inline">Esc</kbd> anytime to close this toolkit.</span>
                <button type="button" class="btn btn-ghost btn-sm" id="btn-close-toolkit-foot">Close</button>
            </div>
        </div>
    </div>

    <!-- ══ MODAL 3: Academy Support & Assistance Hub ════════════════════════════ -->
    <div class="cylab-modal-overlay" id="modal-support-hub" role="dialog" aria-modal="true" aria-labelledby="support-modal-title" style="display: none;">
        <div class="cylab-modal-box guide-modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span class="modal-icon-badge badge-support">
                        <svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </span>
                    <div>
                        <h3 class="modal-heading" id="support-modal-title">ITEC Academy Support &amp; Field Guide</h3>
                        <p class="modal-subheading">Ethical hacking guidelines, hints, and sandbox environment health</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" id="btn-close-support" aria-label="Close modal">&times;</button>
            </div>

            <!-- Support Tabs Bar -->
            <div class="toolkit-tabs-bar">
                <button type="button" class="toolkit-tab-btn active" data-tab="tab-guide-manual">Field Guide</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-guide-rules">Rules of Engagement</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-guide-hints">Challenge Hints</button>
                <button type="button" class="toolkit-tab-btn" data-tab="tab-guide-sandbox">Sandbox Re-Sync</button>
            </div>

            <div class="modal-body">
                <!-- Tab: Field Guide -->
                <div class="toolkit-tab-pane active" id="tab-guide-manual">
                    <div class="guide-sections-list">
                        <div class="guide-section">
                            <h4 class="guide-sec-title">Flag Syntax &amp; Submission</h4>
                            <p class="guide-sec-text">
                                All captured flags adhere strictly to the format:
                                <code class="mono-inline">FLAG{some_secret_token}</code>. You can submit captured flags directly using the <strong>Submit Flag</strong> button in the top navbar or within the challenge page.
                            </p>
                        </div>
                        <div class="guide-section">
                            <h4 class="guide-sec-title">Exploitation Methodology</h4>
                            <p class="guide-sec-text">
                                1. <strong>Reconnaissance:</strong> Inspect the application, parameters, and HTML comments.<br>
                                2. <strong>Probe:</strong> Test special characters like <code class="mono-inline">'</code>, <code class="mono-inline">"</code>, or HTML tags.<br>
                                3. <strong>Exploit:</strong> Construct the full payload to bypass logic or exfiltrate data.<br>
                                4. <strong>Claim:</strong> Retrieve the secret flag string and register your points.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Rules -->
                <div class="toolkit-tab-pane" id="tab-guide-rules" style="display: none;">
                    <ul class="guide-checklist">
                        <li>All vulnerabilities exist deliberately in the target sandbox application logic layer.</li>
                        <li>Do <strong>NOT</strong> attempt Denial of Service (DoS) attacks or automated brute-forcing.</li>
                        <li>Do <strong>NOT</strong> attempt to escalate privileges to the underlying Linux host OS.</li>
                        <li>Flag sharing or unauthorized database manipulation is strictly forbidden.</li>
                    </ul>
                </div>

                <!-- Tab: Hints -->
                <div class="toolkit-tab-pane" id="tab-guide-hints" style="display: none;">
                    <div class="guide-sections-list">
                        <div class="guide-section">
                            <h4 class="guide-sec-title">SQL Injection: Auth Bypass</h4>
                            <p class="guide-sec-text">The login query concatenates input directly: <code>SELECT * FROM users WHERE username = '$u' AND password = '$p'</code>. Try closing the username string with a single quote and commenting out the password check.</p>
                        </div>
                        <div class="guide-section">
                            <h4 class="guide-sec-title">SQL Injection: UNION Exfiltration</h4>
                            <p class="guide-sec-text">Determine the number of columns returned by the product search query using <code>' UNION SELECT 1, 2, 3, 4, 5 --</code>, then query the <code>secrets</code> table.</p>
                        </div>
                        <div class="guide-section">
                            <h4 class="guide-sec-title">IDOR Access Control</h4>
                            <p class="guide-sec-text">Check the profile endpoint query parameter <code>?id=1</code>. Enumerate sequential or administrative identifiers to uncover private user data.</p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Sandbox Re-Sync -->
                <div class="toolkit-tab-pane" id="tab-guide-sandbox" style="display: none;">
                    <div class="guide-section">
                        <h4 class="guide-sec-title">Emergency Sandbox Restoration</h4>
                        <p class="guide-sec-text">
                            Did a test payload accidentally delete products or corrupt the demo tables?
                            You can re-synchronize the sandbox database back to its pristine training state.
                            Your user account and earned scores will <strong>NOT</strong> be lost.
                        </p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-orange btn-sm" id="btn-resync-db">Re-Sync Sandbox Database</button>
                            <span id="resync-status-text" class="resync-status ml-2"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <span class="modal-footer-tip">ITEC Cyber Security Academy &bull; Authorized Educational Environment</span>
                <button type="button" class="btn btn-ghost btn-sm" id="btn-close-support-foot">Close</button>
            </div>
        </div>
    </div>

