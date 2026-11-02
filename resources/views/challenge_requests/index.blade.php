@extends('layouts.app')

@section('title', 'Request Tantangan')

@push('styles')
<!-- Google Font Poppins -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .poppins-theme,
    .poppins-theme * {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif !important;
    }

    /* Floating Card with Depth */
    .portal-card {
        background: #111420;
        border: 1px solid #1e273f;
        border-top: 2px solid var(--accent-orange);
        border-radius: 20px;
        padding: 2.25rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
        position: relative;
    }

    /* Section Category Tag (Authentic Label, Not a Fake Button) */
    .section-label-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.775rem;
        font-weight: 700;
        color: var(--accent-orange);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.85rem;
    }

    .contact-item-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .contact-item-value {
        font-size: 0.975rem;
        font-weight: 600;
        color: #f1f5f9;
        line-height: 1.3;
    }

    /* Inputs with Rich Contrast */
    .portal-input,
    .portal-select,
    .portal-textarea {
        width: 100%;
        box-sizing: border-box;
        background: #151b2a;
        border: 1px solid #232d44;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #ffffff;
        font-size: 0.875rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .portal-input:focus,
    .portal-select:focus,
    .portal-textarea:focus {
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
        outline: none;
        background: #182033;
    }

    .portal-label {
        display: block;
        font-size: 0.825rem;
        font-weight: 500;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
    }

    /* Custom Modern File Upload Box */
    .upload-box {
        background: #151b2a;
        border: 1px solid #232d44;
        border-radius: 10px;
        padding: 0.6rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: border-color 0.2s ease;
    }

    .upload-box:hover {
        border-color: #3b4766;
    }

    .upload-btn-trigger {
        background: #20273a;
        border: 1px solid #2e3852;
        color: #f1f5f9;
        font-size: 0.775rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        user-select: none;
        transition: all 0.15s ease;
    }

    .upload-btn-trigger:hover {
        background: #273148;
        border-color: var(--accent-orange);
        color: #ffffff;
    }

    .upload-file-name {
        font-size: 0.8rem;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    .upload-file-name.has-file {
        color: var(--accent-orange);
        font-weight: 500;
    }

    /* Submit Button (ITEC Brand Orange) */
    .portal-submit-btn {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: #ffffff;
        font-size: 0.925rem;
        font-weight: 600;
        padding: 0.8rem 1.85rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(249, 115, 22, 0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    }

    .portal-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(249, 115, 22, 0.5);
        opacity: 0.95;
    }

    .portal-submit-btn:active {
        transform: translateY(0);
    }

    /* Modern Table Card */
    .portal-table-card {
        background: #111420;
        border: 1px solid #1e273f;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div class="poppins-theme" style="max-width: 1180px; margin: 0 auto; padding: 1.5rem 0 3rem 0;">

    @if ($errors->any())
    <div class="alert-box alert-error" style="margin-bottom: 2rem; border-radius: 12px;">
        <div>
            <div style="font-weight: 600; margin-bottom: 0.25rem;">Mohon periksa kembali formulir Anda</div>
            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.825rem; line-height: 1.5;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Split 2-Column Hero: Left Info & Right Form Card -->
    <div style="display: grid; grid-template-columns: 1fr 1.25fr; gap: 3.5rem; align-items: start;" class="responsive-two-col">

        <!-- Sisi Kiri: Heading Besar & Info Kontak (Berkarakter & Bernyawa) -->
        <div style="padding-top: 0.5rem;">
            
            <!-- Authentic Tag Label (Bukan Fake Button) -->
            <div class="section-label-tag">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                <span>Request Tantangan Baru</span>
            </div>

            <!-- Big Confident Headline -->
            <h1 style="font-size: 2.45rem; font-weight: 700; color: #ffffff; line-height: 1.25; margin: 0 0 1rem 0; letter-spacing: -0.02em;">
                Punya ide tantangan yang menarik?
            </h1>

            <p style="font-size: 0.95rem; color: #94a3b8; line-height: 1.6; margin: 0 0 2.25rem 0; text-align: justify;">
                Kirimkan konsep skenario lab atau kerentanan baru Anda. Tim kurator ITEC siap meninjau dan menerbitkannya ke dalam platform.
            </p>

            <!-- Info Kontak Langsung Sesuai Palet ITEC -->
            <div style="display: flex; flex-direction: column; gap: 1.15rem; margin-bottom: 2.25rem;">
                
                <!-- Email Item -->
                <div class="contact-item-row">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <span class="contact-item-value">{{ $contactEmail }}</span>
                        <button type="button" onclick="copyEmailToClipboard()" id="copyEmailBtn" class="btn-subtle" style="padding: 0.2rem 0.55rem; font-size: 0.725rem; border-radius: 6px;">
                            <span id="copyBtnLabel">Salin</span>
                        </button>
                    </div>
                </div>

                <!-- Review Time Item (1 - 3 Hari Saja) -->
                <div class="contact-item-row">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span class="contact-item-value">1 - 3 Hari</span>
                </div>

                <!-- Flag Format Item -->
                <div class="contact-item-row">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                    <span class="contact-item-value" style="color: #38bdf8; font-family: 'JetBrains Mono', monospace !important;">FLAG{...}</span>
                </div>

            </div>

            <!-- Tautan Email Langsung -->
            <div>
                <a href="{{ $mailtoLink }}" class="btn-subtle" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; padding: 0.65rem 1.15rem; border-radius: 8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>Buka Aplikasi Email Langsung</span>
                </a>
            </div>

        </div>

        <!-- Sisi Kanan: Kartu Form Mengambang (ITEC Palette) -->
        <div class="portal-card">
            
            <form action="{{ route('challenge_requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama & Email Pengusul -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;" class="responsive-two-col">
                    <div>
                        <label for="author_name" class="portal-label">Nama Lengkap*</label>
                        <input
                            type="text"
                            id="author_name"
                            name="author_name"
                            value="{{ old('author_name', $user ? $user->name : '') }}"
                            required
                            placeholder="Nama Anda"
                            class="portal-input"
                        >
                    </div>

                    <div>
                        <label for="author_email" class="portal-label">Email Aktif*</label>
                        <input
                            type="email"
                            id="author_email"
                            name="author_email"
                            value="{{ old('author_email', $user ? $user->email : '') }}"
                            required
                            placeholder="nama@email.com"
                            class="portal-input"
                        >
                    </div>
                </div>

                <!-- Judul Tantangan -->
                <div style="margin-bottom: 1.25rem;">
                    <label for="title" class="portal-label">Judul Tantangan*</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Contoh: Secret Token Leaks, SQLi Bypass, Buffer Gatekeeper"
                        class="portal-input"
                    >
                </div>

                <!-- Kategori & Tingkat Kesulitan -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;" class="responsive-two-col">
                    <div>
                        <label for="category" class="portal-label">Kategori*</label>
                        <select id="category" name="category" required class="portal-select">
                            <option value="web" {{ old('category') === 'web' ? 'selected' : '' }}>Web Exploitation</option>
                            <option value="crypto" {{ old('category') === 'crypto' ? 'selected' : '' }}>Cryptography</option>
                            <option value="recon" {{ old('category') === 'recon' ? 'selected' : '' }}>Reconnaissance</option>
                            <option value="forensics" {{ old('category') === 'forensics' ? 'selected' : '' }}>Digital Forensics</option>
                            <option value="reversing" {{ old('category') === 'reversing' ? 'selected' : '' }}>Reverse Engineering</option>
                            <option value="pwn" {{ old('category') === 'pwn' ? 'selected' : '' }}>Binary Exploitation</option>
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Skills</option>
                        </select>
                    </div>

                    <div>
                        <label for="difficulty" class="portal-label">Tingkat Kesulitan*</label>
                        <select id="difficulty" name="difficulty" required class="portal-select">
                            <option value="easy" {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Mudah</option>
                            <option value="medium" {{ old('difficulty', 'medium') === 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="hard" {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Sulit</option>
                            <option value="insane" {{ old('difficulty') === 'insane' ? 'selected' : '' }}>Sangat Sulit</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Singkat -->
                <div style="margin-bottom: 1.25rem;">
                    <label for="description" class="portal-label">Deskripsi Skenario*</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        placeholder="Tuliskan gambaran skenario, letak kerentanan, atau alur eksploitasinya..."
                        class="portal-textarea"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- File Upload Inputs (Desain Modern Kustom) -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;" class="responsive-two-col">
                    
                    <!-- File Panduan PDF -->
                    <div>
                        <label class="portal-label">Panduan / Hint (PDF)</label>
                        <div class="upload-box">
                            <input
                                type="file"
                                id="hint_file"
                                name="hint_file"
                                accept=".pdf,application/pdf"
                                style="position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden;"
                                onchange="handleFileUpload(this, 'hint_file_name')"
                            >
                            <label for="hint_file" class="upload-btn-trigger">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span>Pilih PDF</span>
                            </label>
                            <span id="hint_file_name" class="upload-file-name">Belum ada file</span>
                        </div>
                    </div>

                    <!-- File Bahan Tantangan -->
                    <div>
                        <label class="portal-label">Berkas Lab (Zip / File)</label>
                        <div class="upload-box">
                            <input
                                type="file"
                                id="challenge_file"
                                name="challenge_file"
                                style="position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden;"
                                onchange="handleFileUpload(this, 'challenge_file_name')"
                            >
                            <label for="challenge_file" class="upload-btn-trigger">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span>Pilih File</span>
                            </label>
                            <span id="challenge_file_name" class="upload-file-name">Belum ada file</span>
                        </div>
                    </div>
                </div>

                <!-- Flag Usulan & Tautan Referensi -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.75rem;" class="responsive-two-col">
                    <div>
                        <label for="suggested_flag" class="portal-label">Flag Usulan</label>
                        <input
                            type="text"
                            id="suggested_flag"
                            name="suggested_flag"
                            value="{{ old('suggested_flag') }}"
                            placeholder="FLAG{contoh_flag_usulan}"
                            class="portal-input"
                            style="font-family: 'JetBrains Mono', monospace !important;"
                        >
                    </div>

                    <div>
                        <label for="reference_url" class="portal-label">Tautan Referensi</label>
                        <input
                            type="text"
                            id="reference_url"
                            name="reference_url"
                            value="{{ old('reference_url') }}"
                            placeholder="https://github.com/..."
                            class="portal-input"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="portal-submit-btn">
                        <span>Kirim Request</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <!-- Riwayat Pengajuan (Tabel Modern & Bersih) -->
    @auth
    <div class="portal-table-card" style="margin-top: 3.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
            <h2 style="font-size: 1.2rem; font-weight: 600; color: #ffffff; margin: 0;">
                Riwayat Pengajuan
            </h2>
            <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                {{ $userRequests->count() }} usulan tersimpan
            </div>
        </div>

        @if($userRequests->isEmpty())
        <div style="text-align: center; padding: 2.5rem 1.5rem; color: #64748b; font-size: 0.875rem;">
            Belum ada tantangan yang diajukan
        </div>
        @else
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-hairline); color: var(--text-muted); font-size: 0.775rem; text-transform: uppercase;">
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Judul Tantangan</th>
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Kategori</th>
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Berkas</th>
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Status</th>
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Waktu</th>
                        <th style="padding: 0.85rem 1rem; font-weight: 600;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userRequests as $req)
                    <tr style="border-bottom: 1px solid #141824; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='var(--surface-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 1rem; color: #f8fafc; font-weight: 600;">
                            <div>{{ $req->title }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.2rem; font-weight: 400; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $req->description }}
                            </div>
                        </td>
                        <td style="padding: 1rem; color: #cbd5e1;">
                            {{ $req->categoryLabel() }}, {{ $req->difficultyLabel() }}
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                                @if($req->hint_file)
                                <a href="{{ $req->hintFileUrl() }}" target="_blank" style="font-size: 0.775rem; color: var(--accent-orange); text-decoration: none; font-weight: 500;">
                                    PDF Tutorial &nearr;
                                </a>
                                @endif
                                @if($req->challenge_file)
                                <a href="{{ $req->challengeFileUrl() }}" download style="font-size: 0.775rem; color: #fbbf24; text-decoration: none; font-weight: 500;">
                                    File Lab &darr;
                                </a>
                                @endif
                                @if(!$req->hint_file && !$req->challenge_file)
                                <span style="font-size: 0.75rem; color: #64748b;">-</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            @if($req->status === 'approved')
                                <span style="color: #34d399; font-weight: 600;">Disetujui</span>
                            @elseif($req->status === 'reviewing')
                                <span style="color: #60a5fa; font-weight: 600;">Sedang Ditinjau</span>
                            @elseif($req->status === 'rejected')
                                <span style="color: #f87171; font-weight: 600;">Memerlukan Revisi</span>
                            @else
                                <span style="color: #fbbf24; font-weight: 600;">Menunggu Review</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #64748b; font-size: 0.775rem;">
                            {{ $req->created_at->format('d M Y') }}
                        </td>
                        <td style="padding: 1rem; color: #cbd5e1; font-size: 0.8rem;">
                            {{ $req->admin_notes ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @endauth

</div>

<script>
function handleFileUpload(input, targetId) {
    const target = document.getElementById(targetId);
    if (input.files && input.files.length > 0) {
        target.textContent = input.files[0].name;
        target.classList.add('has-file');
    } else {
        target.textContent = 'Belum ada file';
        target.classList.remove('has-file');
    }
}

function copyEmailToClipboard() {
    const email = "{{ $contactEmail }}";
    const label = document.getElementById('copyBtnLabel');

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(email).then(() => {
            label.textContent = 'Tersalin!';
            setTimeout(() => {
                label.textContent = 'Salin';
            }, 2500);
        }).catch(() => {
            fallbackCopy(email, label);
        });
    } else {
        fallbackCopy(email, label);
    }
}

function fallbackCopy(text, labelElement) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        labelElement.textContent = 'Tersalin!';
        setTimeout(() => {
            labelElement.textContent = 'Salin';
        }, 2500);
    } catch (e) {
        labelElement.textContent = 'Gagal';
    }
    document.body.removeChild(textarea);
}
</script>
@endsection
