@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<div class="auth-card">
    <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:0.5rem;">Selamat Datang Kembali</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.75rem;">Masuk ke akun Umah Dauh GYM Anda</p>

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
        @csrf
        <div class="form-group">
            <label for="email"><i class="fas fa-envelope" style="margin-right:0.4rem;"></i>Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="email@example.com" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password"><i class="fas fa-lock" style="margin-right:0.4rem;"></i>Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:#94a3b8;cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:#ff8c00;"> Ingat Saya
            </label>
            <a href="{{ route('password.request') }}" style="font-size:0.83rem;color:#ff8c00;text-decoration:none;font-weight:500;">
                <i class="fas fa-key" style="margin-right:0.3rem;"></i>Lupa Password?
            </a>
        </div>
        <button type="submit" class="btn-primary" id="loginBtn">
            <span>Masuk</span>
            <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i>
        </button>
    </form>
</div>
<div class="auth-links">
    Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
    <br><br>
    <a href="{{ route('password.request') }}" style="color:#ff8c00;">
        <i class="fas fa-key"></i> Lupa Password?
    </a>
    <br><br>
    <a href="{{ route('home') }}" style="color:#475569;"><i class="fas fa-home"></i> Kembali ke Beranda</a>
</div>
@endsection
