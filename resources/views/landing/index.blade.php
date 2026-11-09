@extends('layouts.app')

@section('title', 'Get Started &bull; Cybersecurity Academy')

@push('styles')
<style>
    /* Hero & Constellation Container */
    .landing-wrapper {
        display: flex;
        flex-direction: column;
        gap: 3.5rem;
        padding-bottom: 2rem;
    }

    /* Top Announcement Banner (CyLab Benchmark) */
    .cylab-welcome-banner {
        background: #003e75;
        border: none;
        border-radius: 14px;
        padding: 1.65rem 2rem;
        position: relative;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
        margin: 0 auto;
        max-width: 960px;
        width: 100%;
        box-sizing: border-box;
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .cylab-banner-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: transparent;
        border: none;
        color: #93c5fd;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0.75;
        transition: opacity 0.15s ease, background-color 0.15s ease;
    }
    .cylab-banner-close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .cylab-banner-eyebrow {
        font-size: 0.725rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #60a5fa;
        margin-bottom: 0.5rem;
    }

    .cylab-banner-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.025em;
        line-height: 1.25;
    }

    .cylab-banner-subtitle {
        font-size: 0.925rem;
        color: #bfdbfe;
        margin: 0;
        line-height: 1.5;
    }

    /* Constellation Hero Canvas Container */
    .constellation-hero {
        position: relative;
        min-height: 680px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 3rem 1.5rem 4rem 1.5rem;
        overflow: hidden;
        background: transparent;
        border: none;
        box-shadow: none;
    }

    /* Interactive HTML5 Canvas for Constellation Animation */
    .constellation-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    /* Constellation Interactive Node Anchors (Synchronized with Undulating Canvas) */
    .constellation-node {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 3;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        text-decoration: none;
        will-change: transform;
        pointer-events: auto;
    }

    .node-label {
        font-family: 'JetBrains Mono', ui-monospace, monospace;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #7b8fa3;
        background: transparent;
        border: none;
        padding: 0;
        white-space: nowrap;
        transition: color 0.15s ease, text-shadow 0.15s ease;
        user-select: none;
    }
    .constellation-node:hover .node-label {
        color: #ffffff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
    }

    /* Central Start Here Beacon */
    .central-beacon {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 4;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        pointer-events: none;
        will-change: transform;
    }

    .beacon-text {
        font-family: 'JetBrains Mono', ui-monospace, monospace;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #fb923c;
        white-space: nowrap;
    }

    /* Centered Hero Content Box */
    .hero-content-box {
        position: relative;
        z-index: 4;
        max-width: 680px;
        margin: 2.5rem auto 0 auto;
    }

    .hero-headline {
        font-size: 3.25rem;
        font-weight: 800;
        line-height: 1.12;
        letter-spacing: -0.035em;
        color: #ffffff;
        margin: 0 0 1.25rem 0;
        text-shadow: 0 2px 25px rgba(9, 10, 15, 0.95);
    }

    .hero-subtext {
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--text-secondary);
        margin: 0 0 2rem 0;
        font-weight: 400;
    }

    .hero-actions-cluster {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-pill-orange {
        background-color: var(--accent-orange);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.75rem 1.75rem;
        border-radius: 9999px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.15s ease;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
    }
    .btn-pill-orange:hover {
        background-color: var(--accent-orange-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(249, 115, 22, 0.45);
    }

    .btn-pill-slate {
        background-color: var(--surface-secondary);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.75rem 1.6rem;
        border-radius: 9999px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid var(--border-input);
        transition: all 0.15s ease;
    }
    .btn-pill-slate:hover {
        background-color: var(--surface-elevated);
        border-color: var(--text-secondary);
    }

    body {
        background-color: #0b0c10 !important;
    }

    /* Live Telemetry Evidence Bar (Craftsmanship C-5) */
    .telemetry-deck {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    .telemetry-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 1.35rem 1.6rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .telemetry-number {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #ffffff;
        line-height: 1;
    }

    .telemetry-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #94a3b8;
    }

    /* Category Explorer Pathways */
    .pathways-section {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        color: #ffffff;
        margin: 0;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .category-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 1.5rem 1.65rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1.25rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.15s ease;
    }
    .category-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.14);
        transform: translateY(-2px);
    }

    .category-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        letter-spacing: -0.015em;
    }



    /* Responsive Reflow Breakpoints (R-03) */
    @media (max-width: 1024px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .telemetry-deck {
            grid-template-columns: repeat(2, 1fr);
        }
        .constellation-hero {
            min-height: 560px;
        }
    }

    @media (max-width: 768px) {
        .constellation-hero {
            min-height: 500px;
            padding: 2.5rem 1rem;
        }
        .hero-headline {
            font-size: 2.35rem;
        }
        .hero-subtext {
            font-size: 0.95rem;
        }
        .cylab-banner-title {
            font-size: 1.35rem;
        }
        .category-grid {
            grid-template-columns: 1fr;
        }
        .telemetry-deck {
            grid-template-columns: repeat(2, 1fr);
        }
        .constellation-node .node-label {
            font-size: 0.6rem;
            padding: 0.15rem 0.35rem;
        }
    }

    @media (max-width: 480px) {
        .constellation-hero {
            min-height: 480px;
            padding: 1.5rem 0.85rem 2rem 0.85rem;
        }
        .hero-headline {
            font-size: 1.85rem;
        }
        .telemetry-deck {
            grid-template-columns: 1fr;
        }
        .hero-actions-cluster {
            flex-direction: column;
            width: 100%;
        }
        .btn-pill-orange, .btn-pill-slate {
            width: 100%;
            justify-content: center;
            box-sizing: border-box;
        }
        .constellation-node, .central-beacon {
            display: none;
        }
        .hero-content-box {
            margin-top: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="landing-wrapper">

    <!-- CyLab Standard Welcome Announcement Banner (Dismissible) -->
    <section class="cylab-welcome-banner" id="welcomeAnnouncementBanner">
        <button type="button" class="cylab-banner-close" onclick="dismissWelcomeBanner()" aria-label="Tutup pesan selamat datang">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="cylab-banner-eyebrow">SELAMAT DATANG</div>
        <h2 class="cylab-banner-title">ITEC CyberLab Security Academy</h2>
        <p class="cylab-banner-subtitle">
            Wajah baru, materi terstruktur, dan komunitas pembelajar. Selesaikan tantangan keamanan siber berbasis praktik nyata.
        </p>
    </section>

    <!-- Constellation Hero Canvas with Animated Light Pulses (CyLab Get-Started Benchmark) -->
    <section class="constellation-hero" id="constellationHero" aria-label="Gerbang Pembelajaran Keamanan Siber">
        
        <!-- Interactive HTML5 Canvas for Animated Undulating Wireframe & Traveling Photons -->
        <canvas id="constellationCanvas" class="constellation-canvas" aria-hidden="true"></canvas>

        <!-- Constellation Domain Nodes (Clickable Anchors Synchronized with Undulating Canvas) -->
        <a href="#pathways" class="constellation-node" id="node-0" title="Jelajahi General Skills">
            <span class="node-label">GENERAL SKILLS</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-1" title="Jelajahi Kriptografi">
            <span class="node-label">CRYPTOGRAPHY</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-2" title="Jelajahi Web Exploitation">
            <span class="node-label">WEB EXPLOITATION</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-3" title="Jelajahi Forensics">
            <span class="node-label">FORENSICS</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-4" title="Jelajahi Binary Exploitation">
            <span class="node-label">BINARY EXPLOITATION</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-5" title="Jelajahi Reverse Engineering">
            <span class="node-label">REVERSE ENGINEERING</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-6" title="Jelajahi Reconnaissance">
            <span class="node-label">RECONNAISSANCE</span>
        </a>
        <a href="#pathways" class="constellation-node" id="node-7" title="Jelajahi AI & Security Defense">
            <span class="node-label">AI &amp; DEFENSE</span>
        </a>

        <!-- Central Glowing Beacon Marker -->
        <div class="central-beacon" id="centralBeacon">
            <span class="beacon-text">START HERE</span>
        </div>

        <!-- Hero Headline & Action Cluster -->
        <div class="hero-content-box">
            <h1 class="hero-headline">
                Cybersecurity,<br>
                learned by doing
            </h1>
            <p class="hero-subtext">
                No experience needed. Learn at your own pace, with your class, or in a live event. Always free.
            </p>
            <div class="hero-actions-cluster">
                <a href="{{ route('register') }}" class="btn-pill-orange">
                    Mulai Belajar Sekarang
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="btn-pill-slate">
                    Masuk ke Platform
                </a>
                <a href="{{ route('scoreboard') }}" class="btn-subtle" style="font-size: 0.9rem; color: var(--text-muted); text-decoration: none;">
                    Papan Skor Live
                </a>
            </div>
        </div>
    </section>

    <!-- Platform Telemetry Deck (Evidence Over Claims - C-5) -->
    <section aria-label="Statistik Platform Nyata">
        <div class="telemetry-deck">
            <div class="telemetry-card">
                <span class="telemetry-number">{{ $totalChallenges }}</span>
                <span class="telemetry-label">Tantangan Aktif Terverifikasi</span>
            </div>
            <div class="telemetry-card">
                <span class="telemetry-number">{{ count($categories) }}</span>
                <span class="telemetry-label">Domain Kurikulum Praktik</span>
            </div>
            <div class="telemetry-card">
                <span class="telemetry-number">{{ $totalSolves }}</span>
                <span class="telemetry-label">Solves Berhasil Diselesaikan</span>
            </div>
            <div class="telemetry-card">
                <span class="telemetry-number">{{ $totalUsers }}</span>
                <span class="telemetry-label">Kadet Terdaftar Aktif</span>
            </div>
        </div>
    </section>

    <!-- Pathways & Module Exploration -->
    <section class="pathways-section" id="pathways">
        <h2 class="section-title">Kurikulum Laboratorium</h2>

        <div class="category-grid">
            @foreach($categories as $key => $cat)
            <a href="{{ route('login') }}" class="category-card">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="category-name">{{ $cat['name'] }}</h3>
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent-orange);">{{ $cat['points'] }} Poin</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted);">
                    <span>{{ $cat['count'] }} Tantangan</span>
                    <span style="color: var(--text-secondary); font-weight: 500;">Buka Modul &rarr;</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>





</div>

@push('scripts')
<script>
    // Dismissible Welcome Banner with LocalStorage Persistence
    function dismissWelcomeBanner() {
        var banner = document.getElementById('welcomeAnnouncementBanner');
        if (banner) {
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(-8px)';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 250);
            try {
                localStorage.setItem('hide_cylab_welcome_banner', '1');
            } catch(e) {}
        }
    }

    // Check if user previously dismissed banner
    (function() {
        try {
            if (localStorage.getItem('hide_cylab_welcome_banner') === '1') {
                var banner = document.getElementById('welcomeAnnouncementBanner');
                if (banner) {
                    banner.style.display = 'none';
                }
            }
        } catch(e) {}
    })();

    // Interactive Constellation Canvas with Undulating Net & Traveling Photons (CyLab Signature Move)
    (function() {
        var canvas = document.getElementById('constellationCanvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        if (!ctx) return;

        var hero = document.getElementById('constellationHero');
        var width = 0;
        var height = 0;
        var dpr = window.devicePixelRatio || 1;

        // Node definitions with base normalized coordinates [0..1]
        // Framing layout matching CyLab benchmark with comfortable edge margins
        var nodes = [
            // 0: GENERAL SKILLS (Top Center)
            { id: 0, baseX: 0.50, baseY: 0.08, labelAlign: 'center', labelDx: 0, labelDy: -16,
              ampX: 14, ampY: 10, freqX: 0.0009, freqY: 0.0012, phaseX: 0.2, phaseY: 1.1 },
            // 1: CRYPTOGRAPHY (Top Right)
            { id: 1, baseX: 0.73, baseY: 0.20, labelAlign: 'left', labelDx: 14, labelDy: -4,
              ampX: 16, ampY: 14, freqX: 0.0011, freqY: 0.0008, phaseX: 1.5, phaseY: 0.7 },
            // 2: WEB EXPLOITATION (Mid Right)
            { id: 2, baseX: 0.85, baseY: 0.50, labelAlign: 'left', labelDx: 14, labelDy: 0,
              ampX: 12, ampY: 18, freqX: 0.0008, freqY: 0.0010, phaseX: 2.3, phaseY: 2.8 },
            // 3: FORENSICS (Bottom Right)
            { id: 3, baseX: 0.74, baseY: 0.82, labelAlign: 'left', labelDx: 14, labelDy: 8,
              ampX: 15, ampY: 12, freqX: 0.0012, freqY: 0.0009, phaseX: 3.1, phaseY: 1.9 },
            // 4: BINARY EXPLOITATION (Bottom Center)
            { id: 4, baseX: 0.50, baseY: 0.93, labelAlign: 'center', labelDx: 0, labelDy: 16,
              ampX: 14, ampY: 10, freqX: 0.0007, freqY: 0.0011, phaseX: 0.8, phaseY: 3.4 },
            // 5: REVERSE ENGINEERING (Bottom Left)
            { id: 5, baseX: 0.26, baseY: 0.82, labelAlign: 'right', labelDx: -14, labelDy: 8,
              ampX: 16, ampY: 13, freqX: 0.0010, freqY: 0.0013, phaseX: 4.2, phaseY: 0.4 },
            // 6: RECONNAISSANCE (Mid Left)
            { id: 6, baseX: 0.15, baseY: 0.50, labelAlign: 'right', labelDx: -14, labelDy: 0,
              ampX: 13, ampY: 17, freqX: 0.0009, freqY: 0.0007, phaseX: 5.1, phaseY: 2.2 },
            // 7: AI & DEFENSE (Top Left)
            { id: 7, baseX: 0.27, baseY: 0.20, labelAlign: 'right', labelDx: -14, labelDy: -4,
              ampX: 15, ampY: 12, freqX: 0.0013, freqY: 0.0010, phaseX: 1.0, phaseY: 4.5 },
            // 8: START HERE (Center Beacon)
            { id: 8, baseX: 0.50, baseY: 0.27, labelAlign: 'center', labelDx: 0, labelDy: 14,
              ampX: 10, ampY: 8, freqX: 0.0008, freqY: 0.0009, phaseX: 2.0, phaseY: 1.5, isBeacon: true }
        ];

        // Edges connecting the constellation network cleanly without cluttered criss-crossing
        var edges = [
            // Perimeter ring
            { from: 0, to: 1, type: 'outer' },
            { from: 1, to: 2, type: 'outer' },
            { from: 2, to: 3, type: 'outer' },
            { from: 3, to: 4, type: 'outer' },
            { from: 4, to: 5, type: 'outer' },
            { from: 5, to: 6, type: 'outer' },
            { from: 6, to: 7, type: 'outer' },
            { from: 7, to: 0, type: 'outer' },

            // Clean rays from START HERE (8) to upper constellation
            { from: 8, to: 0, type: 'beacon' },
            { from: 8, to: 1, type: 'beacon' },
            { from: 8, to: 7, type: 'beacon' },
            { from: 8, to: 2, type: 'beacon' },
            { from: 8, to: 6, type: 'beacon' }
        ];

        // Travelling Photons / Energy pulses along the wires
        var photons = [
            {
                fromNode: 8,
                toNode: 1,
                progress: 0.15,
                speed: 0.0048,
                color: '#f97316',
                size: 3.5,
                glowColor: 'rgba(249, 115, 22, 0.95)',
                history: []
            },
            {
                fromNode: 1,
                toNode: 2,
                progress: 0.65,
                speed: 0.0040,
                color: '#38bdf8',
                size: 3.2,
                glowColor: 'rgba(56, 189, 248, 0.9)',
                history: []
            },
            {
                fromNode: 6,
                toNode: 7,
                progress: 0.40,
                speed: 0.0044,
                color: '#f59e0b',
                size: 3.2,
                glowColor: 'rgba(245, 158, 11, 0.9)',
                history: []
            },
            {
                fromNode: 3,
                toNode: 4,
                progress: 0.85,
                speed: 0.0036,
                color: '#60a5fa',
                size: 3.0,
                glowColor: 'rgba(96, 165, 250, 0.85)',
                history: []
            },
            {
                fromNode: 8,
                toNode: 6,
                progress: 0.20,
                speed: 0.0042,
                color: '#fb923c',
                size: 3.2,
                glowColor: 'rgba(251, 146, 60, 0.9)',
                history: []
            }
        ];

        // Subtle ambient micro-stars in deep space
        var ambientStars = [];
        for (var s = 0; s < 50; s++) {
            ambientStars.push({
                x: Math.random(),
                y: Math.random(),
                radius: Math.random() * 1.2 + 0.4,
                alpha: Math.random() * 0.45 + 0.1,
                twinkleSpeed: Math.random() * 0.02 + 0.008,
                twinklePhase: Math.random() * Math.PI * 2
            });
        }

        // Expanding ripples on node arrival or hover
        var ripples = [];
        var hoveredNodeIndex = -1;

        // Mouse interaction for organic touch
        var mouseX = -9999;
        var mouseY = -9999;
        hero.addEventListener('mousemove', function(e) {
            var rect = hero.getBoundingClientRect();
            mouseX = e.clientX - rect.left;
            mouseY = e.clientY - rect.top;
        });
        hero.addEventListener('mouseleave', function() {
            mouseX = -9999;
            mouseY = -9999;
        });

        function resize() {
            if (!hero) return;
            width = hero.clientWidth;
            height = hero.clientHeight;
            canvas.width = Math.round(width * dpr);
            canvas.height = Math.round(height * dpr);
            canvas.style.width = width + 'px';
            canvas.style.height = height + 'px';
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(dpr, dpr);
        }

        window.addEventListener('resize', resize);
        resize();

        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function pickNextNode(currentNode, prevNode) {
            var neighbors = [];
            for (var i = 0; i < edges.length; i++) {
                var e = edges[i];
                if (e.from === currentNode && e.to !== prevNode) {
                    neighbors.push(e.to);
                } else if (e.to === currentNode && e.from !== prevNode) {
                    neighbors.push(e.from);
                }
            }
            if (neighbors.length === 0) {
                for (var j = 0; j < edges.length; j++) {
                    var ed = edges[j];
                    if (ed.from === currentNode) neighbors.push(ed.to);
                    if (ed.to === currentNode) neighbors.push(ed.from);
                }
            }
            return neighbors[Math.floor(Math.random() * neighbors.length)];
        }

        var isVisible = true;
        document.addEventListener('visibilitychange', function() {
            isVisible = !document.hidden;
            if (isVisible) requestAnimationFrame(draw);
        });

        function draw(timestamp) {
            if (!isVisible) return;
            var t = (timestamp || performance.now());

            ctx.clearRect(0, 0, width, height);

            // 1. Draw ambient space stars
            for (var i = 0; i < ambientStars.length; i++) {
                var star = ambientStars[i];
                star.twinklePhase += star.twinkleSpeed;
                var currentAlpha = star.alpha + Math.sin(star.twinklePhase) * 0.15;
                if (currentAlpha < 0) currentAlpha = 0;
                ctx.fillStyle = 'rgba(255, 255, 255, ' + currentAlpha + ')';
                ctx.beginPath();
                ctx.arc(star.x * width, star.y * height, star.radius, 0, Math.PI * 2);
                ctx.fill();
            }

            // 2. Compute dynamic undulating coordinates for each node ("terombang-ambing")
            for (var nIdx = 0; nIdx < nodes.length; nIdx++) {
                var n = nodes[nIdx];
                if (prefersReducedMotion) {
                    n.x = n.baseX * width;
                    n.y = n.baseY * height;
                } else {
                    // Fluid harmonic dual-sine waves
                    var waveX = Math.sin(t * n.freqX + n.phaseX) * n.ampX + Math.sin(t * (n.freqX * 0.45) + n.phaseY) * (n.ampX * 0.35);
                    var waveY = Math.cos(t * n.freqY + n.phaseY) * n.ampY + Math.sin(t * (n.freqY * 0.55) + n.phaseX) * (n.ampY * 0.35);

                    var curX = n.baseX * width + waveX;
                    var curY = n.baseY * height + waveY;

                    // Subtle mouse repulsion for organic responsiveness
                    if (mouseX > 0 && mouseY > 0) {
                        var dx = curX - mouseX;
                        var dy = curY - mouseY;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 130 && dist > 0) {
                            var force = (1 - dist / 130) * 14;
                            curX += (dx / dist) * force;
                            curY += (dy / dist) * force;
                        }
                    }

                    n.x = curX;
                    n.y = curY;
                }

                // Synchronize HTML label position via GPU translate3d
                var el = document.getElementById(n.isBeacon ? 'centralBeacon' : ('node-' + n.id));
                if (el) {
                    var lx = Math.round(n.x + n.labelDx);
                    var ly = Math.round(n.y + n.labelDy);
                    var elW = el.offsetWidth;
                    if (n.labelAlign === 'center') {
                        lx -= elW / 2;
                    } else if (n.labelAlign === 'right') {
                        lx -= elW;
                    }
                    // Keep edge labels inside the hero on narrow screens.
                    lx = Math.round(Math.max(8, Math.min(lx, width - elW - 8)));
                    el.style.transform = 'translate3d(' + lx + 'px, ' + ly + 'px, 0)';
                }
            }

            // 3. Draw clearly visible, undulating constellation wireframe lines
            for (var e = 0; e < edges.length; e++) {
                var edge = edges[e];
                var p1 = nodes[edge.from];
                var p2 = nodes[edge.to];

                ctx.beginPath();
                ctx.moveTo(p1.x, p1.y);
                ctx.lineTo(p2.x, p2.y);

                if (edge.type === 'beacon') {
                    ctx.lineWidth = 1.0;
                    ctx.strokeStyle = 'rgba(249, 115, 22, 0.28)'; // Soft warm amber spoke
                } else {
                    ctx.lineWidth = 1.0;
                    ctx.strokeStyle = 'rgba(148, 163, 184, 0.20)'; // Soft celestial slate perimeter
                }
                ctx.stroke();
            }

            // 4. Draw expanding ripples
            for (var r = ripples.length - 1; r >= 0; r--) {
                var rip = ripples[r];
                rip.radius += 0.6;
                rip.alpha -= 0.018;
                if (rip.alpha <= 0) {
                    ripples.splice(r, 1);
                    continue;
                }
                ctx.strokeStyle = rip.color.replace(')', ', ' + rip.alpha + ')').replace('rgb', 'rgba');
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.arc(rip.x, rip.y, rip.radius, 0, Math.PI * 2);
                ctx.stroke();
            }

            // 5. Draw delicate, non-slop vertex points on canvas (100% aligned with lines)
            for (var i = 0; i < nodes.length; i++) {
                var node = nodes[i];
                var isHov = (hoveredNodeIndex === i);
                var isBeacon = !!node.isBeacon;

                ctx.beginPath();
                var rad = isBeacon ? 3.5 : (isHov ? 3.5 : 2.5);
                ctx.arc(node.x, node.y, rad, 0, Math.PI * 2);

                if (isBeacon) {
                    ctx.fillStyle = '#f97316';
                    ctx.shadowColor = 'rgba(249, 115, 22, 0.9)';
                    ctx.shadowBlur = 10;
                } else if (isHov) {
                    ctx.fillStyle = '#ffffff';
                    ctx.shadowColor = 'rgba(255, 255, 255, 0.95)';
                    ctx.shadowBlur = 12;
                } else {
                    ctx.fillStyle = '#93c5fd';
                    ctx.shadowColor = 'rgba(147, 197, 253, 0.65)';
                    ctx.shadowBlur = 6;
                }
                ctx.fill();
                ctx.shadowBlur = 0;
            }

            // 6. Update and draw travelling photons along the swaying edges
            if (!prefersReducedMotion) {
                for (var p = 0; p < photons.length; p++) {
                    var photon = photons[p];
                    photon.progress += photon.speed;

                    var startP = nodes[photon.fromNode];
                    var endP = nodes[photon.toNode];

                    var currentX = startP.x + (endP.x - startP.x) * photon.progress;
                    var currentY = startP.y + (endP.y - startP.y) * photon.progress;

                    // Keep a short motion trail
                    photon.history.push({ x: currentX, y: currentY });
                    if (photon.history.length > 8) {
                        photon.history.shift();
                    }

                    // Draw motion blur trail
                    for (var tr = 0; tr < photon.history.length; tr++) {
                        var trailPt = photon.history[tr];
                        var trailAlpha = (tr / photon.history.length) * 0.45;
                        var trailSize = photon.size * (0.35 + (tr / photon.history.length) * 0.65);
                        ctx.fillStyle = photon.glowColor.replace(/[\d\.]+\)$/, trailAlpha + ')');
                        ctx.beginPath();
                        ctx.arc(trailPt.x, trailPt.y, trailSize, 0, Math.PI * 2);
                        ctx.fill();
                    }

                    // Glowing photon core
                    var grad = ctx.createRadialGradient(currentX, currentY, 0, currentX, currentY, photon.size * 3.5);
                    grad.addColorStop(0, '#ffffff');
                    grad.addColorStop(0.35, photon.color);
                    grad.addColorStop(1, 'rgba(0, 0, 0, 0)');

                    ctx.fillStyle = grad;
                    ctx.beginPath();
                    ctx.arc(currentX, currentY, photon.size * 3.5, 0, Math.PI * 2);
                    ctx.fill();

                    // Solid bright center
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.arc(currentX, currentY, photon.size * 0.7, 0, Math.PI * 2);
                    ctx.fill();

                    // Check if photon reached target node
                    if (photon.progress >= 1.0) {
                        ripples.push({
                            x: endP.x,
                            y: endP.y,
                            radius: 3,
                            alpha: 0.7,
                            color: photon.color === '#f97316' ? 'rgb(249, 115, 22)' : 'rgb(56, 189, 248)'
                        });

                        var oldTarget = photon.toNode;
                        var newTarget = pickNextNode(oldTarget, photon.fromNode);
                        photon.fromNode = oldTarget;
                        photon.toNode = newTarget;
                        photon.progress = 0;
                        photon.history = [];
                    }
                }
            }

            requestAnimationFrame(draw);
        }

        requestAnimationFrame(draw);

        // Click / hover reaction on HTML node anchors
        for (var n = 0; n < 8; n++) {
            (function(index) {
                var nodeEl = document.getElementById('node-' + index);
                if (nodeEl) {
                    nodeEl.addEventListener('mouseenter', function() {
                        hoveredNodeIndex = index;
                        var p = nodes[index];
                        ripples.push({
                            x: p.x,
                            y: p.y,
                            radius: 5,
                            alpha: 0.8,
                            color: 'rgb(249, 115, 22)'
                        });
                    });
                    nodeEl.addEventListener('mouseleave', function() {
                        hoveredNodeIndex = -1;
                    });
                }
            })(n);
        }
    })();
</script>
@endpush
@endsection
