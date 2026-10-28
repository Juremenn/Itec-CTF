#!/usr/bin/env bash
# ITEC CyberLab Platform Startup Script
BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
export PHP_INI_SCAN_DIR="/etc/php/8.4/cli/conf.d:${BASE_DIR}/php_conf"

# Check if MariaDB daemon is active, if not start it
if ! mysqladmin ping -u root -S /tmp/mysql.sock --silent 2>/dev/null; then
    echo "[*] Starting MariaDB database daemon on port 3307..."
    if [ ! -d "/tmp/mariadb_data/mysql" ]; then
        echo "[*] Initializing MariaDB data directory in /tmp/mariadb_data..."
        mariadb-install-db --datadir=/tmp/mariadb_data --auth-root-authentication-method=normal >/dev/null 2>&1
    fi
    mariadbd --datadir=/tmp/mariadb_data --socket=/tmp/mysql.sock --port=3307 --pid-file=/tmp/mariadb.pid >/dev/null 2>&1 &
    
    # Wait for socket to become ready
    for i in {1..20}; do
        if mysqladmin ping -u root -S /tmp/mysql.sock --silent 2>/dev/null; then
            echo "[✓] MariaDB is ready on /tmp/mysql.sock (port 3307)."
            break
        fi
        sleep 0.5
    done
fi

echo "[+] Starting ITEC CyberLab CTF Platform on http://0.0.0.0:8080..."
php -S 0.0.0.0:8080 -t public
