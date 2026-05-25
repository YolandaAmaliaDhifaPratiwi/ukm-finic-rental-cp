{{-- resources/views/member/returns/create.blade.php --}}
@extends('layouts.member')

@section('title', 'Ajukan Pengembalian')

@section('content')
<div class="create-return-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('member.returns.index') }}" style="text-decoration:none;">Pengembalian Alat</a>
        <span class="bc-sep">›</span>
        <span>Ajukan Pengembalian</span>
    </div>

    <div class="page-header">
        <h1 class="page-title">
            Ajukan Pengembalian Alat
        </h1>
        <p class="page-subtitle">Silakan isi formulir di bawah ini untuk melaporkan kondisi alat sebelum dikembalikan ke admin.</p>
    </div>

    <div class="content-grid">

        {{-- LEFT: Form --}}
        <div class="form-card">
            <div class="form-header">
                <h2 class="form-title">Formulir Pengembalian</h2>
                <p class="form-desc">Pastikan peralatan sudah siap dan dalam kondisi bersih sebelum diserahkan.</p>
            </div>

            <form action="{{ route('member.returns.store', $borrowing->id) }}"
                  method="POST" enctype="multipart/form-data" id="returnForm">
                @csrf

                {{-- Kondisi Barang --}}
                <div class="form-group">
                    <label class="form-label" for="condition_notes">
                        Laporan Kondisi Alat <span class="required">*</span>
                    </label>
                    <p class="form-hint">
                        Jelaskan kondisi fisik dan fungsi alat saat ini (misal: "Kondisi mulus dan normal" atau "Ada sedikit goresan di body").
                    </p>
                    <textarea
                        id="condition_notes"
                        name="condition_notes"
                        rows="5"
                        class="form-textarea @error('condition_notes') is-error @enderror"
                        placeholder="Contoh: Kamera berfungsi dengan baik, lensa bersih tanpa jamur, baterai penuh, dan semua kelengkapan ada."
                        maxlength="1000"
                    >{{ old('condition_notes') }}</textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span>/1000 karakter
                    </div>
                    @error('condition_notes')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kondisi Checklist --}}
                <div class="form-group">
                    <label class="form-label">Checklist Kelengkapan &amp; Kondisi Alat</label>
                    <div class="checklist-grid">
                        <label class="check-item">
                            <input type="checkbox" name="checklist[]" value="bersih" class="check-input">
                            <span class="check-box"></span>
                            <span class="check-icon-wrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                                </svg>
                            </span>
                            <span>Alat sudah dibersihkan</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="checklist[]" value="lengkap" class="check-input">
                            <span class="check-box"></span>
                            <span class="check-icon-wrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                            </span>
                            <span>Semua aksesori lengkap</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="checklist[]" value="tidak_rusak" class="check-input">
                            <span class="check-box"></span>
                            <span class="check-icon-wrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <span>Tidak ada kerusakan fisik baru</span>
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="checklist[]" value="charger" class="check-input">
                            <span class="check-box"></span>
                            <span class="check-icon-wrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2M12 12v4M10 14h4"/>
                                </svg>
                            </span>
                            <span>Baterai &amp; charger lengkap</span>
                        </label>
                    </div>
                </div>

                {{-- Upload Foto --}}
                <div class="form-group">
                    <label class="form-label" for="photo">
                        Unggah Foto Alat
                        <span class="optional-tag">Opsional</span>
                    </label>
                    <p class="form-hint">Unggah foto kondisi fisik alat saat ini jika diperlukan untuk memperkuat laporan.</p>

                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="photo" name="photo"
                               accept="image/jpeg,image/png,image/webp"
                               class="upload-input @error('photo') is-error @enderror"
                               onchange="previewPhoto(this)">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <div class="upload-icon-wrap">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                            </div>
                            <p class="upload-text">Klik atau seret file foto ke sini</p>
                            <p class="upload-hint">Format: JPG, PNG, WEBP — Maksimal 5MB</p>
                        </div>
                        <div class="photo-preview" id="photoPreview" style="display:none;">
                            <img id="previewImg" src="" alt="Preview">
                            <button type="button" class="remove-photo" onclick="removePhoto()">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('photo')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pernyataan --}}
                <div class="form-group">
                    <label class="check-item check-declaration">
                        <input type="checkbox" id="declaration" class="check-input" required>
                        <span class="check-box"></span>
                        <span>
                            Saya menyatakan dengan jujur bahwa informasi kondisi alat di atas diisi dengan sebenar-benarnya dan alat siap dikembalikan.
                        </span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <a href="{{ route('member.returns.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
                        </svg>
                        Kirim Pengembalian
                    </button>
                </div>

            </form>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div class="return-sidebar">

            {{-- Info Alat --}}
            <div class="info-card">
                <div class="info-card-header">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Detail Peralatan
                </div>
                <div class="info-card-body">
                    @if($borrowing->equipment->image ?? false)
                        <img src="{{ asset('storage/'.$borrowing->equipment->image) }}"
                             alt="{{ $borrowing->equipment->name }}"
                             class="equipment-photo">
                    @else
                        <div class="equipment-photo-placeholder">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                    @endif

                    <div class="equipment-name">{{ $borrowing->equipment->name }}</div>

                    @if($borrowing->equipment->category ?? false)
                        <div class="equipment-category">
                            @if(strtolower($borrowing->equipment->category) === 'camera') KAMERA
                            @elseif(strtolower($borrowing->equipment->category) === 'lens') LENSA
                            @elseif(strtolower($borrowing->equipment->category) === 'tripod') TRIPOD
                            @elseif(strtolower($borrowing->equipment->category) === 'lighting') LAMPU
                            @else {{ strtoupper($borrowing->equipment->category) }}
                            @endif
                        </div>
                    @endif

                    <div class="info-rows">
                        <div class="info-row">
                            <span class="info-key">Tanggal Pinjam</span>
                            <span class="info-val">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Tanggal Tenggat</span>
                            <span class="info-val {{ now()->gt($borrowing->return_date) ? 'text-red' : '' }}">
                                {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Status Masa Pinjam</span>
                            <span class="info-val">
                                @if(now()->gt($borrowing->return_date))
                                    <span class="pill-overdue">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="pill-ok">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Aman
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Durasi Peminjaman</span>
                            <span class="info-val">
                                {{ \Carbon\Carbon::parse($borrowing->borrow_date)->locale('id')->diffForHumans(now(), ['parts' => 1, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="tips-card">
                <div class="tips-header">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Tips Pengembalian Alat
                </div>
                <ul class="tips-list">
                    <li>Pastikan semua penutup lensa (lens cap) dan bodi kamera sudah terpasang kembali dengan benar.</li>
                    <li>Keluarkan kartu memori pribadi Anda sebelum menyerahkan kamera atau perangkat ke admin.</li>
                    <li>Pastikan baterai bawaan terpasang di dalam kompartemen alat dan bawa kabel chargernya.</li>
                    <li>Bersihkan bodi alat dari debu atau bekas sidik jari menggunakan kain lembut (microfiber).</li>
                    <li>Harap mengembalikan alat tepat waktu sesuai jadwal untuk menghindari sanksi pembatasan pinjaman.</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<style>
/* ===== PAGE LAYOUT ===== */
.create-return-page {
    width: 100%;
    padding: 0 0 60px;
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: #64748b;
    margin-bottom: 18px;
}
.breadcrumb a { color: #22a322; text-decoration: none; font-weight: 500; }
.breadcrumb a:hover { text-decoration: underline; }
.bc-sep { color: #cbd5e1; }

/* Header */
.page-header { margin-bottom: 24px; }

.page-title {
    font-size: 1.55rem;
    font-weight: 700;
    color: #1a2e1a;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 6px;
}

.title-icon {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #1a6b1a, #2d9e2d);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.page-subtitle { color: #64748b; font-size: 0.88rem; margin: 0; }

/* ===== GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 380px);
    gap: 24px;
    align-items: start;
}

.return-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-self: start;
    position: sticky;
    top: 28px;
    min-width: 0;
    overflow: visible;
}

@media (max-width: 960px) {
    .content-grid { grid-template-columns: 1fr; }
}

/* ===== FORM CARD ===== */
.form-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
    height: fit-content;
}

.form-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid #f0f6f0;
    background: #fafcfa;
}

.form-title { font-size: 1rem; font-weight: 700; color: #1a2e1a; margin: 0 0 4px; }
.form-desc  { font-size: 0.82rem; color: #64748b; margin: 0; }

/* Form Elements */
.form-group { padding: 20px 26px 0; }
.form-group:last-of-type { padding-bottom: 0; }

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #1a2e1a;
    margin-bottom: 6px;
}

.required { color: #dc2626; }

.optional-tag {
    font-size: 0.72rem;
    font-weight: 500;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 8px;
}

.form-hint {
    font-size: 0.78rem;
    color: #94a3b8;
    margin: 0 0 10px;
    line-height: 1.5;
}

.form-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1.5px solid #e8f0e8;
    border-radius: 12px;
    font-size: 0.88rem;
    font-family: 'Poppins', sans-serif;
    color: #1a2e1a;
    resize: vertical;
    transition: border-color 0.2s;
    background: #fafcfa;
    box-sizing: border-box;
}
.form-textarea:focus {
    outline: none;
    border-color: #22a322;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(34,163,34,0.08);
}
.form-textarea.is-error { border-color: #dc2626; }

.char-counter {
    text-align: right;
    font-size: 0.73rem;
    color: #94a3b8;
    margin-top: 4px;
}

.field-error { font-size: 0.78rem; color: #dc2626; margin: 5px 0 0; }

/* ===== CHECKLIST ===== */
.checklist-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
@media (max-width: 480px) { .checklist-grid { grid-template-columns: 1fr; } }

.check-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    border: 1.5px solid #e8f0e8;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 0.84rem;
    color: #374151;
    background: #fafcfa;
}
.check-item:hover { border-color: #22a322; background: #f0fdf4; }

.check-input { display: none; }

.check-box {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 5px;
    flex-shrink: 0;
    margin-top: 1px;
    transition: all 0.15s;
    position: relative;
}
.check-input:checked + .check-box {
    background: #22a322;
    border-color: #22a322;
}
.check-input:checked + .check-box::after {
    content: '';
    position: absolute;
    left: 4px; top: 1px;
    width: 6px; height: 10px;
    border: 2px solid #fff;
    border-top: none; border-left: none;
    transform: rotate(45deg);
}

.check-icon-wrap {
    color: #64748b;
    flex-shrink: 0;
    margin-top: 2px;
}
.check-input:checked ~ .check-icon-wrap { color: #22a322; }

.check-declaration {
    padding: 14px 16px;
    background: #f0fdf4;
    border-color: #bbf7d0;
    font-size: 0.83rem;
    line-height: 1.5;
}

/* ===== UPLOAD AREA ===== */
.upload-area {
    position: relative;
    border: 2px dashed #c8e6c8;
    border-radius: 14px;
    background: #fafcfa;
    transition: all 0.2s;
    overflow: hidden;
    cursor: pointer;
}
.upload-area:hover { border-color: #22a322; background: #f0fdf4; }

.upload-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    z-index: 2;
}

.upload-placeholder { padding: 30px 20px; text-align: center; }

.upload-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px; height: 52px;
    background: #f0f6f0;
    border-radius: 14px;
    margin: 0 auto 10px;
    color: #5a8a5a;
}
.upload-text { font-size: 0.88rem; font-weight: 500; color: #374151; margin: 0 0 4px; }
.upload-hint { font-size: 0.76rem; color: #94a3b8; margin: 0; }

.photo-preview { position: relative; padding: 10px; }
.photo-preview img {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}
.remove-photo {
    position: absolute;
    top: 18px; right: 18px;
    width: 28px; height: 28px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 26px 26px;
    margin-top: 20px;
    border-top: 1px solid #f0f6f0;
}

.btn-cancel {
    padding: 11px 22px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    font-size: 0.88rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-cancel:hover { border-color: #94a3b8; color: #374151; text-decoration: none; }

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #1a6b1a, #2d9e2d);
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 11px 24px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(26,107,26,0.25);
}
.btn-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(26,107,26,0.35);
}
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

.info-card, .tips-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    height: fit-content;
}

.info-card-header, .tips-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 18px;
    background: #f8faf8;
    border-bottom: 1px solid #e8f0e8;
    font-size: 0.82rem;
    font-weight: 600;
    color: #1a2e1a;
}
.info-card-header svg, .tips-header svg { color: #22a322; flex-shrink: 0; }

.info-card-body { padding: 16px 18px; }

.equipment-photo {
    width: 100%;
    height: 160px;
    object-fit: contain;
    object-position: center center;
    border-radius: 12px;
    margin-bottom: 14px;
    display: block;
    background: #f0f6f0;
    padding: 8px;
}

.equipment-photo-placeholder {
    width: 100%;
    height: auto;
    min-height: 80px;
    background: #f0f6f0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #86a886;
    margin-bottom: 14px;
    padding: 20px 0;
}

.equipment-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a2e1a;
    margin-bottom: 6px;
}

.equipment-category {
    font-size: 0.70rem;
    color: #22a322;
    background: #f0fdf0;
    display: inline-block;
    padding: 3px 12px;
    border-radius: 10px;
    margin-bottom: 16px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.04em;
}

.info-rows { border-top: 1px solid #f0f6f0; padding-top: 12px; }
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f8faf8;
    font-size: 0.78rem;
}
.info-key { color: #64748b; }
.info-val { font-weight: 600; color: #1a2e1a; }
.text-red  { color: #dc2626; }

.pill-overdue, .pill-ok {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.70rem;
    font-weight: 600;
}
.pill-overdue { background: #fef2f2; color: #dc2626; }
.pill-ok      { background: #f0fdf4; color: #15803d; }

/* Tips */
.tips-list {
    list-style: none;
    padding: 14px 18px 16px;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.tips-list li {
    font-size: 0.78rem;
    color: #374151;
    padding-left: 20px;
    position: relative;
    line-height: 1.6;
}
.tips-list li::before {
    content: '→';
    position: absolute;
    left: 0;
    color: #22a322;
    font-weight: 600;
}
</style>

<script>
// Character counter
const textarea = document.getElementById('condition_notes');
const counter  = document.getElementById('charCount');
if (textarea) {
    textarea.addEventListener('input', () => {
        counter.textContent = textarea.value.length;
    });
}

// Photo preview
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('uploadPlaceholder').style.display = 'none';
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePhoto() {
    document.getElementById('photo').value = '';
    document.getElementById('previewImg').src = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('photoPreview').style.display = 'none';
}

// Submit dengan loading state
document.getElementById('returnForm')?.addEventListener('submit', function(e) {
    const decl = document.getElementById('declaration');
    if (!decl.checked) {
        e.preventDefault();
        alert('Mohon centang pernyataan kebenaran informasi terlebih dahulu.');
        return;
    }
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Mengirim...`;
});
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection