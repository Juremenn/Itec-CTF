@extends('layouts.app')

@section('title', 'Challenges')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Header & Category Filter Tabs -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.35rem 0; letter-spacing: -0.02em;">
                Target Challenges Catalog
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                Select a vulnerability target below to launch interactive laboratory probes and capture flags.
            </p>
        </div>

        <!-- Filter Tabs -->
        <div style="display: flex; gap: 0.4rem; background: var(--surface-card); padding: 0.3rem; border-radius: 8px; border: 1px solid var(--border-hairline);">
            <a href="{{ route('challenges.index') }}" class="btn-subtle {{ $selectedCategory === 'all' ? 'active' : '' }}" style="{{ $selectedCategory === 'all' ? 'background: var(--surface-elevated); color: var(--text-primary); border-color: #2a3142;' : '' }}; font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                All
            </a>
            <a href="{{ route('challenges.index', ['category' => 'web']) }}" class="btn-subtle {{ $selectedCategory === 'web' ? 'active' : '' }}" style="{{ $selectedCategory === 'web' ? 'background: var(--surface-elevated); color: var(--text-primary); border-color: #2a3142;' : '' }}; font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                Web Exploitation
            </a>
            <a href="{{ route('challenges.index', ['category' => 'crypto']) }}" class="btn-subtle {{ $selectedCategory === 'crypto' ? 'active' : '' }}" style="{{ $selectedCategory === 'crypto' ? 'background: var(--surface-elevated); color: var(--text-primary); border-color: #2a3142;' : '' }}; font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                Cryptography
            </a>
            <a href="{{ route('challenges.index', ['category' => 'recon']) }}" class="btn-subtle {{ $selectedCategory === 'recon' ? 'active' : '' }}" style="{{ $selectedCategory === 'recon' ? 'background: var(--surface-elevated); color: var(--text-primary); border-color: #2a3142;' : '' }}; font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                Reconnaissance
            </a>
        </div>
    </div>

    <!-- Challenges Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.35rem;">
        @forelse($challenges as $ch)
        @php
            $isSolved = in_array($ch->id, $userSolves);
            $diffBadge = match($ch->difficulty) {
                'easy'   => ['bg' => '#14532d', 'color' => '#4ade80', 'border' => 'rgba(74, 222, 128, 0.3)'],
                'medium' => ['bg' => '#78350f', 'color' => '#fbbf24', 'border' => 'rgba(251, 191, 36, 0.3)'],
                'hard'   => ['bg' => '#7f1d1d', 'color' => '#f87171', 'border' => 'rgba(248, 113, 113, 0.3)'],
                default  => ['bg' => '#1e232e', 'color' => '#94a3b8', 'border' => 'rgba(148, 163, 184, 0.3)'],
            };
        @endphp

        <div style="background: var(--surface-card); border: 1px solid {{ $isSolved ? 'rgba(34, 197, 94, 0.35)' : 'var(--border-hairline)' }}; border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.15s ease;" onmouseover="this.style.borderColor='var(--accent-orange)'" onmouseout="this.style.borderColor='{{ $isSolved ? 'rgba(34, 197, 94, 0.35)' : 'var(--border-hairline)' }}'">
            <div>
                <!-- Top Row: Category & Badges -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.05em;">
                        {{ $ch->category }}
                    </span>

                    <div style="display: flex; gap: 0.4rem; align-items: center;">
                        <span style="font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; background: {{ $diffBadge['bg'] }}; color: {{ $diffBadge['color'] }}; border: 1px solid {{ $diffBadge['border'] }}; text-transform: capitalize;">
                            {{ $ch->difficulty }}
                        </span>
                        @if($isSolved)
                        <span style="font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 4px; background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3);">
                            SOLVED
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Title & Description -->
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem 0; line-height: 1.3;">
                    <a href="{{ route('challenges.show', $ch->slug) }}" style="color: inherit; text-decoration: none;">
                        {{ $ch->title }}
                    </a>
                </h3>

                <p style="font-size: 0.825rem; color: var(--text-secondary); margin: 0 0 1.25rem 0; line-height: 1.5;">
                    {{ Str::limit($ch->description, 130) }}
                </p>
            </div>

            <!-- Footer: Points & CTA -->
            <div style="display: flex; justify-content: space-between; align-items: center; pt: 1rem; border-top: 1px solid var(--border-hairline); margin-top: 1rem; padding-top: 1rem;">
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; font-weight: 800; color: #fbbf24;">
                    {{ $ch->points }} <span style="font-size: 0.75rem; color: var(--text-muted);">PTS</span>
                </div>

                <a href="{{ route('challenges.show', $ch->slug) }}" class="{{ $isSolved ? 'btn-subtle' : 'btn-orange' }}" style="font-size: 0.8rem; padding: 0.45rem 0.95rem;">
                    {{ $isSolved ? 'Review Lab' : 'Access Target' }} &rarr;
                </a>
            </div>
        </div>

        @empty
        <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; color: var(--text-muted); background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 8px;">
            No challenges available for the selected category filter.
        </div>
        @endforelse
    </div>

</div>
@endsection

