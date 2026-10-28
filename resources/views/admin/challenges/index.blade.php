@extends('layouts.app')

@section('title', 'Kelola Tantangan')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.4rem;">
                <a href="{{ route('admin.index') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Pusat Kontrol Admin</a>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -0.02em;">
                Manajemen Katalog Tantangan
            </h1>
        </div>

        <a href="{{ route('admin.challenges.create') }}" class="btn-orange">
            + Tambah Tantangan Baru
        </a>
    </div>

    <!-- Challenges Table -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 1rem 1.25rem;">Judul & Slug</th>
                    <th style="padding: 1rem 1.25rem;">Kategori</th>
                    <th style="padding: 1rem 1.25rem; text-align: center;">Tingkat</th>
                    <th style="padding: 1rem 1.25rem; text-align: center;">Poin</th>
                    <th style="padding: 1rem 1.25rem; text-align: center;">Target Mode</th>
                    <th style="padding: 1rem 1.25rem; text-align: center;">Status</th>
                    <th style="padding: 1rem 1.25rem; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($challenges as $ch)
                @php
                    $diffLabel = match($ch->difficulty) {
                        'easy' => 'Mudah',
                        'medium' => 'Sedang',
                        'hard' => 'Sulit',
                        default => $ch->difficulty
                    };
                    $catLabel = match($ch->category) {
                        'web' => 'Eksploitasi Web',
                        'crypto' => 'Kriptografi',
                        'recon' => 'Pengintaian',
                        default => $ch->category
                    };
                @endphp
                <tr style="border-bottom: 1px solid var(--border-hairline);">
                    <td style="padding: 1rem 1.25rem;">
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $ch->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $ch->slug }}</div>
                    </td>
                    <td style="padding: 1rem 1.25rem;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--accent-orange); text-transform: uppercase;">
                            {{ $catLabel }}
                        </span>
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center;">
                        {{ $diffLabel }}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center; font-weight: 700; color: #fbbf24;">
                        {{ $ch->points }}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center; font-size: 0.8rem;">
                        {{ match($ch->mode_target) {
                            'both' => 'Keduanya',
                            'competition' => 'Kompetisi',
                            'training' => 'Latihan',
                            default => $ch->mode_target
                        } }}
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: center;">
                        <span style="font-size: 0.75rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; {{ $ch->is_active ? 'background: rgba(34, 197, 94, 0.15); color: #4ade80;' : 'background: rgba(239, 68, 68, 0.15); color: #f87171;' }}">
                            {{ $ch->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('admin.challenges.edit', $ch->id) }}" class="btn-subtle" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.challenges.destroy', $ch->id) }}" style="margin: 0;" onsubmit="return confirm('Hapus tantangan {{ $ch->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.35rem 0.65rem; font-size: 0.75rem; border-radius: 6px; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada tantangan yang dikonfigurasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
