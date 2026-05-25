{{-- resources/views/admin/borrowing/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Detail Peminjaman — '.$borrowing->transaction_code)

@section('content')

{{-- Breadcrumb --}}
<div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--gray-500);margin-bottom:20px;">
    <a href="{{ route('admin.borrowing.index') }}" style="color:var(--primary);text-decoration:none;font-weight:500;">Permintaan Peminjaman</a>
    <span>›</span>
    <span>{{ $borrowing->transaction_code }}</span>
</div>

{{-- Page Header --}}
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0 0 4px;">
            Detail Peminjaman
        </h1>
        <p style="color:var(--gray-500);font-size:14px;margin:0;">
            {{ $borrowing->transaction_code }} &nbsp;·&nbsp; Diajukan {{ $borrowing->created_at->diffForHumans() }}
        </p>
    </div>

    {{-- Status Badge besar --}}
    <div style="display:flex;align-items:center;gap:10px;">
        @php
            $statusStyle = match($borrowing->status) {
                'pending'  => 'background:#fff7ed;color:#c2410c;border:1.5px solid #fed7aa;',
                'approved' => 'background:#f0fdf4;color:#15803d;border:1.5px solid #bbf7d0;',
                'rejected' => 'background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;',
                'returned' => 'background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;',
                'overdue'  => 'background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3;',
                default    => 'background:#f3f4f6;color:#374151;border:1.5px solid #e5e7eb;',
            };
            $statusLabel = match($borrowing->status) {
                'pending'  => '⏳ Menunggu',
                'approved' => '✅ Disetujui',
                'rejected' => '❌ Ditolak',
                'returned' => '↩ Dikembalikan',
                'overdue'  => '⚠ Terlambat',
                default    => '• '.$borrowing->status,
            };
        @endphp
        <span style="padding:8px 20px;border-radius:20px;font-size:14px;font-weight:700;{{ $statusStyle }}">
            {{ $statusLabel }}
        </span>
    </div>
</div>

{{-- ═══ MAIN GRID ═══ --}}
<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

    {{-- ══ LEFT COLUMN ══ --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- MEMBER INFO --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span style="font-size:13px;font-weight:700;color:#374151;">Informasi Anggota</span>
            </div>
            <div style="padding:20px;display:flex;gap:18px;align-items:center;">
                {{-- Avatar --}}
                <div style="flex-shrink:0;">
                    @if($borrowing->user->avatar)
                        <img src="{{ asset('storage/'.$borrowing->user->avatar) }}"
                             style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb;">
                    @else
                        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;border:3px solid #e5e7eb;">
                            {{ strtoupper(substr($borrowing->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div style="flex:1;">
                    <div style="font-size:18px;font-weight:700;color:#111827;margin-bottom:4px;">
                        {{ $borrowing->user->name }}
                    </div>
                    <div style="font-size:13px;color:var(--gray-500);margin-bottom:10px;">
                        {{ $borrowing->user->email }}
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <span style="background:#f3f4f6;color:#374151;padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600;">
                            🎓 {{ $borrowing->user->student_id ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                {{-- Link ke profil member --}}
                <a href="{{ route('admin.users.show', $borrowing->user->id) }}"
                   style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:#f0f9ff;color:#0369a1;border:1px solid #bae6fd;text-decoration:none;"
                   onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Lihat Profil
                </a>
            </div>

            {{-- Member stats kecil --}}
            @php
                $memberTotal  = $borrowing->user->borrowings()->count();
                $memberActive = $borrowing->user->borrowings()->whereIn('status',['approved'])->count();
                $memberDone   = $borrowing->user->borrowings()->where('status','returned')->count();
            @endphp
            <div style="display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--gray-200);">
                <div style="padding:14px 20px;text-align:center;border-right:1px solid var(--gray-200);">
                    <div style="font-size:20px;font-weight:700;color:#111827;">{{ $memberTotal }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">Total Pinjaman</div>
                </div>
                <div style="padding:14px 20px;text-align:center;border-right:1px solid var(--gray-200);">
                    <div style="font-size:20px;font-weight:700;color:#f59e0b;">{{ $memberActive }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">Sedang Aktif</div>
                </div>
                <div style="padding:14px 20px;text-align:center;">
                    <div style="font-size:20px;font-weight:700;color:#059669;">{{ $memberDone }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">Selesai</div>
                </div>
            </div>
        </div>

        {{-- RETURN DATA DARI MEMBER --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="1 4 1 10 7 10"/>
                    <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
                </svg>
                <span style="font-size:13px;font-weight:700;color:#374151;">Pengembalian Mandiri dari Anggota</span>
            </div>

            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                @if($borrowing->returnData)
                    {{-- FOTO --}}
                    @if($borrowing->returnData->photo)
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">
                                Bukti Foto Pengembalian
                            </div>
                            <img src="{{ asset('storage/'.$borrowing->returnData->photo) }}"
                                onclick="openImageModal(this.src)"
                                style="width:100%;max-height:260px;object-fit:cover;border-radius:10px;cursor:pointer;">
                        </div>
                    @endif

                    {{-- CONDITION --}}
                    <div>
                        <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">
                            Catatan Kondisi dari Anggota
                        </div>
                        <div style="background:#f8fafc;border-radius:10px;padding:14px;font-size:14px;color:#374151;border-left:4px solid #3b82f6;">
                            {{ $borrowing->returnData->condition_notes }}
                        </div>
                    </div>

                    {{-- ADMIN NOTES --}}
                    @if($borrowing->returnData->admin_notes)
                    <div>
                        <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">
                            Catatan Admin (Sebelumnya)
                        </div>
                        <div style="background:#fffbeb;border-radius:10px;padding:14px;font-size:14px;color:#92400e;border-left:4px solid #f59e0b;">
                            {{ $borrowing->returnData->admin_notes }}
                        </div>
                    </div>
                    @endif
                @else
                    <div style="text-align:center;color:#9ca3af;font-size:14px;">
                        Anggota belum mengajukan form pengembalian.
                    </div>
                @endif
            </div>
        </div>

        {{-- PURPOSE / DETAIL PEMINJAMAN --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                <span style="font-size:13px;font-weight:700;color:#374151;">Rincian Peminjaman</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">

                {{-- Rental Period --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                    <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                        <div style="font-size:11px;color:var(--gray-500);margin-bottom:4px;text-transform:uppercase;font-weight:600;">Tgl Pinjam</div>
                        <div style="font-size:15px;font-weight:700;color:#111827;">{{ $borrowing->borrow_date->format('d M Y') }}</div>
                        <div style="font-size:11px;color:var(--gray-500);">
                            {{-- Ganti hari ke Indonesia --}}
                            @php
                                $hariPinjam = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
                                echo $hariPinjam[$borrowing->borrow_date->format('l')] ?? $borrowing->borrow_date->format('l');
                            @endphp
                        </div>
                    </div>
                    <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                        <div style="font-size:11px;color:var(--gray-500);margin-bottom:4px;text-transform:uppercase;font-weight:600;">Tgl Kembali</div>
                        <div style="font-size:15px;font-weight:700;color:{{ $borrowing->isOverdue() ? '#dc2626' : '#111827' }};">
                            {{ $borrowing->return_date->format('d M Y') }}
                        </div>
                        <div style="font-size:11px;color:var(--gray-500);">
                            @php
                                $hariKembali = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
                                echo $hariKembali[$borrowing->return_date->format('l')] ?? $borrowing->return_date->format('l');
                            @endphp
                        </div>
                    </div>
                    <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                        <div style="font-size:11px;color:var(--gray-500);margin-bottom:4px;text-transform:uppercase;font-weight:600;">Durasi</div>
                        <div style="font-size:15px;font-weight:700;color:#111827;">{{ $borrowing->duration_days }} Hari</div>
                        <div style="font-size:11px;color:var(--gray-500);">
                            @if($borrowing->isOverdue())
                                <span style="color:#dc2626;font-weight:600;">⚠ Terlambat {{ abs($borrowing->days_remaining) }} hari</span>
                            @elseif($borrowing->status === 'approved')
                                Tersisa {{ $borrowing->days_remaining }} hari lagi
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Purpose --}}
                <div>
                    <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">Tujuan Peminjaman</div>
                    <div style="background:#f8fafc;border-radius:10px;padding:14px;font-size:14px;color:#374151;line-height:1.7;border-left:4px solid #059669;">
                        {{ $borrowing->purpose ?? 'Tidak ada keterangan tujuan.' }}
                    </div>
                </div>

                {{-- Actual return date (jika sudah dikembalikan) --}}
                @if($borrowing->actual_return_date)
                <div style="background:#eff6ff;border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#1d4ed8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.45"/></svg>
                    </div>
                    <div>
                        <div style="font-size:12px;color:#1d4ed8;font-weight:600;">Dikembalikan Pada</div>
                        <div style="font-size:15px;font-weight:700;color:#1e3a8a;">{{ $borrowing->actual_return_date->format('d M Y') }}</div>
                    </div>
                </div>
                @endif

                {{-- Admin Notes --}}
                @if($borrowing->admin_notes)
                <div>
                    <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">Catatan Admin</div>
                    <div style="background:#fffbeb;border-radius:10px;padding:14px;font-size:14px;color:#92400e;line-height:1.7;border-left:4px solid #f59e0b;">
                        💬 {{ $borrowing->admin_notes }}
                    </div>
                </div>
                @endif

                {{-- Final condition (jika sudah returned) --}}
                @if($borrowing->final_condition)
                @php
                    $condStyle = match($borrowing->final_condition) {
                        'excellent'       => 'background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;',
                        'good'            => 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;',
                        'minor_scratches' => 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;',
                        'needs_repair'    => 'background:#fef2f2;color:#dc2626;border:1px solid #fecaca;',
                        default           => 'background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;',
                    };
                    $condLabel = match($borrowing->final_condition) {
                        'excellent'       => '✨ Sangat Baik',
                        'good'            => '👍 Baik',
                        'minor_scratches' => '🔸 Goresan Ringan',
                        'needs_repair'    => '🔧 Perlu Perbaikan',
                        default           => $borrowing->final_condition,
                    };
                @endphp
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;">Kondisi Akhir Alat:</div>
                    <span style="padding:6px 16px;border-radius:20px;font-size:13px;font-weight:700;{{ $condStyle }}">
                        {{ $condLabel }}
                    </span>
                </div>
                @endif

            </div>
        </div>

        {{-- BORROWING TIMELINE --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                <span style="font-size:13px;font-weight:700;color:#374151;">Riwayat Aktivitas (Timeline)</span>
            </div>
            <div style="padding:20px;">
                @php
                    $steps = [
                        ['label' => 'Permintaan Diajukan',    'time' => $borrowing->created_at,          'done' => true],
                        ['label' => 'Ditinjau oleh Admin',         'time' => in_array($borrowing->status,['approved','rejected','returned']) ? $borrowing->updated_at : null, 'done' => in_array($borrowing->status,['approved','rejected','returned'])],
                        ['label' => 'Alat Diambil / Diserahkan', 'time' => $borrowing->status === 'approved' ? $borrowing->updated_at : null, 'done' => in_array($borrowing->status,['approved','returned'])],
                        ['label' => 'Alat Telah Dikembalikan',   'time' => $borrowing->actual_return_date,  'done' => $borrowing->status === 'returned'],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach($steps as $i => $step)
                    <div style="display:flex;gap:14px;align-items:flex-start;">
                        <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;
                                {{ $step['done'] ? 'background:#059669;color:#fff;' : 'background:#f3f4f6;color:#9ca3af;border:2px solid #e5e7eb;' }}">
                                {{ $step['done'] ? '✓' : ($i+1) }}
                            </div>
                            @if(!$loop->last)
                            <div style="width:2px;height:28px;{{ $step['done'] ? 'background:#059669;' : 'background:#e5e7eb;' }}margin:2px 0;"></div>
                            @endif
                        </div>
                        <div style="padding-top:6px;padding-bottom:{{ $loop->last ? '0' : '8px' }};">
                            <div style="font-size:13px;font-weight:600;color:{{ $step['done'] ? '#111827' : '#9ca3af' }};">{{ $step['label'] }}</div>
                            @if($step['time'])
                            <div style="font-size:12px;color:var(--gray-500);margin-top:2px;">
                                {{ is_string($step['time']) ? $step['time'] : $step['time']->format('d M Y, H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══ RIGHT COLUMN ══ --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- EQUIPMENT INFO --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                <span style="font-size:13px;font-weight:700;color:#374151;">Informasi Alat</span>
            </div>
            <div style="padding:20px;">
                {{-- Foto --}}
                <div style="width:100%;height:160px;background:#f3f4f6;border-radius:12px;overflow:hidden;margin-bottom:14px;display:flex;align-items:center;justify-content:center;">
                    @if($borrowing->equipment->image ?? false)
                        <img src="{{ asset('storage/'.$borrowing->equipment->image) }}"
                             style="width:100%;height:100%;object-fit:contain;">
                    @else
                        <svg width="60" height="60" fill="none" stroke="#d1d5db" stroke-width="1" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    @endif
                </div>

                <span class="badge badge-{{ $borrowing->equipment->category }}" style="font-size:12px;margin-bottom:8px;display:inline-block;">
                    {{ ucfirst($borrowing->equipment->category) }}
                </span>
                <div style="font-size:17px;font-weight:700;color:#111827;margin-bottom:12px;">
                    {{ $borrowing->equipment->name }}
                </div>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                        <span style="color:var(--gray-500);">Nomor Seri</span>
                        <span style="font-weight:600;">{{ $borrowing->equipment->serial_number ?? 'UKM-FN-'.str_pad($borrowing->equipment->id,7,'0',STR_PAD_LEFT) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                        <span style="color:var(--gray-500);">Kondisi Alat</span>
                        <span style="font-weight:600;">
                            @php
                                $condMap = ['excellent'=>'Sangat Baik','good'=>'Baik','Good'=>'Baik','minor_scratches'=>'Goresan Ringan','needs_repair'=>'Perlu Perbaikan','fair'=>'Cukup Baik'];
                                echo $condMap[$borrowing->equipment->condition ?? ''] ?? ucfirst($borrowing->equipment->condition ?? '-');
                            @endphp
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;">
                        <span style="color:var(--gray-500);">Status Saat Ini</span>
                        <span style="font-weight:600;">
                            @php
                                $statusMap = ['available'=>'Tersedia','Available'=>'Tersedia','borrowed'=>'Dipinjam','Borrowed'=>'Dipinjam','maintenance'=>'Pemeliharaan','Maintenance'=>'Pemeliharaan'];
                                echo $statusMap[$borrowing->equipment->status ?? ''] ?? ucfirst($borrowing->equipment->status ?? '-');
                            @endphp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTION CARD --}}
        @if($borrowing->status === 'pending' || $borrowing->status === 'approved')
        <div class="card" style="padding:0;overflow:hidden;border:2px solid #e5e7eb;">
            <div style="padding:16px 20px;background:linear-gradient(135deg,#111827,#1f2937);display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/></svg>
                <span style="font-size:13px;font-weight:700;color:#fff;">Tindakan Admin</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:10px;">

                @if($borrowing->status === 'pending')
                {{-- APPROVE --}}
                <form method="POST" action="{{ route('admin.borrowing.approve', $borrowing) }}">
                    @csrf
                    <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:none;background:linear-gradient(135deg,#059669,#10b981);color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 2px 8px rgba(5,150,105,.3);"
                            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 16px rgba(5,150,105,.4)'"
                            onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(5,150,105,.3)'"
                            onclick="return confirm('Setujui permintaan peminjaman dari {{ $borrowing->user->name }}?')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg>
                        Setujui Permintaan
                    </button>
                </form>

                {{-- REJECT --}}
                <button onclick="openModal('rejectModalDetail')"
                        style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:1.5px solid #fecaca;background:#fff;color:#dc2626;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;"
                        onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='#fff'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Tolak Permintaan
                </button>

                @elseif($borrowing->status === 'approved')
                {{-- MARK RETURNED --}}
                <button onclick="openModal('returnModalDetail')"
                        style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:none;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 2px 8px rgba(29,78,216,.3);"
                        onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.45"/></svg>
                    Konfirmasi Selesai / Kembali
                </button>
                @endif

                <a href="{{ route('admin.borrowing.index') }}"
                   style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:10px;border:1px solid var(--gray-200);background:#fff;color:#6b7280;font-size:13px;font-weight:600;text-decoration:none;transition:background .15s;"
                   onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                    ← Kembali ke Daftar
                </a>

            </div>
        </div>
        @else
        <a href="{{ route('admin.borrowing.index') }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:1px solid var(--gray-200);background:#fff;color:#6b7280;font-size:13px;font-weight:600;text-decoration:none;"
           onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
            ← Kembali ke Daftar Peminjaman
        </a>
        @endif

        {{-- APPROVED BY --}}
        @if($borrowing->approver)
        <div class="card" style="padding:16px 20px;">
            <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">Diproses oleh</div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#059669;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;">
                    {{ strtoupper(substr($borrowing->approver->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:#111827;">{{ $borrowing->approver->name }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">Admin • {{ $borrowing->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ══ MODAL REJECT ══ --}}
<div class="modal-overlay" id="rejectModalDetail">
    <div class="modal" style="width:420px;">
        <div class="modal-header">
            <h3>Tolak Permintaan Peminjaman</h3>
            <button class="modal-close" onclick="closeModal('rejectModalDetail')">✕</button>
        </div>
        <p class="text-sm text-gray mb-16">
            Anda akan menolak transaksi <strong>{{ $borrowing->transaction_code }}</strong> —
            alat <strong>{{ $borrowing->equipment->name }}</strong> yang diajukan oleh
            <strong>{{ $borrowing->user->name }}</strong>.
        </p>
        <form method="POST" action="{{ route('admin.borrowing.reject', $borrowing) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan (opsional)</label>
                <textarea name="admin_notes" class="form-control" rows="4"
                          placeholder="Jelaskan alasan mengapa permintaan ini ditolak..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('rejectModalDetail')">Batal</button>
                <button type="submit" class="btn btn-danger">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL RETURN ══ --}}
<div class="modal-overlay" id="returnModalDetail">
    <div class="modal" style="width:420px;">
        <div class="modal-header">
            <h3>Konfirmasi Pengembalian Alat</h3>
            <button class="modal-close" onclick="closeModal('returnModalDetail')">✕</button>
        </div>
        <p class="text-sm text-gray mb-16">
            Konfirmasi pengembalian alat <strong>{{ $borrowing->equipment->name }}</strong>
            dari <strong>{{ $borrowing->user->name }}</strong>.
        </p>
        <form method="POST" action="{{ route('admin.borrowing.return', $borrowing) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Kondisi Akhir Alat</label>
                <select name="final_condition" class="form-control" required>
                    <option value="excellent">✨ Sangat Baik — Tidak ada kerusakan</option>
                    <option value="good" selected>👍 Baik — Lecet pemakaian wajar</option>
                    <option value="minor_scratches">🔸 Goresan Ringan</option>
                    <option value="needs_repair">🔧 Perlu Perbaikan</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Dikembalikan</label>
                <input type="date" name="actual_return_date" class="form-control"
                       value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('returnModalDetail')">Batal</button>
                <button type="submit" class="btn btn-primary">✓ Konfirmasi Kembali</button>
            </div>
        </form>
    </div>
</div>

{{-- IMAGE MODAL --}}
<div id="imageModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.8);justify-content:center;align-items:center;z-index:9999;">
    
    <span onclick="closeImageModal()"
          style="position:absolute;top:20px;right:30px;font-size:30px;color:#fff;cursor:pointer;">
        ✕
    </span>

    <img id="modalImage"
         style="max-width:90%;max-height:90%;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,0.5);">
</div>

<script>
function openImageModal(src) {
    document.getElementById('imageModal').style.display = 'flex';
    document.getElementById('modalImage').src = src;
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>

@endsection