@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')
<div class="auth-card">
    <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:0.5rem;">Reset Password</h2>
    <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.75rem;">
        Buat password baru yang kuat untuk akun Umah Dauh GYM Anda.
    </p>

    <form method="POST" action="{{ route('password.update') }}" id="resetForm">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope" style="margin-right:0.4rem;"></i>Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $email) }}"
                required
                readonly
                style="opacity:0.7;cursor:not-allowed;"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock" style="margin-right:0.4rem;"></i>Password Baru
            </label>
            <div style="position:relative;">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password"
                >
                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer;padding:0;">
                    <i class="fas fa-eye" id="eyeIcon1"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!-- Password strength bar -->
            <div id="strengthBar" style="margin-top:0.5rem;display:none;">
                <div style="height:4px;background:rgba(255,255,255,0.08);border-radius:4px;overflow:hidden;">
                    <div id="strengthFill" style="height:100%;border-radius:4px;transition:all 0.3s ease;width:0%;"></div>
                </div>
                <p id="strengthText" style="font-size:0.75rem;margin-top:0.3rem;color:#94a3b8;"></p>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                <i class="fas fa-shield-alt" style="margin-right:0.4rem;"></i>Konfirmasi Password
            </label>
            <div style="position:relative;">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password baru"
                    required
                    autocomplete="new-password"
                >
                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer;padding:0;">
                    <i class="fas fa-eye" id="eyeIcon2"></i>
                </button>
            </div>
            <p id="matchMsg" style="font-size:0.75rem;margin-top:0.3rem;display:none;"></p>
        </div>

        <button type="submit" class="btn-primary" id="resetBtn" style="margin-top:0.5rem;">
            <i class="fas fa-key" style="margin-right:0.5rem;"></i>
            <span>Reset Password</span>
        </button>
    </form>
</div>

<div class="auth-links">
    <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left" style="margin-right:0.3rem;"></i>Kembali ke Login
    </a>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const passInput    = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar  = document.getElementById('strengthBar');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const matchMsg     = document.getElementById('matchMsg');
    const resetBtn     = document.getElementById('resetBtn');

    function getStrength(password) {
        let score = 0;
        if (password.length >= 8)  score++;
        if (password.length >= 12) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        return score;
    }

    const strengthConfig = {
        0: { color: 'transparent', label: '' },
        1: { color: '#ef4444', label: 'Sangat Lemah' },
        2: { color: '#f97316', label: 'Lemah' },
        3: { color: '#eab308', label: 'Cukup' },
        4: { color: '#22c55e', label: 'Kuat' },
        5: { color: '#10b981', label: 'Sangat Kuat 🔒' },
    };

    passInput.addEventListener('input', function () {
        const score = getStrength(this.value);
        if (this.value.length > 0) {
            strengthBar.style.display = 'block';
            const cfg = strengthConfig[score];
            strengthFill.style.width      = (score / 5 * 100) + '%';
            strengthFill.style.background = cfg.color;
            strengthText.textContent      = cfg.label;
            strengthText.style.color      = cfg.color;
        } else {
            strengthBar.style.display = 'none';
        }
        checkMatch();
    });

    function checkMatch() {
        if (confirmInput.value.length === 0) {
            matchMsg.style.display = 'none';
            return;
        }
        matchMsg.style.display = 'block';
        if (passInput.value === confirmInput.value) {
            matchMsg.textContent   = '✅ Password cocok';
            matchMsg.style.color   = '#4ade80';
            confirmInput.style.borderColor = 'rgba(34,197,94,0.5)';
        } else {
            matchMsg.textContent   = '❌ Password tidak cocok';
            matchMsg.style.color   = '#f87171';
            confirmInput.style.borderColor = 'rgba(239,68,68,0.5)';
        }
    }

    confirmInput.addEventListener('input', checkMatch);

    document.getElementById('resetForm').addEventListener('submit', function (e) {
        resetBtn.disabled = true;
        resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:0.5rem;"></i><span>Memproses...</span>';
    });
});
</script>
@endsection
