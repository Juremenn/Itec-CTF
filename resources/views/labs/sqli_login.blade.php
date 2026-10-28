@extends('layouts.app')

@section('title', 'Lab: SQLi Login Bypass')

@section('content')
<div style="max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'sqli-login-bypass') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Kembali ke Detail Tantangan</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Target: Konsol Login ShopEZ Admin
            </h1>
        </div>
        <span style="font-size: 0.75rem; background: var(--surface-card); border: 1px solid var(--border-hairline); padding: 0.25rem 0.6rem; border-radius: 4px; color: #4ade80;">
            PORTAL AKTIF
        </span>
    </div>

    <!-- Playground Card -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.75rem;">
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.5;">
            Uji formulir login target di bawah ini. Sistem menyatukan string input secara langsung ke dalam query SQL tanpa parameter prepared statement.
        </p>

        @if($message)
        <div style="background: {{ $flag ? 'rgba(34, 197, 94, 0.15)' : 'rgba(239, 68, 68, 0.15)' }}; border: 1px solid {{ $flag ? 'rgba(34, 197, 94, 0.3)' : 'rgba(239, 68, 68, 0.3)' }}; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
            <div style="color: {{ $flag ? '#4ade80' : '#f87171' }}; font-weight: 600; font-size: 0.9rem;">
                {!! $message !!}
            </div>

            @if($flag)
            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(34, 197, 94, 0.2);">
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.35rem;">
                    Token Flag yang Ditemukan:
                </div>
                <code style="font-size: 1.1rem; font-weight: 700; color: #4ade80; background: var(--bg-base); padding: 0.35rem 0.75rem; border-radius: 4px; border: 1px solid rgba(34, 197, 94, 0.4); display: inline-block;">
                    {{ $flag }}
                </code>
            </div>
            @endif
        </div>
        @endif

        <form method="POST" action="{{ route('lab.sqli_login') }}" style="display: flex; flex-direction: column; gap: 1rem;">
            @csrf
            
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Username</label>
                <input 
                    type="text" 
                    id="vuln_username" 
                    name="vuln_username" 
                    placeholder="admin' --" 
                    autocomplete="off"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;"
                >
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Kata Sandi</label>
                <input 
                    type="text" 
                    id="vuln_password" 
                    name="vuln_password" 
                    placeholder="bebas" 
                    autocomplete="off"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;"
                >
            </div>

            <button type="submit" class="btn-orange" style="justify-content: center; padding: 0.75rem;">
                Kirim Query Otentikasi &rarr;
            </button>
        </form>

        <!-- Live SQL Query Preview Box -->
        <div style="margin-top: 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 1rem;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                Pratinjau Query SQL yang Dieksekusi:
            </div>
            <pre style="margin: 0; font-size: 0.8rem; color: #60a5fa; white-space: pre-wrap; word-break: break-all;">SELECT * FROM users WHERE username = '<span id="prev_u" style="color: #fbbf24;">...</span>' AND password = '<span id="prev_p" style="color: #fbbf24;">...</span>' LIMIT 1</pre>
        </div>

    </div>

</div>

<script>
    const uInput = document.getElementById('vuln_username');
    const pInput = document.getElementById('vuln_password');
    const uPrev = document.getElementById('prev_u');
    const pPrev = document.getElementById('prev_p');

    function sync() {
        if (uPrev) uPrev.textContent = uInput.value || '...';
        if (pPrev) pPrev.textContent = pInput.value || '...';
    }

    if (uInput) uInput.addEventListener('input', sync);
    if (pInput) pInput.addEventListener('input', sync);
</script>
@endsection
