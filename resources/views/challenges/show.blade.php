@extends('layouts.app')

@section('title', $challenge->title)

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Breadcrumb & Challenge Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-hairline); padding-bottom: 1.25rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.4rem;">
                <a href="{{ route('challenges.index') }}" style="color: var(--text-secondary); text-decoration: none;">Tantangan</a>
                <span>/</span>
                <span style="text-transform: uppercase; color: var(--accent-orange); font-weight: 600;">
                    {{ match($challenge->category) {
                        'web' => 'Eksploitasi Web',
                        'crypto' => 'Kriptografi',
                        'recon' => 'Pengintaian',
                        'general' => 'Keahlian Umum (General Skills)',
                        default => $challenge->category
                    } }}
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -0.02em;">
                {{ $challenge->title }}
            </h1>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @php
                $diffBadge = match($challenge->difficulty) {
                    'easy'   => ['bg' => '#14532d', 'color' => '#4ade80', 'border' => 'rgba(74, 222, 128, 0.3)', 'text' => 'Mudah'],
                    'medium' => ['bg' => '#78350f', 'color' => '#fbbf24', 'border' => 'rgba(251, 191, 36, 0.3)', 'text' => 'Sedang'],
                    'hard'   => ['bg' => '#7f1d1d', 'color' => '#f87171', 'border' => 'rgba(248, 113, 113, 0.3)', 'text' => 'Sulit'],
                    default  => ['bg' => '#1e232e', 'color' => '#94a3b8', 'border' => 'rgba(148, 163, 184, 0.3)', 'text' => $challenge->difficulty],
                };
            @endphp
            <span style="font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 4px; background: {{ $diffBadge['bg'] }}; color: {{ $diffBadge['color'] }}; border: 1px solid {{ $diffBadge['border'] }};">
                {{ $diffBadge['text'] }}
            </span>

            <span style="font-size: 1.1rem; font-weight: 800; color: #fbbf24; background: var(--surface-card); padding: 0.25rem 0.75rem; border-radius: 4px; border: 1px solid var(--border-hairline);">
                {{ $challenge->points }} POIN
            </span>
        </div>
    </div>

    <!-- Main Two-Column View -->
    <div style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 1.75rem; align-items: start;">
        
        <!-- Left Column: Scenario, Vector Analysis & Educational Walkthrough -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Scenario & Objective Card -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.85rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    Ringkasan Operasional
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; margin: 0 0 1.25rem 0;">
                    {{ $challenge->description }}
                </p>

                <!-- Objective Box -->
                <div style="background: var(--surface-secondary); border-left: 3px solid var(--accent-orange); border-radius: 4px; padding: 1rem; font-size: 0.85rem; color: var(--text-primary);">
                    <div style="font-weight: 700; color: var(--accent-orange); margin-bottom: 0.35rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">
                        Target Eksekusi
                    </div>
                    <ul style="margin: 0; padding-left: 1.25rem; display: flex; flex-direction: column; gap: 0.35rem;">
                        <li>Identifikasi parameter yang rentan atau kesalahan konfigurasi pada arsitektur target.</li>
                        <li>Rancang payload eksploitasi untuk mengekstrak token rahasia dari sistem.</li>
                        <li>Temukan token flag yang valid dan kirimkan pada formulir di sebelah kanan.</li>
                    </ul>
                </div>
            </div>

            <!-- Training Mode: Hints & Remediation Accordion (Only in Training Mode) -->
            @if($activeMode === 'training')
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #60a5fa; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        Panduan Mode Latihan
                    </h3>
                    <span style="font-size: 0.7rem; font-weight: 600; background: rgba(59, 130, 246, 0.15); color: #60a5fa; padding: 0.2rem 0.5rem; border-radius: 4px;">
                        Pembelajaran Interaktif
                    </span>
                </div>

                <!-- Hint Section -->
                <div style="margin-bottom: 1rem;">
                    <details style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1rem;">
                        <summary style="font-size: 0.85rem; font-weight: 600; color: #fbbf24; cursor: pointer; user-select: none;">
                            💡 Buka Petunjuk Tantangan
                        </summary>
                        <div style="margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6; border-top: 1px solid var(--border-hairline); padding-top: 0.75rem; white-space: pre-line;">{!! nl2br(e($challenge->hint ?? 'Analisis respon galat server dan perhatikan anomali refleksi query data.')) !!}</div>
                    </details>
                </div>

                <!-- Remediation Walkthrough -->
                <div>
                    <details style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1rem;">
                        <summary style="font-size: 0.85rem; font-weight: 600; color: #4ade80; cursor: pointer; user-select: none;">
                            🛡️ Panduan Pertahanan & Remedi Keamanan
                        </summary>
                        <div style="margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; border-top: 1px solid var(--border-hairline); padding-top: 0.75rem;">
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.35rem;">Praktik Keamanan Terbaik:</div>
                            {{ $challenge->remediation ?? 'Pastikan seluruh input divalidasi dan query database menggunakan parameterized prepared statements.' }}
                        </div>
                    </details>
                </div>
            </div>
            @endif

            <!-- Interactive Laboratory Target Information -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.85rem 0;">
                    Akses Endpoint Target
                </h3>

                @php $isAdmin = Auth::check() && Auth::user()->isAdmin(); @endphp

                @if($challenge->slug === 'sqli-login-bypass')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Target interaktif: Konsol Login Administrator ShopEZ. Form otentikasi tidak menerapkan escaping pada string input.
                    </p>
                    @if($isSubmissionLocked && !$isAdmin)
                        <button type="button" disabled class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box; opacity: 0.6; cursor: not-allowed; border-color: var(--border-hairline); color: var(--text-muted); background: var(--surface-secondary); display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Target Lab Dikunci
                        </button>
                    @else
                        <a href="{{ route('lab.sqli_login') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                            Buka Konsol Target: /lab/sqli-login {{ $isSubmissionLocked ? '(Pratinjau Admin)' : '' }} &nearr;
                        </a>
                    @endif
                @elseif($challenge->slug === 'sqli-union-exfiltration')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Target interaktif: Katalog Pencarian Produk. Lakukan teknik UNION SELECT untuk mengekstrak data dari tabel rahasia.
                    </p>
                    @if($isSubmissionLocked && !$isAdmin)
                        <button type="button" disabled class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box; opacity: 0.6; cursor: not-allowed; border-color: var(--border-hairline); color: var(--text-muted); background: var(--surface-secondary); display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Target Lab Dikunci
                        </button>
                    @else
                        <a href="{{ route('lab.sqli_search') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                            Buka Endpoint Target: /lab/sqli-search {{ $isSubmissionLocked ? '(Pratinjau Admin)' : '' }} &nearr;
                        </a>
                    @endif
                @elseif($challenge->slug === 'idor-profile-leak')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Target interaktif: Portal Profil Pengguna. Sistem mempercayai parameter ID tanpa memvalidasi hak akses otorisasi pemilik.
                    </p>
                    @if($isSubmissionLocked && !$isAdmin)
                        <button type="button" disabled class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box; opacity: 0.6; cursor: not-allowed; border-color: var(--border-hairline); color: var(--text-muted); background: var(--surface-secondary); display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Target Lab Dikunci
                        </button>
                    @else
                        <a href="{{ route('lab.idor') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                            Buka Endpoint Target: /lab/idor?id=1 {{ $isSubmissionLocked ? '(Pratinjau Admin)' : '' }} &nearr;
                        </a>
                    @endif
                @elseif($challenge->slug === 'xss-reflected')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Target interaktif: Modul Refleksi Query. Parameter pencarian dicetak langsung ke halaman peramban tanpa sanitasi HTML.
                    </p>
                    @if($isSubmissionLocked && !$isAdmin)
                        <button type="button" disabled class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box; opacity: 0.6; cursor: not-allowed; border-color: var(--border-hairline); color: var(--text-muted); background: var(--surface-secondary); display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Target Lab Dikunci
                        </button>
                    @else
                        <a href="{{ route('lab.xss') }}" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                            Buka Endpoint Target: /lab/xss {{ $isSubmissionLocked ? '(Pratinjau Admin)' : '' }} &nearr;
                        </a>
                    @endif
                @elseif($challenge->slug === 'info-disclosure-recon')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Target intelijen: Periksa file kebijakan robot web crawler di <code style="color: var(--accent-orange);">/robots.txt</code> untuk menemukan konsol debug tersembunyi.
                    </p>
                    @if($isSubmissionLocked && !$isAdmin)
                        <button type="button" disabled class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box; opacity: 0.6; cursor: not-allowed; border-color: var(--border-hairline); color: var(--text-muted); background: var(--surface-secondary); display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Target Lab Dikunci
                        </button>
                    @else
                        <a href="/robots.txt" target="_blank" class="btn-subtle" style="width: 100%; justify-content: center; box-sizing: border-box;">
                            Periksa Kebijakan Robot: /robots.txt {{ $isSubmissionLocked ? '(Pratinjau Admin)' : '' }} &nearr;
                        </a>
                    @endif
                @elseif($challenge->slug === 'wave-a-flag')
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                        Program binary Linux (ELF 64-bit) tersedia untuk diunduh dan dieksekusi di terminal shell Anda.
                    </p>
                    
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                        <a href="{{ route('files.download', 'warm') }}" class="btn-orange" style="padding: 0.6rem 1.25rem; text-decoration: none;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Unduh Binary: warm
                        </a>
                    </div>

                    <div style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 6px; padding: 0.85rem 1rem;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.4rem;">
                            Perintah Terminal (Wget & Eksekusi Shell):
                        </div>
                        <pre style="margin: 0; color: #4ade80; font-family: 'Consolas', 'Courier New', monospace; font-size: 0.8rem; background: var(--bg-base); padding: 0.6rem 0.85rem; border-radius: 4px; line-height: 1.6; overflow-x: auto;">wget {{ url('/files/warm') }}
chmod +x warm
./warm -h</pre>
                    </div>
                @else
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; line-height: 1.5;">
                        Artefak analisis kriptografi atau pengintaian statis. Tinjau ringkasan operasional di atas untuk memecahkan sandi.
                    </p>
                @endif
            </div>

        </div>

        <!-- Right Column: Submission Panel & Solvers Widget -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Flag Submission Card -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-orange)" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                    Pengiriman Flag
                </h3>

                <!-- Locked Banner if Competition is paused/inactive -->
                @if($isSubmissionLocked)
                <div style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.825rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <span>{{ $lockReason }}</span>
                </div>
                @endif

                <!-- Already Solved Banner -->
                @if($isSolved)
                <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #4ade80; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Tantangan Berhasil Diselesaikan
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">
                        Diselesaikan pada {{ $solveRecord?->solved_at?->format('d M Y, H:i') }} (Mode {{ $solveRecord?->mode === 'competition' ? 'Kompetisi' : 'Latihan' }}).
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('challenges.submit', $challenge->id) }}">
                    @csrf
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="flag" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem;">
                            Token Flag
                        </label>
                        <input 
                            type="text" 
                            id="flag" 
                            name="flag" 
                            required 
                            autocomplete="off"
                            placeholder="Masukkan token flag yang ditemukan"
                            {{ $isSubmissionLocked ? 'disabled' : '' }}
                            style="width: 100%; box-sizing: border-box; background: var(--bg-base); border: 1px solid var(--border-input); border-radius: 6px; padding: 0.7rem 0.85rem; color: var(--text-primary); font-size: 0.9rem; outline: none; transition: border-color 0.15s ease;"
                            onfocus="this.style.borderColor='var(--accent-orange)'"
                            onblur="this.style.borderColor='var(--border-input)'"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="btn-orange" 
                        {{ $isSubmissionLocked ? 'disabled' : '' }}
                        style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem;"
                    >
                        @if($isSubmissionLocked)
                            Pengiriman Dikunci
                        @elseif($isSolved)
                            Kirim Ulang Flag
                        @else
                            Submit Flag &rarr;
                        @endif
                    </button>
                </form>
            </div>

            <!-- Solvers Preview Widget -->
            <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.25rem;">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin: 0 0 0.85rem 0; text-transform: uppercase; letter-spacing: 0.04em;">
                    Penyelesai Tercepat
                </h4>

                @if($topSolvers->isEmpty())
                <div style="color: var(--text-muted); font-size: 0.8rem; text-align: center; padding: 1rem 0; line-height: 1.4;">
                    Belum ada yang menyelesaikan. Jadilah yang pertama merebut First Blood!
                </div>
                @else
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($topSolvers as $idx => $ts)
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-weight: 700; color: {{ $idx === 0 ? '#fbbf24' : ($idx === 1 ? '#94a3b8' : ($idx === 2 ? '#b45309' : 'var(--text-muted)')) }};">
                                #{{ $idx + 1 }}
                            </span>
                            <span style="font-weight: 600; color: var(--text-primary);">{{ $ts->user->username }}</span>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $ts->solved_at?->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
