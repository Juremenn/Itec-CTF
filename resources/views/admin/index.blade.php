@extends('layouts.app')

@section('title', 'Admin Control Center')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Admin Stealth Header -->
    <div style="background: linear-gradient(135deg, #13151c 0%, #1f1816 100%); border: 1px solid rgba(249, 115, 22, 0.3); border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; font-weight: 700; background: rgba(249, 115, 22, 0.2); color: var(--accent-orange); padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid rgba(249, 115, 22, 0.4); text-transform: uppercase;">
                    Stealth Operation Gateway
                </span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.8rem; font-family: monospace; color: var(--text-secondary);">/itec/admin</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.4rem 0; letter-spacing: -0.02em;">
                Instructor CTF Control Center
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                Manage competition operational states, toggle operating modes, broadcast announcements, and regulate scoreboard states.
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('admin.challenges.create') }}" class="btn-orange">
                + Create Challenge
            </a>
            <a href="{{ route('admin.challenges.index') }}" class="btn-subtle">
                Manage Challenges
            </a>
        </div>
    </div>

    <!-- Quick State Control Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        
        <!-- Operating Mode Toggle Card -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Platform Operating Mode
                </div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $activeMode === 'competition' ? '#f97316' : '#3b82f6' }};"></span>
                    {{ ucfirst($activeMode) }} Mode Active
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.4;">
                    Switch between time-sensitive tournament rules (competition) and open-ended pedagogical practice with hints (training).
                </p>
            </div>

            <form method="POST" action="{{ route('admin.toggle_mode') }}">
                @csrf
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" name="active_mode" value="training" class="btn-subtle" style="flex: 1; justify-content: center; {{ $activeMode === 'training' ? 'background: rgba(59, 130, 246, 0.2); color: #60a5fa; border-color: rgba(59, 130, 246, 0.4);' : '' }}">
                        Training
                    </button>
                    <button type="submit" name="active_mode" value="competition" class="btn-subtle" style="flex: 1; justify-content: center; {{ $activeMode === 'competition' ? 'background: rgba(249, 115, 22, 0.2); color: #f97316; border-color: rgba(249, 115, 22, 0.4);' : '' }}">
                        Competition
                    </button>
                </div>
            </form>
        </div>

        <!-- Competition Status Controller -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Tournament Gate State
                </div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $competitionStatus === 'active' ? '#22c55e' : '#ef4444' }};"></span>
                    Status: {{ strtoupper($competitionStatus) }}
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.4;">
                    When paused or ended, participant flag submissions are strictly hard-locked across all endpoints.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.toggle_mode') }}">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <button type="submit" name="competition_status" value="active" class="btn-subtle" style="{{ $competitionStatus === 'active' ? 'background: rgba(34, 197, 94, 0.2); color: #4ade80; border-color: rgba(34, 197, 94, 0.4);' : '' }}; justify-content: center;">
                        ▶ Activate
                    </button>
                    <button type="submit" name="competition_status" value="paused" class="btn-subtle" style="{{ $competitionStatus === 'paused' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border-color: rgba(239, 68, 68, 0.4);' : '' }}; justify-content: center;">
                        ⏸ Pause
                    </button>
                    <button type="submit" name="competition_status" value="upcoming" class="btn-subtle" style="{{ $competitionStatus === 'upcoming' ? 'background: rgba(234, 179, 8, 0.2); color: #facc15; border-color: rgba(234, 179, 8, 0.4);' : '' }}; justify-content: center;">
                        ⏳ Upcoming
                    </button>
                    <button type="submit" name="competition_status" value="ended" class="btn-subtle" style="{{ $competitionStatus === 'ended' ? 'background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.4);' : '' }}; justify-content: center;">
                        ⏹ Ended
                    </button>
                </div>
            </form>
        </div>

        <!-- Global Broadcast Controller -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Global Announcement Banner
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.75rem; line-height: 1.4;">
                    Broadcast messages appear immediately across the top of all participant screens.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.broadcast') }}" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                <input 
                    type="text" 
                    name="broadcast_message" 
                    value="{{ $broadcast }}" 
                    placeholder="e.g. Competition ends in 15 minutes! Submit flags now."
                    style="background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.55rem 0.75rem; color: var(--text-primary); font-size: 0.85rem; outline: none;"
                >
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn-orange" style="flex: 1; justify-content: center; font-size: 0.8rem; padding: 0.5rem;">
                        Publish Broadcast
                    </button>
                    <button type="button" onclick="document.querySelector('input[name=broadcast_message]').value=''; document.forms[2].submit();" class="btn-subtle" style="font-size: 0.8rem; padding: 0.5rem;">
                        Clear
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Scoreboard Wipe / Reset Section -->
    <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 10px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="font-size: 1rem; font-weight: 700; color: #f87171; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                Emergency Scoreboard Reset
            </div>
            <p style="font-size: 0.825rem; color: var(--text-secondary); margin: 0; max-width: 650px;">
                Purges all student solve entries from the <code style="font-family: monospace; color: #f87171;">solves</code> table to launch a fresh competition round. Participant accounts and challenges remain intact.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.reset_scores') }}" style="display: flex; gap: 0.5rem; align-items: center;" onsubmit="return confirm('WARNING: This will permanently wipe all solves and leaderboard scores! Continue?');">
            @csrf
            <input 
                type="text" 
                name="confirm_reset" 
                placeholder="Type RESET" 
                required 
                style="background: var(--bg-base); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 6px; padding: 0.5rem 0.75rem; color: #f87171; font-family: monospace; font-size: 0.85rem; width: 120px; outline: none;"
            >
            <button type="submit" style="background: #ef4444; color: #fff; font-weight: 600; font-size: 0.85rem; padding: 0.55rem 1rem; border-radius: 6px; border: none; cursor: pointer;">
                Purge Scores
            </button>
        </form>
    </div>

    <!-- Participant Monitoring Table -->
    <div>
        <h2 style="font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">
            Enrolled Student Participants ({{ $totalParticipants }})
        </h2>

        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 1rem 1.25rem;">Participant</th>
                        <th style="padding: 1rem 1.25rem;">Email</th>
                        <th style="padding: 1rem 1.25rem; text-align: center;">Competition Solves</th>
                        <th style="padding: 1rem 1.25rem; text-align: center;">Total Solves</th>
                        <th style="padding: 1rem 1.25rem; text-align: right;">Cumulative Points</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                    <tr style="border-bottom: 1px solid var(--border-hairline);">
                        <td style="padding: 0.85rem 1.25rem;">
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $p->name }}</div>
                            <div style="font-size: 0.75rem; font-family: monospace; color: var(--text-muted);">{{ '@' . $p->username }}</div>
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: var(--text-secondary); font-family: monospace; font-size: 0.8rem;">
                            {{ $p->email }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: center; font-family: monospace; font-weight: 700; color: #4ade80;">
                            {{ $p->competition_solves }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: center; font-family: monospace; font-weight: 700; color: var(--text-secondary);">
                            {{ $p->total_solves }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: right; font-family: monospace; font-weight: 800; color: #fbbf24; font-size: 1rem;">
                            {{ number_format($p->total_points ?? 0) }} PTS
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                            No participants registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

