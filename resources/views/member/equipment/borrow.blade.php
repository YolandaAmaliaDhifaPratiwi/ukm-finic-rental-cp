@extends('layouts.member')
@section('title', 'Pinjam Alat')

@section('content')
{{-- Pengaturan margin-left digeser agar sejajar sempurna dengan judul di atas --}}
<div class="borrow-page" style="max-width: 100%; padding: 0; margin: 0;">
    
    {{-- Tautan kembali disesuaikan posisi padding agar tegak lurus --}}
    <div style="margin-bottom: 24px; padding-left: 4px;">
        <a href="{{ route('member.equipment.index') }}" class="flex-center gap-8 text-gray text-sm" style="text-decoration:none; display: inline-flex; align-items: center; color: var(--gray-500);">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><polyline points="15,18 9,12 15,6"/></svg>
            Kembali ke Daftar Alat
        </a>
    </div>

    {{-- Lebar card dibuat maksimal dan sejajar penuh --}}
    <div class="card" style="background: #fff; border-radius: 12px; border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 32px; margin-bottom: 32px; box-sizing: border-box; width: 100%;">
        
        {{-- EQUIPMENT PREVIEW --}}
        <div class="equip-preview" style="display: flex; gap: 24px; align-items: center; padding-bottom: 24px; border-bottom: 1px solid var(--gray-100); margin-bottom: 28px; width: 100%;">
            <div style="width:90px; height:90px; background:var(--gray-100); border-radius:10px; overflow:hidden; flex-shrink:0; display: flex; align-items: center; justify-content: center; border: 1px solid var(--gray-200);">
                @if($equipment->image)
                    <img src="{{ asset('storage/'.$equipment->image) }}" style="width:100%; height:100%; object-fit:contain;">
                @else
                    <div style="font-size: 24px;">📷</div>
                @endif
            </div>
            <div style="flex: 1;">
                <div style="display:flex; gap:8px; margin-bottom:8px; flex-wrap: wrap;">
                    {{-- CATEGORY --}}
                    <span class="badge badge-{{ strtolower($equipment->category) }}" style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                        @if(strtolower($equipment->category) === 'camera')
                            KAMERA
                        @elseif(strtolower($equipment->category) === 'lens')
                            LENSA
                        @elseif(strtolower($equipment->category) === 'tripod')
                            TRIPOD
                        @elseif(strtolower($equipment->category) === 'lighting')
                            LAMPU
                        @else
                            {{ strtoupper($equipment->category) }}
                        @endif
                    </span>

                    {{-- CONDITION --}}
                    <span class="badge" style="background:var(--gray-100); color:var(--gray-700); font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                        Kondisi: 
                        @if(strtolower($equipment->condition) === 'excellent')
                            Sangat Baik
                        @elseif(strtolower($equipment->condition) === 'good')
                            Baik
                        @elseif(strtolower($equipment->condition) === 'fair')
                            Cukup
                        @else
                            {{ ucfirst($equipment->condition) }}
                        @endif
                    </span>
                </div>
                <h2 style="font-size:24px; font-weight: 700; margin: 0 0 6px 0; color: var(--text-main);">{{ $equipment->name }}</h2>
                <div class="text-sm text-gray" style="color: var(--gray-500); display: flex; align-items: center; gap: 4px;">
                    <span>⊙</span> ID Peralatan: {{ $equipment->serial_number ?? 'UKM-FN-' . str_pad($equipment->id, 7, '0', STR_PAD_LEFT) }}
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="background: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('member.equipment.borrow', $equipment) }}" style="margin: 0; padding: 0;">
            @csrf

            {{-- RENTAL PERIOD --}}
            <div style="margin-bottom: 32px;">
                <div class="section-title mb-16" style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 16px; margin-bottom: 16px; color: var(--text-main);">
                    <svg width="18" height="18" fill="none" stroke="var(--blue)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                    Periode Peminjaman
                </div>
                <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; width: 100%; box-sizing: border-box;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13.5px;">Tanggal Pinjam</label>
                        <div class="input-wrap" style="position: relative; display: flex; align-items: center;">
                            <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 14px; color: var(--gray-400); pointer-events: none;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <input type="date" id="borrow_date" name="borrow_date" class="form-control with-icon"
                                value="{{ old('borrow_date', date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required
                                style="width: 100%; padding: 11px 14px 11px 40px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; box-sizing: border-box; outline: none; font-family: inherit;">
                        </div>
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13.5px;">Tanggal Kembali</label>
                        <div class="input-wrap" style="position: relative; display: flex; align-items: center;">
                            <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 14px; color: var(--gray-400); pointer-events: none;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <input type="date" id="return_date" name="return_date" class="form-control with-icon"
                                value="{{ old('return_date', date('Y-m-d', strtotime('+3 days'))) }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                style="width: 100%; padding: 11px 14px 11px 40px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; box-sizing: border-box; outline: none; font-family: inherit;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- USAGE DETAILS --}}
            <div style="margin-bottom: 32px;">
                <div class="section-title mb-16" style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 16px; margin-bottom: 16px; color: var(--text-main);">
                    <svg width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Detail Penggunaan
                </div>
                <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13.5px;">Alasan Peminjaman Alat</label>
                    <textarea name="purpose" required maxlength="1000" 
                            placeholder="Tuliskan tujuan penggunaan alat secara jelas..." 
                            style="width: 100%; padding: 12px 16px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; box-sizing: border-box; resize: vertical; min-height: 100px; font-family:inherit; outline:none; background:var(--bg-card); color:var(--text-main);"
                            onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'" 
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('purpose') }}</textarea>
                    <small style="color: var(--gray-400); font-size: 12px; margin-top: 4px; display: block;">
                        Jelaskan secara singkat kegunaan alat ini demi kelancaran proses verifikasi admin.
                    </small>
                </div>
            </div>

            {{-- TERMS NOTICE --}}
            <div class="terms-notice" style="display: flex; gap: 12px; align-items: flex-start; background: #fff5f5; border: 1px solid #fee2e2; border-radius: 10px; padding: 16px; margin-bottom: 32px; color: #991b1b; font-size: 13.5px; line-height: 1.5; box-sizing: border-box; width: 100%;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Dengan mengirimkan permohonan ini, <a href="#" style="color:#b91c1c; text-decoration:underline; font-weight: 600;">Anda menyetujui Ketentuan Penggunaan Peralatan</a>. Segala kerusakan atau kehilangan yang terjadi selama masa peminjaman menjadi tanggung jawab peminjam.</span>
            </div>

            {{-- FORM BUTTONS --}}
            <div style="display:flex; justify-content:flex-end; gap:12px; border-top: 1px solid var(--gray-100); padding-top: 24px;">
                <a href="{{ route('member.equipment.index') }}" class="btn btn-outline" style="padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">Ajukan Peminjaman Alat →</button>
            </div>
        </form>
    </div>

    {{-- INFO PERKS --}}
    <div class="info-perks" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px; width: 100%; box-sizing: border-box;">
        <div class="perk" style="background: #fff; border: 1px solid var(--gray-200); border-radius: 12px; padding: 24px; text-align: center; box-sizing: border-box;">
            <svg width="24" height="24" fill="none" stroke="var(--gray-500)" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block;"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
            <h5 style="font-size: 15px; font-weight: 700; margin: 0 0 8px 0; color: var(--text-main);">Persetujuan Cepat</h5>
            <p style="font-size: 13px; color: var(--gray-500); margin: 0; line-height: 1.4;">Admin biasanya meninjau pengajuan dalam waktu kurang dari 24 jam.</p>
        </div>
        <div class="perk" style="background: #fff; border: 1px solid var(--gray-200); border-radius: 12px; padding: 24px; text-align: center; box-sizing: border-box;">
            <svg width="24" height="24" fill="none" stroke="var(--gray-500)" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>
            <h5 style="font-size: 15px; font-weight: 700; margin: 0 0 8px 0; color: var(--text-main);">Pengecekan Kondisi</h5>
            <p style="font-size: 13px; color: var(--gray-500); margin: 0; line-height: 1.4;">Kami memverifikasi status kelayakan alat sebelum dan sesudah serah terima dilakukan.</p>
        </div>
        <div class="perk" style="background: #fff; border: 1px solid var(--gray-200); border-radius: 12px; padding: 24px; text-align: center; box-sizing: border-box;">
            <svg width="24" height="24" fill="none" stroke="var(--gray-500)" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <h5 style="font-size: 15px; font-weight: 700; margin: 0 0 8px 0; color: var(--text-main);">Pengambilan Fleksibel</h5>
            <p style="font-size: 13px; color: var(--gray-500); margin: 0; line-height: 1.4;">Alat dapat diambil langsung di Gedung UKM Kampus.</p>
        </div>
    </div>
</div>
@endsection