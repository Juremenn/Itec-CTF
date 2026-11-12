# ITEC CyberLab Platform

Platform Capture-the-Flag (CTF) dan laboratorium praktik keamanan siber berbasis Laravel 11 dan MySQL. Dirancang khusus untuk pelatihan terstruktur, kompetisi terjadwal, dan pengajuan materi tantangan komunitas.

Sistem antarmuka mengusung tema gelap berdensitas tinggi dengan palet ITEC CyberLab (`#090a0f`, `#11141f`, `#1e2538`, dan aksen oranye `#f97316`), tipografi Poppins/Inter, serta validasi anti-slop yang bersih tanpa ornamen berlebih.

---

## Kebutuhan Sistem

Sebelum memulai instalasi, pastikan server atau mesin lokal telah terpasang dependensi berikut.

| Komponen | Versi Minimal | Keterangan |
| --- | --- | --- |
| PHP | 8.2 atau lebih baru | Ekstensi `bcmath`, `ctype`, `curl`, `fileinfo`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip` |
| Composer | 2.5 atau lebih baru | Manajemen paket pustaka PHP |
| MySQL / MariaDB | 8.0+ / 10.6+ | Basis data relasional utama |
| Web Server | Nginx atau Apache | Nginx direkomendasikan untuk VPS produksi |
| Node.js & NPM | 18+ (Opsional) | Dibutuhkan jika ingin melakukan kompilasi aset frontend mandiri |

---

## Panduan Instalasi Lokal (Development)

Ikuti langkah di bawah ini untuk menjalankan platform di komputer lokal Anda.

### 1. Clone Repositori
```bash
git clone https://github.com/Juremenn/Itec-CTF.git
cd Itec-CTF
```

### 2. Pasang Dependensi
```bash
composer install
```

### 3. Konfigurasi Environment
Salin berkas template environment dan generate application key Laravel.
```bash
cp .env.example .env
php artisan key:generate
```

Buka berkas `.env` menggunakan teks editor Anda, lalu sesuaikan koneksi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itec_ctf
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan Migrasi & Seeder
Buat seluruh tabel basis data dan akun default instruktur:
```bash
php artisan migrate --seed
```

### 5. Buat Tautan Penyimpanan Berkas (Storage Symlink)
Tautan ini penting agar berkas panduan PDF dan arsip tantangan pada fitur pengajuan dapat diakses dan diunduh:
```bash
php artisan storage:link
```

### 6. Jalankan Server Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Buka peramban web dan akses alamat:
```
http://127.0.0.1:8000
```

---

## Panduan Deployment di VPS Produksi (Ubuntu / Debian & Nginx)

Berikut adalah panduan lengkap untuk melakukan deploy platform ini pada Virtual Private Server (VPS) berbasis Linux Ubuntu 22.04 / 24.04 LTS atau Debian 12.

### 1. Update Server & Pasang Dependensi Utama
Jalankan pembaruan sistem dan pasang Nginx, PHP, MySQL, serta ekstensi yang diperlukan:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server git curl unzip zip

# Pasang PHP 8.3 dan ekstensi pendukung
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-curl \
    php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl
```

Pasang Composer versi terbaru secara global:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Siapkan Basis Data MySQL
Masuk ke terminal MySQL untuk membuat basis data dan pengguna khusus aplikasi:
```bash
sudo mysql
```
Jalankan perintah SQL berikut:
```sql
CREATE DATABASE itec_ctf CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'itec_user'@'localhost' IDENTIFIED BY 'PasswordKuatAnda123!';
GRANT ALL PRIVILEGES ON itec_ctf.* TO 'itec_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Clone Proyek ke Direktori Web
Tempatkan kode sumber pada direktori standar web server:
```bash
sudo git clone https://github.com/Juremenn/Itec-CTF.git /var/www/itec-ctf
cd /var/www/itec-ctf
```

Pasang dependensi Composer tanpa paket development untuk performa maksimal:
```bash
sudo composer install --no-dev --optimize-autoloader
```

### 4. Atur Berkas Environment Produksi
Buat berkas `.env` dari template:
```bash
sudo cp .env.example .env
sudo php artisan key:generate
```

Sesuaikan parameter produksi di dalam berkas `.env`:
```env
APP_NAME="ITEC CyberLab"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://ctf.domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itec_ctf
DB_USERNAME=itec_user
DB_PASSWORD=PasswordKuatAnda123!
```

### 5. Jalankan Migrasi & Optimasi Cache Laravel
```bash
sudo php artisan migrate --seed --force
sudo php artisan storage:link

