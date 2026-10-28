@extends('layouts.app')

@section('title', 'Secret Debug Console')

@section('content')
<div style="max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'info-disclosure-recon') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Back to Challenge Details</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #fbbf24; margin: 0;">
                Hidden Diagnostic Console
            </h1>
        </div>
        <span style="font-size: 0.75rem; font-family: monospace; background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.3); padding: 0.25rem 0.6rem; border-radius: 4px; color: #fbbf24;">
            ENDPOINT DISCOVERED
        </span>
    </div>

    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 2rem;">
        <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.5rem;">
            Congratulations on discovering this unlinked maintenance endpoint through information reconnaissance!
            In a real-world scenario, sensitive administrative dashboards left unauthenticated or documented in crawler policies (such as <code style="font-family: monospace; color: var(--accent-orange);">/robots.txt</code>) lead directly to system compromise.
        </p>

        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1.25rem;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #4ade80; text-transform: uppercase; margin-bottom: 0.4rem;">
                Flag Captured:
            </div>
            <code style="font-size: 1.15rem; font-weight: 700; color: #4ade80; font-family: 'JetBrains Mono', monospace; background: var(--bg-base); padding: 0.4rem 0.8rem; border-radius: 4px; display: inline-block;">
                {{ $flag }}
            </code>
        </div>
    </div>

</div>
@endsection

