@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Scoreboard Header & Tabs -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.35rem 0; letter-spacing: -0.02em;">
                Platform Scoreboard
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                Live ranking of student cadets. Administrative accounts are strictly excluded from public standings.
            </p>
        </div>

        <!-- Board Tabs -->
        <div style="display: flex; gap: 0.4rem; background: var(--surface-card); padding: 0.3rem; border-radius: 8px; border: 1px solid var(--border-hairline);">
            <a href="{{ route('scoreboard', ['tab' => 'competition']) }}" class="btn-subtle" style="{{ $tab === 'competition' ? 'background: var(--surface-elevated); color: #4ade80; border-color: rgba(34, 197, 94, 0.4);' : '' }}; font-size: 0.85rem; padding: 0.45rem 1rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                Competition Board
            </a>
            <a href="{{ route('scoreboard', ['tab' => 'practice']) }}" class="btn-subtle" style="{{ $tab === 'practice' ? 'background: var(--surface-elevated); color: #60a5fa; border-color: rgba(59, 130, 246, 0.4);' : '' }}; font-size: 0.85rem; padding: 0.45rem 1rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                All-Time Practice Board
            </a>
        </div>
    </div>

    @php
        $currentList = ($tab === 'competition') ? $competitionUsers : $practiceUsers;
    @endphp

    <!-- Active Tab Description Pill -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1.25rem; font-size: 0.85rem; color: var(--text-secondary); display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span>ℹ️</span>
            @if($tab === 'competition')
                <span>Currently displaying <strong>Official Competition Rankings</strong> (flags captured strictly during active tournament mode).</span>
            @else
                <span>Currently displaying <strong>Cumulative Practice Leaderboard</strong> (lifetime challenges completed across all modules).</span>
            @endif
        </div>
        <div style="font-family: monospace; font-size: 0.75rem; color: var(--text-muted);">
            {{ count($currentList) }} ranked cadets
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 1rem 1.25rem; width: 80px;">Rank</th>
                    <th style="padding: 1rem 1.25rem;">Cadet / Participant</th>
                    <th style="padding: 1rem 1.25rem; width: 140px; text-align: center;">Labs Solved</th>
                    <th style="padding: 1rem 1.25rem; width: 160px; text-align: right;">Total Points</th>
                </tr>
            </thead>
            <tbody>
                @forelse($currentList as $index => $u)
                @php
                    $rank = $index + 1;
                    $rankMedal = match($rank) {
                        1 => ['color' => '#fbbf24', 'bg' => 'rgba(251, 191, 36, 0.15)', 'border' => 'rgba(251, 191, 36, 0.3)'],
                        2 => ['color' => '#cbd5e1', 'bg' => 'rgba(203, 213, 225, 0.15)', 'border' => 'rgba(203, 213, 225, 0.3)'],
                        3 => ['color' => '#d97706', 'bg' => 'rgba(217, 119, 6, 0.15)', 'border' => 'rgba(217, 119, 6, 0.3)'],
                        default => ['color' => 'var(--text-muted)', 'bg' => 'transparent', 'border' => 'transparent'],
                    };
                    $isCurrentUser = Auth::check() && Auth::id() === $u->id;
                @endphp
                <tr style="border-bottom: 1px solid var(--border-hairline); {{ $isCurrentUser ? 'background: rgba(249, 115, 22, 0.05);' : '' }}">
                    
                    <!-- Rank Column -->
                    <td style="padding: 1rem 1.25rem;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; font-weight: 700; font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; color: {{ $rankMedal['color'] }}; background: {{ $rankMedal['bg'] }}; border: 1px solid {{ $rankMedal['border'] }};">
                            #{{ $rank }}
                        </span>
                    </td>

                    <!-- Cadet Info -->
                    <td style="padding: 1rem 1.25rem;">
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">
                            {{ $u->name }}
                            @if($isCurrentUser)
                                <span style="font-size: 0.7rem; font-weight: 700; background: var(--accent-orange); color: #fff; padding: 0.15rem 0.45rem; border-radius: 4px; margin-left: 0.4rem;">YOU</span>
                            @endif
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">
                            {{ '@' . $u->username }}
                        </div>
                    </td>

                    <!-- Solved Count -->
                    <td style="padding: 1rem 1.25rem; text-align: center;">
                        <span style="font-weight: 700; font-family: 'JetBrains Mono', monospace; color: var(--text-primary);">
                            {{ $u->solved_count ?? 0 }}
                        </span>
                    </td>

                    <!-- Points -->
                    <td style="padding: 1rem 1.25rem; text-align: right;">
                        <span style="font-size: 1.15rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; color: #fbbf24;">
                            {{ number_format($u->total_points ?? 0) }}
                        </span>
                        <span style="font-size: 0.75rem; color: var(--text-muted); margin-left: 0.2rem;">PTS</span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        No entries recorded on this scoreboard yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

