@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Welcome & Hero Header -->
    <div style="background: linear-gradient(135deg, var(--surface-card) 0%, #171b26 100%); border: 1px solid var(--border-hairline); border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.05em;">Security Training Ground</span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">Cadet ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem 0; letter-spacing: -0.02em;">
                Welcome back, {{ $user->name }}
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0; max-width: 650px;">
                @if($activeMode === 'competition')
                    Competition Mode is in effect. Flag submissions are ranked on the official dynamic leaderboard.
                @else
                    Training & Practice Mode is active. Explore challenges freely with guided vulnerability remediation walkthroughs.
                @endif
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('challenges.index') }}" class="btn-orange">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                Browse Challenges &rarr;
            </a>
            <a href="{{ route('scoreboard') }}" class="btn-subtle">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                Leaderboard
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem;">
        
        <!-- Total Score -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.35rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                <span>Total Score</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary); margin: 0.5rem 0 0.2rem 0; font-family: 'JetBrains Mono', monospace;">
                {{ number_format($userPoints) }} <span style="font-size: 0.9rem; font-weight: 500; color: var(--accent-orange);">PTS</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                {{ $activeMode === 'competition' ? 'Accumulated in competition window' : 'Lifetime cumulative practice points' }}
            </div>
        </div>

        <!-- Solves Count -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.35rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                <span>Completed Labs</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary); margin: 0.5rem 0 0.2rem 0; font-family: 'JetBrains Mono', monospace;">
                {{ $solvedCount }} <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">/ {{ $totalChallenges }}</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                {{ $totalChallenges - $solvedCount }} challenges remaining
            </div>
        </div>

        <!-- Completion Percentage -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.35rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                <span>Completion</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #4ade80; margin: 0.5rem 0 0.2rem 0; font-family: 'JetBrains Mono', monospace;">
                {{ $completionRate }}%
            </div>
            <!-- Progress Bar -->
            <div style="height: 5px; width: 100%; background: var(--border-hairline); border-radius: 9999px; overflow: hidden; margin-top: 0.5rem;">
                <div style="height: 100%; width: {{ $completionRate }}%; background: #22c55e; border-radius: 9999px;"></div>
            </div>
        </div>

        <!-- Mode Indicator -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.35rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                <span>Platform State</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            </div>
            <div style="font-size: 1.35rem; font-weight: 700; color: var(--text-primary); margin: 0.5rem 0 0.2rem 0; text-transform: capitalize;">
                {{ $activeMode }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                Competition status: <span style="font-weight: 600; color: {{ $competitionStatus === 'active' ? '#4ade80' : '#f87171' }};">{{ strtoupper($competitionStatus) }}</span>
            </div>
        </div>

    </div>

    <!-- Category Progression Cards -->
    <div>
        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">
            Skill Domains & Tracks
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.25rem;">
            @foreach($categories as $catKey => $cat)
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.03em;">
                            {{ $cat['name'] }}
                        </span>
                        <span style="font-size: 0.75rem; font-family: monospace; color: var(--text-secondary); background: var(--surface-secondary); padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid var(--border-hairline);">
                            {{ $cat['solved'] }} / {{ $cat['total'] }} SOLVED
                        </span>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        @if($catKey === 'web')
                            SQL Injection, Insecure Direct Object References, Cross-Site Scripting
                        @elseif($catKey === 'crypto')
                            Substitution ciphers, Hash inversion, Rainbow table analysis
                        @else
                            Information disclosure, Subdomain enumeration, Infrastructure probing
                        @endif
                    </div>
                </div>

                <div>
                    @php $catPct = $cat['total'] > 0 ? round(($cat['solved'] / $cat['total']) * 100) : 0; @endphp
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                        <span>Domain Mastery</span>
                        <span style="font-weight: 600; color: var(--text-secondary);">{{ $catPct }}%</span>
                    </div>
                    <div style="height: 6px; width: 100%; background: var(--bg-base); border-radius: 9999px; overflow: hidden; border: 1px solid var(--border-hairline);">
                        <div style="height: 100%; width: {{ $catPct }}%; background: var(--accent-orange); border-radius: 9999px;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Challenges Overview Grid & Recent Activity (Two Columns) -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
        
        <!-- Left: Challenges Overview List -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0;">
                    Target Challenges Catalog
                </h2>
                <a href="{{ route('challenges.index') }}" style="font-size: 0.8rem; color: var(--accent-orange); font-weight: 600; text-decoration: none;">
                    View All ({{ $totalChallenges }}) &rarr;
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @foreach($challenges->take(6) as $ch)
                @php $solved = in_array($ch->id, $solvedChallengeIds); @endphp
                <a href="{{ route('challenges.show', $ch->slug) }}" style="display: flex; align-items: center; justify-content: space-between; background: var(--surface-card); border: 1px solid {{ $solved ? 'rgba(34, 197, 94, 0.3)' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1rem 1.25rem; text-decoration: none; transition: transform 0.15s ease, border-color 0.15s ease;" onmouseover="this.style.borderColor='#f97316'" onmouseout="this.style.borderColor='{{ $solved ? 'rgba(34, 197, 94, 0.3)' : 'var(--border-hairline)' }}'">
                    
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 36px; height: 36px; border-radius: 6px; background: {{ $solved ? 'rgba(34, 197, 94, 0.15)' : 'var(--surface-secondary)' }}; display: flex; align-items: center; justify-content: center; color: {{ $solved ? '#4ade80' : 'var(--text-secondary)' }};">
                            @if($solved)
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                            @endif
                        </div>

                        <div>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.2rem;">
                                {{ $ch->title }}
                            </div>
                            <div style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.75rem;">
                                <span style="text-transform: uppercase; color: var(--accent-orange); font-weight: 600;">{{ $ch->category }}</span>
                                <span style="color: var(--text-muted);">&bull;</span>
                                <span style="color: var(--text-secondary); text-transform: capitalize;">{{ $ch->difficulty }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 0.95rem; font-weight: 700; color: #fbbf24;">
                            {{ $ch->points }} <span style="font-size: 0.75rem; color: var(--text-muted);">PTS</span>
                        </div>
                        <span style="font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; {{ $solved ? 'background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3);' : 'background: var(--surface-secondary); color: var(--text-muted); border: 1px solid var(--border-hairline);' }}">
                            {{ $solved ? 'CAPTURED' : 'UNSOLVED' }}
                        </span>
                    </div>

                </a>
                @endforeach
            </div>
        </div>

        <!-- Right: Recent Live Solves Feed -->
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0 0 1rem 0;">
                Live Activity Wire
            </h2>

            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.25rem;">
                @if($recentSolves->isEmpty())
                <div style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 1.5rem 0;">
                    No recent submissions recorded yet.
                </div>
                @else
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    @foreach($recentSolves as $solve)
                    <div style="padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-hairline); font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600; color: var(--text-primary);">{{ $solve->user->username }}</span>
                            <span style="color: #4ade80; font-family: monospace; font-weight: 700;">+{{ $solve->challenge?->points }} pts</span>
                        </div>
                        <div style="color: var(--text-secondary); font-size: 0.75rem;">
                            Solved <span style="color: var(--accent-orange);">{{ $solve->challenge?->title }}</span>
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.7rem;">
                            {{ $solve->solved_at?->diffForHumans() }} &bull; <span style="text-transform: capitalize;">{{ $solve->mode }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

