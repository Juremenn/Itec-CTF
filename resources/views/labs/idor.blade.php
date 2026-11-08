@extends('layouts.app')

@section('title', 'Lab: IDOR Profile Gateway')

@section('content')
<div style="max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'idor-profile-leak') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Kembali ke Detail Tantangan</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Target: Gateway Profil Pengguna Internal
            </h1>
        </div>
    </div>

    <!-- Parameter Indicator -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.85rem; color: var(--text-secondary);">
            Parameter Query URL: <code style="color: var(--accent-orange); background: var(--bg-base); padding: 0.15rem 0.45rem; border-radius: 4px; border: 1px solid var(--border-input);">?id={{ $id }}</code>
        </div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">
            Ubah nilai <code style="color: var(--text-secondary);">?id=</code> pada URL peramban untuk menguji IDOR
        </div>
    </div>

    <!-- Profile Card -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 2rem;">
        @if($profile)
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--surface-secondary); border: 1px solid var(--border-hairline); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--accent-orange); font-weight: 700;">
                {{ strtoupper(substr($profile->username, 0, 1)) }}
            </div>
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.25rem 0;">
                    {{ $profile->username }}
                </h3>
                <span style="font-size: 0.75rem; font-weight: 600; padding: 0.15rem 0.55rem; border-radius: 4px; {{ $profile->role === 'admin' ? 'background: rgba(249, 115, 22, 0.2); color: #f97316; border: 1px solid rgba(249, 115, 22, 0.4);' : 'background: var(--surface-secondary); color: var(--text-muted);' }}; text-transform: uppercase;">
                    Peran: {{ $profile->role }}
                </span>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem; color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5;">
            {{ $profile->bio ?? 'Tidak ada data biografi yang terdaftar.' }}
        </div>

        @if(!empty($profile->secret))
        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1.25rem;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #4ade80; text-transform: uppercase; margin-bottom: 0.4rem;">
                Data Rahasia Akun Administrator Bocor:
            </div>
            <code style="font-size: 1.15rem; font-weight: 700; color: #4ade80; background: var(--bg-base); padding: 0.4rem 0.8rem; border-radius: 4px; display: inline-block;">
                {{ $profile->secret }}
            </code>
        </div>
        @endif

        @else
        <div style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
            Tidak ditemukan profil pengguna untuk ID: <code>{{ $id }}</code>
        </div>
        @endif
    </div>

</div>
@endsection
