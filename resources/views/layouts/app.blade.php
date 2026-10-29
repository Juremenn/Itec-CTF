<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CyberLab') &bull; ITEC CyberLab</title>
    
    <!-- Fonts: Strictly Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">

    <style>
        :root {
            --bg-base: #0c0d12;
            --surface-card: #13151c;
            --surface-secondary: #171a23;
            --surface-elevated: #1b1e2a;
            --border-hairline: #1f232e;
            --border-input: #272d3b;
            --accent-orange: #f97316;
            --accent-orange-hover: #ea580c;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
        }

        *, *::before, *::after, html, body, input, button, select, textarea, pre, code, kbd, samp {
            font-family: 'Poppins', sans-serif !important;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        /* Top Navigation Bar */
        .cylab-navbar {
            background-color: rgba(12, 13, 18, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-hairline);
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .nav-brand-group {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .nav-logo-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
        }

        .nav-logo-img {
            height: 38px;
            width: auto;
            border-radius: 6px;
            object-fit: contain;
            border: 1px solid var(--border-hairline);
        }

        .nav-brand-title {
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-brand-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            background: rgba(249, 115, 22, 0.15);
            color: var(--accent-orange);
            border: 1px solid rgba(249, 115, 22, 0.3);
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item-link {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-item-link:hover, .nav-item-link.active {
            color: var(--text-primary);
            background: var(--surface-secondary);
        }

        .nav-right-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Mode Badges */
        .status-pill {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .pill-training {
            background: rgba(59, 130, 246, 0.12);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .pill-training .status-dot { background: #3b82f6; }

        .pill-competition-active {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .pill-competition-active .status-dot { background: #22c55e; box-shadow: 0 0 8px #22c55e; }

        .pill-competition-paused {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .pill-competition-paused .status-dot { background: #ef4444; }

        .pill-competition-upcoming {
            background: rgba(234, 179, 8, 0.12);
            color: #facc15;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .pill-competition-upcoming .status-dot { background: #eab308; }

        /* Global Broadcast Banner */
        .broadcast-banner {
            background: linear-gradient(90deg, rgba(249, 115, 22, 0.15) 0%, rgba(20, 22, 30, 0.8) 100%);
            border-bottom: 1px solid rgba(249, 115, 22, 0.3);
            color: #fdba74;
            padding: 0.5rem 1.5rem;
            font-size: 0.825rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Content Container */
        .main-container {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 1.75rem 1.5rem 3rem 1.5rem;
            box-sizing: border-box;
        }

        /* Alerts & Toasts */
        .alert-box {
            padding: 0.85rem 1.15rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        /* Buttons & CTAs */
        .btn-orange {
            background-color: var(--accent-orange);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.55rem 1.25rem;
            border-radius: 6px;
            border: 1px solid rgba(249, 115, 22, 0.5);
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }
        .btn-orange:hover:not(:disabled) {
            background-color: var(--accent-orange-hover);
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.35);
        }
        .btn-orange:disabled {
            background-color: #272d3b;
            color: #64748b;
            border-color: #1f232e;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-subtle {
            background-color: var(--surface-secondary);
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid var(--border-hairline);
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-subtle:hover {
            color: var(--text-primary);
            background-color: var(--surface-elevated);
            border-color: #2a3142;
        }

        /* Modern Segmented Filter Track (CyLab SecOps Standard) */
        .segmented-filter-track {
            display: inline-flex;
            align-items: center;
            background: #0b0d13;
            border: 1px solid #1c2233;
            border-radius: 6px;
            padding: 3px;
            gap: 2px;
            max-width: 100%;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .segmented-filter-track::-webkit-scrollbar {
            display: none;
        }

        .segmented-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.38rem 0.85rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #8b949e;
            text-decoration: none;
            background: transparent;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            white-space: nowrap;
            user-select: none;
        }

        .segmented-filter-btn svg {
            transition: stroke 0.15s ease;
            flex-shrink: 0;
        }

        .segmented-filter-btn:hover {
            color: #f1f5f9;
            background: rgba(255, 255, 255, 0.05);
        }

        .segmented-filter-btn.active {
            background: #171b26;
            color: #ffffff;
            font-weight: 600;
            border-color: #2b364c;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        .segmented-filter-btn.active svg {
            stroke: var(--accent-orange);
        }

        .segmented-filter-btn.active.mode-competition {
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.35);
        }
        .segmented-filter-btn.active.mode-competition svg {
            stroke: #4ade80;
        }

        .segmented-filter-btn.active.mode-practice {
            color: #60a5fa;
            border-color: rgba(59, 130, 246, 0.35);
        }
        .segmented-filter-btn.active.mode-practice svg {
            stroke: #60a5fa;
        }

        /* Footer */
        .cylab-footer {
            border-top: 1px solid var(--border-hairline);
            padding: 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            background: #090a0f;
        }

        /* Mobile Responsive Navigation & System */
        .desktop-only {
            display: flex !important;
        }
        .mobile-only {
            display: none !important;
        }

        .nav-mobile-toggle {
            display: none;
            background: var(--surface-card);
            border: 1px solid var(--border-hairline);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 0.45rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            line-height: 1;
        }
        .nav-mobile-toggle:hover {
            background: var(--surface-elevated);
            border-color: var(--border-input);
        }

        .nav-mobile-drawer {
            display: none;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            background: rgba(12, 13, 18, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-hairline);
            padding: 1rem 1rem 1.5rem 1rem;
            z-index: 49;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.7);
            max-height: calc(100vh - 60px);
            overflow-y: auto;
            flex-direction: column;
            gap: 0.5rem;
        }
        .nav-mobile-drawer.is-open {
            display: flex;
            animation: mobileSlideDown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes mobileSlideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            background: var(--surface-card);
            border: 1px solid var(--border-hairline);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.15s ease;
        }
        .mobile-nav-link:hover {
            background: var(--surface-elevated);
            border-color: var(--border-input);
        }
        .mobile-nav-link.active {
            border-color: rgba(249, 115, 22, 0.4);
            background: rgba(249, 115, 22, 0.1);
            color: var(--accent-orange);
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .responsive-two-col {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }
        }

        @media (max-width: 768px) {
            .cylab-navbar {
                height: 60px;
                padding: 0 1rem;
            }
            .desktop-only {
                display: none !important;
            }
            .mobile-only {
                display: flex !important;
            }
            .nav-mobile-toggle {
                display: inline-flex;
            }
            .main-container {
                padding: 1.25rem 0.85rem 3rem 0.85rem;
            }
            .hero-header-box {
                padding: 1.25rem !important;
            }
            .hero-actions-group {
                width: 100% !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            .hero-actions-group a, 
            .hero-actions-group button {
                width: 100% !important;
                justify-content: center !important;
                box-sizing: border-box !important;
            }
            .challenges-catalog-grid {
                grid-template-columns: 1fr !important;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
            }
            .cylab-footer {
                padding: 1.25rem 1rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .nav-brand-title {
                font-size: 0.95rem;
            }
            .nav-logo-img {
                height: 30px;
            }
            .segmented-filter-track {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $activeMode = \App\Models\SystemSetting::activeMode();
        $competitionStatus = \App\Models\SystemSetting::competitionStatus();
        $broadcast = \App\Models\SystemSetting::get('broadcast_message', '');
    @endphp

    <!-- Global Broadcast Banner if present -->
    @if(!empty($broadcast))
    <div class="broadcast-banner">
        <span>📢</span>
        <span>{{ $broadcast }}</span>
    </div>
    @endif

    <!-- Top Navigation Bar -->
    <header class="cylab-navbar">
        <div class="nav-brand-group">
            <a href="{{ route('home') }}" class="nav-logo-link">
                <img src="{{ asset('assets/img/logoitec.jpg') }}" alt="ITEC Logo" class="nav-logo-img">
                <span class="nav-brand-title">
                    ITEC CyberLab
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="desktop-only">
                <ul class="nav-links">
                    @auth
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard', 'home') ? 'active' : '' }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('challenges.index') }}" class="nav-item-link {{ request()->routeIs('challenges.*') ? 'active' : '' }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                            Tantangan
                        </a>
                    </li>
                    @endauth
                    <li>
                        <a href="{{ route('scoreboard') }}" class="nav-item-link {{ request()->routeIs('scoreboard*') ? 'active' : '' }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                            Papan Skor
                        </a>
                    </li>
                    @auth
                    @if(Auth::user()->isAdmin())
                    <li>
                        <a href="{{ route('admin.index') }}" class="nav-item-link {{ request()->is('itec/admin*') ? 'active' : '' }}" style="color: #f97316;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Panel Admin
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </nav>
        </div>

        <!-- Desktop Right Section -->
        <div class="nav-right-actions desktop-only">
            @auth
            <!-- User Info & Logout -->
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="text-align: right; line-height: 1.2;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">{{ '@' . Auth::user()->username }} &bull; {{ Auth::user()->isAdmin() ? 'Administrator' : 'Peserta' }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-subtle" title="Keluar dari akun" style="padding: 0.4rem 0.65rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-orange">Masuk</a>
            @endauth
        </div>

        <!-- Mobile Hamburger Toggle Button -->
        <button type="button" class="nav-mobile-toggle mobile-only" id="mobileMenuBtn" aria-label="Buka menu navigasi" onclick="toggleMobileNav()">
            <svg id="hamburgerIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <svg id="closeIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="nav-mobile-drawer" id="mobileNavDrawer">
        @auth
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 0.25rem;">
            <div style="font-size: 0.875rem; font-weight: 700; color: var(--text-primary);">{{ Auth::user()->name }}</div>
            <div style="font-size: 0.725rem; color: var(--text-muted); margin-top: 0.15rem;">{{ '@' . Auth::user()->username }} &bull; {{ Auth::user()->isAdmin() ? 'Administrator' : 'Peserta' }}</div>
        </div>

        <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard', 'home') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>
        <a href="{{ route('challenges.index') }}" class="mobile-nav-link {{ request()->routeIs('challenges.*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
            Tantangan
        </a>
        <a href="{{ route('scoreboard') }}" class="mobile-nav-link {{ request()->routeIs('scoreboard*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            Papan Skor
        </a>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.index') }}" class="mobile-nav-link {{ request()->is('itec/admin*') ? 'active' : '' }}" style="color: #f97316;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Panel Admin
        </a>
        @endif

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.5rem;">
            @csrf
            <button type="submit" class="btn-subtle" style="width: 100%; justify-content: center; padding: 0.65rem 1rem; box-sizing: border-box;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Keluar Akun
            </button>
        </form>
        @else
        <a href="{{ route('scoreboard') }}" class="mobile-nav-link {{ request()->routeIs('scoreboard*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            Papan Skor
        </a>
        <a href="{{ route('login') }}" class="btn-orange" style="justify-content: center; padding: 0.65rem 1rem; margin-top: 0.5rem;">
            Masuk ke Akun
        </a>
        @endauth
    </div>

    <!-- Main Body Area -->
    <main class="main-container">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert-box alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-box alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if(session('info'))
        <div class="alert-box alert-info">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div>{{ session('info') }}</div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="cylab-footer">
        <div>
            &copy; {{ date('Y') }} <strong>ITEC Organization</strong> &bull; <a href="https://smkmetland.net" target="_blank" rel="noopener noreferrer" style="color: var(--text-primary); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; transition: color 0.15s ease;" onmouseover="this.style.color='var(--accent-orange)'" onmouseout="this.style.color='var(--text-primary)'">SMK Metland</a> &bull; Platform Edukasi Keamanan Siber & CTF &bull; Didukung oleh Laravel & MySQL
        </div>
    </footer>

    <script>
        function toggleMobileNav() {
            var drawer = document.getElementById('mobileNavDrawer');
            var hamburger = document.getElementById('hamburgerIcon');
            var close = document.getElementById('closeIcon');
            if (!drawer) return;
            var isOpen = drawer.classList.toggle('is-open');
            if (hamburger && close) {
                hamburger.style.display = isOpen ? 'none' : 'block';
                close.style.display = isOpen ? 'block' : 'none';
            }
        }
        document.addEventListener('click', function(e) {
            var drawer = document.getElementById('mobileNavDrawer');
            var btn = document.getElementById('mobileMenuBtn');
            if (drawer && drawer.classList.contains('is-open') && !drawer.contains(e.target) && btn && !btn.contains(e.target)) {
                toggleMobileNav();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