# Optimasi cache konfigurasi, rute, dan blade view
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
```

### 6. Atur Hak Akses Direktori (Permissions)
Beri kepemilikan direktori kepada pengguna web server `www-data` dan amankan hak akses folder storage:
```bash
sudo chown -R www-data:www-data /var/www/itec-ctf
sudo chmod -R 755 /var/www/itec-ctf
sudo chmod -R 775 /var/www/itec-ctf/storage /var/www/itec-ctf/bootstrap/cache
```

### 7. Konfigurasi Nginx Server Block
Buat berkas konfigurasi Nginx untuk domain platform:
```bash
sudo nano /etc/nginx/sites-available/itec-ctf
```

Isikan konfigurasi berikut (sesuaikan nama domain dan versi socket PHP-FPM):
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name ctf.domainanda.com;
    root /var/www/itec-ctf/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    # Batas ukuran upload berkas lampiran tantangan dan PDF
    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi Nginx dan muat ulang layanan:
```bash
sudo ln -s /etc/nginx/sites-available/itec-ctf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 8. Pasang Sertifikat SSL Gratis (Let's Encrypt Certbot)
Amankan situs dengan HTTPS resmi:
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d ctf.domainanda.com
```
Certbot akan secara otomatis mengonfigurasi sertifikat SSL dan penjadwalan pembaruan berkala.

---

## Akun Administrator Awal

Setelah menjalankan migrasi dan database seeder, akun instruktur utama siap digunakan:

| Parameter | Kredensial |
| --- | --- |
| Username | `admin` |
| Password | `Admin12345!` |
| Peran (Role) | `admin` |
| URL Portal Admin | `/itec/admin` |

Akun peserta dapat mendaftar mandiri melalui menu registrasi publik dengan proteksi rate limit otomatis per perangkat.

---

## Fitur Unggulan Platform

### 1. Dual Mode Operasional (Kompetisi vs Latihan)
Pengaturan mode diakses melalui menu panel admin:
- **Mode Kompetisi (Competition)**: Ditujukan untuk turnamen langsung dengan batas waktu ketat. Ketika status turnamen berada dalam kondisi Dijeda (Paused), Belum Dimulai (Upcoming), atau Selesai (Ended), pengiriman flag dan akses lab langsung terkunci otomatis oleh middleware sistem.
- **Mode Latihan (Training)**: Bersifat santai dan berkelanjutan. Peserta bebas mencoba seluruh tantangan kapan saja dengan petunjuk yang dapat dibuka.

### 2. Stealth Admin Routing (`/itec/admin`)
Panel administratif menggunakan proteksi stealth. Pengguna biasa atau peretas yang mencoba mengakses `/itec/admin` akan menerima respon **`404 Not Found`** murni (bukan `403 Forbidden`), sehingga keberadaan antarmuka admin terlindungi dari pemindaian endpoint.

### 3. Portal Request Tantangan Komunitas (`/request-challenge`)
Memfasilitasi anggota dan peserta untuk berkontribusi mengajukan konsep soal baru:
- Formulir pengajuan terstruktur dengan dukungan unggah dokumen panduan/hint berupa PDF dan berkas lab (ZIP, RAR, 7Z, TAR.GZ).
- Kanal komunikasi kontak langsung ke kurator via `saresonel123@gmail.com`.
- Panel moderasi khusus bagi instruktur di `/itec/admin/challenge-requests` untuk meninjau, menyetujui, menolak, dan mengunduh berkas kiriman.

### 4. Papan Skor Dinamis & Anti-Admin Leaks
Papan skor secara ketat mengecualikan akun instruktur dan operator. Penilaian hanya menampilkan peserta murni dengan grafik perkembangan waktu nyata (Chart.js lokal tanpa ketergantungan CDN luar).

### 5. Laboratorium Kerentanan Web Terintegrasi
Menyediakan target eksploitasi lokal mandiri untuk materi pembelajaran:
- SQL Injection Login Bypass (`/lab/sqli-login`)
- SQL Injection Search & Data Extraction (`/lab/sqli-search`)
- Insecure Direct Object Reference / IDOR (`/lab/idor`)
- Cross-Site Scripting / XSS Reflected (`/lab/xss`)

---

## Verifikasi Otomatis Sistem

Platform dilengkapi perintah verifikasi mandiri untuk menguji integritas skema database, penguncian middleware kompetisi, stealth routing, dan logika scoring:

```bash
php artisan ctf:verify
```
Perintah ini memvalidasi seluruh fungsionalitas inti tanpa perlu melakukan pengujian manual satu per satu.

---

## Lisensi

Proyek ini dilisensikan di bawah ketentuan **GNU General Public License v3.0 (GPLv3)**. Siapa pun yang menggunakan, memodifikasi, atau mendistribusikan kode ini diwajibkan untuk tetap membuka sumber kodenya secara penuh di bawah lisensi yang sama dan tidak diperkenankan menjadikannya perangkat lunak tertutup (*closed-source*). Rincian lengkap dapat dibaca pada berkas [LICENSE](file:///home/kali/Documents/Itec-001-Practice/LICENSE).

