@extends('layouts.app')

@section('title', $challenge->title)

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Breadcrumb & Challenge Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.4rem;">
                <a href="{{ route('challenges.index') }}" style="color: var(--text-secondary); text-decoration: none;">Challenges</a>
                <span>/</span>
                <span style="text-transform: uppercase; color: var(--accent-orange); font-weight: 600;">{{ $challenge->category }}</span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -0.02em;">
                {{ $challenge->title }}
            </h1>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @php
                $diffBadge = match($challenge->difficulty) {
                    'easy'   => ['bg' => '#14532d', 'color' => '#4ade80', 'border' => 'rgba(74, 222, 128, 0.3)'],
                    'medium' => ['bg' => '#78350f', 'color' => '#fbbf24', 'border' => 'rgba(251, 191, 36, 0.3)'],
                    'hard'   => ['bg' => '#7f1d1d', 'color' => '#f87171', 'border' => 'rgba(248, 113, 113, 0.3)'],
                    default  => ['bg' => '#1e232e', 'color' => '#94a3b8', 'border' => 'rgba(148, 163, 184, 0.3)'],
                };
            @endphp
            <span style="font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; background: {{ $diffBadge['bg'] }}; color: {{ $diffBadge['color'] }}; border: 1px solid {{ $diffBadge['border'] }}; text-transform: capitalize;">
                {{ $challenge->difficulty }}
            </span>

            <span style="font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; font-weight: 800; color: #fbbf24; background: var(--surface-card); padding: 0.25rem 0.75rem; border-radius: 4px; border: 1px solid var(--border-hairline);">
                {{ $challenge->points }} PTS
            </span>
        </div>
    </div>

    <!-- Main Two-Column View -->
    <div style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 1.75rem; align-items: start;">
        
        <!-- Left Column: Scenario, Vector Analysis & Educational Walkthrough -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Scenario & Objective Card -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.85rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    Operational Briefing
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; margin: 0 0 1.25rem 0;">
                    {{ $challenge->description }}
                </p>

                <!-- Objective Box -->
                <div style="background: var(--surface-secondary); border-left: 3px solid var(--accent-orange); border-radius: 4px; padding: 1rem; font-size: 0.85rem; color: var(--text-primary);">
                    <div style="font-weight: 700; color: var(--accent-orange); margin-bottom: 0.35rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">
                        Execution Objective
                    </div>
                    <ul style="margin: 0; padding-left: 1.25rem; display: flex; flex-direction: column; gap: 0.25rem;">
                        <li>Identify the vulnerable parameter or architectural misconfiguration.</li>
                        <li>Craft an exploit payload or exfiltration vector to capture the secret token.</li>
                        <li>Extract the token formatted as <code style="font-family: monospace; color: #4ade80;">FLAG{...}</code> and submit in the right panel.</li>
                    </ul>
                </div>
            </div>

            <!-- Training Mode: Hints & Remediation Accordion (Only in Training Mode) -->
            @if($activeMode === 'training')
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #60a5fa; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        Training Mode Guidance
                    </h3>
                    <span style="font-size: 0.7rem; font-weight: 600; background: rgba(59, 130, 246, 0.15); color: #60a5fa; padding: 0.2rem 0.5rem; border-radius: 4px;">
                        Interactive Learning
                    </span>
                </div>

                <!-- Hint Section -->
                <div style="margin-bottom: 1rem;">
                    <details style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1rem;">
                        <summary style="font-size: 0.85rem; font-weight: 600; color: #fbbf24; cursor: pointer; user-select: none;">
                            💡 Reveal Challenge Hint
                        </summary>
                        <div style="margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; border-top: 1px solid var(--border-hairline); padding-top: 0.75rem;">
                            {{ $challenge->hint ?? 'Analyze server error strings and query payloads for reflection anomalies.' }}
                        </div>
                    </details>
                </div>

                <!-- Remediation Walkthrough -->
                <div>
                    <details style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1rem;">
                        <summary style="font-size: 0.85rem; font-weight: 600; color: #4ade80; cursor: pointer; user-select: none;">
                            🛡️ Defense & Remediation Walkthrough
                        </summary>
                        <div style="margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; border-top: 1px solid var(--border-hairline); padding-top: 0.75rem;">
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.35rem;">Security Best Practice:</div>
                            {{ $challenge->remediation ?? 'Ensure all input streams are strictly validated and output is safely escaped or parameterized.' }}
                        </div>
                    </details>
                </div>
            </div>
            @endif

            <!-- Interactive Laboratory Target Information -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.85rem 0;">
                    Target Environment Endpoint
                </h3>

                @if($challenge->slug === 'sqli-login-bypass')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Interactive target: ShopEZ Administrative Login Console. The authentication query accepts unescaped input.
                    </p>
                    <a href="{{ route('lab.sqli_login') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                        Launch Target Console: /lab/sqli-login &nearr;
                    </a>
                @elseif($challenge->slug === 'sqli-union-exfiltration')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Interactive target: Enterprise Product Search API. Enumerate hidden columns to exfiltrate database records.
                    </p>
                    <a href="{{ route('lab.sqli_search') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                        Launch Target Endpoint: /lab/sqli-search &nearr;
                    </a>
                @elseif($challenge->slug === 'idor-profile-leak')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Interactive target: User Profile Gateway. Trusts user-supplied query identifier without authorization verification.
                    </p>
                    <a href="{{ route('lab.idor') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                        Launch Target Endpoint: /lab/idor?id=1 &nearr;
                    </a>
                @elseif($challenge->slug === 'xss-reflected')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Interactive target: Query Reflector Service. Reflects user strings directly into the active browser DOM.
                    </p>
                    <a href="{{ route('lab.xss') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                        Launch Target Endpoint: /lab/xss &nearr;
                    </a>
                @elseif($challenge->slug === 'info-disclosure-recon')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Target artifact: Investigate public crawler definitions in <code style="font-family: monospace; color: var(--accent-orange);">/robots.txt</code> to find obfuscated debug consoles.
                    </p>
                    <a href="/robots.txt" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                        Inspect Crawler Policy: /robots.txt &nearr;
                    </a>
                @else
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Static cryptographic or reconnaissance artifact. Review the briefing above to solve the payload.
                    </p>
                @endif
            </div>

        </div>

        <!-- Right Column: Submission Panel & Solvers Widget -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Flag Submission Card -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-orange)" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                    Flag Submission
                </h3>

                <!-- Locked Banner if Competition is paused/inactive -->
                @if($isSubmissionLocked)
                <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #f87171; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Flag Submissions Locked
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.4;">
                        {{ $lockReason }}
                    </div>
                </div>
                @endif

                <!-- Already Solved Banner -->
                @if($isSolved)
                <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #4ade80; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Challenge Completed
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">
                        Solved on {{ $solveRecord?->solved_at?->format('d M Y, H:i') }} ({{ $solveRecord?->mode }} mode).
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('challenges.submit', $challenge->id) }}">
                    @csrf
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="flag" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">
                            Flag Token
                        </label>
                        <input 
                            type="text" 
                            id="flag" 
                            name="flag" 
                            required 
                            autocomplete="off"
                            placeholder="FLAG{...}"
                            {{ $isSubmissionLocked ? 'disabled' : '' }}
                            style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.7rem 0.85rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                            onfocus="this.style.borderColor='var(--accent-orange)'"
                            onblur="this.style.borderColor='var(--border-input)'"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="btn-orange" 
                        {{ $isSubmissionLocked ? 'disabled' : '' }}
                        style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem;"
                    >
                        @if($isSubmissionLocked)
                            Submissions Locked
                        @elseif($isSolved)
                            Resubmit Flag
                        @else
                            Submit Flag &rarr;
                        @endif
                    </button>
                </form>
            </div>

            <!-- Solvers Preview Widget -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.25rem;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.85rem 0; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary);">
                    First Solvers
                </h4>

                @if($topSolvers->isEmpty())
                <div style="color: var(--text-muted); font-size: 0.8rem; text-align: center; padding: 1rem 0;">
                    No solvers yet. Be the first to claim First Blood!
                </div>
                @else
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($topSolvers as $idx => $ts)
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-weight: 700; color: {{ $idx === 0 ? '#fbbf24' : ($idx === 1 ? '#94a3b8' : ($idx === 2 ? '#b45309' : 'var(--text-muted)')) }}; font-family: monospace;">
                                #{{ $idx + 1 }}
                            </span>
                            <span style="font-weight: 600; color: var(--text-primary);">{{ $ts->user->username }}</span>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $ts->solved_at?->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

