@extends('layouts.app')

@section('title', 'Moderasi Request Tantangan')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Admin Header -->
    <div style="background: linear-gradient(135deg, #13151c 0%, #1f1816 100%); border: 1px solid rgba(249, 115, 22, 0.3); border-radius: 12px; padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; font-weight: 700; background: rgba(249, 115, 22, 0.2); color: var(--accent-orange); padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid rgba(249, 115, 22, 0.4); text-transform: uppercase;">
                    Panel Kurasi Konten
                </span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">Moderasi Proposal Masuk</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.4rem 0; letter-spacing: -0.02em;">
                Daftar Permintaan Tantangan
            </h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0; line-height: 1.5;">
                Evaluasi usulan skenario kerentanan dari komunitas dan peserta. Berikan status peninjauan serta masukan teknis.
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('admin.index') }}" class="btn-subtle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span>Kembali ke Pusat Kontrol</span>
            </a>
            <a href="{{ route('challenge_requests.index') }}" class="btn-subtle" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>Buka Formulir Publik</span>
            </a>
        </div>
    </div>

    <!-- Stats Filter Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('admin.challenge_requests.index', ['status' => 'all']) }}" style="text-decoration: none; background: var(--surface-card); border: 1px solid {{ $statusFilter === 'all' ? 'var(--accent-orange)' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1.15rem; transition: border-color 0.15s ease;">
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Total Proposal</div>
            <div style="font-size: 1.6rem; font-weight: 700; color: #f8fafc; margin-top: 0.25rem;">{{ $stats['total'] }}</div>
        </a>

        <a href="{{ route('admin.challenge_requests.index', ['status' => 'pending']) }}" style="text-decoration: none; background: var(--surface-card); border: 1px solid {{ $statusFilter === 'pending' ? '#fbbf24' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1.15rem; transition: border-color 0.15s ease;">
            <div style="font-size: 0.75rem; color: #fbbf24; font-weight: 600; text-transform: uppercase;">Menunggu Review</div>
            <div style="font-size: 1.6rem; font-weight: 700; color: #fbbf24; margin-top: 0.25rem;">{{ $stats['pending'] }}</div>
        </a>

        <a href="{{ route('admin.challenge_requests.index', ['status' => 'reviewing']) }}" style="text-decoration: none; background: var(--surface-card); border: 1px solid {{ $statusFilter === 'reviewing' ? '#60a5fa' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1.15rem; transition: border-color 0.15s ease;">
            <div style="font-size: 0.75rem; color: #60a5fa; font-weight: 600; text-transform: uppercase;">Sedang Ditinjau</div>
            <div style="font-size: 1.6rem; font-weight: 700; color: #60a5fa; margin-top: 0.25rem;">{{ $stats['reviewing'] }}</div>
        </a>

        <a href="{{ route('admin.challenge_requests.index', ['status' => 'approved']) }}" style="text-decoration: none; background: var(--surface-card); border: 1px solid {{ $statusFilter === 'approved' ? '#34d399' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1.15rem; transition: border-color 0.15s ease;">
            <div style="font-size: 0.75rem; color: #34d399; font-weight: 600; text-transform: uppercase;">Disetujui</div>
            <div style="font-size: 1.6rem; font-weight: 700; color: #34d399; margin-top: 0.25rem;">{{ $stats['approved'] }}</div>
        </a>

        <a href="{{ route('admin.challenge_requests.index', ['status' => 'rejected']) }}" style="text-decoration: none; background: var(--surface-card); border: 1px solid {{ $statusFilter === 'rejected' ? '#f87171' : 'var(--border-hairline)' }}; border-radius: 8px; padding: 1.15rem; transition: border-color 0.15s ease;">
            <div style="font-size: 0.75rem; color: #f87171; font-weight: 600; text-transform: uppercase;">Perlu Revisi</div>
            <div style="font-size: 1.6rem; font-weight: 700; color: #f87171; margin-top: 0.25rem;">{{ $stats['rejected'] }}</div>
        </a>
    </div>

    <!-- Requests List Table Card -->
    <div style="background: var(--surface-card); border: 1px solid var(--border-hairline); border-radius: 10px; padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #f8fafc; margin: 0 0 0.25rem 0;">
                    Daftar Usulan Masuk
                </h2>
                <div style="font-size: 0.8rem; color: #94a3b8;">
                    Menampilkan {{ $requests->total() }} proposal (filter status {{ $statusFilter }})
                </div>
            </div>

            <!-- Segmented Track -->
            <div class="segmented-filter-track">
                <a href="{{ route('admin.challenge_requests.index', ['status' => 'all']) }}" class="segmented-filter-btn {{ $statusFilter === 'all' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('admin.challenge_requests.index', ['status' => 'pending']) }}" class="segmented-filter-btn {{ $statusFilter === 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('admin.challenge_requests.index', ['status' => 'reviewing']) }}" class="segmented-filter-btn {{ $statusFilter === 'reviewing' ? 'active' : '' }}">Ditinjau</a>
                <a href="{{ route('admin.challenge_requests.index', ['status' => 'approved']) }}" class="segmented-filter-btn {{ $statusFilter === 'approved' ? 'active' : '' }}">Disetujui</a>
                <a href="{{ route('admin.challenge_requests.index', ['status' => 'rejected']) }}" class="segmented-filter-btn {{ $statusFilter === 'rejected' ? 'active' : '' }}">Revisi</a>
            </div>
        </div>

        @if($requests->isEmpty())
        <div style="text-align: center; padding: 3rem 1.5rem; background: var(--surface-secondary); border: 1px dashed var(--border-hairline); border-radius: 8px;">
            <div style="font-size: 0.95rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.25rem;">Tidak ada permintaan tantangan pada kriteria ini</div>
            <div style="font-size: 0.8rem; color: #64748b;">Semua kiriman baru dari peserta akan muncul di panel moderasi ini.</div>
        </div>
        @else
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($requests as $req)
            <div style="background: var(--surface-secondary); border: 1px solid var(--border-hairline); border-radius: 8px; padding: 1.25rem;">
                
                <!-- Card Header -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.85rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.725rem; font-weight: 600; background: rgba(255, 255, 255, 0.06); padding: 0.15rem 0.5rem; border-radius: 4px; color: #94a3b8;">
                                #{{ $req->id }}
                            </span>
                            <span style="font-size: 1.05rem; font-weight: 700; color: #f8fafc;">
                                {{ $req->title }}
                            </span>
                            <span style="font-size: 0.725rem; font-weight: 600; color: #cbd5e1; background: rgba(56, 189, 248, 0.12); padding: 0.15rem 0.5rem; border-radius: 4px; border: 1px solid rgba(56, 189, 248, 0.25);">
                                {{ $req->categoryLabel() }}
                            </span>
                            <span style="font-size: 0.725rem; font-weight: 600; color: #94a3b8; background: rgba(255, 255, 255, 0.05); padding: 0.15rem 0.5rem; border-radius: 4px;">
                                Tingkat {{ $req->difficultyLabel() }}
                            </span>
                        </div>

                        <div style="font-size: 0.8rem; color: #94a3b8; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="color: #f1f5f9; font-weight: 600;">{{ $req->author_name }}</span>
                            <span>&bull;</span>
                            <a href="mailto:{{ $req->author_email }}" style="color: #38bdf8; text-decoration: none;">{{ $req->author_email }}</a>
                            @if($req->user)
                            <span>&bull;</span>
                            <span style="color: #64748b;">Akun {{ '@' . $req->user->username }}</span>
                            @endif
                            <span>&bull;</span>
                            <span style="color: #64748b;">Diajukan {{ $req->created_at->translatedFormat('d M Y, H:i') }}</span>
                            @if($req->submission_ip)
                            <span>&bull;</span>
                            <span style="color: #64748b; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;">IP {{ $req->submission_ip }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Status Display -->
                    <div>
                        @if($req->status === 'approved')
                            <span style="font-size: 0.8rem; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.12); padding: 0.25rem 0.65rem; border-radius: 4px; border: 1px solid rgba(52, 211, 153, 0.3);">
                                Disetujui
                            </span>
                        @elseif($req->status === 'reviewing')
                            <span style="font-size: 0.8rem; font-weight: 600; color: #60a5fa; background: rgba(96, 165, 250, 0.12); padding: 0.25rem 0.65rem; border-radius: 4px; border: 1px solid rgba(96, 165, 250, 0.3);">
                                Sedang Ditinjau
                            </span>
                        @elseif($req->status === 'rejected')
                            <span style="font-size: 0.8rem; font-weight: 600; color: #f87171; background: rgba(248, 113, 113, 0.12); padding: 0.25rem 0.65rem; border-radius: 4px; border: 1px solid rgba(248, 113, 113, 0.3);">
                                Memerlukan Revisi
                            </span>
                        @else
                            <span style="font-size: 0.8rem; font-weight: 600; color: #fbbf24; background: rgba(251, 191, 36, 0.12); padding: 0.25rem 0.65rem; border-radius: 4px; border: 1px solid rgba(251, 191, 36, 0.3);">
                                Menunggu Review
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Scenario Concept Body -->
                <div style="background: #090a0f; border: 1px solid var(--border-hairline); border-radius: 6px; padding: 0.85rem 1rem; margin-bottom: 0.85rem; font-size: 0.825rem; color: #cbd5e1; line-height: 1.55; white-space: pre-wrap;">{{ $req->description }}</div>

                <!-- Metadata Row (Hint, Reference, Flag) -->
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.775rem; margin-bottom: 1rem; color: #94a3b8;">
                    @if($req->hint)
                    <div>
                        <span style="color: #64748b; font-weight: 600;">Petunjuk</span>
                        <span style="color: #cbd5e1; margin-left: 0.25rem;">{{ $req->hint }}</span>
                    </div>
                    @endif

                    @if($req->reference_url)
                    <div>
                        <span style="color: #64748b; font-weight: 600;">Tautan Berkas</span>
                        <a href="{{ $req->reference_url }}" target="_blank" rel="noopener noreferrer" style="color: #38bdf8; text-decoration: none; margin-left: 0.25rem;">
                            {{ $req->reference_url }} &nearr;
                        </a>
                    </div>
                    @endif

                    @if($req->suggested_flag)
                    <div>
                        <span style="color: #64748b; font-weight: 600;">Flag Usulan</span>
                        <code style="font-family: 'JetBrains Mono', monospace; color: #34d399; margin-left: 0.25rem; background: rgba(0,0,0,0.3); padding: 0.1rem 0.35rem; border-radius: 3px;">{{ $req->suggested_flag }}</code>
                    </div>
                    @endif

                    @if($req->hint_file)
                    <div>
                        <span style="color: #64748b; font-weight: 600;">File PDF Tutorial</span>
                        <a href="{{ $req->hintFileUrl() }}" target="_blank" style="color: #38bdf8; text-decoration: none; margin-left: 0.25rem; font-weight: 600;">
                            {{ $req->original_hint_filename ?? 'Lihat PDF' }} &nearr;
                        </a>
                    </div>
                    @endif

                    @if($req->challenge_file)
                    <div>
                        <span style="color: #64748b; font-weight: 600;">File Bahan Lab</span>
                        <a href="{{ $req->challengeFileUrl() }}" download style="color: #fbbf24; text-decoration: none; margin-left: 0.25rem; font-weight: 600;">
                            {{ $req->original_challenge_filename ?? 'Download File' }} &darr;
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Review Form Row -->
                <div style="border-top: 1px solid var(--border-hairline); padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    
                    <form action="{{ route('admin.challenge_requests.update_status', $req->id) }}" method="POST" style="display: flex; align-items: center; gap: 0.75rem; flex: 1; flex-wrap: wrap;">
                        @csrf
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <label style="font-size: 0.775rem; font-weight: 600; color: #94a3b8;">Ubah Status</label>
                            <select name="status" style="background: var(--surface-card); border: 1px solid var(--border-input); color: #f8fafc; font-size: 0.775rem; border-radius: 4px; padding: 0.35rem 0.6rem;">
                                <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="reviewing" {{ $req->status === 'reviewing' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                <option value="approved" {{ $req->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ $req->status === 'rejected' ? 'selected' : '' }}>Memerlukan Revisi</option>
                            </select>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.4rem; flex: 1; min-width: 220px;">
                            <input
                                type="text"
                                name="admin_notes"
                                value="{{ $req->admin_notes }}"
                                placeholder="Catatan atau masukan untuk pengusul..."
                                style="width: 100%; background: var(--surface-card); border: 1px solid var(--border-input); color: #f8fafc; font-size: 0.775rem; border-radius: 4px; padding: 0.35rem 0.6rem;"
                            >
                        </div>

                        <button type="submit" class="btn-subtle" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                            Simpan Review
                        </button>
                    </form>

                    <form action="{{ route('admin.challenge_requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus usulan ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-subtle" style="color: #f87171; border-color: rgba(248, 113, 113, 0.3); padding: 0.35rem 0.65rem; font-size: 0.775rem;" title="Hapus usulan">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            <span>Hapus</span>
                        </button>
                    </form>

                </div>

            </div>
            @endforeach
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
