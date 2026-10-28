@extends('layouts.app')

@section('title', 'Lab: Reflected XSS')

@section('content')
<div style="max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'xss-reflected') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Back to Challenge Details</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Target: Live Search Reflector
            </h1>
        </div>
    </div>

    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.75rem;">
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
            Submit a search string below to observe how the application reflects parameters back into the DOM.
        </p>

        @if($flag)
        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #4ade80; text-transform: uppercase; margin-bottom: 0.4rem;">
                XSS Execution Vector Triggered! Flag Captured:
            </div>
            <code style="font-size: 1.15rem; font-weight: 700; color: #4ade80; font-family: 'JetBrains Mono', monospace; background: var(--bg-base); padding: 0.4rem 0.8rem; border-radius: 4px; display: inline-block;">
                {{ $flag }}
            </code>
        </div>
        @endif

        <form method="GET" action="{{ route('lab.xss') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1.25rem;">
            <input 
                type="text" 
                name="q" 
                value="{{ $search }}" 
                placeholder="Enter payload: <script>alert(1)</script>" 
                style="flex: 1; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-family: monospace; font-size: 0.9rem; outline: none;"
            >
            <button type="submit" class="btn-orange">
                Search
            </button>
        </form>

        @if($search !== '')
        <div style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 1.25rem;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                Unsanitized Reflected Output Stream:
            </div>
            <div style="font-size: 0.95rem; color: var(--text-primary); background: var(--bg-base); padding: 1rem; border-radius: 6px; border: 1px dashed var(--border-hairline);">
                Searching for: {!! $search !!}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection

