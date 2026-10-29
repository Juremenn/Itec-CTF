@extends('layouts.app')

@section('title', 'Lab: SQLi Login Bypass')

@section('content')
<div style="max-width: 680px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'sqli-login-bypass') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Kembali ke Detail Tantangan</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Target: Konsol Login ShopEZ Admin
            </h1>
        </div>
        <span style="font-size: 0.75rem; background: var(--surface-card); border: 1px solid var(--border-hairline); padding: 0.25rem 0.6rem; border-radius: 4px; color: #4ade80; font-weight: 600;">
            PORTAL AKTIF
        </span>
    </div>

    <!-- Login Console Card -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 2rem;">
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.5;">
            Masukkan kredensial akun administrator untuk mengautentikasi dan membuka akses panel kontrol sistem.
        </p>

        <!-- Real Native MySQL Database Error -->
        @if($sqlError)
        <pre style="margin: 0 0 1.5rem 0; background: #07080b; border: 1px solid rgba(239, 68, 68, 0.4); border-left: 4px solid #ef4444; border-radius: 6px; padding: 0.85rem 1.15rem; color: #fca5a5; font-family: 'Consolas', 'Courier New', monospace !important; font-size: 0.825rem; white-space: pre-wrap; word-break: break-all; line-height: 1.5;">{{ $sqlError }}</pre>
        @elseif($authFailed)
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 6px; padding: 0.75rem 1rem; color: #f87171; font-size: 0.85rem; margin-bottom: 1.5rem;">
            Otentikasi gagal: Kredensial tidak valid.
        </div>
        @endif

        <!-- Successful Flag Box -->
        @if($flag)
        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; color: #4ade80; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.4rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Otentikasi Berhasil! Masuk sebagai: {{ $authenticatedUser ?? 'admin' }}
            </div>
            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.6rem;">
                Bypass otentikasi SQL Injection berhasil. Gunakan token flag di bawah ini:
            </div>
            <code style="font-size: 1.15rem; font-weight: 700; color: #4ade80; background: var(--bg-base); padding: 0.4rem 0.85rem; border-radius: 4px; border: 1px solid rgba(34, 197, 94, 0.4); display: inline-block;">
                {{ $flag }}
            </code>
        </div>
        @endif

        <form method="POST" action="{{ route('lab.sqli_login') }}" style="display: flex; flex-direction: column; gap: 1.15rem;">
            @csrf
            
            <div>
                <label for="vuln_username" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Username</label>
                <input 
                    type="text" 
                    id="vuln_username" 
                    name="vuln_username" 
                    value="{{ request('vuln_username') }}"
                    placeholder="Masukkan username target" 
                    autocomplete="off"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <div>
                <label for="vuln_password" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Kata Sandi</label>
                <input 
                    type="password" 
                    id="vuln_password" 
                    name="vuln_password" 
                    value="{{ request('vuln_password') }}"
                    placeholder="Masukkan kata sandi" 
                    autocomplete="off"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <button type="submit" class="btn-orange" style="justify-content: center; padding: 0.75rem; margin-top: 0.5rem; font-size: 0.9rem;">
                Kirim Query Otentikasi &rarr;
            </button>
        </form>

    </div>

</div>
@endsection
