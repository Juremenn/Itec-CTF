/**
 * ITEC CyberLab 101 – app.js
 * Minimal vanilla JavaScript for UI enhancements.
 * NO external dependencies. NO eval(). NO innerHTML with user data.
 */
(function () {
    'use strict';

    /* ── Sidebar toggle (mobile) ──────────────────────────────────────────── */
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar   = document.querySelector('.sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (e) {
            if (sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) &&
                e.target !== toggleBtn) {
                sidebar.classList.remove('open');
            }
        });
    }

    /* ── Animate progress bars on page load ──────────────────────────────── */
    function animateBars() {
        const bars = document.querySelectorAll('.progress-fill, .score-bar, .matrix-fill, .progress-bar-fill');
        bars.forEach(function (bar) {
            const target = bar.style.width;
            bar.style.width = '0%';
            requestAnimationFrame(function () {
                setTimeout(function () {
                    bar.style.transition = 'width 0.8s cubic-bezier(0.4,0,0.2,1)';
                    bar.style.width = target;
                }, 100);
            });
        });
    }

    /* ── Flash message auto-dismiss ──────────────────────────────────────── */
    function setupFlashDismiss() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            // Add close button
            const closeBtn = document.createElement('button');
            closeBtn.textContent = '[X]';
            closeBtn.style.cssText = 'margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-family:monospace;font-size:0.75rem;opacity:0.6;padding:0 0 0 0.5rem;';
            closeBtn.setAttribute('aria-label', 'Dismiss');
            closeBtn.addEventListener('click', function () {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-4px)';
                alert.style.transition = 'opacity 0.3s, transform 0.3s';
                setTimeout(function () { alert.remove(); }, 350);
            });
            alert.style.display = 'flex';
            alert.appendChild(closeBtn);
        });
    }

    /* ── Scoreboard: highlight current user row ───────────────────────────── */
    function highlightOwnRow() {
        const rows = document.querySelectorAll('.score-row-me');
        rows.forEach(function (row) {
            row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        });
    }

    /* ── Flag input: trim whitespace ────────────────────────────────────────── */
    function setupFlagInputs() {
        document.querySelectorAll('input[name="flag"]').forEach(function (input) {
            // Prevent accidental form submission on Enter in flag field
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (form) form.requestSubmit();
                }
            });
        });
    }

    /* ── Copy-to-clipboard for flag codes ────────────────────────────────── */
    function setupFlagCopy() {
        document.querySelectorAll('.flag-code').forEach(function (el) {
            el.style.cursor = 'pointer';
            el.title = 'Click to copy';
            el.addEventListener('click', function () {
                const text = this.textContent.trim();
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () {
                        el.dataset.original = el.textContent;
                        el.textContent = '[COPIED TO CLIPBOARD]';
                        setTimeout(function () {
                            el.textContent = el.dataset.original;
                        }, 1500);
                    });
                }
            });
        });
    }

    /* ── Keyboard shortcuts ───────────────────────────────────────────────── */
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function (e) {
            // Alt+S → Scoreboard
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                window.location.href = '/index.php?page=scoreboard';
            }
            // Alt+D → Dashboard
            if (e.altKey && e.key === 'd') {
                e.preventDefault();
                window.location.href = '/index.php?page=dashboard';
            }
            // Escape → Close sidebar on mobile
            if (e.key === 'Escape' && sidebar) {
                sidebar.classList.remove('open');
            }
        });
    }

    /* ── Challenge card hover effects ────────────────────────────────────── */
    function setupCardEffects() {
        document.querySelectorAll('.challenge-card').forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                this.style.transition = 'all 0.2s ease';
            });
        });
    }

    /* ── Tooltip for nav badges ───────────────────────────────────────────── */
    function setupTooltips() {
        document.querySelectorAll('[title]').forEach(function (el) {
            el.setAttribute('data-tooltip', el.title);
        });
    }

    /* ── Terminal typing effect for auth brand ───────────────────────────── */
    function setupTypingEffect() {
        const sub = document.querySelector('.auth-subtitle');
        if (!sub) return;
        const text = sub.textContent;
        sub.textContent = '';
        sub.style.borderRight = '2px solid #10b981';
        let i = 0;
        const interval = setInterval(function () {
            if (i < text.length) {
                sub.textContent += text[i++];
            } else {
                clearInterval(interval);
                setTimeout(function () {
                    sub.style.borderRight = 'none';
                }, 500);
            }
        }, 30);
    }

    /* ── Scoreboard live refresh every 30s ───────────────────────────────── */
    function setupScoreboardRefresh() {
        if (!document.getElementById('scoreboard-table')) return;
        setTimeout(function () {
            window.location.reload();
        }, 30000);
    }

    /* ── Navbar Dropdowns (Challenges & Profile) ────────────────────────── */
    function setupNavbarDropdowns() {
        const triggers = [
            { btn: 'challenges-dropdown-btn', panel: 'challenges-dropdown-panel', wrap: 'challenges-dropdown-wrap' },
            { btn: 'profile-dropdown-btn', panel: 'profile-dropdown-panel', wrap: 'profile-dropdown-wrap' }
        ];

        triggers.forEach(function (t) {
            const btnEl = document.getElementById(t.btn);
            const panelEl = document.getElementById(t.panel);
            if (!btnEl || !panelEl) return;

            btnEl.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = panelEl.classList.contains('show');
                // Close all other dropdowns
                document.querySelectorAll('.cylab-dropdown-panel.show').forEach(function (p) {
                    if (p !== panelEl) p.classList.remove('show');
                });
                panelEl.classList.toggle('show', !isOpen);
                btnEl.setAttribute('aria-expanded', String(!isOpen));
            });
        });

        // Close dropdowns on outside click or Escape
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.cylab-dropdown-wrapper')) {
                document.querySelectorAll('.cylab-dropdown-panel.show').forEach(function (panel) {
                    panel.classList.remove('show');
                });
                document.querySelectorAll('.cylab-dropdown-trigger[aria-expanded="true"]').forEach(function (b) {
                    b.setAttribute('aria-expanded', 'false');
                });
            }
        });
    }

    /* ── CyberLab Modals & Quick Actions (Universal Flag, Toolkit & Support) ── */
    function setupModalsAndToolkit() {
        const flagModal    = document.getElementById('modal-quick-flag');
        const toolkitModal = document.getElementById('modal-cyber-toolkit');
        const supportModal = document.getElementById('modal-support-hub');

        const openFlagBtn    = document.getElementById('btn-open-quick-flag');
        const openToolkitBtn = document.getElementById('btn-open-toolkit');
        const openSupportBtn = document.getElementById('btn-open-support');

        function openModal(modal) {
            if (!modal) return;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // Focus primary input if present
            const firstInput = modal.querySelector('input:not([readonly]), textarea');
            if (firstInput) setTimeout(function () { firstInput.focus(); }, 50);
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Open modal triggers
        if (openFlagBtn && flagModal) {
            openFlagBtn.addEventListener('click', function () { openModal(flagModal); });
        }
        if (openToolkitBtn && toolkitModal) {
            openToolkitBtn.addEventListener('click', function () { openModal(toolkitModal); });
        }
        if (openSupportBtn && supportModal) {
            openSupportBtn.addEventListener('click', function () { openModal(supportModal); });
        }

        // Close button handlers
        ['btn-close-flag', 'btn-close-flag-foot'].forEach(function (id) {
            const b = document.getElementById(id);
            if (b) b.addEventListener('click', function () { closeModal(flagModal); });
        });

        ['btn-close-toolkit', 'btn-close-toolkit-foot'].forEach(function (id) {
            const b = document.getElementById(id);
            if (b) b.addEventListener('click', function () { closeModal(toolkitModal); });
        });

        ['btn-close-support', 'btn-close-support-foot'].forEach(function (id) {
            const b = document.getElementById(id);
            if (b) b.addEventListener('click', function () { closeModal(supportModal); });
        });

        // Close on backdrop click
        [flagModal, toolkitModal, supportModal].forEach(function (modal) {
            if (!modal) return;
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal(modal);
            });
        });

        // Global Esc key to dismiss any open modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal(flagModal);
                closeModal(toolkitModal);
                closeModal(supportModal);
                document.querySelectorAll('.cylab-dropdown-panel.show').forEach(function (p) {
                    p.classList.remove('show');
                });
            }
        });

        /* ── MODAL 1: Quick Flag Submission Logic ─────────────────────────── */
        const flagInput = document.getElementById('quick-flag-input');
        const flagSubmitBtn = document.getElementById('btn-quick-flag-submit');
        const flagAlert = document.getElementById('quick-flag-alert');

        function doQuickFlagSubmit() {
            if (!flagInput || !flagSubmitBtn) return;
            const flagVal = flagInput.value.trim();
            if (!flagVal) {
                showFlagAlert('Please enter a flag token before submitting.', 'error');
                return;
            }

            flagSubmitBtn.disabled = true;
            flagSubmitBtn.textContent = 'Verifying...';

            fetch('/index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ api_action: 'submit_flag', flag: flagVal })
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                flagSubmitBtn.disabled = false;
                flagSubmitBtn.textContent = 'Verify & Submit';

                if (data.success) {
                    showFlagAlert(
                        '🏆 ' + data.message + ' You solved ' + data.challenge_title + ' (+' + data.points + ' pts)!',
                        'success'
                    );
                    flagInput.value = '';

                    // Update Navbar Live Score Pill
                    const navPts = document.getElementById('nav-score-pts');
                    const navFrac = document.getElementById('nav-score-frac');
                    if (navPts) navPts.textContent = data.new_score + ' pts';
                    if (navFrac) navFrac.textContent = '(' + data.new_count + '/' + data.total_challenges + ')';

                    // Update Profile Dropdown Stats
                    const profPts = document.getElementById('profile-score-val');
                    const profSolv = document.getElementById('profile-solved-val');
                    if (profPts) profPts.textContent = data.new_score + ' pts';
                    if (profSolv) profSolv.textContent = data.new_count + '/' + data.total_challenges;

                    // Update Challenges dropdown item
                    if (data.challenge_id) {
                        const chLinks = document.querySelectorAll('.dropdown-item-link');
                        chLinks.forEach(function (lnk) {
                            if (lnk.href.indexOf(data.challenge_id) !== -1) {
                                const statusDiv = lnk.querySelector('.dropdown-item-status');
                                if (statusDiv) {
                                    statusDiv.innerHTML = '<span class="dropdown-status-solved" title="Solved"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>';
                                }
                            }
                        });
                    }
                } else {
                    showFlagAlert(data.message || 'Incorrect flag.', 'error');
                }
            })
            .catch(function () {
                flagSubmitBtn.disabled = false;
                flagSubmitBtn.textContent = 'Verify & Submit';
                showFlagAlert('Server connection error. Please try again.', 'error');
            });
        }

        function showFlagAlert(msg, type) {
            if (!flagAlert) return;
            flagAlert.style.display = 'block';
            flagAlert.className = 'modal-alert-box ' + (type === 'success' ? 'alert-success' : 'alert-error');
            flagAlert.textContent = msg;
        }

        if (flagSubmitBtn) {
            flagSubmitBtn.addEventListener('click', doQuickFlagSubmit);
        }
        if (flagInput) {
            flagInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doQuickFlagSubmit();
                }
            });
        }

        /* ── Tab Switching Helper for Modals ─────────────────────────────── */
        function bindModalTabs(parentModal) {
            if (!parentModal) return;
            const tabBtns = parentModal.querySelectorAll('.toolkit-tab-btn');
            const tabPanes = parentModal.querySelectorAll('.toolkit-tab-pane');

            tabBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = this.dataset.tab;
                    tabBtns.forEach(function (b) { b.classList.remove('active'); });
                    tabPanes.forEach(function (p) { p.style.display = 'none'; });
                    this.classList.add('active');
                    const targetPane = parentModal.querySelector('#' + targetId);
                    if (targetPane) targetPane.style.display = 'block';
                });
            });
        }

        bindModalTabs(toolkitModal);
        bindModalTabs(supportModal);

        /* ── MODAL 2: CyberLab Toolkit Tools ──────────────────────────────── */
        // Tab 1: Base64
        const b64In = document.getElementById('b64-input');
        const b64Out = document.getElementById('b64-output');
        const btnB64Enc = document.getElementById('btn-b64-encode');
        const btnB64Dec = document.getElementById('btn-b64-decode');
        const btnB64Clr = document.getElementById('btn-b64-clear');
        const btnB64Copy = document.getElementById('btn-copy-b64');

        if (btnB64Enc && b64In && b64Out) {
            btnB64Enc.addEventListener('click', function () {
                try {
                    b64Out.value = btoa(unescape(encodeURIComponent(b64In.value)));
                } catch (err) {
                    b64Out.value = 'Error: Invalid characters for Base64 encoding';
                }
            });
        }
        if (btnB64Dec && b64In && b64Out) {
            btnB64Dec.addEventListener('click', function () {
                try {
                    b64In.value = decodeURIComponent(escape(atob(b64Out.value.trim())));
                } catch (err) {
                    b64In.value = 'Error: Invalid Base64 string for decoding';
                }
            });
        }
        if (btnB64Clr && b64In && b64Out) {
            btnB64Clr.addEventListener('click', function () {
                b64In.value = '';
                b64Out.value = '';
            });
        }
        if (btnB64Copy && b64Out) {
            btnB64Copy.addEventListener('click', function () {
                if (b64Out.value) {
                    navigator.clipboard.writeText(b64Out.value);
                    btnB64Copy.textContent = 'Copied!';
                    setTimeout(function () { btnB64Copy.textContent = 'Copy'; }, 1500);
                }
            });
        }

        // Tab 2: URL Encode/Decode Tool
        const urlIn = document.getElementById('url-input');
        const urlOut = document.getElementById('url-output');
        const btnUrlEnc = document.getElementById('btn-url-encode');
        const btnUrlDec = document.getElementById('btn-url-decode');
        const btnUrlClr = document.getElementById('btn-url-clear');
        const btnUrlCopy = document.getElementById('btn-copy-url');

        if (btnUrlEnc && urlIn && urlOut) {
            btnUrlEnc.addEventListener('click', function () {
                urlOut.value = encodeURIComponent(urlIn.value);
            });
        }
        if (btnUrlDec && urlIn && urlOut) {
            btnUrlDec.addEventListener('click', function () {
                try {
                    urlIn.value = decodeURIComponent(urlOut.value);
                } catch (err) {
                    urlIn.value = 'Error: Malformed URL encoded string';
                }
            });
        }
        if (btnUrlClr && urlIn && urlOut) {
            btnUrlClr.addEventListener('click', function () {
                urlIn.value = '';
                urlOut.value = '';
            });
        }
        if (btnUrlCopy && urlOut) {
            btnUrlCopy.addEventListener('click', function () {
                if (urlOut.value) {
                    navigator.clipboard.writeText(urlOut.value);
                    btnUrlCopy.textContent = 'Copied!';
                    setTimeout(function () { btnUrlCopy.textContent = 'Copy'; }, 1500);
                }
            });
        }

        // Tab 3: Hash Calculator Tool (SHA-256 & SHA-1 via Web Crypto)
        const hashIn = document.getElementById('hash-input');
        const hashOut256 = document.getElementById('hash-output-sha256');
        const hashOut1 = document.getElementById('hash-output-sha1');

        async function computeHashes(text) {
            if (!text) {
                if (hashOut256) hashOut256.value = '';
                if (hashOut1) hashOut1.value = '';
                return;
            }
            const encoder = new TextEncoder();
            const data = encoder.encode(text);

            if (crypto && crypto.subtle) {
                try {
                    const buf256 = await crypto.subtle.digest('SHA-256', data);
                    const hex256 = Array.from(new Uint8Array(buf256)).map(function (b) {
                        return b.toString(16).padStart(2, '0');
                    }).join('');
                    if (hashOut256) hashOut256.value = hex256;

                    const buf1 = await crypto.subtle.digest('SHA-1', data);
                    const hex1 = Array.from(new Uint8Array(buf1)).map(function (b) {
                        return b.toString(16).padStart(2, '0');
                    }).join('');
                    if (hashOut1) hashOut1.value = hex1;
                } catch (e) {
                    if (hashOut256) hashOut256.value = 'Crypto API unavailable';
                }
            }
        }

        if (hashIn) {
            hashIn.addEventListener('input', function () {
                computeHashes(this.value);
            });
        }

        const copySha256 = document.getElementById('btn-copy-sha256');
        if (copySha256 && hashOut256) {
            copySha256.addEventListener('click', function () {
                if (hashOut256.value) {
                    navigator.clipboard.writeText(hashOut256.value);
                    copySha256.textContent = 'Copied!';
                    setTimeout(function () { copySha256.textContent = 'Copy'; }, 1500);
                }
            });
        }

        const copySha1 = document.getElementById('btn-copy-sha1');
        if (copySha1 && hashOut1) {
            copySha1.addEventListener('click', function () {
                if (hashOut1.value) {
                    navigator.clipboard.writeText(hashOut1.value);
                    copySha1.textContent = 'Copied!';
                    setTimeout(function () { copySha1.textContent = 'Copy'; }, 1500);
                }
            });
        }

        // Tab 4: Payload Cards Instant Copy
        document.querySelectorAll('.payload-card').forEach(function (card) {
            card.addEventListener('click', function () {
                const payload = this.dataset.payload;
                if (payload) {
                    navigator.clipboard.writeText(payload);
                    const hint = this.querySelector('.payload-copy-hint');
                    if (hint) {
                        hint.textContent = 'Copied!';
                        hint.style.color = '#22c55e';
                        setTimeout(function () {
                            hint.textContent = 'Copy';
                            hint.style.color = '';
                        }, 1500);
                    }
                }
            });
        });

        // Tab 5: Hacker Scratchpad (Saved in localStorage)
        const scratchpad = document.getElementById('tool-scratchpad');
        const scratchStatus = document.getElementById('scratchpad-status');
        const btnCopyNotes = document.getElementById('btn-copy-scratchpad');
        const btnClearNotes = document.getElementById('btn-clear-scratchpad');

        if (scratchpad) {
            const savedNotes = localStorage.getItem('cylab_scratchpad');
            if (savedNotes !== null) scratchpad.value = savedNotes;

            let saveTimeout = null;
            scratchpad.addEventListener('input', function () {
                if (scratchStatus) scratchStatus.textContent = 'Typing...';
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(function () {
                    localStorage.setItem('cylab_scratchpad', scratchpad.value);
                    if (scratchStatus) scratchStatus.textContent = 'Saved';
                }, 400);
            });
        }

        if (btnCopyNotes && scratchpad) {
            btnCopyNotes.addEventListener('click', function () {
                if (scratchpad.value) {
                    navigator.clipboard.writeText(scratchpad.value);
                    btnCopyNotes.textContent = 'Copied All!';
                    setTimeout(function () { btnCopyNotes.textContent = 'Copy All Notes'; }, 1500);
                }
            });
        }

        if (btnClearNotes && scratchpad) {
            btnClearNotes.addEventListener('click', function () {
                if (confirm('Clear all scratchpad notes?')) {
                    scratchpad.value = '';
                    localStorage.removeItem('cylab_scratchpad');
                    if (scratchStatus) scratchStatus.textContent = 'Cleared';
                }
            });
        }

        /* ── MODAL 3: Support Hub & Sandbox Re-Sync ───────────────────────── */
        const btnResyncDb = document.getElementById('btn-resync-db');
        const resyncStatus = document.getElementById('resync-status-text');

        if (btnResyncDb) {
            btnResyncDb.addEventListener('click', function () {
                if (!confirm('Re-synchronize sandbox tables? (Users and your earned score will be preserved)')) {
                    return;
                }
                btnResyncDb.disabled = true;
                btnResyncDb.textContent = 'Re-syncing...';

                fetch('/index.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ api_action: 'reset_sandbox' })
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    btnResyncDb.disabled = false;
                    btnResyncDb.textContent = 'Re-Sync Sandbox Database';
                    if (resyncStatus) {
                        resyncStatus.textContent = data.message || 'Sandbox tables successfully restored!';
                        resyncStatus.style.color = '#22c55e';
                        setTimeout(function () { resyncStatus.textContent = ''; }, 4000);
                    }
                })
                .catch(function () {
                    btnResyncDb.disabled = false;
                    btnResyncDb.textContent = 'Re-Sync Sandbox Database';
                    if (resyncStatus) {
                        resyncStatus.textContent = 'Re-sync error.';
                        resyncStatus.style.color = '#ef4444';
                    }
                });
            });
        }
    }

    /* ── True White Light Mode / Dark Mode Toggle ───────────────────────── */
    function setupThemeToggle() {
        const themeBtn = document.getElementById('btn-toggle-theme');
        const savedTheme = localStorage.getItem('cylab_theme') || 'dark';

        function applyTheme(theme) {
            if (theme === 'light') {
                document.body.classList.add('light-mode');
                document.body.classList.remove('high-contrast-mode');
                if (themeBtn) {
                    themeBtn.title = 'Switch to Dark Mode';
                    themeBtn.setAttribute('aria-label', 'Switch to Dark Mode');
                    themeBtn.innerHTML = '<svg class="svg-icon theme-moon-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
                }
            } else {
                document.body.classList.remove('light-mode');
                document.body.classList.remove('high-contrast-mode');
                if (themeBtn) {
                    themeBtn.title = 'Switch to Light Mode';
                    themeBtn.setAttribute('aria-label', 'Switch to Light Mode');
                    themeBtn.innerHTML = '<svg class="svg-icon theme-sun-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
                }
            }
        }

        applyTheme(savedTheme);

        if (themeBtn) {
            themeBtn.addEventListener('click', function () {
                const isLight = document.body.classList.contains('light-mode');
                const nextTheme = isLight ? 'dark' : 'light';
                localStorage.setItem('cylab_theme', nextTheme);
                applyTheme(nextTheme);
            });
        }
    }

    /* ── Boot ─────────────────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        animateBars();
        setupFlashDismiss();
        setupFlagInputs();
        setupFlagCopy();
        setupKeyboardShortcuts();
        setupCardEffects();
        setupTooltips();
        setupTypingEffect();
        setupScoreboardRefresh();
        setupNavbarDropdowns();
        setupModalsAndToolkit();
        setupThemeToggle();

        if (document.querySelector('.row-me, .score-row-me')) {
            setTimeout(highlightOwnRow, 400);
        }

        console.log(
            '%c[+] ITEC CyberLab – Security Academy Navbar Tools Ready',
            'color:#ea580c;font-family:monospace;font-size:0.9rem;font-weight:bold;'
        );
    });

})();
