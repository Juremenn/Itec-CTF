@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Welcome & Hero Header -->
    <div class="hero-header-box" style="background: linear-gradient(135deg, var(--surface-card) 0%, #171b26 100%); border: 1px solid var(--border-hairline); border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.05em;">Pusat Latihan Keamanan Siber</span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">ID Peserta: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem 0; letter-spacing: -0.02em;">
                Selamat datang, {{ $user->name }}
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0; max-width: 650px; line-height: 1.5;">
                @if($activeMode === 'competition')
                    Mode Kompetisi sedang berlangsung. Poin flag yang disubmit dihitung secara langsung pada papan peringkat turnamen.
                @else
                    Mode Latihan aktif. Pelajari tantangan secara mendalam dengan bantuan petunjuk dan analisis remedi celah keamanan.
                @endif
            </p>
        </div>

        <div class="hero-actions-group" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('challenges.index') }}" class="btn-orange" style="padding: 0.55rem 1.15rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                Jelajahi Tantangan &rarr;
            </a>
            <a href="{{ route('scoreboard') }}" class="btn-subtle" style="padding: 0.55rem 1.15rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                Papan Skor
            </a>
        </div>
    </div>

    <!-- Symmetrical Two-Column Content Grid (Responsive on Mobile) -->
    <div class="responsive-two-col" style="display: grid; grid-template-columns: 1.35fr 1fr; gap: 1.75rem; align-items: start;">
        
        <!-- Left Column: Challenges Overview List -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; min-height: 28px; margin-bottom: 1rem;">
                <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0;">
                    Katalog Target Tantangan
                </h2>
                <a href="{{ route('challenges.index') }}" style="font-size: 0.8rem; color: var(--accent-orange); font-weight: 600; text-decoration: none;">
                    Lihat Semua ({{ $totalChallenges }}) &rarr;
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @php $isLocked = ($competitionStatus !== 'active'); @endphp
                @foreach($challenges->take(6) as $ch)
                @php 
                    $solved = in_array($ch->id, $solvedChallengeIds); 
                    $diffLabel = match($ch->difficulty) {
                        'easy' => 'Mudah',
                        'medium' => 'Sedang',
                        'hard' => 'Sulit',
                        default => $ch->difficulty
                    };
                    $catLabel = match($ch->category) {
                        'web' => 'Web',
                        'crypto' => 'Kripto',
                        'recon' => 'Pengintaian',
                        'general' => 'Keahlian Umum',
                        default => $ch->category
                    };
                    $isAdmin = Auth::check() && Auth::user()->isAdmin();
                @endphp

                @if($isLocked && !$isAdmin)
                <div class="dash-challenge-card is-locked">
                @else
                <a href="{{ route('challenges.show', $ch->slug) }}" class="dash-challenge-card">
                @endif
                    
                    <div class="dash-challenge-card-main">
                        <div class="dash-challenge-card-icon">
                            @if($solved)
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                            @endif
                        </div>

                        <div class="dash-challenge-card-text">
                            <div class="dash-challenge-card-title">
                                {{ $ch->title }}
                            </div>
                            <div class="dash-challenge-card-meta">
                                <span style="text-transform: uppercase; font-weight: 600; color: var(--accent-orange);">{{ $catLabel }}</span>
                                <span>&bull;</span>
                                <span>Tingkat: {{ $diffLabel }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="dash-challenge-card-side">
                        <div class="dash-challenge-card-points">
                            {{ $ch->points }} POIN
                        </div>
                        
                        <div class="dash-challenge-card-status">
                            @if($solved)
                            <span style="font-size: 0.75rem; color: #4ade80; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Selesai
                            </span>
                            @elseif($isLocked)
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                Terkunci
                            </span>
                            @else
                            <span style="font-size: 0.75rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 0.2rem;">
                                Buka &rarr;
                            </span>
                            @endif
                        </div>
                    </div>

                @if($isLocked && !$isAdmin)
                </div>
                @else
                </a>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Right Column: Live Solves Feed & Lab Rules of Engagement -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            
            <!-- Widget 1: Aktivitas Terkini (Live Feed) -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; min-height: 28px; margin-bottom: 1rem;">
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0;">
                        Aktivitas Terkini
                    </h2>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                        Pembaruan Langsung
                    </span>
                </div>

                <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.15rem;">
                    @if($recentSolves->isEmpty())
                    <div style="color: var(--text-muted); font-size: 0.825rem; text-align: center; padding: 1.25rem 0; line-height: 1.5;">
                        Belum ada pengiriman flag yang tercatat.<br>Jadilah yang pertama menembus lab!
                    </div>
                    @else
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($recentSolves->take(3) as $solve)
                        <div style="padding-bottom: 0.65rem; border-bottom: 1px solid var(--border-hairline); font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: var(--text-primary);">{{ $solve->user->username }}</span>
                                <span style="color: #4ade80; font-weight: 700;">+{{ $solve->challenge?->points }} poin</span>
                            </div>
                            <div style="color: var(--text-secondary); font-size: 0.75rem;">
                                Menyelesaikan <span style="color: var(--accent-orange);">{{ $solve->challenge?->title }}</span>
                            </div>
                            <div style="color: var(--text-muted); font-size: 0.7rem;">
                                {{ $solve->solved_at?->diffForHumans() }} &bull; Mode {{ $solve->mode === 'competition' ? 'Kompetisi' : 'Latihan' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Widget 2: Ketentuan & Etika Lab (Rules of Engagement) -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; min-height: 28px; margin-bottom: 1rem;">
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0;">
                        Ketentuan & Etika Lab
                    </h2>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                        Panduan Peserta
                    </span>
                </div>

                <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.825rem; color: var(--text-secondary); line-height: 1.5;">
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 24px; height: 24px; border-radius: 4px; background: rgba(249, 115, 22, 0.12); color: var(--accent-orange); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <strong style="color: var(--text-primary);">Format Flag:</strong> Jawaban token valid selalu berformat <code style="background: var(--surface-secondary); padding: 0.1rem 0.35rem; border-radius: 4px; color: var(--accent-orange); font-size: 0.775rem;">FLAG{...}</code>.
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 24px; height: 24px; border-radius: 4px; background: rgba(239, 68, 68, 0.12); color: #f87171; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        </div>
                        <div>
                            <strong style="color: var(--text-primary);">Dilarang DoS/Brute Force:</strong> Pengujian otomatis berlebih atau serangan denial-of-service ke infrastruktur dilarang keras.
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 24px; height: 24px; border-radius: 4px; background: rgba(59, 130, 246, 0.12); color: #60a5fa; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </div>
                        <div>
                            <strong style="color: var(--text-primary);">Petunjuk Terarah:</strong> Gunakan petunjuk bertahap di halaman detail tantangan bila memerlukan arahan teknis.
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
