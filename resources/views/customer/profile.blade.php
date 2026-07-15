@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', '👤 Profil Saya')
@section('content')

<div class="profile-layout">
    <!-- Profile Card -->
    <div class="card" style="text-align:center;">
        <div style="position:relative;display:inline-block;margin-bottom:1rem;">
            <img src="{{ $user->photo_url }}" alt="Foto Profil"
                style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #ff8c00;">
        </div>
        <h3 style="font-weight:700;margin-bottom:0.25rem;">{{ $user->name }}</h3>
        <p style="color:#64748b;font-size:0.85rem;">{{ $user->email }}</p>
        <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:1.25rem 0;">
        @if($subscription)
        <div style="background:linear-gradient(135deg,rgba(255,140,0,0.15),rgba(255,69,0,0.1));border:1px solid rgba(255,140,0,0.3);border-radius:12px;padding:1rem;">
            <span style="background:{{ $subscription->membership->badge_color }};color:#000;padding:0.2rem 0.75rem;border-radius:20px;font-size:0.72rem;font-weight:700;">
                {{ strtoupper($subscription->membership->name) }}
            </span>
            <div style="margin-top:0.75rem;font-size:0.82rem;color:#94a3b8;">
                <div>Aktif hingga:</div>
                <div style="font-weight:700;color:#ff8c00;">{{ $subscription->end_date->format('d M Y') }}</div>
                <div style="color:#4ade80;font-weight:600;margin-top:0.25rem;">{{ $subscription->days_remaining }} hari lagi</div>
            </div>
        </div>
        @else
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:1rem;font-size:0.85rem;color:#f87171;">
            Belum ada membership aktif
        </div>
        @endif
    </div>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-title"><i class="fas fa-edit" style="color:#ff8c00;margin-right:0.5rem;"></i>Edit Profil</div>
        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-2col">
                <div class="form-group" style="grid-column:1/-1;">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="phone">No. HP</label>
                    <input type="tel" id="phone" name="phone" class="form-control"
                        value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Pilih...</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" class="form-control"
                        value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" style="color-scheme:dark;">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Masukkan alamat...">{{ old('address', $user->address) }}</textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label for="photo">Foto Profil (JPG/PNG, max 2MB)</label>
                    <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror"
                        accept="image/*" style="cursor:pointer;">
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

@push('styles')
<style>
.profile-layout { display:grid; grid-template-columns:1fr; gap:1rem; }
@media (min-width:700px) { .profile-layout { grid-template-columns:260px 1fr; gap:1.5rem; align-items:start; } }
.form-2col { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media (max-width:540px) { .form-2col { grid-template-columns:1fr; } }
</style>
@endpush
@endsection
