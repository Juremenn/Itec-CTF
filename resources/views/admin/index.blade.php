@extends('layouts.app')

@section('title', 'Pusat Kontrol Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Admin Stealth Header -->
    <div style="background: linear-gradient(135deg, #13151c 0%, #1f1816 100%); border: 1px solid rgba(249, 115, 22, 0.3); border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; font-weight: 700; background: rgba(249, 115, 22, 0.2); color: var(--accent-orange); padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid rgba(249, 115, 22, 0.4); text-transform: uppercase;">
                    Gerbang Operasi Khusus
                </span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">/itec/admin</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.4rem 0; letter-spacing: -0.02em;">
                Pusat Kontrol Instruktur CTF
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0; line-height: 1.5;">
                Kelola status gerbang turnamen, ganti mode operasional, publikasikan pengumuman global, dan pantau hasil pengerjaan peserta.
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('admin.challenges.create') }}" class="btn-orange">
                + Tambah Tantangan
            </a>
            <a href="{{ route('admin.challenges.index') }}" class="btn-subtle">
                Kelola Tantangan
            </a>
        </div>
    </div>

    <!-- Quick State Control Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        
        <!-- Operating Mode Toggle Card -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Mode Operasional Platform
                </div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $activeMode === 'competition' ? '#f97316' : '#3b82f6' }};"></span>
                    {{ $activeMode === 'competition' ? 'Mode Kompetisi Aktif' : 'Mode Latihan Aktif' }}
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.4;">
                    Pilih antara aturan turnamen terikat waktu (kompetisi) atau eksplorasi terbuka berpemandu (latihan).
                </p>
            </div>

            <form method="POST" action="{{ route('admin.toggle_mode') }}">
                @csrf
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" name="active_mode" value="training" class="btn-subtle" style="flex: 1; justify-content: center; {{ $activeMode === 'training' ? 'background: rgba(59, 130, 246, 0.2); color: #60a5fa; border-color: rgba(59, 130, 246, 0.4);' : '' }}">
                        Latihan
                    </button>
                    <button type="submit" name="active_mode" value="competition" class="btn-subtle" style="flex: 1; justify-content: center; {{ $activeMode === 'competition' ? 'background: rgba(249, 115, 22, 0.2); color: #f97316; border-color: rgba(249, 115, 22, 0.4);' : '' }}">
                        Kompetisi
                    </button>
                </div>
            </form>
        </div>

        <!-- Competition Status Controller -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Status Gerbang Kompetisi
                </div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $competitionStatus === 'active' ? '#22c55e' : '#ef4444' }};"></span>
                    Status: {{ match($competitionStatus) {
                        'active' => 'AKTIF',
                        'paused' => 'DIJEDA',
                        'upcoming' => 'AKAN DATANG',
                        'ended' => 'SELESAI',
                        default => strtoupper($competitionStatus)
                    } }}
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.4;">
                    Saat turnamen dijeda atau berakhir, pengiriman flag peserta akan dikunci di seluruh sistem.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.toggle_mode') }}">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <button type="submit" name="competition_status" value="active" class="btn-subtle" style="{{ $competitionStatus === 'active' ? 'background: rgba(34, 197, 94, 0.2); color: #4ade80; border-color: rgba(34, 197, 94, 0.4);' : '' }}; justify-content: center;">
                        ▶ Aktifkan
                    </button>
                    <button type="submit" name="competition_status" value="paused" class="btn-subtle" style="{{ $competitionStatus === 'paused' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border-color: rgba(239, 68, 68, 0.4);' : '' }}; justify-content: center;">
                        ⏸ Jeda
                    </button>
                    <button type="submit" name="competition_status" value="upcoming" class="btn-subtle" style="{{ $competitionStatus === 'upcoming' ? 'background: rgba(234, 179, 8, 0.2); color: #facc15; border-color: rgba(234, 179, 8, 0.4);' : '' }}; justify-content: center;">
                        ⏳ Akan Datang
                    </button>
                    <button type="submit" name="competition_status" value="ended" class="btn-subtle" style="{{ $competitionStatus === 'ended' ? 'background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.4);' : '' }}; justify-content: center;">
                        ⏹ Selesai
                    </button>
                </div>
            </form>
        </div>

        <!-- Global Broadcast Controller -->
        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                    Banner Pengumuman Global
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.75rem; line-height: 1.4;">
                    Pesan pengumuman akan langsung tampil di bilah paling atas semua layar peserta.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.broadcast') }}" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                <input 
                    type="text" 
                    name="broadcast_message" 
                    value="{{ $broadcast }}" 
                    placeholder="Contoh: Kompetisi berakhir dalam 15 menit! Segera kirimkan flag Anda."
                    style="background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.55rem 0.75rem; color: var(--text-primary); font-size: 0.85rem; outline: none;"
                >
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn-orange" style="flex: 1; justify-content: center; font-size: 0.8rem; padding: 0.5rem;">
                        Publikasikan Pengumuman
                    </button>
                    <button type="button" onclick="document.querySelector('input[name=broadcast_message]').value=''; document.forms[2].submit();" class="btn-subtle" style="font-size: 0.8rem; padding: 0.5rem;">
                        Hapus
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
                Reset Darurat Papan Skor
            </div>
            <p style="font-size: 0.825rem; color: var(--text-secondary); margin: 0; max-width: 650px; line-height: 1.4;">
                Menghapus seluruh rekaman solve peserta dari database untuk memulai babak turnamen baru. Akun peserta dan data tantangan tetap aman.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.reset_scores') }}" style="display: flex; gap: 0.5rem; align-items: center;" onsubmit="return confirm('PERINGATAN: Tindakan ini akan menghapus seluruh rekaman poin dan papan skor secara permanen! Lanjutkan?');">
            @csrf
            <input 
                type="text" 
                name="confirm_reset" 
                placeholder="Ketik RESET" 
                required 
                style="background: var(--bg-base); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 6px; padding: 0.5rem 0.75rem; color: #f87171; font-size: 0.85rem; width: 120px; outline: none;"
            >
            <button type="submit" style="background: #ef4444; color: #fff; font-weight: 600; font-size: 0.85rem; padding: 0.55rem 1rem; border-radius: 6px; border: none; cursor: pointer;">
                Reset Skor
            </button>
        </form>
    </div>

    <!-- Participant Monitoring Table -->
    <div>
        <h2 style="font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">
            Daftar Peserta Terdaftar ({{ $totalParticipants }})
        </h2>

        <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 1rem 1.25rem;">Peserta</th>
                        <th style="padding: 1rem 1.25rem;">Email</th>
                        <th style="padding: 1rem 1.25rem; text-align: center;">Solve Kompetisi</th>
                        <th style="padding: 1rem 1.25rem; text-align: center;">Total Solve</th>
                        <th style="padding: 1rem 1.25rem; text-align: right;">Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                    <tr style="border-bottom: 1px solid var(--border-hairline);">
                        <td style="padding: 0.85rem 1.25rem;">
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $p->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ '@' . $p->username }}</div>
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: var(--text-secondary); font-size: 0.8rem;">
                            {{ $p->email }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: center; font-weight: 700; color: #4ade80;">
                            {{ $p->competition_solves }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: center; font-weight: 700; color: var(--text-secondary);">
                            {{ $p->total_solves }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; text-align: right; font-weight: 800; color: #fbbf24; font-size: 1rem;">
                            {{ number_format($p->total_points ?? 0) }} POIN
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--text-muted);">
                            Belum ada peserta yang mendaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
