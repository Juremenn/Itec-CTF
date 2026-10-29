<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Solve;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with required initial state.
     */
    public function run(): void
    {
        // 1. Seed System Settings
        $settings = [
            'active_mode'        => 'competition', // 'competition' | 'training'
            'competition_status' => 'active',      // 'active' | 'paused' | 'ended'
            'broadcast_message'  => 'Selamat datang di Platform Edukasi Keamanan Siber ITEC CyberLab!',
        ];

        foreach ($settings as $key => $val) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $val]
            );
        }

        // 2. Seed Single Authoritative Administrator
        // Note: Zero dummy cadet accounts seeded to preserve leaderboard integrity.
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Platform Instructor',
                'email'    => 'admin@cyberlab.local',
                'password' => Hash::make('Admin12345!'),
                'role'     => 'admin',
            ]
        );

        // 3. Seed Realistic Multi-Domain Challenges (All in Bahasa Indonesia)
        $challengesData = [
            [
                'title'       => 'SQL Injection: Bypass Otentikasi',
                'slug'        => 'sqli-login-bypass',
                'category'    => 'web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{sql1_byp4ss_m4st3r}',
                'description' => 'Formulir login target menggabungkan input pengguna secara langsung ke dalam query SQL tanpa parameter prepared statement. Lewati mekanisme otentikasi tanpa kredensial akun yang valid.',
                'hint'        => "Coba batalkan validasi kata sandi menggunakan sintaks komentar SQL: admin' -- atau ' OR '1'='1",
                'remediation' => "Gunakan prepared statements dengan query terparameterisasi (seperti PDO prepared statements atau Eloquent ORM) untuk memisahkan struktur query dari input data.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Insecure Direct Object Reference (IDOR): Kebocoran Profil',
                'slug'        => 'idor-profile-leak',
                'category'    => 'web',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{id0r_p4th_tr4v3rs4l}',
                'description' => 'Halaman gateway profil mempercayai parameter identifikasi numerik (ID) yang dikirim pengguna tanpa memverifikasi hak akses otorisasi. Lakukan enumerasi ID untuk menemukan profil administrator yang tersembunyi.',
                'hint'        => "Ubah parameter id pada query URL bilah peramban. Lakukan enumerasi angka atau periksa nilai tinggi seperti ?id=99.",
                'remediation' => "Terapkan pemeriksaan otorisasi di sisi server untuk memvalidasi bahwa pengguna yang mengakses memiliki hak resmi untuk melihat objek data tersebut.",
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
                'description' => 'Parameter pencarian merefleksikan string payload yang tidak disanitasi langsung ke dalam DOM peramban. Suntikkan payload JavaScript untuk mengeksekusi skrip berbahaya sembarang.',
                'hint'        => "Masukkan payload JavaScript seperti <script>alert(1)</script> atau payload error gambar seperti <img src=x onerror=alert(1)>.",
                'remediation' => "Terapkan context-aware HTML entity encoding (seperti htmlspecialchars) pada setiap output dinamis dan terapkan Content Security Policy (CSP) yang ketat.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Pengungkapan Informasi & Pengintaian Web',
                'slug'        => 'info-disclosure-recon',
                'category'    => 'recon',
                'difficulty'  => 'easy',
                'points'      => 75,
                'flag'        => 'FLAG{h1dd3n_p4n3l_f0und}',
                'description' => 'Konfigurasi endpoint dan komentar kode pengembang membocorkan rute administratif internal. Telusuri artefak kebijakan pengindeksan perayap web untuk menemukan konsol debug tersembunyi.',
                'hint'        => "Periksa file /robots.txt atau komentar kode sumber HTML untuk menemukan rute direktori rahasia atau portal pemeliharaan.",
                'remediation' => "Jangan mengandalkan konsep obscurity atau robots.txt untuk keamanan. Batasi rute sensitif menggunakan middleware otentikasi dan firewall jaringan.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Kelemahan Kriptografi: Analisis Caesar Cipher',
                'slug'        => 'crypto-caesar-cipher',
                'category'    => 'crypto',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{c43s4r_c1ph3r_cr4ck3d}',
                'description' => 'Sebuah transmisi data yang berhasil dicegat berisi pesan terenkripsi menggunakan sandi substitusi alfabet: "SYNT{p43f4e_p1cu3e_pe4px3q}". Dekripsi sandi tersebut untuk mendapatkan token flag aslinya.',
                'hint'        => "Analisis pergeseran alfabet. Perhatikan bahwa huruf 'S' bergeser menjadi 'F' dengan rotasi 13 langkah (ROT13)!",
                'remediation' => "Jangan pernah menggunakan sandi substitusi klasik untuk data sensitif. Gunakan standar enkripsi modern seperti AES-256-GCM atau ChaCha20-Poly1305.",
                'mode_target' => 'both',
                'is_active'   => true,
            ],
            [
                'title'       => 'Wave a Flag - Eksekusi Binary Bantuan',
                'slug'        => 'wave-a-flag',
                'category'    => 'general',
                'difficulty'  => 'easy',
                'points'      => 100,
                'flag'        => 'FLAG{b1scu1ts_4nd_gr4vy_57d8c79}',
                'description' => 'Dapatkah Anda memanggil opsi bantuan (help flag) dari sebuah tool atau program biner? Program biner ini menyimpan informasi yang sangat bermanfaat... warm',
                'hint'        => "1. Program ini hanya dapat berjalan di Linux webshell atau komputer berbasis Linux.\n2. Untuk mengunduh file ke shell/terminal Anda, jalankan perintah: wget <URL>, di mana URL dapat ditemukan di bagian detail tantangan.\n3. Jalankan program dengan mengetik: ./warm, namun pastikan Anda telah memberikan izin eksekusi terlebih dahulu menggunakan: chmod +x warm\n4. Argumen -h dan --help adalah parameter paling umum untuk meminta informasi bantuan dari suatu program atau biner!\n5. Tidak semua program mengimplementasikan fitur bantuan seperti -h dan --help.",
                'remediation' => "Saat mengompilasi biner produksi dan perkakas baris perintah, jangan pernah menanamkan (hardcode) flag sensitif, token rahasia, atau kredensial privat ke dalam menu bantuan, string debug, atau seksi data biner.",
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

        // Purge challenges that have been removed from the seeder list
        Challenge::whereNotIn('slug', array_column($challengesData, 'slug'))->delete();

        // 4. Seed Vulnerable Lab data (Products, Secrets, Profiles)
        DB::table('products')->truncate();
        $products = [
            ['name' => 'Laptop Pro X1',       'category' => 'Elektronik',    'price' => 1299.99, 'description' => 'Laptop performa tinggi dengan RAM 32GB'],
            ['name' => 'Mouse Nirkabel',      'category' => 'Aksesoris',     'price' => 29.99,   'description' => 'Mouse nirkabel ergonomis, 2.4 GHz'],
            ['name' => 'Keyboard Mekanikal', 'category' => 'Aksesoris',     'price' => 89.99,   'description' => 'Keyboard mekanikal RGB dengan lampu latar'],
            ['name' => 'Monitor 4K 27"',      'category' => 'Elektronik',    'price' => 399.99,  'description' => 'Monitor panel IPS 4K ultra-wide'],
            ['name' => 'USB-C Hub 7-in-1',    'category' => 'Aksesoris',     'price' => 45.00,   'description' => 'Hub USB-C 7 port dengan port HDMI & SD'],
            ['name' => 'SSD 1TB NVMe',        'category' => 'Penyimpanan',   'price' => 79.99,   'description' => 'Solid state drive PCIe Gen4 NVMe M.2'],
            ['name' => 'Webcam HD 1080p',     'category' => 'Aksesoris',     'price' => 59.99,   'description' => 'Webcam Full HD dengan mikrofon internal'],
            ['name' => 'Kabel Ethernet Cat8', 'category' => 'Jaringan',      'price' => 9.99,    'description' => 'Kabel ethernet Cat8 shielded panjang 3 meter'],
            ['name' => 'RAM DDR5 32GB',       'category' => 'Komponen',      'price' => 129.99,  'description' => 'Modul memori desktop DDR5-5600'],
            ['name' => 'Power Bank 20000mAh', 'category' => 'Ponsel',        'price' => 49.99,   'description' => 'Power bank portabel pengisian daya cepat'],
        ];
        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        DB::table('secrets')->truncate();
        DB::table('secrets')->insert([
            ['key' => 'ctf_flag',   'value' => 'FLAG{uni0n_s3l3ct_h3r0}', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'db_version', 'value' => 'MariaDB 11 Server', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'admin_note', 'value' => 'Ingat selalu untuk membersihkan dan memvalidasi parameter pengguna sebelum rilis ke produksi!', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('profiles')->truncate();
        $profiles = [
            ['id' => 1, 'username' => 'alice',   'bio' => 'Penggemar keamanan siber dan peserta CTF.', 'role' => 'user', 'secret' => null],
            ['id' => 2, 'username' => 'bob',     'bio' => 'Pengembang Python dan penikmat kopi.', 'role' => 'user', 'secret' => null],
            ['id' => 3, 'username' => 'charlie', 'bio' => 'Pecinta Linux dan kontributor open-source.', 'role' => 'user', 'secret' => null],
            ['id' => 4, 'username' => 'diana',   'bio' => 'Insinyur jaringan dengan pengalaman 10 tahun.', 'role' => 'user', 'secret' => null],
            ['id' => 5, 'username' => 'eve',     'bio' => 'Analis malware dan reverse engineering.', 'role' => 'user', 'secret' => null],
            ['id' => 99, 'username' => 'hidden_admin', 'bio' => 'Selamat, Anda menemukan profil tersembunyi! Ini hadiah untuk Anda.', 'role' => 'admin', 'secret' => 'FLAG{id0r_p4th_tr4v3rs4l}'],
        ];
        foreach ($profiles as $p) {
            DB::table('profiles')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
