@extends('layouts.app')

@section('title', 'Katalog Tantangan')

@section('content')
<div style="position: relative; min-height: 70vh;">

    <!-- Background Content: Catalog & Challenges Grid (Blurred when Locked) -->
    <div style="{{ $isLocked ? 'filter: blur(6px); -webkit-filter: blur(6px); opacity: 0.2; pointer-events: none; user-select: none;' : '' }}">
        
        <!-- Header & Category Filter Tabs -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem; margin-bottom: 1.75rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.35rem 0; letter-spacing: -0.02em;">
                    Katalog Target Tantangan
                </h1>
                <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                    Pilih target kerentanan di bawah ini untuk memulai investigasi lab interaktif dan merebut flag.
                </p>
            </div>

            <!-- Filter Tabs -->
            <div class="segmented-filter-track">
                <a href="{{ route('challenges.index') }}" class="segmented-filter-btn {{ $selectedCategory === 'all' ? 'active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Semua
                </a>
                <a href="{{ route('challenges.index', ['category' => 'web']) }}" class="segmented-filter-btn {{ $selectedCategory === 'web' ? 'active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    Eksploitasi Web
                </a>
                <a href="{{ route('challenges.index', ['category' => 'crypto']) }}" class="segmented-filter-btn {{ $selectedCategory === 'crypto' ? 'active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Kriptografi
                </a>
                <a href="{{ route('challenges.index', ['category' => 'recon']) }}" class="segmented-filter-btn {{ $selectedCategory === 'recon' ? 'active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>
                    Pengintaian
                </a>
                <a href="{{ route('challenges.index', ['category' => 'general']) }}" class="segmented-filter-btn {{ $selectedCategory === 'general' ? 'active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>
                    Keahlian Umum
                </a>
            </div>
        </div>

        <!-- Challenges Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.35rem;">
            @forelse($challenges as $ch)
            @php
                $isSolved = in_array($ch->id, $userSolves);
                $diffBadge = match($ch->difficulty) {
                    'easy'   => ['bg' => '#14532d', 'color' => '#4ade80', 'border' => 'rgba(74, 222, 128, 0.3)', 'text' => 'Mudah'],
                    'medium' => ['bg' => '#78350f', 'color' => '#fbbf24', 'border' => 'rgba(251, 191, 36, 0.3)', 'text' => 'Sedang'],
                    'hard'   => ['bg' => '#7f1d1d', 'color' => '#f87171', 'border' => 'rgba(248, 113, 113, 0.3)', 'text' => 'Sulit'],
                    default  => ['bg' => '#1e232e', 'color' => '#94a3b8', 'border' => 'rgba(148, 163, 184, 0.3)', 'text' => $ch->difficulty],
                };
                $catLabel = match($ch->category) {
                    'web' => 'Eksploitasi Web',
                    'crypto' => 'Kriptografi',
                    'recon' => 'Pengintaian',
                    'general' => 'Keahlian Umum',
                    default => $ch->category
                };
            @endphp

            <!-- Clean Challenge Card -->
            <div style="background: var(--surface-card); border: 1px solid {{ $isSolved ? 'rgba(34, 197, 94, 0.35)' : 'var(--border-hairline)' }}; border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; min-height: 230px;">
                <div>
                    <!-- Top Row: Category & Badges -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ $catLabel }}
                        </span>

                        <div style="display: flex; gap: 0.4rem; align-items: center;">
                            <span style="font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; background: {{ $diffBadge['bg'] }}; color: {{ $diffBadge['color'] }}; border: 1px solid {{ $diffBadge['border'] }};">
                                {{ $diffBadge['text'] }}
                            </span>
                            @if($isSolved)
                            <span style="font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 4px; background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3);">
                                SELESAI
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title & Description -->
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem 0; line-height: 1.3;">
                        {{ $ch->title }}
                    </h3>

                    <p style="font-size: 0.825rem; color: var(--text-secondary); margin: 0 0 1.25rem 0; line-height: 1.5;">
                        {{ Str::limit($ch->description, 130) }}
                    </p>
                </div>

                <!-- Footer: Points & CTA -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-hairline); margin-top: 1rem; padding-top: 1rem;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: #fbbf24;">
                        {{ $ch->points }} <span style="font-size: 0.75rem; color: var(--text-muted);">POIN</span>
                    </div>

                    <a href="{{ route('challenges.show', $ch->slug) }}" class="{{ $isSolved ? 'btn-subtle' : 'btn-orange' }}" style="font-size: 0.8rem; padding: 0.45rem 0.95rem;">
                        {{ $isSolved ? 'Tinjau Lab' : 'Akses Target' }} &rarr;
                    </a>
                </div>
            </div>

            @empty
            <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; color: var(--text-muted); background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 8px;">
                Tidak ada tantangan yang tersedia untuk kategori yang dipilih.
            </div>
            @endforelse
        </div>

    </div>

    <!-- Clean, Minimalist Center Lock State (No AI slop, no glow, no pill badge) -->
    @if($isLocked)
    @php $isAdmin = Auth::check() && Auth::user()->isAdmin(); @endphp
    <div style="position: fixed; inset: 0; top: 68px; display: flex; align-items: center; justify-content: center; z-index: 45; padding: 1.5rem; pointer-events: none;">
        
        <div style="pointer-events: auto; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; max-width: 420px;">

            <!-- Simple, clean yellow lock icon -->
            <div style="width: 58px; height: 58px; border-radius: 50%; background: rgba(234, 179, 8, 0.12); border: 1px solid rgba(234, 179, 8, 0.3); display: flex; align-items: center; justify-content: center; margin-bottom: 0.25rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <!-- Title -->
            <h2 style="font-size: 1.35rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.01em;">
                Tantangan Terkunci
            </h2>

            <!-- Simple, concise text without robotic boilerplate -->
            <p style="font-size: 0.875rem; color: #94a3b8; margin: 0; line-height: 1.5;">
                {{ match($competitionStatus) {
                    'paused' => 'Sesi tantangan saat ini sedang dijeda.',
                    'ended' => 'Sesi kompetisi telah berakhir.',
                    'upcoming' => 'Sesi tantangan belum dimulai.',
                    default => 'Akses tantangan sedang ditutup.'
                } }}
            </p>

            <!-- Actions -->
            <div style="margin-top: 0.6rem; display: flex; gap: 0.6rem;">
                @if($isAdmin)
                <a href="{{ route('admin.index') }}" class="btn-subtle" style="font-size: 0.8rem; padding: 0.45rem 1rem;">
                    Buka Panel Admin &rarr;
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="btn-subtle" style="font-size: 0.8rem; padding: 0.45rem 1rem;">
                    Kembali ke Dashboard
                </a>
                @endif
            </div>

        </div>

    </div>
    @endif

</div>
@endsection
