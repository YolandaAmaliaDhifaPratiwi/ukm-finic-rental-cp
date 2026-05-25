@extends('layouts.admin')
@section('title', 'Anggota')

@section('content')

{{-- HEADER --}}
<div class="flex-between mb-24" style="margin-bottom: 28px;">
    <div>
        <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin-bottom: 6px; letter-spacing: -0.02em;">Manajemen Anggota</h1>
        <p style="font-size: 14px; color: #64748b;">Kelola seluruh akun member UKM Finic.</p>
    </div>
</div>

{{-- TOP BAR (Stats dan Filter Berjejer ke Samping) --}}
<div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 24px; align-items: start;">
    
    {{-- STATS (Total Members) --}}
    <div class="card" style="padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 16px; height: 74px; box-sizing: border-box; border: 1px solid #e2e8f0;">
        <div style="width: 40px; height: 40px; background: rgba(249, 115, 22, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--orange); flex-shrink: 0;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
        </div>
        <div>
            <div style="font-size: 22px; font-weight: 800; color: #1e293b; line-height: 1.2;">{{ $totalUsers }}</div>
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">Total Anggota</div>
        </div>
    </div>

    {{-- FILTER & SEARCH (Berada di samping Stats) --}}
    <div class="card" style="padding: 16px 20px; border-radius: 12px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: 74px; box-sizing: border-box; display: flex; align-items: center; border: 1px solid #e2e8f0;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="width: 100%;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <div class="search-bar" style="flex: 1; position: relative; display: flex; align-items: center;">
                    <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 14px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau NIM..." value="{{ request('search') }}" style="width: 100%; padding-left: 40px; height: 40px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px; box-sizing: border-box;">
                </div>
                <select name="sort" class="form-control" style="width: 160px; height: 40px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px; padding: 0 12px; background: #fff; box-sizing: border-box;">
                    <option value="">Terbaru</option>
                    <option value="borrowings" {{ request('sort')==='borrowings'?'selected':'' }}>Total Peminjaman</option>
                </select>
                <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 20px; font-weight: 600; border-radius: 8px; cursor: pointer;">Filter</button>
                @if(request()->hasAny(['search','membership','sort']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="height: 40px; display: inline-flex; align-items: center; padding: 0 16px; border-radius: 8px; text-decoration: none; font-size: 14px; box-sizing: border-box;">Reset</a>
                @endif
            </div>
        </form>
    </div>

</div>

{{-- TABLE (Di paling bawah, Full Width & Elegan) --}}
<div class="card" style="padding: 0; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0;">
    <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
        <span style="font-weight: 700; font-size: 15px; color: #1e293b; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #64748b;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Daftar Anggota
        </span>
        <span style="color: #64748b; font-size: 13px; font-weight: 500;">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} hasil</span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 24px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">Anggota</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">NIM / ID</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">Total Pinjam</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">Bergabung</th>
                    <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    
                    {{-- MEMBER --}}
                    <td style="padding: 14px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/profile_photos/' . $user->avatar) }}"
                                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--orange);">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--orange); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: #fff; flex-shrink: 0; box-shadow: 0 2px 4px rgba(249,115,22,0.2);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600; font-size: 14px; color: #1e293b;">{{ $user->name }}</div>
                                <div style="font-size: 12px; color: #64748b;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- STUDENT ID --}}
                    <td style="padding: 14px 16px; font-size: 13px; color: #334155; font-variant-numeric: tabular-nums;">
                        {{ $user->student_id ?? '—' }}
                    </td>

                    {{-- BORROWINGS --}}
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="font-size: 14px; font-weight: 700; color: #1e293b; background: #f1f5f9; padding: 4px 10px; border-radius: 20px;">{{ $user->borrowings_count }}</span>
                    </td>

                    {{-- JOINED --}}
                    <td style="padding: 14px 16px; font-size: 13px; color: #64748b;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>

                    {{-- ACTION --}}
                    <td style="padding: 14px 24px;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.users.show', $user) }}"
                               style="padding: 6px 14px; background: #eff6ff; color: #2563eb; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                Detail
                            </a>
                            <button onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->student_id }}')"
                                    style="padding: 6px 14px; background: #f0fdf4; color: #16a34a; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                Edit
                            </button>
                            <button onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    style="padding: 6px 14px; background: #fef2f2; color: #dc2626; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 64px 24px; text-align: center; color: #94a3b8;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 16px; display: block; color: #cbd5e1;">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                        <span style="font-size: 14px; font-weight: 500;">Tidak ada anggota ditemukan.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; background: #fafafa;">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- MODAL EDIT USER --}}
