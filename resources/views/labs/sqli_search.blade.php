@extends('layouts.app')

@section('title', 'Lab: SQLi UNION Exfiltration')

@section('content')
<div style="max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <a href="{{ route('challenges.show', 'sqli-union-exfiltration') }}" style="color: var(--accent-orange); text-decoration: none;">&larr; Kembali ke Detail Tantangan</a>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">
                Target: API Pencarian Katalog Produk
            </h1>
        </div>
    </div>

    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.75rem;">
        <form method="GET" action="{{ route('lab.sqli_search') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1.25rem;">
            <input 
                type="text" 
                name="q" 
                value="{{ $q }}" 
                placeholder="Cari produk atau suntikkan payload UNION SELECT..." 
                style="flex: 1; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.65rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none;"
            >
            <button type="submit" class="btn-orange" style="padding: 0.65rem 1.25rem;">
                Eksekusi Query &rarr;
            </button>
        </form>

        @if($error)
        <div style="margin-bottom: 1.25rem;">
            <div style="font-size: 0.75rem; color: #f87171; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #ef4444;"></span>
                MySQL / MariaDB Database Error:
            </div>
            <pre style="margin: 0; background: #07080b; border: 1px solid rgba(239, 68, 68, 0.4); border-left: 4px solid #ef4444; border-radius: 6px; padding: 0.85rem 1.15rem; color: #fca5a5; font-family: 'Consolas', 'Courier New', monospace !important; font-size: 0.825rem; white-space: pre-wrap; word-break: break-all; line-height: 1.5;">{{ $error }}</pre>
        </div>
        @endif

        <!-- Query Results -->
        <div style="border: 1px solid var(--border-hairline); border-radius: 8px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 0.75rem 1rem;">ID</th>
                        <th style="padding: 0.75rem 1rem;">Nama / Kunci</th>
                        <th style="padding: 0.75rem 1rem;">Kategori</th>
                        <th style="padding: 0.75rem 1rem;">Harga</th>
                        <th style="padding: 0.75rem 1rem;">Deskripsi / Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $row)
                    @php
                        $isFlag = is_object($row) && (str_contains($row->description ?? '', 'FLAG{') || str_contains($row->name ?? '', 'FLAG{') || str_contains($row->category ?? '', 'FLAG{'));
                    @endphp
                    <tr style="border-bottom: 1px solid var(--border-hairline); {{ $isFlag ? 'background: rgba(34, 197, 94, 0.15);' : '' }}">
                        <td style="padding: 0.75rem 1rem; color: var(--text-secondary);">
                            {{ is_object($row) ? $row->id : $row['id'] }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-weight: 600; color: {{ $isFlag ? '#4ade80' : 'var(--text-primary)' }};">
                            {{ is_object($row) ? $row->name : $row['name'] }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: var(--text-secondary);">
                            {{ is_object($row) ? $row->category : $row['category'] }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #fbbf24;">
                            ${{ number_format((float)(is_object($row) ? $row->price : $row['price']), 2) }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: {{ $isFlag ? '#4ade80' : 'var(--text-secondary)' }}; font-weight: {{ $isFlag ? '700' : 'normal' }};">
                            {{ is_object($row) ? $row->description : $row['description'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                            Tidak ada produk yang cocok dengan parameter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
