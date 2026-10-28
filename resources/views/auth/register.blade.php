@extends('layouts.app')

@section('title', 'Daftar Akun Peserta')

@section('content')
<div style="min-height: calc(100vh - 220px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 460px; background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 12px; padding: 2.25rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);">
        
        <!-- Logo & Header -->
        <div style="text-align: center; margin-bottom: 1.75rem;">
            <img src="{{ asset('assets/img/logoitec.jpg') }}" alt="Logo ITEC" style="height: 60px; width: auto; border-radius: 10px; margin-bottom: 0.85rem; border: 1px solid var(--border-hairline);">
            <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-primary); margin: 0; letter-spacing: -0.02em;">ITEC CyberLab</h2>
        </div>

        @if($errors->any())
        <div class="alert-box alert-error" style="margin-bottom: 1.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.1rem;">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Nama Lengkap *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    placeholder="Contoh: Ahmad Fauzi"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Username -->
            <div>
                <label for="username" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Username Peserta *</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required 
                    placeholder="Contoh: shadow_hunter"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
                <span style="font-size: 0.7rem; color: var(--text-muted);">Gunakan kombinasi huruf, angka, dan garis bawah (3-30 karakter)</span>
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Alamat Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="peserta@kampus.ac.id"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Kata Sandi *</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Minimal 6 karakter"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem;">Konfirmasi Kata Sandi *</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ketik ulang kata sandi Anda"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-orange" style="justify-content: center; width: 100%; padding: 0.75rem; font-size: 0.95rem; margin-top: 0.5rem;">
                Daftar Sekarang &rarr;
            </button>
        </form>

        <!-- Login Link -->
        <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--border-hairline); text-align: center; font-size: 0.85rem; color: var(--text-secondary);">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" style="color: var(--accent-orange); font-weight: 600; text-decoration: none; margin-left: 0.25rem;">
                Masuk di Sini &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
