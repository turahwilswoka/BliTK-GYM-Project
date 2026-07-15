@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('content')
<div class="auth-card">
    <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:0.5rem;">Lupa Password?</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.75rem;">
        Masukkan email Gmail Anda dan kami akan mengirim link reset password secara langsung.
    </p>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">
            <i class="fas fa-paper-plane" style="margin-right:0.5rem;"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
        @csrf
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope" style="margin-right:0.4rem;"></i>Email Gmail
            </label>
            <div style="position:relative;">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="contoh@gmail.com"
                    required
                    autocomplete="email"
                    autofocus
                >
                <span id="gmailBadge" style="
                    display:none;
                    position:absolute;
                    right:12px;
                    top:50%;
                    transform:translateY(-50%);
                    background:rgba(34,197,94,0.15);
                    color:#4ade80;
                    font-size:0.72rem;
                    padding:2px 8px;
                    border-radius:20px;
                    font-weight:600;
                    letter-spacing:0.03em;
                ">
                    <i class="fas fa-check-circle" style="margin-right:3px;"></i>Gmail
                </span>
            </div>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <p style="font-size:0.78rem;color:#64748b;margin-top:0.4rem;">
                <i class="fab fa-google" style="color:#ea4335;margin-right:0.3rem;"></i>
                Hanya akun Gmail (@gmail.com) yang diizinkan
            </p>
        </div>

        <button type="submit" class="btn-primary" id="submitBtn">
            <i class="fas fa-paper-plane" style="margin-right:0.5rem;"></i>
            <span>Kirim Link Reset Password</span>
        </button>
    </form>
</div>

<div class="auth-links">
    <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left" style="margin-right:0.3rem;"></i>Kembali ke Login
    </a>
    <br><br>
    <a href="{{ route('home') }}" style="color:#475569;">
        <i class="fas fa-home"></i> Kembali ke Beranda
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const gmailBadge = document.getElementById('gmailBadge');
    const submitBtn  = document.getElementById('submitBtn');
    const form       = document.getElementById('forgotForm');

    function checkGmail(value) {
        return value.toLowerCase().endsWith('@gmail.com') && value.length > 10;
    }

    emailInput.addEventListener('input', function () {
        if (checkGmail(this.value)) {
            gmailBadge.style.display = 'inline-flex';
            emailInput.style.borderColor = 'rgba(34,197,94,0.5)';
            emailInput.style.boxShadow   = '0 0 0 3px rgba(34,197,94,0.1)';
        } else {
            gmailBadge.style.display = 'none';
            emailInput.style.borderColor = '';
            emailInput.style.boxShadow   = '';
        }
    });

    form.addEventListener('submit', function (e) {
        if (!checkGmail(emailInput.value)) {
            e.preventDefault();
            emailInput.classList.add('is-invalid');
            let fb = emailInput.nextElementSibling;
            // find or create feedback div
            let errDiv = emailInput.parentElement.parentElement.querySelector('.invalid-feedback');
            if (!errDiv) {
                errDiv = document.createElement('div');
                errDiv.className = 'invalid-feedback';
                emailInput.parentElement.insertAdjacentElement('afterend', errDiv);
            }
            errDiv.textContent = 'Hanya email Gmail (@gmail.com) yang diizinkan.';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:0.5rem;"></i><span>Mengirim...</span>';
    });
});
</script>
@endsection
