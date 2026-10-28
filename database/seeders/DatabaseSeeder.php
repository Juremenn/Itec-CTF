<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SystemSetting;
use App\Models\Challenge;
use App\Models\Solve;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed System Settings
        $settings = [
            'competition_status' => 'paused',
            'platform_mode'      => 'training',
            'active_mode'        => 'training',
            'broadcast_message'  => 'Welcome to ITEC CyberLab Practice Platform! Platform is in Training Mode.',
            'competition_start'  => '',
            'competition_end'    => '',
        ];

        foreach ($settings as $key => $value) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Seed Users
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Platform Instructor',
                'email' => 'admin@cyberlab.local',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
            ]
        );

        $alice = User::updateOrCreate(
            ['username' => 'alice'],
            [
                'name' => 'Alice Margatroid',
                'email' => 'alice@cyberlab.local',
                'password' => Hash::make('alice123'),
                'role' => 'participant',
            ]
        );

        $bob = User::updateOrCreate(
            ['username' => 'bob'],
            [
                'name' => 'Bob Hacker',
                'email' => 'bob@cyberlab.local',
                'password' => Hash::make('bob456'),
                'role' => 'participant',
            ]
        );

        $charlie = User::updateOrCreate(
            ['username' => 'charlie'],
            [
                'name' => 'Charlie Root',
                'email' => 'charlie@cyberlab.local',
                'password' => Hash::make('charlie789'),
                'role' => 'participant',
            ]
        );

        // 3. Seed Challenges
        $challengesData = [
            [
                'title'       => 'SQL Injection: Authentication Bypass',
                'slug'        => 'sqli-login-bypass',
                'category'    => 'web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{sql1_byp4ss_m4st3r}',
                'description' => 'The login form concatenates user input directly into an inline SQL statement. Bypass authentication controls without valid credentials.',
                'hint'        => "Try commenting out the password check using SQL comment syntax: admin' -- or ' OR '1'='1",
                'remediation' => "Use prepared statements with parameterized queries (e.g. PDO prepared statements or Eloquent ORM) to separate query structure from data inputs.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'SQL Injection: UNION Data Exfiltration',
                'slug'        => 'sqli-union-exfiltration',
                'category'    => 'web',
                'difficulty'  => 'medium',
                'points'      => 150,
                'flag'        => 'FLAG{uni0n_s3l3ct_h3r0}',
                'description' => 'The catalog query endpoint is vulnerable to UNION injection. Determine result column counts and exfiltrate hidden records from the secrets table.',
                'hint'        => "Determine the number of returned columns using ORDER BY or UNION SELECT null, null, null, null... Then extract from secrets table (id, key, value).",
                'remediation' => "Enforce strict ORM/parameterized queries and apply strict input validation on filter parameters.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Insecure Direct Object Reference (IDOR)',
                'slug'        => 'idor-profile-leak',
                'category'    => 'web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{id0r_p4th_tr4v3rs4l}',
                'description' => 'The profile view trusts a user-supplied numeric identifier without verifying caller authorization. Enumerate records to locate the administrator profile.',
                'hint'        => "Change the id parameter in the query URL. Enumerate numbers or check high values like 99.",
                'remediation' => "Implement server-side authorization checks verifying that the authenticated user owns or is authorized to view the requested resource.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Reflected Cross-Site Scripting (XSS)',
                'slug'        => 'xss-reflected',
                'category'    => 'web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{xss_r3fl3ct3d_pwn3d}',
                'description' => 'The search parameter reflects unsanitized payload strings into the DOM. Inject an execution vector to demonstrate arbitrary script execution.',
                'hint'        => "Enter a JavaScript script payload such as <script>alert(1)</script> or an image error payload <img src=x onerror=alert(1)>.",
                'remediation' => "Apply context-aware HTML entity encoding (e.g., Blade {{ \$var }} or htmlspecialchars) and deploy a strict Content Security Policy (CSP).",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Information Disclosure & Recon',
                'slug'        => 'info-disclosure-recon',
                'category'    => 'recon',
                'difficulty'  => 'easy',
                'points'      => 75,
                'flag'        => 'FLAG{h1dd3n_p4n3l_f0und}',
                'description' => 'Exposed endpoint configurations and development comments reveal administrative routes. Probe indexing artifacts to discover the hidden console.',
                'hint'        => "Check /robots.txt or developer comments in source code to find hidden routes or maintenance endpoints.",
                'remediation' => "Do not rely on obscurity or robots.txt for access control. Restrict sensitive paths using authentication middleware and firewall rules.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Cryptographic Weakness: Caesar Shift Analysis',
                'slug'        => 'crypto-caesar-cipher',
                'category'    => 'crypto',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{c43s4r_c1ph3r_cr4ck3d}',
                'description' => 'An intercepted transmission contains ciphertext encrypted with a monoalphabetic shift cipher: "SYNT{p43f4e_p1cu3e_pe4px3q}". Recover the plaintext flag token.',
                'hint'        => "Analyze the substitution offset. Notice that 'S' shifts to 'F' with ROT13!",
                'remediation' => "Never use classical substitution ciphers for confidential data. Use modern authenticated encryption like AES-256-GCM or ChaCha20-Poly1305.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Hash Inversion & Rainbow Cracking',
                'slug'        => 'crypto-hash-inversion',
                'category'    => 'crypto',
                'difficulty'  => 'medium',
                'points'      => 150,
                'flag'        => 'FLAG{md5_unc0nd1t10n4l_c0ll1s10n}',
                'description' => 'An exfiltrated dump reveals an unsalted MD5 digest: "b9c3f4e9124be97193259dd1a47313bf". Identify the original plaintext token (secret password: itec2024).',
                'hint'        => "Query rainbow table or MD5 lookup databases or crack using rockyou wordlist for 'itec2024'.",
                'remediation' => "Store passwords using slow, memory-hard adaptive hashing algorithms like Argon2id or Bcrypt with cryptographic salts.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'DNS & Subdomain Reconnaissance',
                'slug'        => 'recon-subdomain-discovery',
                'category'    => 'recon',
                'difficulty'  => 'hard',
                'points'      => 200,
                'flag'        => 'FLAG{subd0m41n_3num3r4t10n_succ3ss}',
                'description' => 'Inspect metadata headers and TLS certificate SAN (Subject Alternative Names) to locate our internal development staging server.',
                'hint'        => "Investigate certificate transparency logs or virtual host headers for staging.itec.cyberlab.local.",
                'remediation' => "Ensure staging systems are isolated in internal VPCs with zero public DNS/SAN exposure.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
        ];

        $challengeModels = [];
        foreach ($challengesData as $data) {
            $challengeModels[$data['slug']] = Challenge::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 4. Seed Vulnerable Lab data (Products, Secrets, Profiles)
        DB::table('products')->truncate();
        $products = [
            ['name' => 'Laptop Pro X1',       'category' => 'Electronics',   'price' => 1299.99, 'description' => 'High-performance laptop with 32GB RAM'],
            ['name' => 'Wireless Mouse',      'category' => 'Accessories',   'price' => 29.99,   'description' => 'Ergonomic wireless mouse, 2.4 GHz'],
            ['name' => 'Mechanical Keyboard', 'category' => 'Accessories',   'price' => 89.99,   'description' => 'RGB backlit mechanical keyboard'],
            ['name' => '4K Monitor 27"',      'category' => 'Electronics',   'price' => 399.99,  'description' => 'Ultra-wide 4K IPS panel monitor'],
            ['name' => 'USB-C Hub 7-in-1',    'category' => 'Accessories',   'price' => 45.00,   'description' => '7-port USB-C hub with HDMI & SD'],
            ['name' => 'SSD 1TB NVMe',        'category' => 'Storage',       'price' => 79.99,   'description' => 'PCIe Gen4 NVMe M.2 solid state drive'],
            ['name' => 'Webcam HD 1080p',     'category' => 'Accessories',   'price' => 59.99,   'description' => 'Full HD webcam with built-in mic'],
            ['name' => 'Ethernet Cable Cat8', 'category' => 'Networking',    'price' => 9.99,    'description' => '3m shielded Cat8 ethernet cable'],
            ['name' => 'RAM DDR5 32GB',       'category' => 'Components',    'price' => 129.99,  'description' => 'DDR5-5600 desktop memory module'],
            ['name' => 'Power Bank 20000mAh', 'category' => 'Mobile',        'price' => 49.99,   'description' => 'Fast-charge portable power bank'],
        ];
        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        DB::table('secrets')->truncate();
        DB::table('secrets')->insert([
            ['key' => 'ctf_flag',   'value' => 'FLAG{uni0n_s3l3ct_h3r0}', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'db_version', 'value' => 'MySQL 8.4 Community Server', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'admin_note', 'value' => 'Remember to sanitize user parameters before production release!', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('profiles')->truncate();
        $profiles = [
            ['id' => 1, 'username' => 'alice',   'bio' => 'Security enthusiast and CTF player.', 'role' => 'user', 'secret' => null],
            ['id' => 2, 'username' => 'bob',     'bio' => 'Python developer, coffee addict.', 'role' => 'user', 'secret' => null],
            ['id' => 3, 'username' => 'charlie', 'bio' => 'Linux lover and open-source contributor.', 'role' => 'user', 'secret' => null],
            ['id' => 4, 'username' => 'diana',   'bio' => 'Network engineer with 10 years experience.', 'role' => 'user', 'secret' => null],
            ['id' => 5, 'username' => 'eve',     'bio' => 'Malware analyst and reverse engineer.', 'role' => 'user', 'secret' => null],
            ['id' => 99, 'username' => 'hidden_admin', 'bio' => 'You found me! Here is your reward.', 'role' => 'admin', 'secret' => 'FLAG{id0r_p4th_tr4v3rs4l}'],
        ];
        foreach ($profiles as $p) {
            DB::table('profiles')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Seed some initial sample solves (Alice & Bob)
        Solve::firstOrCreate([
            'user_id' => $alice->id,
            'challenge_id' => $challengeModels['sqli-login-bypass']->id,
        ], [
            'mode' => 'competition',
            'solved_at' => now()->subMinutes(45),
        ]);

        Solve::firstOrCreate([
            'user_id' => $alice->id,
            'challenge_id' => $challengeModels['idor-profile-leak']->id,
        ], [
            'mode' => 'training',
            'solved_at' => now()->subMinutes(20),
        ]);

        Solve::firstOrCreate([
            'user_id' => $bob->id,
            'challenge_id' => $challengeModels['sqli-login-bypass']->id,
        ], [
            'mode' => 'competition',
            'solved_at' => now()->subMinutes(30),
        ]);
    }
}
