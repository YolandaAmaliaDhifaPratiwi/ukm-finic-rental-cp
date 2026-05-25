{{-- resources/views/member/returns/show.blade.php --}}
@extends('layouts.member')

@section('title', 'Detail Status Pengembalian')

@section('content')
<div class="return-show-page">

    <div class="breadcrumb">
        <a href="{{ route('member.returns.index') }}" style="text-decoration:none;">Pengembalian Alat</a>
        <span class="bc-sep">›</span>
        <span>Detail Pengembalian</span>
    </div>

    {{-- Timeline Status --}}
    <div class="timeline-card">
        <h2 class="timeline-title">Status Pengembalian</h2>
        <div class="timeline">
            {{-- Step 1: Diajukan --}}
            <div class="timeline-step step-done">
                <div class="step-circle">✓</div>
                <div class="step-content">
                    <div class="step-label">Pengembalian Diajukan</div>
                    <div class="step-time">{{ $return->returned_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </div>

            {{-- Connector --}}
            <div class="timeline-line {{ in_array($return->status, ['confirmed','rejected']) ? 'line-done' : '' }}"></div>

            {{-- Step 2: Proses Admin --}}
            <div class="timeline-step {{ $return->status === 'pending' ? 'step-active' : 'step-done' }}
                                       {{ $return->status === 'rejected' ? 'step-rejected' : '' }}">
                <div class="step-circle">
                    @if($return->status === 'pending')
                        <span class="step-pulse"></span>
                    @elseif($return->status === 'confirmed') ✓
                    @else ✕ @endif
                </div>
                <div class="step-content">
                    <div class="step-label">
                        @if($return->status === 'pending') Menunggu Konfirmasi Admin
                        @elseif($return->status === 'confirmed') Dikonfirmasi oleh Admin
                        @else Ditolak oleh Admin @endif
                    </div>
                    @if($return->confirmed_at)
                        <div class="step-time">{{ $return->confirmed_at->format('d M Y, H:i') }} WIB</div>
                    @endif
                    @if($return->admin_notes)
                        <div class="step-note">💬 Catatan Admin: {{ $return->admin_notes }}</div>
                    @endif
                </div>
            </div>

            {{-- Connector --}}
            <div class="timeline-line {{ $return->status === 'confirmed' ? 'line-done' : '' }}"></div>

            {{-- Step 3: Selesai --}}
            <div class="timeline-step {{ $return->status === 'confirmed' ? 'step-done' : 'step-inactive' }}">
                <div class="step-circle">{{ $return->status === 'confirmed' ? '✓' : '○' }}</div>
                <div class="step-content">
                    <div class="step-label">Pengembalian Selesai</div>
                    @if($return->status === 'confirmed')
                        <div class="step-time">Peralatan telah berhasil dikembalikan!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-grid">
        {{-- Info Alat --}}
        <div class="info-card">
            <div class="info-card-header"><span>🎞</span> Informasi Alat</div>
            <div class="info-card-body">
                <div class="eq-name">{{ $return->borrowing->equipment->name }}</div>
                @if($return->borrowing->equipment->category ?? false)
                    <div class="eq-category">
                        @if(strtolower($return->borrowing->equipment->category) === 'camera')
                            KAMERA
                        @elseif(strtolower($return->borrowing->equipment->category) === 'lens')
                            LENSA
                        @elseif(strtolower($return->borrowing->equipment->category) === 'tripod')
                            TRIPOD
                        @elseif(strtolower($return->borrowing->equipment->category) === 'lighting')
                            LAMPU
                        @else
                            {{ strtoupper($return->borrowing->equipment->category) }}
                        @endif
                    </div>
                @endif
                <div class="info-rows" style="margin-top:12px;">
                    <div class="info-row">
                        <span class="info-key">Tanggal Pinjam</span>
                        <span class="info-val">{{ \Carbon\Carbon::parse($return->borrowing->borrow_date)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tanggal Tenggat</span>
                        <span class="info-val">{{ \Carbon\Carbon::parse($return->borrowing->return_date)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Dikembalikan</span>
                        <span class="info-val">{{ $return->returned_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kondisi --}}
        <div class="info-card">
            <div class="info-card-header"><span>📝</span> Laporan Kondisi Anda</div>
            <div class="info-card-body">
                <div class="condition-text">{{ $return->condition_notes }}</div>
                @if($return->photo)
                    <img src="{{ asset('storage/'.$return->photo) }}" alt="Foto" class="condition-photo">
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('member.returns.index') }}" class="btn-back">Kembali</a>

</div>

<style>
.return-show-page {
    max-width:900px; margin:0 auto;
    padding:28px 20px 60px;
    font-family:'Poppins',sans-serif;
}
.breadcrumb { display:flex; align-items:center; gap:8px; font-size:0.82rem; color:#64748b; margin-bottom:20px; }
.breadcrumb a { color:#22a322; text-decoration:none; font-weight:500; }
.bc-sep { color:#cbd5e1; }

.timeline-card {
    background:#fff; border-radius:18px;
    border:1px solid #e8f0e8;
    padding:24px 28px; margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.04);
}
.timeline-title { font-size:1rem; font-weight:700; color:#1a2e1a; margin:0 0 24px; }

.timeline { display:flex; flex-direction:column; }

.timeline-step { display:flex; align-items:flex-start; gap:16px; }

.step-circle {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:0.9rem; font-weight:700; flex-shrink:0;
    position:relative;
}
.step-done     .step-circle { background:#22a322; color:#fff; }
.step-active   .step-circle { background:#fef9c3; border:2px solid #eab308; color:#a16207; }
.step-rejected .step-circle { background:#fef2f2; border:2px solid #fecaca; color:#dc2626; }
.step-inactive .step-circle { background:#f1f5f9; border:2px solid #e2e8f0; color:#94a3b8; }

.step-pulse {
    width:10px; height:10px; background:#eab308;
    border-radius:50%; animation:pulse 1.5s infinite;
}

.step-content { padding-top:6px; }
.step-label { font-size:0.88rem; font-weight:600; color:#1a2e1a; }
.step-time  { font-size:0.76rem; color:#94a3b8; margin-top:2px; }
.step-note  {
    font-size:0.8rem; color:#374151; background:#fafcfa;
    padding:8px 12px; border-radius:8px; margin-top:6px;
    border-left:3px solid #e2e8f0;
}

.timeline-line {
    width:2px; height:32px;
    background:#f0f0f0; margin:8px 0 8px 17px;
    border-radius:2px;
}
.line-done { background:linear-gradient(#22a322,#22a322); }

.detail-grid {
    display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;
}
@media(max-width:640px){ .detail-grid{ grid-template-columns:1fr; } }

.info-card { background:#fff; border-radius:16px; border:1px solid #e8f0e8; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); }
.info-card-header {
    display:flex; align-items:center; gap:8px;
    padding:14px 18px; background:#fafcfa;
    border-bottom:1px solid #f0f6f0;
    font-size:0.86rem; font-weight:600; color:#1a2e1a;
}
.info-card-body { padding:18px; }

.eq-name { font-size:1rem; font-weight:700; color:#1a2e1a; margin-bottom:4px; }
.eq-category {
    font-size:0.73rem; color:#22a322; background:#f0fdf0;
    display:inline-block; padding:2px 10px; border-radius:10px; font-weight:600;
}
.info-rows { border-top:1px solid #f0f6f0; padding-top:10px; }
.info-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #f8faf8; font-size:0.82rem; }
.info-key { color:#64748b; }
.info-val { font-weight:600; color:#1a2e1a; }

.condition-text {
    font-size:0.87rem; color:#374151; line-height:1.7;
    background:#f8fafc; padding:14px; border-radius:10px;
    border-left:3px solid #22a322;
}
.condition-photo { width:100%; border-radius:10px; margin-top:12px; border:1px solid #e8f0e8; }

.btn-back { font-size:0.84rem; color:#64748b; text-decoration:none; font-weight:500; }
.btn-back:hover { color:#1a6b1a; }

@keyframes pulse { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.4);opacity:0.6;} }
</style>
@endsection