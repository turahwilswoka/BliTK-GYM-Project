@extends('layouts.guest')
@section('title', 'Daftar')
@section('content')
<div class="auth-card">
    <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:0.5rem;">Buat Akun Baru</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.75rem;">Bergabung dengan Umah Dauh GYM Community</p>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="form-group">
            <label for="name"><i class="fas fa-user" style="margin-right:0.4rem;"></i>Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="John Doe" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="reg-email"><i class="fas fa-envelope" style="margin-right:0.4rem;"></i>Email</label>
            <input type="email" id="reg-email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="email@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="phone"><i class="fas fa-phone" style="margin-right:0.4rem;"></i>No. HP</label>
                <input type="tel" id="phone" name="phone" class="form-control"
                    value="{{ old('phone') }}" placeholder="08123456789">
            </div>
            <div class="form-group">
                <label for="gender"><i class="fas fa-venus-mars" style="margin-right:0.4rem;"></i>Jenis Kelamin</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="">Pilih...</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="birth_date"><i class="fas fa-birthday-cake" style="margin-right:0.4rem;"></i>Tanggal Lahir</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"
                style="color-scheme: dark;">
        </div>
        <div class="form-group">
            <label for="reg-password"><i class="fas fa-lock" style="margin-right:0.4rem;"></i>Password</label>
            <input type="password" id="reg-password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-lock" style="margin-right:0.4rem;"></i>Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                placeholder="Ulangi password" required>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-user-plus"></i> <span>Daftar Sekarang</span>
        </button>
    </form>
</div>
<div class="auth-links">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    <br><br>
    <a href="{{ route('home') }}" style="color:#475569;"><i class="fas fa-home"></i> Kembali ke Beranda</a>
</div>
@endsection