<div id="editUserModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index:9999; align-items:center; justify-content:center; transition: 0.2s;">
    <div style="background:#fff; border-radius:16px; padding:32px; width:100%; max-width:480px; margin:0 16px; position:relative; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <button onclick="closeEditUser()" style="position:absolute; top:20px; right:20px; background:none; border:none; font-size:20px; cursor:pointer; color:#94a3b8;">✕</button>
        <h3 style="margin-bottom:24px; font-size:18px; font-weight:700; color:#1e293b;">Edit Informasi Anggota</h3>
        <form id="editUserForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #475569;">Nama Lengkap</label>
                <input type="text" name="name" id="eu-name" class="form-control" style="width:100%; height:40px; border-radius:8px; border:1px solid #e2e8f0; padding:0 12px; box-sizing: border-box;" required>
            </div>
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #475569;">Email</label>
                <input type="email" name="email" id="eu-email" class="form-control" style="width:100%; height:40px; border-radius:8px; border:1px solid #e2e8f0; padding:0 12px; box-sizing: border-box;" required>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #475569;">NIM (Student ID)</label>
                <input type="text" name="student_id" id="eu-student" class="form-control" style="width:100%; height:40px; border-radius:8px; border:1px solid #e2e8f0; padding:0 12px; box-sizing: border-box;">
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-primary" style="flex:1; height:40px; font-weight:600; border-radius:8px; cursor: pointer;">Simpan Perubahan</button>
                <button type="button" onclick="closeEditUser()" class="btn btn-outline" style="flex:1; height:40px; font-weight:600; border-radius:8px; cursor: pointer;">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="deleteUserModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px; width:100%; max-width:400px; margin:0 16px; text-align:center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="width:56px; height:56px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <svg width="24" height="24" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6M9 6V4h6v2"/></svg>
        </div>
        <h3 style="font-size:18px; font-weight:700; margin-bottom:8px; color:#1e293b;">Hapus Anggota?</h3>
        <p style="font-size:14px; color:#64748b; margin-bottom:24px; line-height:1.5;">Data <strong id="delete-name" style="color:#1e293b;"></strong> akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <form id="deleteUserForm" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex; gap:12px;">
                <button type="submit" style="flex:1; padding:10px; background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; height:40px;">Ya, Hapus</button>
                <button type="button" onclick="closeDeleteUser()" style="flex:1; padding:10px; background:#f1f5f9; color:#475569; border:none; border-radius:8px; font-weight:600; cursor:pointer; height:40px;">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditUser(id, name, email, studentId) {
    document.getElementById('eu-name').value = name;
    document.getElementById('eu-email').value = email;
    document.getElementById('eu-student').value = studentId || '';
    document.getElementById('editUserForm').action = '/admin/users/' + id;
    const modal = document.getElementById('editUserModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeEditUser() {
    document.getElementById('editUserModal').style.display = 'none';
    document.body.style.overflow = '';
}
function confirmDelete(id, name) {
    document.getElementById('delete-name').textContent = name;
    document.getElementById('deleteUserForm').action = '/admin/users/' + id;
    const modal = document.getElementById('deleteUserModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeDeleteUser() {
    document.getElementById('deleteUserModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('editUserModal').addEventListener('click', function(e){ if(e.target===this) closeEditUser(); });
document.getElementById('deleteUserModal').addEventListener('click', function(e){ if(e.target===this) closeDeleteUser(); });
</script>
@endpush
@endsection