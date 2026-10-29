<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LabController extends Controller
{
    /**
     * Challenge 1: SQL Injection Login Bypass Playground
     */
    public function sqliLogin(Request $request): View
    {
        $message = null;
        $flag = null;
        $executedQuery = null;

        if ($request->isMethod('post')) {
            $username = $request->input('vuln_username', '');
            $password = $request->input('vuln_password', '');

            // In MySQL/MariaDB, comment syntax '--' requires a trailing whitespace character.
            // Ensure any '--' delimiter has a trailing space so MariaDB treats it as a valid comment.
            $sqlUsername = preg_replace('/--(?!\s)/', '-- ', $username);
            $sqlPassword = preg_replace('/--(?!\s)/', '-- ', $password);

            $rawSql = "SELECT * FROM users WHERE username = '{$sqlUsername}' AND password = '{$sqlPassword}' LIMIT 1";

            // If a block comment /* was started without closing */, append */ to close comment
            if (str_contains($rawSql, '/*') && !str_contains($rawSql, '*/')) {
                $rawSql .= '*/';
            }

            $executedQuery = $rawSql;

            try {
                $user = DB::selectOne($rawSql);
                if ($user) {
                    $message = "Otentikasi berhasil sebagai akun: " . htmlspecialchars($user->username);
                    $flag = 'FLAG{sql1_byp4ss_m4st3r}';
                } else {
                    $message = 'Otentikasi gagal: Kredensial tidak valid.';
                }
            } catch (\Throwable $e) {
                $message = 'Kesalahan Sintaks Database (SQL Exception): ' . htmlspecialchars($e->getMessage());
            }
        }

        return view('labs.sqli_login', compact('message', 'flag', 'executedQuery'));
    }

    /**
     * Challenge 2: SQL Injection UNION Data Exfiltration Playground
     */
    public function sqliSearch(Request $request): View
    {
        $q = $request->query('q', '');
        $results = [];
        $error = null;
        $executedQuery = null;

        if ($q !== '') {
            $rawSql = "SELECT id, name, category, price, description FROM products WHERE name LIKE '%{$q}%'";
            $executedQuery = $rawSql;

            try {
                $results = DB::select($rawSql);
            } catch (\Throwable $e) {
                $error = 'Kesalahan Eksekusi SQL: ' . htmlspecialchars($e->getMessage());
            }
        } else {
            $results = DB::table('products')->limit(5)->get();
        }

        return view('labs.sqli_search', compact('q', 'results', 'error', 'executedQuery'));
    }

    /**
     * Challenge 3: Insecure Direct Object Reference (IDOR) Playground
     */
    public function idor(Request $request): View
    {
        $id = $request->query('id', '1');
        $profile = DB::table('profiles')->where('id', $id)->first();

        return view('labs.idor', compact('id', 'profile'));
    }

    /**
     * Challenge 4: Reflected Cross-Site Scripting (XSS) Playground
     */
    public function xss(Request $request): View
    {
        $search = $request->query('q', '');
        $flag = null;

        if ($search !== '' && (stripos($search, '<script') !== false || stripos($search, 'onerror=') !== false || stripos($search, 'onload=') !== false || stripos($search, 'javascript:') !== false)) {
            $flag = 'FLAG{xss_r3fl3ct3d_pwn3d}';
        }

        return view('labs.xss', compact('search', 'flag'));
    }

    /**
     * Hidden Console for Challenge 5 (Recon / Info Disclosure)
     */
    public function hiddenDebugConsole(): View
    {
        $flag = 'FLAG{h1dd3n_p4n3l_f0und}';
        return view('labs.hidden_console', compact('flag'));
    }
}

