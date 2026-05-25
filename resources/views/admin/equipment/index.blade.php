@extends('layouts.admin')
@section('title', 'Kelola Alat')

@section('content')
<div class="flex-between mb-24">
    <div>
        <h1>Kelola Alat</h1>
        <p class="text-gray" style="margin-top:4px;">Kelola semua inventaris peralatan fotografi dan videografi.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addModal')">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Alat
    </button>
</div>

{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card"><div><div class="label">Total Alat</div><div class="value">{{ $totalAll }}</div></div><div class="stat-icon stat-icon-blue"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div></div>
    <div class="stat-card"><div><div class="label">Tersedia</div><div class="value" style="color:#059669;">{{ $available }}</div></div><div class="stat-icon stat-icon-green"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg></div></div>
    <div class="stat-card"><div><div class="label">Dipinjam</div><div class="value" style="color:var(--orange);">{{ $borrowed }}</div></div><div class="stat-icon stat-icon-orange"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div></div>
    <div class="stat-card"><div><div class="label">Perawatan / Rusak</div><div class="value" style="color:#EF4444;">{{ $maintenance }}</div></div><div class="stat-icon stat-icon-red"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg></div></div>
</div>

{{-- FILTERS --}}
<div class="card" style="margin-bottom:16px;padding:14px 20px;">
    <form method="GET" action="{{ route('admin.equipment.index') }}" style="display:flex;gap:12px;align-items:center;">
        <div class="search-bar" style="flex:1;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="form-control" placeholder="Cari nama alat atau nomor seri..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="width:160px;">
            <option value="">Semua Kategori</option>
            <option value="camera" {{ request('category')==='camera'?'selected':'' }}>Kamera</option>
            <option value="lens" {{ request('category')==='lens'?'selected':'' }}>Lensa</option>
            <option value="tripod" {{ request('category')==='tripod'?'selected':'' }}>Tripod</option>
            <option value="lighting" {{ request('category')==='lighting'?'selected':'' }}>Lighting</option>
            <option value="accessory" {{ request('category')==='accessory'?'selected':'' }}>Aksesoris</option>
        </select>
        <select name="status" class="form-control" style="width:140px;">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status')==='available'?'selected':'' }}>Tersedia</option>
            <option value="borrowed" {{ request('status')==='borrowed'?'selected':'' }}>Dipinjam</option>
            <option value="maintenance" {{ request('status')==='maintenance'?'selected':'' }}>Perawatan</option>
        </select>
        <button type="submit" class="btn btn-outline btn-sm">Filter</button>
        @if(request()->anyFilled(['search','category','status']))
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Nomor Seri</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $eq)
                <tr>
                    <td class="text-gray text-sm">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <div style="width:40px;height:40px;background:var(--gray-100);border-radius:8px;overflow:hidden;flex-shrink:0;">
                                @if($eq->image)
                                    <img src="{{ asset('storage/'.$eq->image) }}" style="width:100%;height:100%;object-fit:contain;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:18px;">📷</div>
                                @endif
                            </div>
                            <div class="font-bold">{{ $eq->name }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $eq->category }}">
                            @if($eq->category === 'camera') Kamera
                            @elseif($eq->category === 'lens') Lensa
                            @elseif($eq->category === 'tripod') Tripod
                            @elseif($eq->category === 'lighting') Lighting
                            @elseif($eq->category === 'accessory') Aksesoris
                            @else {{ ucfirst($eq->category) }} @endif
                        </span>
                    </td>
                    <td class="text-sm">
                        @if($eq->condition === 'excellent') Sangat Bagus
                        @elseif($eq->condition === 'good') Bagus
                        @elseif($eq->condition === 'fair') Cukup
                        @elseif($eq->condition === 'needs_repair') Perlu Perbaikan
                        @else {{ $eq->condition_label }} @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $eq->status }}">
                            @if($eq->status === 'available') Tersedia
                            @elseif($eq->status === 'borrowed') Dipinjam
                            @elseif($eq->status === 'maintenance') Perawatan
                            @else {{ ucfirst($eq->status) }} @endif
                        </span>
                    </td>
                    <td class="text-sm text-gray">{{ $eq->serial_number ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <button class="btn btn-outline btn-sm"
                                data-edit="{{ json_encode(['name'=>$eq->name,'category'=>$eq->category,'condition'=>$eq->condition,'status'=>$eq->status,'serial_number'=>$eq->serial_number,'description'=>$eq->description]) }}"
                                data-modal="editModal"
                                onclick="document.getElementById('editForm').action='{{ route('admin.equipment.update', $eq) }}'">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.equipment.destroy', $eq) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus {{ $eq->name }}?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:48px;color:var(--gray-500);">Data alat tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $equipment->links() }}</div>
</div>

{{-- ADD MODAL --}}
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Alat Baru</h3>
            <button class="modal-close" onclick="closeModal('addModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Canon EOS R6 Mark II" required>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-control" required>
                        <option value="camera">Kamera</option>
                        <option value="lens">Lensa</option>
                        <option value="tripod">Tripod</option>
                        <option value="lighting">Lighting</option>
                        <option value="accessory">Aksesoris</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Alat</label>
                    <select name="condition" class="form-control" required>
                        <option value="excellent">Sangat Bagus</option>
                        <option value="good">Bagus</option>
                        <option value="fair">Cukup</option>
                        <option value="needs_repair">Perlu Perbaikan</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Seri / Tag Aset</label>
                <input type="text" name="serial_number" class="form-control" placeholder="UKM-FIN-XXXXXX">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi tambahan (opsional)..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Foto Alat</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan ke Inventaris</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Informasi Alat</h3>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-control">
                        <option value="camera">Kamera</option>
                        <option value="lens">Lensa</option>
                        <option value="tripod">Tripod</option>
                        <option value="lighting">Lighting</option>
                        <option value="accessory">Aksesoris</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Alat</label>
                    <select name="condition" class="form-control">
                        <option value="excellent">Sangat Bagus</option>
                        <option value="good">Bagus</option>
                        <option value="fair">Cukup</option>
                        <option value="needs_repair">Perlu Perbaikan</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="available">Tersedia</option>
                    <option value="borrowed">Dipinjam</option>
                    <option value="maintenance">Perawatan</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Ganti Foto Alat (opsional)</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection