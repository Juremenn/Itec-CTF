@extends('layouts.app')

@section('title', 'Challenge Library')

@section('content')
@php
    $totalChallenges = $challenges->count();
    $totalSolved = count($userSolves);
    $overallPercent = $totalChallenges > 0 ? round(($totalSolved / $totalChallenges) * 100) : 0;

    $easyChallenges = $challenges->filter(fn($c) => strtolower($c->difficulty) === 'easy');
    $easyTotal = $easyChallenges->count();
    $easySolved = $easyChallenges->filter(fn($c) => in_array($c->id, $userSolves))->count();
    $easyPercent = $easyTotal > 0 ? round(($easySolved / $easyTotal) * 100) : 0;

    $mediumChallenges = $challenges->filter(fn($c) => strtolower($c->difficulty) === 'medium');
    $mediumTotal = $mediumChallenges->count();
    $mediumSolved = $mediumChallenges->filter(fn($c) => in_array($c->id, $userSolves))->count();
    $mediumPercent = $mediumTotal > 0 ? round(($mediumSolved / $mediumTotal) * 100) : 0;

    $hardChallenges = $challenges->filter(fn($c) => strtolower($c->difficulty) === 'hard');
    $hardTotal = $hardChallenges->count();
    $hardSolved = $hardChallenges->filter(fn($c) => in_array($c->id, $userSolves))->count();
    $hardPercent = $hardTotal > 0 ? round(($hardSolved / $hardTotal) * 100) : 0;

    $categoryStats = [
        'web' => [
            'name' => 'Web Exploitation',
            'display' => 'Eksploitasi Web',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
        ],
        'crypto' => [
            'name' => 'Cryptography',
            'display' => 'Kriptografi',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
        ],
        'recon' => [
            'name' => 'Reconnaissance',
            'display' => 'Pengintaian',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>',
        ],
        'general' => [
            'name' => 'General Skills',
            'display' => 'Keahlian Umum',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>',
        ],
    ];

    $groupedChallenges = $challenges->groupBy('category');
    $categoryOrder = ['web', 'crypto', 'recon', 'general'];
    if ($selectedCategory !== 'all') {
        $categoryOrder = array_intersect([$selectedCategory], $categoryOrder);
    }
@endphp

<div style="position: relative; min-height: 70vh;">

    <!-- CyLab Top Header Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.6rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.02em;">
            Challenge Library
        </h1>
        
        <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: #cbd5e1; text-decoration: none; padding: 0.45rem 0.85rem; background: #11141c; border: 1px solid #1c2230; border-radius: 6px; transition: border-color 0.15s ease, color 0.15s ease;" onmouseover="this.style.borderColor='#384258'; this.style.color='#f8fafc'" onmouseout="this.style.borderColor='#1c2230'; this.style.color='#cbd5e1'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Open Workspace</span>
            <span style="font-size: 0.95rem;">&rarr;</span>
        </a>
    </div>

    <!-- SecOps Operational Status Notice Banner (When Not Active, Clean Typography, Zero Slop) -->
    @if($competitionStatus !== 'active')
    <div style="background: #11141c; border: 1px solid #1c2230; border-left: 3px solid #f59e0b; border-radius: 6px; padding: 0.95rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.85rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <div>
                <div style="font-size: 0.875rem; font-weight: 700; color: #f8fafc; letter-spacing: -0.01em;">
                    {{ match($competitionStatus) {
                        'paused'   => 'Sesi Kompetisi Sedang Dijeda',
                        'ended'    => 'Sesi Kompetisi Telah Berakhir',
                        'upcoming' => 'Sesi Kompetisi Belum Dimulai',
                        default    => 'Status Kompetisi Terkunci',
                    } }}
                </div>
                <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.15rem; line-height: 1.45;">
                    {{ match($competitionStatus) {
                        'paused'   => 'Akses server lab interaktif dan pengiriman flag ditangguhkan sementara oleh instruktur. Anda tetap dapat meninjau katalog target di bawah.',
                        'ended'    => 'Pengiriman flag dan akses target telah ditutup secara permanen. Katalog dapat diakses sebagai referensi.',
                        'upcoming' => 'Jadwal turnamen belum dibuka. Katalog tantangan dapat ditinjau sebelum sesi dimulai.',
                        default    => 'Akses target sedang dikunci.',
                    } }}
                </div>
            </div>
        </div>

        <div>
            @if($isAdmin)
            <a href="{{ route('admin.index') }}" class="btn-subtle" style="font-size: 0.8rem; padding: 0.45rem 0.95rem; border-radius: 5px; white-space: nowrap;">
                Buka Panel Admin &rarr;
            </a>
            @else
            <span style="font-size: 0.775rem; font-family: 'JetBrains Mono', monospace; color: #f59e0b; font-weight: 600;">
                MODE TINJAUAN
            </span>
            @endif
        </div>
    </div>
    @endif

    <!-- CyLab Expandable Progress Tracker Accordion (Exact Benchmark) -->
    <div class="cylab-progress-card" style="background: #11141c; border: 1px solid #1c2230; border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; transition: border-color 0.15s ease;">
        
        <!-- Clickable Header Strip -->
        <div id="progressTrackerHeader" style="padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <span style="font-size: 0.925rem; font-weight: 700; color: #f8fafc;">Progress Tracker</span>
            </div>
            
            <svg id="progressTrackerChevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.25s ease; transform: rotate(0deg);">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <!-- Collapsible Content Drawer (Collapsed by Default) -->
        <div id="progressTrackerDrawer" style="max-height: 0px; transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1); overflow: hidden;">
            <div style="padding: 0 1.25rem 1.25rem 1.25rem; border-top: 1px solid rgba(255, 255, 255, 0.04);">
                
                <!-- Overall & Difficulty Progress Bars -->
                <div style="display: flex; flex-direction: column; gap: 1rem; padding-top: 1rem;">
                    
                    <!-- Overall -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; margin-bottom: 0.45rem;">
                            <span style="color: #cbd5e1; font-weight: 600;">Overall</span>
                            <span style="color: #cbd5e1; font-family: 'JetBrains Mono', monospace; font-weight: 600;">{{ $totalSolved }}/{{ $totalChallenges }}</span>
                        </div>
                        <div style="height: 4px; background: #1c2230; border-radius: 9999px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $overallPercent }}%; background: var(--accent-orange); border-radius: 9999px; transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                    <!-- Easy -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; margin-bottom: 0.45rem;">
                            <span style="color: #94a3b8; font-weight: 500;">Easy</span>
                            <span style="color: #94a3b8; font-family: 'JetBrains Mono', monospace;">{{ $easySolved }}/{{ $easyTotal }}</span>
                        </div>
                        <div style="height: 4px; background: #1c2230; border-radius: 9999px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $easyPercent }}%; background: #22c55e; border-radius: 9999px; transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                    <!-- Medium -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; margin-bottom: 0.45rem;">
                            <span style="color: #94a3b8; font-weight: 500;">Medium</span>
                            <span style="color: #94a3b8; font-family: 'JetBrains Mono', monospace;">{{ $mediumSolved }}/{{ $mediumTotal }}</span>
                        </div>
                        <div style="height: 4px; background: #1c2230; border-radius: 9999px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $mediumPercent }}%; background: #f59e0b; border-radius: 9999px; transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                    <!-- Hard -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.825rem; margin-bottom: 0.45rem;">
                            <span style="color: #94a3b8; font-weight: 500;">Hard</span>
                            <span style="color: #94a3b8; font-family: 'JetBrains Mono', monospace;">{{ $hardSolved }}/{{ $hardTotal }}</span>
                        </div>
                        <div style="height: 4px; background: #1c2230; border-radius: 9999px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $hardPercent }}%; background: #ef4444; border-radius: 9999px; transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                </div>

                <!-- By Category Section -->
                <div style="margin-top: 1.75rem;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.75rem;">
                        By category
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr)); gap: 0.75rem;">
                        @foreach($categoryStats as $cKey => $cMeta)
                        @php
                            $catChs = $challenges->filter(fn($c) => strtolower($c->category) === $cKey);
                            $cTotal = $catChs->count();
                            $cSolved = $catChs->filter(fn($c) => in_array($c->id, $userSolves))->count();
                            $cPercent = $cTotal > 0 ? round(($cSolved / $cTotal) * 100) : 0;
                        @endphp
                        <div style="background: #0d1017; border: 1px solid #1c2230; border-radius: 6px; padding: 0.9rem 1.1rem; display: flex; flex-direction: column; justify-content: space-between; min-height: 74px;">
                            <div>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #f8fafc; margin-bottom: 0.25rem;">
                                    {{ $cMeta['name'] }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; font-family: 'JetBrains Mono', monospace;">
                                    {{ $cSolved }}/{{ $cTotal }}
                                </div>
                            </div>
                            <div style="height: 3px; background: #1c2230; border-radius: 9999px; overflow: hidden; margin-top: 0.75rem;">
                                <div style="height: 100%; width: {{ $cPercent }}%; background: var(--accent-orange); border-radius: 9999px; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- CyLab Unified Filter Toolbar (Directly on Page Canvas, Pure Pill Geometry, Zero AI-Slop Boxes) -->
    <div class="cylab-filter-toolbar">
        
        <!-- Top Controls Row: Fluid Pill Search & Pill Dropdowns -->
        <div class="filter-inputs-grid">
            
            <!-- Fluid Pill Search Field -->
            <div class="filter-field filter-field-search">
                <label for="challengeSearchInput" class="filter-label">Search</label>
                <div class="cylab-search-pill">
                    <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="challengeSearchInput" placeholder="Search challenges..." autocomplete="off">
                    <span class="search-kbd-hint">/</span>
                </div>
            </div>

            <!-- Pill Category Dropdown Field -->
            <div class="filter-field filter-field-dropdown" id="categoryDropdown">
                <label class="filter-label">Category</label>
                <button type="button" class="cylab-pill-btn" id="categoryTriggerBtn" aria-haspopup="listbox" aria-expanded="false">
                    <span id="categoryDisplayValue" class="pill-value-text">
                        {{ $selectedCategory === 'all' ? 'All categories' : ($categoryStats[$selectedCategory]['display'] ?? ucfirst($selectedCategory)) }}
                    </span>
                    <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="cylab-popover-menu" id="categoryMenu" role="listbox">
                    <div class="popover-item {{ $selectedCategory === 'all' ? 'active' : '' }}" data-value="all" role="option">
                        <span>All categories</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item {{ $selectedCategory === 'web' ? 'active' : '' }}" data-value="web" role="option">
                        <span>Eksploitasi Web</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item {{ $selectedCategory === 'crypto' ? 'active' : '' }}" data-value="crypto" role="option">
                        <span>Kriptografi</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item {{ $selectedCategory === 'recon' ? 'active' : '' }}" data-value="recon" role="option">
                        <span>Pengintaian</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item {{ $selectedCategory === 'general' ? 'active' : '' }}" data-value="general" role="option">
                        <span>Keahlian Umum</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </div>
                <input type="hidden" id="challengeCategoryFilter" value="{{ $selectedCategory }}">
            </div>

            <!-- Pill Difficulty Dropdown Field -->
            <div class="filter-field filter-field-dropdown" id="difficultyDropdown">
                <label class="filter-label">Difficulty</label>
                <button type="button" class="cylab-pill-btn" id="difficultyTriggerBtn" aria-haspopup="listbox" aria-expanded="false">
                    <span id="difficultyDisplayValue" class="pill-value-text">All levels</span>
                    <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="cylab-popover-menu" id="difficultyMenu" role="listbox">
                    <div class="popover-item active" data-value="all" role="option">
                        <span>All levels</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item" data-value="easy" role="option">
                        <span>Easy</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item" data-value="medium" role="option">
                        <span>Medium</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div class="popover-item" data-value="hard" role="option">
                        <span>Hard</span>
                        <svg class="item-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </div>
                <input type="hidden" id="challengeDifficultyFilter" value="all">
            </div>

        </div>

        <!-- Bottom Row: CyLab Modern Pill Switches & Clear Filters (Directly on Canvas) -->
        <div class="filter-switches-row">
            <div class="switches-group">
                <!-- Hide Solved (Active Interactive Filter) -->
                <label class="cylab-switch-label">
                    <input type="checkbox" id="hideSolvedToggle" style="display: none;">
                    <span class="cylab-switch-track">
                        <span class="cylab-switch-thumb"></span>
                    </span>
                    <span class="switch-title">Hide Solved</span>
                </label>

                <!-- Bookmarked Toggle (Visual CyLab Benchmark) -->
                <label class="cylab-switch-label">
                    <input type="checkbox" id="bookmarkedToggle" style="display: none;">
                    <span class="cylab-switch-track">
                        <span class="cylab-switch-thumb"></span>
                    </span>
                    <span class="switch-title" style="color: #64748b;">Bookmarked</span>
                </label>

                <!-- Assigned Toggle -->
                <label class="cylab-switch-label">
                    <input type="checkbox" id="assignedToggle" style="display: none;">
                    <span class="cylab-switch-track">
                        <span class="cylab-switch-thumb"></span>
                    </span>
                    <span class="switch-title" style="color: #64748b;">Assigned</span>
                </label>

                <!-- Retired Toggle -->
                <label class="cylab-switch-label">
                    <input type="checkbox" id="retiredToggle" style="display: none;">
                    <span class="cylab-switch-track">
                        <span class="cylab-switch-thumb"></span>
                    </span>
                    <span class="switch-title" style="color: #64748b;">Retired</span>
                </label>
            </div>

            <!-- Clear Filters Button -->
            <button type="button" id="clearFiltersBtn" class="clear-filters-btn">
                Reset filter
            </button>
        </div>

    </div>

    <!-- Sequential Category Sections (Top to Bottom, CyLab Card Layout) -->
    <div id="challengeSectionsContainer" style="display: flex; flex-direction: column; gap: 2.25rem;">
        @php $renderedAny = false; @endphp

        @foreach($categoryOrder as $catKey)
            @php
                $catChallenges = $groupedChallenges->get($catKey, collect());
                if ($catChallenges->isEmpty()) continue;
                $renderedAny = true;
                $meta = $categoryStats[$catKey] ?? [
                    'display' => ucfirst($catKey),
                    'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"></rect></svg>'
                ];
                $solvedInCat = $catChallenges->filter(fn($c) => in_array($c->id, $userSolves))->count();
            @endphp

            <!-- Category Section -->
            <div class="category-block" data-category="{{ $catKey }}" style="display: flex; flex-direction: column; gap: 1rem;">
                
                <!-- Section Header Strip -->
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #1c2230; padding-bottom: 0.65rem;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <span style="color: var(--accent-orange); display: inline-flex; align-items: center;">
                            {!! $meta['icon'] !!}
                        </span>
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.01em;">
                            {{ $meta['display'] }}
                        </h2>
                    </div>
                    <div style="font-size: 0.8rem; font-family: 'JetBrains Mono', monospace; color: #64748b;">
                        {{ $solvedInCat }} / {{ $catChallenges->count() }} selesai
                    </div>
                </div>

                <!-- 3-Column Responsive Cards Grid -->
                <div class="category-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr)); gap: 1rem;">
                    @foreach($catChallenges as $ch)
                    @php
                        $isSolved = in_array($ch->id, $userSolves);
                        $diff = strtolower($ch->difficulty);

                        // Exact CyLab Signal Meter Bars Calculation
                        if ($diff === 'medium') {
                            $bar1 = '#f59e0b';
                            $bar2 = '#f59e0b';
                            $bar3 = '#f59e0b';
                            $bar4 = 'rgba(255, 255, 255, 0.15)';
                            $diffColor = '#f59e0b';
                            $diffLabel = 'Medium';
                        } elseif ($diff === 'hard') {
                            $bar1 = '#ef4444';
                            $bar2 = '#ef4444';
                            $bar3 = '#ef4444';
                            $bar4 = '#ef4444';
                            $diffColor = '#ef4444';
                            $diffLabel = 'Hard';
                        } else {
                            $bar1 = '#22c55e';
                            $bar2 = '#22c55e';
                            $bar3 = 'rgba(255, 255, 255, 0.15)';
                            $bar4 = 'rgba(255, 255, 255, 0.15)';
                            $diffColor = '#22c55e';
                            $diffLabel = 'Easy';
                        }
                    @endphp

                    <!-- CyLab Challenge Library Card -->
                    <a href="{{ route('challenges.show', $ch->slug) }}" 
                       class="cylab-card-item {{ $isSolved ? 'is-solved' : '' }}" 
                       data-title="{{ strtolower($ch->title) }}" 
                       data-category="{{ $ch->category }}" 
                       data-difficulty="{{ $diff }}" 
                       data-solved="{{ $isSolved ? 'true' : 'false' }}">

                        <!-- Top Row: Title on Left, Category Plain Text on Right -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem;">
                                <h3 class="cylab-card-title">
                                    {{ $ch->title }}
                                </h3>
                                <span class="cylab-card-cat-label">
                                    {{ $meta['display'] }}
                                </span>
                            </div>
                            <div class="cylab-card-author">
                                by ITEC
                            </div>
                        </div>

                        <!-- Middle Row: CyLab Difficulty Signal Meter -->
                        <div class="cylab-signal-container">
                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none">
                                <rect x="1" y="10" width="3.2" height="6" rx="0.8" fill="{{ $bar1 }}" />
                                <rect x="5.5" y="7" width="3.2" height="9" rx="0.8" fill="{{ $bar2 }}" />
                                <rect x="10" y="4" width="3.2" height="12" rx="0.8" fill="{{ $bar3 }}" />
                                <rect x="14.5" y="1" width="3.2" height="15" rx="0.8" fill="{{ $bar4 }}" />
                            </svg>
                            <span class="cylab-signal-text" style="color: {{ $diffColor }};">
                                {{ $diffLabel }}
                            </span>
                        </div>

                        <!-- Bottom Row: Points on Left, Solve Count / Selesai / Terkunci on Right -->
                        <div class="cylab-card-footer">
                            <div class="cylab-card-pts" style="{{ $isSolved ? 'color: #22c55e;' : '' }}">
                                @if($isSolved)
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                                @endif
                                <span>{{ $ch->points }} PTS</span>
                            </div>

                            <div>
                                @if($isSolved)
                                <div class="cylab-card-solves-count">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                    </svg>
                                    <span>{{ $ch->solves_count ?? 0 }} solves</span>
                                </div>
                                @elseif($isLocked)
                                <div class="cylab-card-solves-count">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <span>Terkunci</span>
                                </div>
                                @else
                                <div class="cylab-card-solves-count">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                    </svg>
                                    <span>{{ $ch->solves_count ?? 0 }} solves</span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </a>
                    @endforeach
                </div>

            </div>
        @endforeach

        <!-- Empty State if no challenges available -->
        <div id="noChallengesFoundNotice" style="display: {{ $renderedAny ? 'none' : 'block' }}; padding: 3.5rem 2rem; text-align: center; color: var(--text-muted); background: #11141c; border: 1px solid #1c2230; border-radius: 8px;">
            Tidak ada tantangan yang cocok dengan kriteria pencarian Anda.
        </div>
    </div>

</div>

<!-- Styles for CyLab Elements & Custom Dropdowns -->
<style>
/* CyLab Filter Toolbar (Non-Boxy, Exact CyLab Benchmark) */
.cylab-filter-toolbar {
    margin-bottom: 2.25rem;
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}

.filter-inputs-grid {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.filter-field-search {
    flex: 1;
    min-width: 260px;
}

.filter-field-dropdown {
    min-width: 170px;
    position: relative;
    user-select: none;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #94a3b8;
    letter-spacing: 0.01em;
    user-select: none;
}

/* Pill Search Input */
.cylab-search-pill {
    position: relative;
    height: 38px;
    background: #12151e;
    border: 1px solid #1e2538;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    transition: border-color 0.15s ease, background 0.15s ease;
    box-sizing: border-box;
}

.cylab-search-pill:focus-within {
    border-color: #3b4764;
    background: #151926;
}

.cylab-search-pill .search-icon {
    position: absolute;
    left: 1rem;
    pointer-events: none;
    color: #64748b;
    flex-shrink: 0;
}

.cylab-search-pill input {
    width: 100%;
    height: 100%;
    background: transparent;
    border: none;
    outline: none;
    padding: 0 2.4rem 0 2.5rem;
    color: #f8fafc;
    font-size: 0.85rem;
    font-family: inherit;
    box-sizing: border-box;
}

.cylab-search-pill input::placeholder {
    color: #64748b;
}

.cylab-search-pill .search-kbd-hint {
    position: absolute;
    right: 0.85rem;
    font-size: 0.65rem;
    color: #64748b;
    background: #181d2a;
    border: 1px solid #232b3d;
    padding: 0.1rem 0.35rem;
    border-radius: 4px;
    font-family: 'JetBrains Mono', monospace;
    pointer-events: none;
}

/* Pill Dropdown Button */
.cylab-pill-btn {
    height: 38px;
    width: 100%;
    background: #12151e;
    border: 1px solid #1e2538;
    border-radius: 9999px;
    padding: 0 1.1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    color: #f8fafc;
    font-size: 0.84rem;
    font-weight: 500;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
    user-select: none;
    box-sizing: border-box;
    font-family: inherit;
}

.cylab-pill-btn:hover,
.filter-field-dropdown.open .cylab-pill-btn {
    border-color: #384258;
    background: #151926;
}

.cylab-pill-btn .pill-value-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #f8fafc;
    font-weight: 500;
}

.cylab-pill-btn .dropdown-chevron {
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    color: #64748b;
    flex-shrink: 0;
}

.filter-field-dropdown.open .cylab-pill-btn .dropdown-chevron {
    transform: rotate(180deg);
}

/* Floating Popover */
.cylab-popover-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    min-width: 195px;
    background: #0e111a;
    border: 1px solid #1e2538;
    border-radius: 8px;
    padding: 0.35rem;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.75);
    z-index: 70;
    display: none;
    flex-direction: column;
    gap: 2px;
}

