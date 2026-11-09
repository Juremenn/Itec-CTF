@extends('layouts.app')

@section('title', 'Papan Skor')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Scoreboard Header & Mode Switcher -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.25rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.02em;">
                Papan Skor
            </h1>
        </div>

        <!-- Mode Segmented Switcher -->
        <div class="segmented-filter-track">
            <a href="{{ route('scoreboard', ['tab' => 'competition']) }}" class="segmented-filter-btn {{ $tab === 'competition' ? 'active mode-competition' : '' }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                Mode Turnamen
            </a>
            <a href="{{ route('scoreboard', ['tab' => 'practice']) }}" class="segmented-filter-btn {{ $tab === 'practice' ? 'active mode-practice' : '' }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                Mode Latihan
            </a>
        </div>
    </div>

    @php
        $currentList = ($tab === 'competition') ? $competitionUsers : $practiceUsers;
        $hasRealData = $chartData['hasRealData'] ?? false;
        $datasets = $chartData['datasets'] ?? [];
    @endphp

    <!-- Professional CTF Score Timeline Card (Smooth Spline Interpolation & Ring Node Milestones) -->
    <div style="background: #11141c; border: 1px solid #1c2230; border-radius: 12px; padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 1.25rem;">
        
        <!-- Timeline Header Strip -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <h2 style="font-size: 1.05rem; font-weight: 700; color: #f8fafc; margin: 0; letter-spacing: -0.01em;">
                    {{ $tab === 'competition' ? 'Timeline Poin Turnamen' : 'Timeline Poin Latihan' }}
                </h2>
                <span style="font-size: 0.725rem; font-family: 'JetBrains Mono', monospace; color: #34d399; font-weight: 600; letter-spacing: 0.04em;">
                    LIVE SYNC
                </span>
            </div>

            <!-- View Segmented Controller (Top 3 / Top 5 / Semua) -->
            <div style="display: flex; align-items: center; gap: 0.25rem; background: #090b10; border: 1px solid #1e2538; border-radius: 6px; padding: 3px;">
                <button type="button" class="ctf-view-btn" data-view="3" style="background: transparent; border: none; color: #94a3b8; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; cursor: pointer; transition: all 0.15s ease;">
                    Top 3
                </button>
                <button type="button" class="ctf-view-btn active" data-view="5" style="background: #1e2538; border: none; color: #f8fafc; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; cursor: pointer; transition: all 0.15s ease;">
                    Top 5
                </button>
                <button type="button" class="ctf-view-btn" data-view="all" style="background: transparent; border: none; color: #94a3b8; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; cursor: pointer; transition: all 0.15s ease;">
                    Semua
                </button>
            </div>
        </div>

        <!-- Elegant Minimalist Cadets Legend Strip (No bulky AI-slop buttons) -->
        <div id="chartLegendChips" style="display: flex; align-items: center; gap: 0.75rem 1.5rem; flex-wrap: wrap; {{ $hasRealData ? '' : 'display: none;' }}; padding-bottom: 0.15rem;">
            @foreach($datasets as $idx => $ds)
            @php $isInitialVisible = $idx < 5; @endphp
            <div class="ctf-cadet-legend-item {{ $isInitialVisible ? '' : 'dimmed' }}" 
                 data-dataset-index="{{ $idx }}" 
                 style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; transition: all 0.15s ease;">
                <span style="width: 14px; height: 3px; border-radius: 2px; background: {{ $ds['borderColor'] }}; flex-shrink: 0;"></span>
                <span style="font-size: 0.8rem; font-weight: 600; color: #f8fafc;">#{{ $ds['rank'] ?? ($idx + 1) }} {{ $ds['label'] }}</span>
                <span style="font-family: 'JetBrains Mono', monospace; color: #64748b; font-size: 0.725rem;">{{ $ds['totalPoints'] ?? 0 }} PTS</span>
            </div>
            @endforeach
        </div>

        <!-- Chart Canvas Container -->
        <div style="position: relative; width: 100%; min-height: 340px; background: #0c0e14; border: 1px solid #1a202c; border-radius: 8px; padding: 1.15rem 1rem;">
            <canvas id="scoreboardChart" style="width: 100%; height: 340px; {{ $hasRealData ? '' : 'display: none;' }}"></canvas>
            
            <div id="chartEmptyPlaceholder" style="{{ $hasRealData ? 'display: none;' : 'display: flex;' }} min-height: 310px; align-items: center; justify-content: center; text-align: center; padding: 2rem;">
                <div style="max-width: 380px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.75" style="margin-bottom: 0.65rem;">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 500; margin: 0;">
                        Belum Ada Data Solves
                    </p>
                </div>
            </div>
        </div>

        <!-- Quiet Operational Note -->
        <div style="display: flex; justify-content: flex-end; align-items: center; font-size: 0.75rem; color: #64748b; border-top: 1px solid rgba(255, 255, 255, 0.04); padding-top: 0.75rem;">
            <span style="font-family: 'JetBrains Mono', monospace;">Auto-refresh 25s</span>
        </div>
    </div>

    <!-- Active Tab Strip -->
    <div style="background: #11141c; border: 1px solid #1c2230; border-radius: 6px; padding: 0.85rem 1.25rem; font-size: 0.825rem; color: #94a3b8; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
        <div style="font-weight: 500; color: #cbd5e1;">
            @if($tab === 'competition')
                <span>Klasemen Resmi Turnamen</span>
            @else
                <span>Klasemen Akumulatif Modul Latihan</span>
            @endif
        </div>
        <div id="registeredCountNotice" style="font-size: 0.775rem; font-family: 'JetBrains Mono', monospace; color: #64748b;">
            {{ count($currentList) }} peserta terdaftar
        </div>
    </div>

    <!-- Leaderboard Table (High-Density SecOps Style) -->
    <div class="table-responsive" style="background: #11141c; border: 1px solid #1c2230; border-radius: 8px; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; min-width: 600px; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #0d1017; border-bottom: 1px solid #1c2230; color: #64748b; font-size: 0.725rem; font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">
                    <th style="padding: 0.85rem 1.25rem; width: 90px; text-align: center;">Rank</th>
                    <th style="padding: 0.85rem 1.25rem;">Peserta</th>
                    <th style="padding: 0.85rem 1.25rem; width: 140px; text-align: center;">Lab Selesai</th>
                    <th style="padding: 0.85rem 1.25rem; width: 160px; text-align: right;">Total Poin</th>
                </tr>
            </thead>
            <tbody id="leaderboardTbody">
                @forelse($currentList as $index => $u)
                @php
                    $rank = $index + 1;
                    $rankColor = match($rank) {
                        1 => '#f59e0b',
                        2 => '#cbd5e1',
                        3 => '#d97706',
                        default => '#64748b',
                    };
                    $isCurrentUser = Auth::check() && Auth::id() === $u->id;
                @endphp
                <tr class="leaderboard-row" style="border-bottom: 1px solid #1c2230; {{ $isCurrentUser ? 'background: rgba(249, 115, 22, 0.04);' : '' }}">
                    
                    <!-- Clean Symmetrical Rank -->
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; font-weight: 700; font-family: 'JetBrains Mono', monospace; font-size: 0.95rem; color: {{ $rankColor }};">
                        #{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}
                    </td>

                    <!-- Cadet Info -->
                    <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                        <div style="display: flex; align-items: center; gap: 0.45rem;">
                            <span style="font-weight: 600; color: #f8fafc; font-size: 0.9rem;">
                                {{ $u->name }}
                            </span>
                            @if($isCurrentUser)
                                <span style="font-size: 0.65rem; font-weight: 600; background: #1c2336; color: var(--accent-orange); padding: 0.1rem 0.45rem; border-radius: 4px; border: 1px solid #2d374d;">Anda</span>
                            @endif
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem; font-family: 'JetBrains Mono', monospace;">
                            {{ '@' . $u->username }}
                        </div>
                    </td>

                    <!-- Solved Count -->
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; color: #cbd5e1; font-weight: 600; font-family: 'JetBrains Mono', monospace;">
                        {{ $u->solved_count ?? 0 }}
                    </td>

                    <!-- Points -->
                    <td style="padding: 1rem 1.25rem; text-align: right; vertical-align: middle;">
                        <span style="font-size: 1.05rem; font-weight: 700; color: #fbbf24; font-family: 'JetBrains Mono', monospace;">
                            {{ number_format($u->total_points ?? 0) }}
                        </span>
                        <span style="font-size: 0.725rem; color: #64748b; font-weight: 600; margin-left: 0.2rem;">PTS</span>
                    </td>

                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" style="padding: 3.5rem 1.5rem; text-align: center; color: #64748b; font-size: 0.85rem;">
                        <div class="scoreboard-empty-msg">Belum ada data skor pada papan ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- Styles for Scoreboard -->
<style>
.leaderboard-row {
    transition: background 0.15s ease;
}
.leaderboard-row:hover {
    background: #141824;
}
.ctf-cadet-legend-item {
    cursor: pointer;
    user-select: none;
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.ctf-cadet-legend-item:hover {
    transform: translateY(-1px);
}
.ctf-cadet-legend-item.dimmed {
    opacity: 0.3 !important;
}
.ctf-view-btn.active {
    background: #1e2538 !important;
    color: #f8fafc !important;
}
.ctf-view-btn:hover:not(.active) {
    color: #f8fafc !important;
}
/* The table scrolls sideways on phones; pin the empty message to the visible part. */
.scoreboard-empty-msg {
    position: sticky;
    left: 1.5rem;
    width: calc(100vw - 4.75rem);
    max-width: 100%;
}
</style>

<!-- Local Chart.js Library -->
<script src="{{ asset('assets/js/chart.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeTab = '{{ $tab }}';
    const initialChartData = @json($datasets);
    const timeBounds = @json($chartData['timeBounds'] ?? null);
    const canvas = document.getElementById('scoreboardChart');
    const placeholder = document.getElementById('chartEmptyPlaceholder');
    const chipsContainer = document.getElementById('chartLegendChips');
    let chartInstance = null;

    if (canvas) {
        const ctx = canvas.getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: initialChartData
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        tension: 0.35,
                        stepped: false
                    },
                    point: {
                        radius: 4.5,
                        hoverRadius: 7,
                        borderWidth: 2.5
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: false,
                    axis: 'x'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#11141f',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        borderColor: '#262f47',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 4,
                        usePointStyle: true,
                        titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                        bodyFont: { family: "'JetBrains Mono', monospace", size: 11 },
                        filter: function(item) {
                            if (Array.isArray(item.dataset.pointRadius)) {
                                return (item.dataset.pointRadius[item.dataIndex] || 0) > 0;
                            }
                            return true;
                        },
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                const d = new Date(items[0].raw.x);
                                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' · ' + d.toLocaleDateString([], { month: 'short', day: 'numeric' });
                            },
                            label: function(item) {
                                return ` ${item.dataset.label} (#${item.dataset.rank}): ${item.raw.y} PTS`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        min: timeBounds ? timeBounds.min : undefined,
                        max: timeBounds ? timeBounds.max : undefined,
                        grid: {
                            display: false
                        },
                        border: {
                            color: '#1e2538'
                        },
                        ticks: {
                            color: '#64748b',
                            font: { family: "'JetBrains Mono', monospace", size: 10 },
                            maxTicksLimit: 7,
                            callback: function(val) {
                                const d = new Date(val);
                                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)',
                            borderDash: [3, 3]
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: { family: "'JetBrains Mono', monospace", size: 10 },
                            padding: 10,
                            callback: function(val) { return val + ' PTS'; }
                        }
                    }
                }
            }
        });

        setupChipToggles();
        setupViewFilters();
    }

    function setupChipToggles() {
        const chips = document.querySelectorAll('.ctf-cadet-legend-item');
        chips.forEach(chip => {
            chip.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-dataset-index'), 10);
                if (chartInstance && chartInstance.isDatasetVisible) {
                    const isVisible = chartInstance.isDatasetVisible(idx);
                    chartInstance.setDatasetVisibility(idx, !isVisible);
                    chartInstance.update();

                    if (!isVisible) {
                        this.classList.remove('dimmed');
                    } else {
                        this.classList.add('dimmed');
                    }
                }
            });
        });
    }

    function setupViewFilters() {
        const filterBtns = document.querySelectorAll('.ctf-view-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const view = this.getAttribute('data-view');
                if (!chartInstance) return;

                const limit = (view === '3') ? 3 : ((view === '5') ? 5 : 999);

                chartInstance.data.datasets.forEach((ds, idx) => {
                    const shouldShow = (idx < limit);
                    chartInstance.setDatasetVisibility(idx, shouldShow);
                    
                    const chip = document.querySelector(`.ctf-cadet-legend-item[data-dataset-index="${idx}"]`);
                    if (chip) {
                        if (shouldShow) chip.classList.remove('dimmed');
                        else chip.classList.add('dimmed');
                    }
                });
                chartInstance.update();
            });
        });
    }

    // Silent background real-time updates (every 25 seconds)
    function fetchLiveData() {
        fetch(`{{ route('scoreboard.live_data') }}?tab=${activeTab}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update chart
                    if (chartInstance && data.chart) {
                        const hasData = data.chart.hasRealData;
                        chartInstance.data.datasets = data.chart.datasets || [];
                        if (data.chart.timeBounds) {
                            chartInstance.options.scales.x.min = data.chart.timeBounds.min;
                            chartInstance.options.scales.x.max = data.chart.timeBounds.max;
                        }
                        chartInstance.update();

                        if (hasData) {
                            if (canvas) canvas.style.display = 'block';
                            if (placeholder) placeholder.style.display = 'none';
                            if (chipsContainer) chipsContainer.style.display = 'flex';
                        } else {
                            if (canvas) canvas.style.display = 'none';
                            if (placeholder) placeholder.style.display = 'flex';
                            if (chipsContainer) chipsContainer.style.display = 'none';
                        }
                    }

                    // Update Table
                    renderTable(data.users);
                }
            })
            .catch(() => {});
    }

    function renderTable(users) {
        const tbody = document.getElementById('leaderboardTbody');
        const countNotice = document.getElementById('registeredCountNotice');
        if (countNotice) countNotice.textContent = `${users.length} peserta terdaftar`;

        if (!users || users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="padding: 3.5rem 1.5rem; text-align: center; color: #64748b; font-size: 0.85rem;">
                        <div class="scoreboard-empty-msg">Belum ada data skor pada papan ini.</div>
                    </td>
                </tr>`;
            return;
        }

        let html = '';
        users.forEach((u, idx) => {
            const rank = idx + 1;
            let rankColor = '#64748b';
            if (rank === 1) rankColor = '#f59e0b';
            else if (rank === 2) rankColor = '#cbd5e1';
            else if (rank === 3) rankColor = '#d97706';

            const rankStr = String(rank).padStart(2, '0');

            html += `
                <tr class="leaderboard-row" style="border-bottom: 1px solid #1c2230;">
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; font-weight: 700; font-family: 'JetBrains Mono', monospace; font-size: 0.95rem; color: ${rankColor};">
                        #${rankStr}
                    </td>
                    <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                        <div style="font-weight: 600; color: #f8fafc; font-size: 0.9rem;">${u.name}</div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem; font-family: 'JetBrains Mono', monospace;">@${u.username}</div>
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; color: #cbd5e1; font-weight: 600; font-family: 'JetBrains Mono', monospace;">
                        ${u.solved_count}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: right; vertical-align: middle;">
                        <span style="font-size: 1.05rem; font-weight: 700; color: #fbbf24; font-family: 'JetBrains Mono', monospace;">${u.total_points.toLocaleString()}</span>
                        <span style="font-size: 0.725rem; color: #64748b; font-weight: 600; margin-left: 0.2rem;">PTS</span>
                    </td>
                </tr>`;
        });

        tbody.innerHTML = html;
    }

    // Silent background interval every 25 seconds
    setInterval(fetchLiveData, 25000);
});
</script>
@endsection
