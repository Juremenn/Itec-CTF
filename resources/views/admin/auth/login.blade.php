@extends('layouts.app')

@section('title', 'Masuk Administrator')

@section('content')
<div style="min-height: calc(100vh - 220px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 440px; background: var(--surface-card); border: 1px solid rgba(249, 115, 22, 0.4); border-radius: 12px; padding: 2.25rem; box-shadow: 0 10px 35px rgba(249, 115, 22, 0.1);">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('assets/img/logoitec.jpg') }}" alt="Logo ITEC" style="height: 58px; width: auto; border-radius: 8px; margin-bottom: 1rem; border: 1px solid var(--border-hairline);">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.25rem;">
                Gerbang Operasional Khusus
            </div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.35rem 0;">
                Portal Masuk Administrator
            </h2>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
                Endpoint: /itec/admin
            </p>
        </div>

        @if($errors->any())
        <div class="alert-box alert-error" style="margin-bottom: 1.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" style="display: flex; flex-direction: column; gap: 1.15rem;">
            @csrf

            <!-- Admin Username or Email -->
            <div>
                <label for="admin_login" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Username atau Email Admin</label>
                <input 
                    type="text" 
                    id="admin_login" 
                    name="login" 
                    value="{{ old('login') }}" 
                    required 
                    autofocus
                    placeholder="admin"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="admin_password" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Kata Sandi</label>
                <input 
                    type="password" 
                    id="admin_password" 
                    name="password" 
                    required 
                    placeholder="••••••••••••"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-orange" style="justify-content: center; width: 100%; padding: 0.75rem; font-size: 0.95rem; margin-top: 0.5rem;">
                Verifikasi Akses Admin &rarr;
            </button>
        </form>

    </div>
</div>
@endsection
