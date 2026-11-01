@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- CyLab Standard Welcome Hero Banner (Deep Midnight Navy Gradient, Two-Tone Greeting) -->
    <div class="cylab-dash-hero" style="background: linear-gradient(135deg, #071930 0%, #0a2347 100%); border: 1px solid #13345e; border-radius: 12px; padding: 2.25rem 2.5rem; position: relative; overflow: hidden;">
        
        <!-- Subtle Top-Right Ambient Watermark Graphic -->
        <div style="position: absolute; right: -15px; top: -25px; opacity: 0.05; pointer-events: none; user-select: none;">
            <svg width="260" height="260" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
        </div>

        <!-- Date Timestamp Badge -->
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; color: #7ba4cc; font-weight: 500; margin-bottom: 0.75rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>{{ $dateString }}</span>
        </div>

        <!-- Headline with Soft Sky Blue Highlight -->
        <h1 style="font-size: 2rem; font-weight: 700; color: #f8fafc; margin: 0 0 0.75rem 0; letter-spacing: -0.025em; line-height: 1.25;">
            {{ $greeting }}, <span style="color: #60a5fa;">{{ $user->name }}</span>
        </h1>

        <!-- Subtitle -->
        <p style="font-size: 0.925rem; color: #cbd5e1; margin: 0 0 1.75rem 0; max-width: 680px; line-height: 1.6;">
            @if($activeMode === 'training')
                Mode latihan mandiri aktif. Jelajahi target lab keamanan, pelajari teknik eksploitasi, dan tingkatkan pemahaman tanpa batasan waktu turnamen.
            @else
                Lanjutkan progres investigasi lab terakhir Anda, atau jelajahi target baru. Tantangan berikutnya siap dianalisis dan diselesaikan.
            @endif
        </p>

        <!-- Dual Pill Action Buttons (Exact CyLab Geometry) -->
        <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
            <a href="{{ route('challenges.index') }}" class="cylab-hero-btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.4rem; background: var(--accent-orange); color: #ffffff; font-size: 0.85rem; font-weight: 600; border-radius: 9999px; text-decoration: none; transition: background 0.15s ease, transform 0.15s ease;" onmouseover="this.style.background='#ea580c'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='var(--accent-orange)'; this.style.transform='none'">
                <span>Jelajahi Tantangan</span>
                <span style="font-size: 0.95rem;">&rarr;</span>
            </a>

            <a href="{{ $activeMode === 'training' ? route('scoreboard', ['tab' => 'practice']) : route('scoreboard', ['tab' => 'competition']) }}" class="cylab-hero-btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.4rem; background: #162a45; border: 1px solid #244b7a; color: #f8fafc; font-size: 0.85rem; font-weight: 600; border-radius: 9999px; text-decoration: none; transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;" onmouseover="this.style.background='#1e395e'; this.style.borderColor='#3b6da8'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#162a45'; this.style.borderColor='#244b7a'; this.style.transform='none'">
                <span>{{ $activeMode === 'training' ? 'Papan Skor Latihan' : 'Papan Skor Turnamen' }}</span>
            </a>
        </div>

    </div>

    <!-- Symmetrical Two-Column Content Grid (Responsive) -->
    <div class="cylab-dash-layout" style="display: grid; grid-template-columns: 320px 1fr; gap: 1.75rem; align-items: start;">
        
        <!-- Left Column: Your Progress -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <h2 style="font-size: 1rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.01em;">
                Your Progress
            </h2>

            <!-- Card 1: Streak / Tournament Participation Status -->
            <div style="background: #11141c; border: 1px solid #232b3d; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; min-height: 100px;">
                @if($activeMode === 'training')
                <div style="display: flex; align-items: center; gap: 0.55rem; color: #60a5fa;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    <span style="font-size: 0.875rem; font-weight: 700; color: #f8fafc;">
                        Status Latihan
                    </span>
                </div>
                
                <div style="font-size: 0.8rem; color: #94a3b8; margin: 0.5rem 0 0.85rem 0; line-height: 1.45;">
                    Platform membuka seluruh modul untuk latihan mandiri. Anda dapat menganalisis kerentanan dan menguji payload secara leluasa.
                </div>

                <a href="{{ route('challenges.index') }}" style="font-size: 0.775rem; color: #cbd5e1; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;" onmouseover="this.style.color='var(--accent-orange)'" onmouseout="this.style.color='#cbd5e1'">
                    <span>Akses modul latihan</span>
                    <span style="font-size: 0.9rem;">&rarr;</span>
                </a>
                @else
                <div style="display: flex; align-items: center; gap: 0.55rem; color: #f59e0b;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                    </svg>
                    <span style="font-size: 0.875rem; font-weight: 700; color: #f8fafc;">
                        Status Partisipasi
                    </span>
                </div>
                
                <div style="font-size: 0.8rem; color: #94a3b8; margin: 0.5rem 0 0.85rem 0; line-height: 1.45;">
                    @if($competitionStatus === 'active')
                        Sesi turnamen sedang berjalan aktif. Setiap flag yang disubmit tercatat langsung di papan peringkat.
                    @else
                        Sesi turnamen saat ini sedang dalam mode tinjauan.
                    @endif
                </div>

                <a href="{{ route('scoreboard', ['tab' => 'competition']) }}" style="font-size: 0.775rem; color: #cbd5e1; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;" onmouseover="this.style.color='var(--accent-orange)'" onmouseout="this.style.color='#cbd5e1'">
                    <span>Pantau posisi peringkat</span>
                    <span style="font-size: 0.9rem;">&rarr;</span>
                </a>
                @endif
            </div>

            <!-- Card 2: Challenges Completed Metric -->
            <div style="background: #11141c; border: 1px solid #232b3d; border-radius: 10px; padding: 1.25rem;">
                <div style="display: flex; align-items: baseline; gap: 0.6rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.85rem; font-weight: 700; color: #f8fafc; font-family: 'JetBrains Mono', monospace; line-height: 1;">
                        {{ $solvedCount }}
                    </span>
                    <span style="font-size: 0.85rem; color: #94a3b8; font-weight: 500;">
                        Challenges Completed
                    </span>
                </div>

                <!-- Green Progress Bar -->
                <div style="height: 6px; background: #1c2230; border-radius: 9999px; overflow: hidden; margin-bottom: 0.55rem;">
                    <div style="height: 100%; width: {{ $completionRate }}%; background: #22c55e; border-radius: 9999px; transition: width 0.35s ease;"></div>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #64748b; font-family: 'JetBrains Mono', monospace;">
                    <span>{{ $solvedCount }}/{{ $totalChallenges }} selesai</span>
                    <span>{{ $completionRate }}%</span>
                </div>
            </div>

            <!-- Card 3: Cadet Account Meta -->
            <div style="background: #11141c; border: 1px solid #1c2230; border-radius: 10px; padding: 1.15rem 1.25rem;">
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.75rem;">
                    Identitas Peserta
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                    <span style="color: #94a3b8;">Total Poin</span>
                    <span style="color: #f59e0b; font-family: 'JetBrains Mono', monospace; font-weight: 700;">{{ $userPoints }} PTS</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; margin-top: 0.5rem;">
                    <span style="color: #94a3b8;">ID Peserta</span>
                    <span style="color: #cbd5e1; font-family: 'JetBrains Mono', monospace;">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

        </div>

        <!-- Right Column: Continue where you left off & Lab Targets -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            
            <!-- Section Title -->
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.01em;">
                    Continue where you left off
                </h2>
                <a href="{{ route('challenges.index') }}" style="font-size: 0.8rem; color: var(--accent-orange); font-weight: 600; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    Katalog Lengkap &rarr;
                </a>
            </div>

            @if($nextChallenge)
            @php
                $diff = strtolower($nextChallenge->difficulty);
                $isNextSolved = in_array($nextChallenge->id, $solvedChallengeIds);
                if ($diff === 'medium') {
                    $b1 = '#f59e0b'; $b2 = '#f59e0b'; $b3 = '#f59e0b'; $b4 = 'rgba(255, 255, 255, 0.15)';
                    $diffColor = '#f59e0b'; $diffLabel = 'Medium';
                } elseif ($diff === 'hard') {
                    $b1 = '#ef4444'; $b2 = '#ef4444'; $b3 = '#ef4444'; $b4 = '#ef4444';
                    $diffColor = '#ef4444'; $diffLabel = 'Hard';
                } else {
                    $b1 = '#22c55e'; $b2 = '#22c55e'; $b3 = 'rgba(255, 255, 255, 0.15)'; $b4 = 'rgba(255, 255, 255, 0.15)';
                    $diffColor = '#22c55e'; $diffLabel = 'Easy';
                }
            @endphp
            <!-- Large Hero Mission Card -->
            <div class="cylab-featured-card" style="background: #11141c; border: 1px solid #1c2230; border-radius: 12px; padding: 1.75rem; display: flex; flex-direction: column; gap: 1.25rem; transition: border-color 0.15s ease;">
                
                <!-- Card Top Meta: Featured Tag + Difficulty Signal Meter -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem;">
                        <span style="font-weight: 600; color: #f59e0b; letter-spacing: -0.01em;">
                            Target Rekomendasi
                        </span>
                        <span style="color: #475569;">/</span>
                        <span style="color: #64748b; font-weight: 500;">
                            by ITEC
                        </span>
                    </div>

                    <!-- Difficulty Cellular Signal Meter -->
                    <div style="display: flex; align-items: center; gap: 0.45rem;">
                        <svg width="16" height="14" viewBox="0 0 18 16" fill="none">
                            <rect x="1" y="10" width="3.2" height="6" rx="0.8" fill="{{ $b1 }}" />
                            <rect x="5.5" y="7" width="3.2" height="9" rx="0.8" fill="{{ $b2 }}" />
                            <rect x="10" y="4" width="3.2" height="12" rx="0.8" fill="{{ $b3 }}" />
                            <rect x="14.5" y="1" width="3.2" height="15" rx="0.8" fill="{{ $b4 }}" />
                        </svg>
                        <span style="font-size: 0.85rem; font-weight: 700; color: {{ $diffColor }};">
                            {{ $diffLabel }}
                        </span>
                    </div>
                </div>

                <!-- Mission Title & Category -->
                <div>
                    <h3 style="font-size: 1.35rem; font-weight: 700; color: #f8fafc; margin: 0 0 0.45rem 0; letter-spacing: -0.015em;">
                        {{ $nextChallenge->title }}
                    </h3>
                    <div style="font-size: 0.85rem; color: #94a3b8; line-height: 1.55;">
                        {{ Str::limit($nextChallenge->description ?? 'Analisis kelemahan sistem, telusuri celah keamanan interaktif, dan dapatkan flag otoritas.', 160) }}
                    </div>
                </div>

                <!-- Footer: Points + Action CTA Button -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.04);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #f59e0b; font-weight: 700;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>{{ $nextChallenge->points }} PTS</span>
                    </div>

                    <a href="{{ route('challenges.show', $nextChallenge->slug) }}" class="btn-orange" style="padding: 0.55rem 1.25rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem;">
                        <span>{{ $isNextSolved ? 'Buka Lab Kembali' : 'Mulai Investigasi Lab' }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>
            @endif

            <!-- Mini List of Other Available Challenges -->
            <div>
                <div style="font-size: 0.825rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.85rem; letter-spacing: 0.01em;">
                    Target Lainnya di Katalog
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @php $isLocked = ($competitionStatus !== 'active'); @endphp
                    @foreach($challenges->take(5) as $ch)
                    @php 
                        $solved = in_array($ch->id, $solvedChallengeIds); 
                        $isAdmin = Auth::check() && Auth::user()->isAdmin();
                    @endphp
                    @if($isLocked && !$isAdmin)
                    <div class="dash-challenge-card is-locked" style="background: #11141c; border: 1px solid #1c2230; border-radius: 8px; padding: 0.85rem 1.15rem; display: flex; justify-content: space-between; align-items: center; opacity: 0.6;">
                    @else
                    <a href="{{ route('challenges.show', $ch->slug) }}" class="dash-challenge-card" style="background: #11141c; border: 1px solid #1c2230; border-radius: 8px; padding: 0.85rem 1.15rem; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: inherit; transition: border-color 0.15s ease, background 0.15s ease; {{ $solved ? 'opacity: 0.65;' : '' }}" onmouseover="this.style.borderColor='#384258'; this.style.background='#141926'" onmouseout="this.style.borderColor='#1c2230'; this.style.background='#11141c'">
                    @endif
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            <div style="color: {{ $solved ? '#22c55e' : '#64748b' }}; display: flex; align-items: center;">
                                @if($solved)
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @else
                                    <span style="font-size: 0.75rem; font-family: 'JetBrains Mono', monospace; font-weight: 700;">#{{ str_pad($ch->id, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: #f8fafc;">
                                    {{ $ch->title }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem;">
                                    by ITEC
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: #f59e0b; font-weight: 600;">
                                {{ $ch->points }} PTS
                            </span>
                            <span style="font-size: 0.75rem; color: #64748b;">
                                &rarr;
                            </span>
                        </div>
                    @if($isLocked && !$isAdmin)
                    </div>
                    @else
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Widget: Live Feed Solves -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
                    <div style="font-size: 0.825rem; font-weight: 600; color: #94a3b8; letter-spacing: 0.01em;">
                        Aktivitas Solves Terkini
                    </div>
                    <span style="font-size: 0.725rem; color: #64748b;">
                        Pembaruan Langsung
                    </span>
                </div>

                <div style="background: #11141c; border: 1px solid #1c2230; border-radius: 10px; padding: 1.15rem;">
                    @if($recentSolves->isEmpty())
                    <div style="color: #64748b; font-size: 0.825rem; text-align: center; padding: 1rem 0; line-height: 1.55;">
                        Belum ada pengiriman flag yang tercatat.<br>Jadilah yang pertama menembus lab!
                    </div>
                    @else
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($recentSolves->take(3) as $solve)
                        <div style="padding-bottom: 0.65rem; border-bottom: 1px solid rgba(255, 255, 255, 0.04); font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #f8fafc;">{{ $solve->user->username }}</span>
                                <span style="color: #22c55e; font-weight: 700; font-family: 'JetBrains Mono', monospace;">+{{ $solve->challenge?->points }} PTS</span>
                            </div>
                            <div style="color: #94a3b8; font-size: 0.75rem;">
                                Menyelesaikan <span style="color: #cbd5e1;">{{ $solve->challenge?->title }}</span>
                            </div>
                            <div style="color: #64748b; font-size: 0.7rem;">
                                {{ $solve->solved_at?->diffForHumans() }} &bull; Mode {{ $solve->mode === 'competition' ? 'Kompetisi' : 'Latihan' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>

<style>
@media (max-width: 960px) {
    .cylab-dash-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
