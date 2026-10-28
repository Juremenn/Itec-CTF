@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div style="min-height: calc(100vh - 220px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 420px; background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 12px; padding: 2.25rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);">
        
        <!-- Logo & Header -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('assets/img/logoitec.jpg') }}" alt="ITEC Logo" style="height: 64px; width: auto; border-radius: 10px; margin-bottom: 1rem; border: 1px solid var(--border-hairline);">
            <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; letter-spacing: -0.02em;">ITEC CyberLab</h2>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">Sign in to access your cybersecurity practice dashboard</p>
        </div>

        @if($errors->any())
        <div class="alert-box alert-error" style="margin-bottom: 1.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.15rem;">
            @csrf

            <!-- Username or Email -->
            <div>
                <label for="login" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Username or Email</label>
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    value="{{ old('login') }}" 
                    required 
                    autofocus
                    placeholder="Enter your registered username or email"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="••••••••••••"
                    style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                    onfocus="this.style.borderColor='var(--accent-orange)'"
                    onblur="this.style.borderColor='var(--border-input)'"
                >
            </div>

            <!-- Remember Me -->
            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); cursor: pointer;">
                    <input type="checkbox" name="remember" value="1" checked style="accent-color: var(--accent-orange);">
                    Remember session (30 days)
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-orange" style="justify-content: center; width: 100%; padding: 0.75rem; font-size: 0.95rem; margin-top: 0.5rem;">
                Authenticate &rarr;
            </button>
        </form>

        <!-- Registration Link -->
        <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--border-hairline); text-align: center; font-size: 0.85rem; color: var(--text-secondary);">
            Don't have an account? 
            <a href="{{ route('register') }}" style="color: var(--accent-orange); font-weight: 600; text-decoration: none; margin-left: 0.25rem;">
                Register Cadet Account &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
