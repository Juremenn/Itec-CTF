<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * init.php – Run once to create & seed the SQLite database.
 * Called automatically by index.php on every boot if the DB is missing.
 */
function init_database(): void {
    $db = get_db();

    // ── Users table ───────────────────────────────────────────────────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS users (
            id             INTEGER PRIMARY KEY AUTOINCREMENT,
            username       TEXT    NOT NULL UNIQUE,
            password       TEXT    NOT NULL,
            email          TEXT    NOT NULL,
            role           TEXT    NOT NULL DEFAULT 'student',
            remember_token TEXT,
            created_at     TEXT    NOT NULL DEFAULT (datetime('now'))
        );
    SQL);

    // Migration in case table already exists without remember_token
    $user_cols = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('remember_token', $user_cols, true)) {
        $db->exec("ALTER TABLE users ADD COLUMN remember_token TEXT");
    }

    // ── Platform settings table (CTF Start/Stop & Announcement) ───────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS settings (
            key   TEXT PRIMARY KEY,
            value TEXT NOT NULL
        );
    SQL);

    $db->exec(<<<SQL
        INSERT OR IGNORE INTO settings (key, value) VALUES
            ('ctf_status', 'running'),
            ('ctf_start_time', ''),
            ('ctf_end_time', ''),
            ('ctf_announcement', 'Selamat datang di ITEC CyberLab 101! Bersainglah secara sportif.');
    SQL);

    // ── Solved challenges table ───────────────────────────────────────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS solved (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id      INTEGER NOT NULL,
            challenge_id TEXT    NOT NULL,
            points       INTEGER NOT NULL DEFAULT 0,
            solved_at    TEXT    NOT NULL DEFAULT (datetime('now')),
            UNIQUE(user_id, challenge_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
    SQL);

    // ── Vuln products table (for SQLi search challenge) ────────────────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS products (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            name        TEXT    NOT NULL,
            category    TEXT    NOT NULL,
            price       REAL    NOT NULL,
            description TEXT
        );
    SQL);

    // ── Secret table – contains the SQLi search flag ──────────────────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS secrets (
            id     INTEGER PRIMARY KEY AUTOINCREMENT,
            key    TEXT NOT NULL,
            value  TEXT NOT NULL
        );
    SQL);

    // ── Vuln users table for IDOR challenge ───────────────────────────────────
    $db->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS profiles (
            id       INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            bio      TEXT,
            role     TEXT NOT NULL DEFAULT 'user',
            secret   TEXT
        );
    SQL);

    // ── Seed: platform users (Only admin seeded by default) ──────────────────
    $seed_users = [
        ['admin', password_hash('Adm1n@Secr3t!2024', PASSWORD_BCRYPT), 'admin@cyberlab.local', 'admin'],
    ];
    $stmt = $db->prepare(
        "INSERT OR IGNORE INTO users (username, password, email, role) VALUES (?, ?, ?, ?)"
    );
    foreach ($seed_users as $u) {
        $stmt->execute($u);
    }

    // ── Seed: products ────────────────────────────────────────────────────────
    $products = [
        ['Laptop Pro X1',        'Electronics',   1299.99, 'High-performance laptop with 32GB RAM'],
        ['Wireless Mouse',       'Accessories',    29.99,  'Ergonomic wireless mouse, 2.4 GHz'],
        ['Mechanical Keyboard',  'Accessories',    89.99,  'RGB backlit mechanical keyboard'],
        ['4K Monitor 27"',       'Electronics',   399.99,  'Ultra-wide 4K IPS panel monitor'],
        ['USB-C Hub 7-in-1',     'Accessories',    45.00,  '7-port USB-C hub with HDMI & SD'],
        ['SSD 1TB NVMe',         'Storage',        79.99,  'PCIe Gen4 NVMe M.2 solid state drive'],
        ['Webcam HD 1080p',      'Accessories',    59.99,  'Full HD webcam with built-in mic'],
        ['Ethernet Cable Cat8',  'Networking',      9.99,  '3m shielded Cat8 ethernet cable'],
        ['RAM DDR5 32GB',        'Components',    129.99,  'DDR5-5600 desktop memory module'],
        ['Power Bank 20000mAh',  'Mobile',         49.99,  'Fast-charge portable power bank'],
    ];
    $stmt = $db->prepare(
        "INSERT OR IGNORE INTO products (name, category, price, description) VALUES (?, ?, ?, ?)"
    );
    $count = (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count === 0) {
        foreach ($products as $p) {
            $stmt->execute($p);
        }
    }

    // ── Seed: secret (flag for SQLi search challenge) ─────────────────────────
    $db->exec(
        "INSERT OR IGNORE INTO secrets (id, key, value) VALUES
         (1, 'ctf_flag', '" . FLAGS['sqli_search'] . "'),
         (2, 'db_version', 'SQLite 3.41.2'),
         (3, 'admin_note', 'Remember to disable debug mode before production!');"
    );

    // ── Seed: profiles (for IDOR challenge) ──────────────────────────────────
    $db->exec(
        "INSERT OR IGNORE INTO profiles (id, username, bio, role, secret) VALUES
         (1, 'alice',    'Security enthusiast and CTF player.',         'user',  NULL),
         (2, 'bob',      'Python developer, coffee addict.',            'user',  NULL),
         (3, 'charlie',  'Linux lover and open-source contributor.',    'user',  NULL),
         (4, 'diana',    'Network engineer with 10 years experience.',  'user',  NULL),
         (5, 'eve',      'Malware analyst and reverse engineer.',       'user',  NULL),
         (6, 'frank',    'DevSecOps practitioner.',                     'user',  NULL),
         (7, 'grace',    'Frontend developer learning security.',       'user',  NULL),
         (8, 'henry',    'Database administrator.',                     'user',  NULL),
         (9, 'iris',     'Cloud security architect.',                   'user',  NULL),
         (99, 'hidden_admin', 'You found me! Here is your reward.',    'admin', '" . FLAGS['idor'] . "');"
    );
}
