@extends('layouts.app')

@section('title', 'Edit Tantangan')

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('admin.challenges.index') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Kembali ke Daftar Tantangan</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Edit Tantangan: {{ $challenge->title }}
            </h1>
        </div>
    </div>

    @if($errors->any())
    <div class="alert-box alert-error">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.75rem;">
        <form method="POST" action="{{ route('admin.challenges.update', $challenge->id) }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <!-- Title & Slug -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Judul Tantangan *</label>
                    <input type="text" name="title" value="{{ old('title', $challenge->title) }}" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug', $challenge->slug) }}" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                </div>
            </div>

            <!-- Category, Difficulty, Points, Mode Target -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Kategori *</label>
                    <select name="category" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                        <option value="web" {{ old('category', $challenge->category) === 'web' ? 'selected' : '' }}>Web</option>
                        <option value="crypto" {{ old('category', $challenge->category) === 'crypto' ? 'selected' : '' }}>Kripto</option>
                        <option value="recon" {{ old('category', $challenge->category) === 'recon' ? 'selected' : '' }}>Pengintaian</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Tingkat Kesulitan *</label>
                    <select name="difficulty" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                        <option value="easy" {{ old('difficulty', $challenge->difficulty) === 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ old('difficulty', $challenge->difficulty) === 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ old('difficulty', $challenge->difficulty) === 'hard' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Poin Nilai *</label>
                    <input type="number" name="points" value="{{ old('points', $challenge->points) }}" required min="1" style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Target Mode *</label>
                    <select name="mode_target" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
                        <option value="both" {{ old('mode_target', $challenge->mode_target) === 'both' ? 'selected' : '' }}>Kedua Mode</option>
                        <option value="competition" {{ old('mode_target', $challenge->mode_target) === 'competition' ? 'selected' : '' }}>Kompetisi Saja</option>
                        <option value="training" {{ old('mode_target', $challenge->mode_target) === 'training' ? 'selected' : '' }}>Latihan Saja</option>
                    </select>
                </div>
            </div>

            <!-- Flag Token -->
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Token Flag Jawaban *</label>
                <input type="text" name="flag" value="{{ old('flag', $challenge->flag) }}" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.9rem; outline: none;">
            </div>

            <!-- Description -->
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">Deskripsi & Ringkasan Tantangan *</label>
                <textarea name="description" rows="4" required style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.85rem; outline: none;">{{ old('description', $challenge->description) }}</textarea>
            </div>

            <!-- Hint (Training mode) -->
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #fbbf24; margin-bottom: 0.4rem;">Petunjuk (Mode Latihan)</label>
                <textarea name="hint" rows="2" style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.85rem; outline: none;">{{ old('hint', $challenge->hint) }}</textarea>
            </div>

            <!-- Remediation Walkthrough -->
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #4ade80; margin-bottom: 0.4rem;">Panduan Remedi & Mitigasi Keamanan</label>
                <textarea name="remediation" rows="2" style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.6rem 0.8rem; color: var(--text-primary); font-size: 0.85rem; outline: none;">{{ old('remediation', $challenge->remediation) }}</textarea>
            </div>

            <!-- Active Status Checkbox -->
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $challenge->is_active) ? 'checked' : '' }} style="accent-color: var(--accent-orange);">
                <label style="color: var(--text-secondary);">Aktifkan dan publikasikan tantangan</label>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 0.5rem;">
                <button type="submit" class="btn-orange" style="padding: 0.65rem 1.5rem;">
                    Perbarui Tantangan &rarr;
                </button>
                <a href="{{ route('admin.challenges.index') }}" class="btn-subtle">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
