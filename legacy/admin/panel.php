<?php
declare(strict_types=1);

// ── Hidden Admin Panel ────────────────────────────────────────────────────────
// This file is intentionally left unauthenticated as the vulnerability target
// for Challenge 5: Information Disclosure.
// In a real app, this would ALWAYS require auth. Here it's the bug.

// We need config for FLAGS but NOT for session/auth
require_once __DIR__ . '/../config.php';

$flag = FLAGS['info_disclosure'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEZ Admin Panel – DEBUG</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0a0a;
            color: #00ff41;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            min-height: 100vh;
            padding: 2rem;
        }
        .terminal {
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #00ff41;
            border-radius: 8px;
            overflow: hidden;
        }
        .terminal-bar {
            background: #1a1a1a;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #00ff41;
        }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .dot-r { background: #ff5f57; }
        .dot-y { background: #febc2e; }
        .dot-g { background: #28c840; }
        .terminal-title { margin-left: 1rem; color: #888; font-size: 0.85rem; }
        .terminal-body { padding: 2rem; }
        .line { line-height: 1.8; }
        .prompt { color: #00ff41; }
        .cmd { color: #fff; }
        .output { color: #888; }
        .highlight { color: #ffcc00; }
        .flag-box {
            border: 2px solid #ff0055;
            background: rgba(255,0,85,0.1);
            border-radius: 6px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
        }
        .flag-label { color: #ff0055; font-size: 0.9rem; margin-bottom: 0.5rem; letter-spacing: 2px; }
        .flag-value {
            color: #00ff41;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 0 10px #00ff41;
        }
        .warning {
            color: #ffcc00;
            border: 1px solid #ffcc00;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .section-head { color: #00aaff; margin: 1.5rem 0 0.5rem; border-bottom: 1px solid #00aaff; padding-bottom: 0.25rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th { color: #00ff41; text-align: left; padding: 0.5rem; border-bottom: 1px solid #333; }
        td { padding: 0.5rem; color: #ccc; border-bottom: 1px solid #1a1a1a; font-size: 0.9rem; }
        .back-link { display: inline-block; margin-top: 1.5rem; color: #00aaff; text-decoration: none; }
        .back-link:hover { color: #00ff41; }
        .blink { animation: blink 1s step-end infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
    </style>
</head>
<body>
<div class="terminal">
    <div class="terminal-bar">
        <div class="dot dot-r"></div>
        <div class="dot dot-y"></div>
        <div class="dot dot-g"></div>
        <span class="terminal-title">shopez-admin@debug:~$ /admin/panel.php</span>
    </div>
    <div class="terminal-body">
        <div class="warning">
            [WARNING]: This debug console lacks authentication verification. Do not expose in production!
        </div>

        <div class="line"><span class="prompt">root@shopez-admin:~$ </span><span class="cmd">cat /etc/banner</span></div>
        <pre class="output" style="margin:.5rem 0 1rem">
 ____  _                 _____  _____   _       _           _
/ ___|| |__   ___  _ __ | ____||__  /  / \   __| |_ __ ___ (_)_ __
\___ \| '_ \ / _ \| '_ \|  _|    / /  / _ \ / _` | '_ ` _ \| | '_ \
 ___) | | | | (_) | |_) | |___  / /_ / ___ \ (_| | | | | | | | | | |
|____/|_| |_|\___/| .__/|_____| /____/_/   \_\__,_|_| |_| |_|_|_| |_|
                  |_|       DEBUG ADMIN PANEL v0.1-dev</pre>

        <h3 class="section-head">[FLAG EXFILTRATION TARGET]</h3>
        <div class="flag-box">
            <div class="flag-label">[CAPTURED SECRET]</div>
            <div class="flag-value" id="admin-panel-flag"><?= htmlspecialchars($flag, ENT_QUOTES, 'UTF-8') ?></div>
        </div>
        <p class="output">Target asset extracted successfully via unauthorized endpoint discovery.</p>
        <p class="output">Submit this token in Challenge 5 on the CTF platform to verify points.</p>

        <h3 class="section-head">[SYSTEM ENVIRONMENT METADATA]</h3>
        <table>
            <thead><tr><th>Parameter</th><th>Runtime Value</th></tr></thead>
            <tbody>
                <tr><td>App Version</td><td>ShopEZ v2.3.1-debug</td></tr>
                <tr><td>PHP Version</td><td><?= htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><td>Database</td><td>SQLite 3 (PDO)</td></tr>
                <tr><td>Environment</td><td><span class="highlight">DEVELOPMENT</span></td></tr>
                <tr><td>Debug Mode</td><td><span class="highlight">ACTIVE</span></td></tr>
                <tr><td>Server Time</td><td><?= htmlspecialchars(date('Y-m-d H:i:s T'), ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><td>DB Path</td><td><?= htmlspecialchars(DB_PATH, ENT_QUOTES, 'UTF-8') ?></td></tr>
            </tbody>
        </table>

        <h3 class="section-head">[INTERNAL DEVELOPER LOGS]</h3>
        <div class="output" style="line-height:2">
            <div class="line">[ ] Enforce authentication middleware prior to deployment</div>
            <div class="line">[ ] Purge database.sqlite from exposed webroot</div>
            <div class="line">[ ] Suppress PHP execution warnings in production environment</div>
            <div class="line">[ ] Remove staging debug endpoints</div>
            <div class="line">[x] Provision CTF verification target flag token [OK]</div>
        </div>

        <div class="line" style="margin-top:2rem">
            <span class="prompt">root@shopez-admin:~$ </span><span class="blink">█</span>
        </div>

        <a href="/index.php?page=challenge_info" class="back-link">← Back to Challenge 5</a>
    </div>
</div>
</body>
</html>
