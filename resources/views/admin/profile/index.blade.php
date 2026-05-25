@extends('layouts.admin')
@section('title', 'Profil Saya')

@section('content')
<div class="mb-24">
    <h1>Profil Saya</h1>
    <p class="text-gray" style="margin-top:4px;">Kelola foto profil, informasi akun, dan keamanan kata sandi Anda.</p>
</div>

{{-- Pesan error validasi --}}
@if($errors->any())
    <div style="background: #FDE8E8; color: #9B1C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; max-width: 1000px;">
        <ul style="margin: 0; padding-left: 16px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: flex; flex-direction: column; gap: 24px; max-width: 1000px; width: 100%;">

    {{-- ===== KOTAK 1: FOTO PROFIL ===== --}}
    <div style="background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 32px;">

            {{-- Preview Foto --}}
            <div style="flex-shrink: 0;">
                @if($user->avatar)
                    <img id="avatar-preview"
                         src="{{ asset('storage/profile_photos/'.$user->avatar) }}"
                         style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid #f1f5f9;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                @else
                    <div id="avatar-preview-initials"
                         style="width:110px;height:110px;border-radius:50%;background:#22c55e;display:flex;align-items:center;justify-content:center;font-size:40px;color:#fff;font-weight:700;box-shadow:0 4px 12px rgba(34,197,94,0.2);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Form Upload Foto --}}
            <div style="flex-grow: 1;">
                <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:4px;">Foto Profil</h3>
                <p style="font-size:13px;color:#64748b;margin-bottom:16px;">Format JPG, JPEG, atau PNG. Maksimal 2MB.</p>

                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data"
                      style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="name"  value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display:none;">

                    <label for="avatar-input"
                           style="background:#f1f5f9;color:#334155;padding:10px 16px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;border:1px solid #cbd5e1;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Pilih Foto
                    </label>

                    <span id="filename-text"
                          style="font-size:13px;color:#64748b;font-style:italic;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        Belum ada berkas dipilih
                    </span>

                    <button type="submit"
                            style="background:#22c55e;color:#fff;border:none;padding:11px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                        Unggah Foto
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== KOTAK 2: INFO PROFIL & KATA SANDI ===== --}}
    <div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 4px 24px rgba(0,0,0,0.04);border:1px solid #e2e8f0;display:flex;flex-direction:column;gap:36px;">

        {{-- SEKSI A: INFORMASI DATA DIRI --}}
        <div>
            <h4 style="font-size:16px;font-weight:700;margin-bottom:20px;color:#0f172a;">Informasi Profil</h4>

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#475569;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('name') ? '#f87171' : '#cbd5e1' }};border-radius:8px;font-size:14px;color:#334155;box-sizing:border-box;">
                        @error('name')
                            <p style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#475569;">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               style="width:100%;padding:10px 12px;border:1px solid {{ $errors->has('email') ? '#f87171' : '#cbd5e1' }};border-radius:8px;font-size:14px;color:#334155;box-sizing:border-box;">
                        @error('email')
                            <p style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                        style="background:#22c55e;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <div style="height:1px;background:#f1f5f9;width:100%;"></div>

        {{-- SEKSI B: UBAH KATA SANDI --}}
        <div>
            <h4 style="font-size:16px;font-weight:700;margin-bottom:20px;color:#0f172a;">Ubah Kata Sandi</h4>

            <form method="POST" action="{{ route('admin.profile.password') }}">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:24px;">

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#475569;">Kata Sandi Sekarang</label>
                        <div style="position:relative;">
                            <input type="password" name="current_password" id="current_password"
                                   style="width:100%;padding:10px 40px 10px 12px;border:1px solid {{ $errors->has('current_password') ? '#f87171' : '#cbd5e1' }};border-radius:8px;font-size:14px;color:#334155;box-sizing:border-box;">
                            <button type="button" onclick="togglePassword('current_password', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#94a3b8;display:flex;align-items:center;">
                                <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#475569;">Kata Sandi Baru</label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="password"
                                   style="width:100%;padding:10px 40px 10px 12px;border:1px solid {{ $errors->has('password') ? '#f87171' : '#cbd5e1' }};border-radius:8px;font-size:14px;color:#334155;box-sizing:border-box;">
                            <button type="button" onclick="togglePassword('password', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#94a3b8;display:flex;align-items:center;">
                                <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#475569;">Konfirmasi Kata Sandi</label>
                        <div style="position:relative;">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   style="width:100%;padding:10px 40px 10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;color:#334155;box-sizing:border-box;">
                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#94a3b8;display:flex;align-items:center;">
                                <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                </div>

                <button type="submit"
                        style="background:#22c55e;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                    Perbarui Kata Sandi
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const avatarInput  = document.getElementById('avatar-input');
const filenameText = document.getElementById('filename-text');

if (avatarInput) {
    avatarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        if (filenameText) {
            filenameText.textContent     = file.name;
            filenameText.style.color     = '#0f172a';
            filenameText.style.fontStyle  = 'normal';
            filenameText.style.fontWeight = '500';
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const preview  = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-preview-initials');
            if (preview) {
                preview.src = e.target.result;
            } else if (initials) {
                const img         = document.createElement('img');
                img.id            = 'avatar-preview';
                img.src           = e.target.result;
                img.style.cssText = 'width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid #f1f5f9;box-shadow:0 4px 12px rgba(0,0,0,0.05);';
                initials.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });
}

function togglePassword(inputId, buttonElement) {
    const input   = document.getElementById(inputId);
    const icon    = buttonElement.querySelector('.eye-icon');
    const showing = input.type === 'text';

    input.type = showing ? 'password' : 'text';

    icon.innerHTML = showing
        ? '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
        : '<path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';

    icon.style.color = showing ? '#94a3b8' : '#22c55e';
}
</script>
@endpush