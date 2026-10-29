@extends('layouts.app')

@section('title', 'Papan Skor')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Scoreboard Header & Tabs -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.35rem 0; letter-spacing: -0.02em;">
                Papan Skor
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                Klasemen peserta yang diperbarui secara langsung. Akun instruktur dan administrator tidak disertakan.
            </p>
        </div>

        <!-- Mode Tabs -->
        <div class="segmented-filter-track">
            <a href="{{ route('scoreboard', ['tab' => 'competition']) }}" class="segmented-filter-btn {{ $tab === 'competition' ? 'active mode-competition' : '' }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                Papan Kompetisi
            </a>
            <a href="{{ route('scoreboard', ['tab' => 'practice']) }}" class="segmented-filter-btn {{ $tab === 'practice' ? 'active mode-practice' : '' }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                Papan Latihan
            </a>
        </div>
    </div>

    @php
        $currentList = ($tab === 'competition') ? $competitionUsers : $practiceUsers;
        $hasRealData = $chartData['hasRealData'] ?? false;
    @endphp

    <!-- Score Progression Timeline Chart Card -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
        
        <div>
            <h2 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.25rem 0;">
                Grafik Perkembangan Skor
            </h2>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
                Pergerakan akumulasi poin peserta berdasarkan waktu submit flag.
            </p>
        </div>

        <!-- Chart Container -->
        <div style="position: relative; width: 100%; min-height: 280px;">
            <canvas id="scoreboardChart" style="width: 100%; height: 280px; {{ $hasRealData ? '' : 'display: none;' }}"></canvas>
            
            <div id="chartEmptyPlaceholder" style="{{ $hasRealData ? 'display: none;' : 'display: flex;' }} min-height: 240px; align-items: center; justify-content: center; text-align: center; padding: 2rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                    Grafik perkembangan skor akan muncul secara otomatis saat peserta mulai mengirimkan flag.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.725rem; color: var(--text-muted); border-top: 1px solid var(--border-hairline); padding-top: 0.75rem;">
            <span>Sumbu X: Waktu &bull; Sumbu Y: Total Poin</span>
            <span>Real-time</span>
        </div>
    </div>

    <!-- Active Tab Description -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.75rem 1.25rem; font-size: 0.825rem; color: var(--text-secondary); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            @if($tab === 'competition')
                <span>Peringkat Resmi Turnamen (flag pada mode kompetisi).</span>
            @else
                <span>Peringkat Akumulatif (seluruh modul yang diselesaikan).</span>
            @endif
        </div>
        <div id="registeredCountNotice" style="font-size: 0.75rem; color: var(--text-muted);">
            {{ count($currentList) }} peserta
        </div>
    </div>

    <!-- Leaderboard Table (Responsive Touch Scroll on Mobile) -->
    <div class="table-responsive" style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; min-width: 580px; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.725rem; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">
                    <th style="padding: 0.85rem 1.25rem; width: 100px; text-align: center;">Peringkat</th>
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
                        1 => '#fbbf24',
                        2 => '#cbd5e1',
                        3 => '#d97706',
                        default => 'var(--text-muted)',
                    };
                    $isCurrentUser = Auth::check() && Auth::id() === $u->id;
                @endphp
                <tr style="border-bottom: 1px solid var(--border-hairline); {{ $isCurrentUser ? 'background: rgba(249, 115, 22, 0.04);' : '' }}">
                    
                    <!-- Clean Symmetrical Centered Rank -->
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; font-weight: 700; font-size: 1rem; color: {{ $rankColor }};">
                        #{{ $rank }}
                    </td>

                    <!-- Cadet Info -->
                    <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">
                            {{ $u->name }}
                            @if($isCurrentUser)
                                <span style="font-size: 0.65rem; font-weight: 600; background: var(--surface-secondary); color: var(--accent-orange); padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.4rem; border: 1px solid var(--border-hairline);">Anda</span>
                            @endif
                        </div>
                        <div style="font-size: 0.725rem; color: var(--text-muted); margin-top: 0.15rem;">
                            {{ '@' . $u->username }}
                        </div>
                    </td>

                    <!-- Solved Count -->
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; color: var(--text-secondary); font-weight: 500;">
                        {{ $u->solved_count ?? 0 }}
                    </td>

                    <!-- Points -->
                    <td style="padding: 1rem 1.25rem; text-align: right; vertical-align: middle;">
                        <span style="font-size: 1rem; font-weight: 700; color: #fbbf24;">
                            {{ number_format($u->total_points ?? 0) }}
                        </span>
                        <span style="font-size: 0.7rem; color: var(--text-muted); margin-left: 0.2rem;">POIN</span>
                    </td>

                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" style="padding: 3.5rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                        Belum ada data skor pada papan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- Chart.js Library -->
<script src="{{ asset('assets/js/chart.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeTab = '{{ $tab }}';
    const initialChartData = @json($chartData['datasets'] ?? []);
    const canvas = document.getElementById('scoreboardChart');
    const placeholder = document.getElementById('chartEmptyPlaceholder');
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
                interaction: {
                    mode: 'nearest',
                    intersect: false,
                    axis: 'x'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#94a3b8',
                            font: { family: 'Poppins', size: 11 },
                            boxWidth: 10,
                            padding: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 17, 24, 0.95)',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        borderColor: '#2a3142',
                        borderWidth: 1,
                        padding: 8,
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                const d = new Date(items[0].raw.x);
                                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            },
                            label: function(item) {
                                return ` ${item.dataset.label}: ${item.raw.y} Poin`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: {
                            color: '#64748b',
                            font: { family: 'Poppins', size: 10 },
                            callback: function(val) {
                                const d = new Date(val);
                                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: {
                            color: '#64748b',
                            font: { family: 'Poppins', size: 10 },
                            callback: function(val) { return val + ' pts'; }
                        }
                    }
                }
            }
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
                        chartInstance.update();

                        if (hasData) {
                            if (canvas) canvas.style.display = 'block';
                            if (placeholder) placeholder.style.display = 'none';
                        } else {
                            if (canvas) canvas.style.display = 'none';
                            if (placeholder) placeholder.style.display = 'flex';
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
        if (countNotice) countNotice.textContent = `${users.length} peserta`;

        if (!users || users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="padding: 3.5rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                        Belum ada data skor pada papan ini.
                    </td>
                </tr>`;
            return;
        }

        let html = '';
        users.forEach((u, idx) => {
            const rank = idx + 1;
            let rankColor = 'var(--text-muted)';
            if (rank === 1) rankColor = '#fbbf24';
            else if (rank === 2) rankColor = '#cbd5e1';
            else if (rank === 3) rankColor = '#d97706';

            html += `
                <tr style="border-bottom: 1px solid var(--border-hairline);">
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; font-weight: 700; font-size: 1rem; color: ${rankColor};">
                        #${rank}
                    </td>
                    <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">${u.name}</div>
                        <div style="font-size: 0.725rem; color: var(--text-muted); margin-top: 0.15rem;">@${u.username}</div>
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center; vertical-align: middle; color: var(--text-secondary); font-weight: 500;">
                        ${u.solved_count}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: right; vertical-align: middle;">
                        <span style="font-size: 1rem; font-weight: 700; color: #fbbf24;">${u.total_points.toLocaleString()}</span>
                        <span style="font-size: 0.7rem; color: var(--text-muted); margin-left: 0.2rem;">POIN</span>
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