.filter-field-dropdown.open .cylab-popover-menu {
    display: flex;
}

.popover-item {
    padding: 0.5rem 0.75rem;
    border-radius: 5px;
    font-size: 0.825rem;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background 0.12s ease, color 0.12s ease;
    user-select: none;
}

.popover-item:hover {
    background: #161c2b;
    color: #f8fafc;
}

.popover-item.active {
    color: var(--accent-orange);
    font-weight: 600;
    background: rgba(249, 115, 22, 0.08);
}

.popover-item .item-check {
    opacity: 0;
    color: var(--accent-orange);
    flex-shrink: 0;
}

.popover-item.active .item-check {
    opacity: 1;
}

/* Switches Row (Directly on Canvas) */
.filter-switches-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.switches-group {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

/* Modern CyLab Pill Toggle Switch */
.cylab-switch-label {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    user-select: none;
}

.cylab-switch-track {
    width: 32px;
    height: 18px;
    background: #1e2538;
    border: 1px solid #28334a;
    border-radius: 9999px;
    position: relative;
    display: inline-block;
    transition: background 0.2s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.cylab-switch-thumb {
    width: 12px;
    height: 12px;
    background: #94a3b8;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), background 0.2s ease;
}

.cylab-switch-label input:checked + .cylab-switch-track {
    background: var(--accent-orange);
    border-color: var(--accent-orange);
}

.cylab-switch-label input:checked + .cylab-switch-track .cylab-switch-thumb {
    background: #ffffff;
    transform: translateX(14px);
}

.switch-title {
    font-size: 0.8rem;
    color: #94a3b8;
    font-weight: 500;
}

.clear-filters-btn {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    background: transparent;
    border: none;
    padding: 0.25rem 0.5rem;
    cursor: pointer;
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.15s ease;
}

.clear-filters-btn:hover {
    color: #cbd5e1;
}

/* Cards */
.cylab-card-item {
    background: #11141c;
    border: 1px solid #1c2230;
    border-radius: 8px;
    padding: 1.15rem 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 148px;
    text-decoration: none;
    color: inherit;
    transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease, opacity 0.2s ease, box-shadow 0.15s ease;
}

.cylab-card-item:hover {
    border-color: #334155;
    background: #131722;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}

/* Solved Challenge Cards: Dimmed opacity (Exact CyLab Benchmark) */
.cylab-card-item.is-solved {
    opacity: 0.52;
    border-color: #171d2b;
}

.cylab-card-item.is-solved:hover {
    opacity: 0.92;
    border-color: #334155;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}

.cylab-card-title {
    font-size: 1.025rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0;
    line-height: 1.35;
    letter-spacing: -0.01em;
}

.cylab-card-cat-label {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 500;
    white-space: nowrap;
    text-align: right;
    flex-shrink: 0;
}

.cylab-card-author {
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.35rem;
}

.cylab-signal-container {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    margin: 1.15rem 0 1.25rem 0;
}

.cylab-signal-text {
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1;
}

.cylab-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.65rem;
    border-top: 1px solid rgba(255, 255, 255, 0.04);
}

.cylab-card-pts {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.775rem;
    color: #94a3b8;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
}

.cylab-card-solved-status {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.775rem;
    color: #34d399;
    font-weight: 600;
}

.cylab-card-solves-count {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.775rem;
    color: #64748b;
}
</style>

<!-- Interactive Accordion, Custom Dropdowns & Live Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Progress Tracker Accordion Interaction
    const trackerHeader = document.getElementById('progressTrackerHeader');
    const trackerDrawer = document.getElementById('progressTrackerDrawer');
    const trackerChevron = document.getElementById('progressTrackerChevron');

    if (trackerHeader && trackerDrawer && trackerChevron) {
        let isExpanded = false;
        trackerHeader.addEventListener('click', function () {
            isExpanded = !isExpanded;
            if (isExpanded) {
                trackerDrawer.style.maxHeight = '1200px';
                trackerChevron.style.transform = 'rotate(180deg)';
            } else {
                trackerDrawer.style.maxHeight = '0px';
                trackerChevron.style.transform = 'rotate(0deg)';
            }
        });
    }

    // 2. Custom Popover Dropdown Handlers
    function setupCustomDropdown(dropdownId, triggerId, menuId, hiddenInputId, displayId) {
        const dropdown = document.getElementById(dropdownId);
        const trigger = document.getElementById(triggerId);
        const menu = document.getElementById(menuId);
        const hiddenInput = hiddenInputId ? document.getElementById(hiddenInputId) : null;
        const display = document.getElementById(displayId);

        if (!dropdown || !trigger || !menu || !display) return;

        // Toggle open
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains('open');
            // Close all dropdowns first
            document.querySelectorAll('.filter-field-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('open');
                    const btn = d.querySelector('.cylab-pill-btn');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });

            if (!isOpen) {
                dropdown.classList.add('open');
                trigger.setAttribute('aria-expanded', 'true');
            } else {
                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Option click
        const options = menu.querySelectorAll('.popover-item');
        options.forEach(opt => {
            opt.addEventListener('click', function (e) {
                e.stopPropagation();
                const val = this.getAttribute('data-value');
                const textSpan = this.querySelector('span');
                const labelText = textSpan ? textSpan.textContent.trim() : this.textContent.trim();

                if (hiddenInput) hiddenInput.value = val;
                display.textContent = labelText;

                options.forEach(o => o.classList.remove('active'));
                this.classList.add('active');

                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');

                applyFilters();
            });
        });
    }

    setupCustomDropdown('categoryDropdown', 'categoryTriggerBtn', 'categoryMenu', 'challengeCategoryFilter', 'categoryDisplayValue');
    setupCustomDropdown('difficultyDropdown', 'difficultyTriggerBtn', 'difficultyMenu', 'challengeDifficultyFilter', 'difficultyDisplayValue');

    // Close dropdowns on outside click or Escape
    document.addEventListener('click', function () {
        document.querySelectorAll('.filter-field-dropdown').forEach(d => {
            d.classList.remove('open');
            const btn = d.querySelector('.cylab-pill-btn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.filter-field-dropdown').forEach(d => {
                d.classList.remove('open');
                const btn = d.querySelector('.cylab-pill-btn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }
        // Focus search on '/' keypress
        if (e.key === '/' && document.activeElement !== searchInput) {
            e.preventDefault();
            if (searchInput) searchInput.focus();
        }
    });

    // 3. Filter & Live Search Interaction
    const searchInput = document.getElementById('challengeSearchInput');
    const categoryHidden = document.getElementById('challengeCategoryFilter');
    const difficultyHidden = document.getElementById('challengeDifficultyFilter');
    const hideSolvedToggle = document.getElementById('hideSolvedToggle');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const categoryBlocks = document.querySelectorAll('.category-block');
    const emptyNotice = document.getElementById('noChallengesFoundNotice');

    function applyFilters() {
        const query = (searchInput.value || '').trim().toLowerCase();
        const selectedCat = categoryHidden.value || 'all';
        const selectedDiff = difficultyHidden.value || 'all';
        const hideSolved = hideSolvedToggle.checked;

        let totalVisibleCards = 0;

        categoryBlocks.forEach(block => {
            const blockCategory = block.getAttribute('data-category');
            const blockCards = block.querySelectorAll('.cylab-card-item');
            let visibleInBlock = 0;

            if (selectedCat !== 'all' && selectedCat !== blockCategory) {
                block.style.display = 'none';
                return;
            }

            blockCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const cat = card.getAttribute('data-category') || '';
                const diff = card.getAttribute('data-difficulty') || '';
                const isSolved = card.getAttribute('data-solved') === 'true';

                let matchesSearch = !query || title.includes(query);
                let matchesCategory = (selectedCat === 'all' || selectedCat === cat);
                let matchesDiff = (selectedDiff === 'all' || selectedDiff === diff);
                let matchesSolved = !hideSolved || !isSolved;

                if (matchesSearch && matchesCategory && matchesDiff && matchesSolved) {
                    card.style.display = 'flex';
                    visibleInBlock++;
                    totalVisibleCards++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleInBlock > 0) {
                block.style.display = 'flex';
            } else {
                block.style.display = 'none';
            }
        });

        if (emptyNotice) {
            emptyNotice.style.display = totalVisibleCards === 0 ? 'block' : 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (hideSolvedToggle) hideSolvedToggle.addEventListener('change', applyFilters);

    // 4. Clear Filters Handler
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (hideSolvedToggle) hideSolvedToggle.checked = false;

            // Reset Category
            if (categoryHidden) categoryHidden.value = 'all';
            const catDisplay = document.getElementById('categoryDisplayValue');
            if (catDisplay) catDisplay.textContent = 'All categories';
            document.querySelectorAll('#categoryMenu .popover-item').forEach(opt => {
                opt.classList.toggle('active', opt.getAttribute('data-value') === 'all');
            });

            // Reset Difficulty
            if (difficultyHidden) difficultyHidden.value = 'all';
            const diffDisplay = document.getElementById('difficultyDisplayValue');
            if (diffDisplay) diffDisplay.textContent = 'All levels';
            document.querySelectorAll('#difficultyMenu .popover-item').forEach(opt => {
                opt.classList.toggle('active', opt.getAttribute('data-value') === 'all');
            });

            applyFilters();
        });
    }
});
</script>
@endsection
