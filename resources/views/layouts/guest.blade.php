<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Umah Dauh GYM')</title>
    <meta name="description" content="Umah Dauh GYM – Wujudkan Tubuh Ideal Anda Bersama Kami">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter', sans-serif;
            background:#0a0a0f;
            color:#e2e8f0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow-x:hidden;
            padding:1.25rem;
        }

        /* ─── BACKGROUND: gym image + dark overlay ──────────────────────── */
        body::before {
            content:'';
            position:fixed; inset:0; z-index:0;
            background-image:url('/images/gym-login-bg.png');
            background-size:cover;
            background-position:center;
            filter:brightness(0.22) saturate(0.8);
        }

        /* Gradient vignette on top of image */
        body::after {
            content:'';
            position:fixed; inset:0; z-index:1;
            background:
                radial-gradient(ellipse at 50% 50%, rgba(0,0,0,0) 0%, rgba(0,0,0,0.55) 100%),
                radial-gradient(ellipse at 50% 0%, rgba(255,140,0,0.07) 0%, transparent 60%);
            pointer-events:none;
        }

        /* ─── CENTER CONTAINER ──────────────────────────────────────────── */
        .auth-center {
            position:relative; z-index:2;
            width:100%; max-width:460px;
            animation:fadeInScale 0.5s ease both;
        }

        @keyframes fadeInScale {
            from { opacity:0; transform:scale(0.97) translateY(12px); }
            to   { opacity:1; transform:scale(1)    translateY(0); }
        }

        /* ─── LOGO ──────────────────────────────────────────────────────── */
        .auth-logo { text-align:center; margin-bottom:1.75rem; }
        .logo-icon-wrap {
            display:inline-flex; align-items:center; justify-content:center;
            width:68px; height:68px;
            background:linear-gradient(135deg, #ff8c00, #ff4500);
            border-radius:20px; font-size:2rem; margin-bottom:1rem;
            box-shadow:0 0 50px rgba(255,140,0,0.45), 0 12px 32px rgba(255,140,0,0.25);
            position:relative;
        }
        .logo-icon-wrap::after {
            content:''; position:absolute; inset:-5px;
            border-radius:25px; border:1px solid rgba(255,140,0,0.25);
            pointer-events:none;
        }
        .auth-logo h1 {
            font-size:1.8rem; font-weight:900; letter-spacing:-0.5px;
            background:linear-gradient(135deg, #ff8c00, #ffd700);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .auth-logo p { color:#475569; font-size:0.84rem; margin-top:0.2rem; }

        /* ─── CARD ──────────────────────────────────────────────────────── */
        .auth-card {
            background:rgba(12,12,20,0.85);
            border:1px solid rgba(255,255,255,0.09);
            border-radius:24px; padding:2.25rem;
            backdrop-filter:blur(32px);
            box-shadow:
                0 32px 80px rgba(0,0,0,0.7),
                0 0 0 1px rgba(255,255,255,0.04) inset,
                0 1px 0 rgba(255,255,255,0.08) inset;
        }

        /* ─── FORM ELEMENTS ─────────────────────────────────────────────── */
        .form-group { margin-bottom:1.15rem; }
        .form-group label {
            display:block; font-size:0.75rem; font-weight:600;
            color:#64748b; margin-bottom:0.45rem;
            text-transform:uppercase; letter-spacing:0.07em;
        }
        .form-control {
            width:100%; padding:0.78rem 1rem;
            background:rgba(255,255,255,0.05);
            border:1px solid rgba(255,255,255,0.09);
            border-radius:11px; color:#e2e8f0;
            font-size:0.93rem; font-family:'Inter',sans-serif;
            transition:all 0.25s ease;
        }
        .form-control::placeholder { color:#374151; }
        .form-control:focus {
            outline:none; border-color:#ff8c00;
            background:rgba(255,140,0,0.05);
            box-shadow:0 0 0 3px rgba(255,140,0,0.15);
        }
        .form-control.is-invalid { border-color:#ef4444; }
        .invalid-feedback { color:#f87171; font-size:0.79rem; margin-top:0.35rem; }

        /* ─── BUTTON ────────────────────────────────────────────────────── */
        .btn-primary {
            width:100%; padding:0.88rem;
            background:linear-gradient(135deg, #ff8c00, #ff4500);
            border:none; border-radius:12px; color:#fff;
            font-size:1rem; font-weight:700; font-family:'Inter',sans-serif;
            cursor:pointer; letter-spacing:0.02em;
            transition:all 0.3s ease; position:relative; overflow:hidden;
        }
        .btn-primary::before {
            content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            transition:left 0.5s ease;
        }
        .btn-primary:hover::before { left:100%; }
        .btn-primary:hover {
            transform:translateY(-2px);
            box-shadow:0 12px 36px rgba(255,140,0,0.5);
        }
        .btn-primary:active { transform:scale(0.99); }

        /* ─── ALERTS ────────────────────────────────────────────────────── */
        .alert { padding:0.8rem 1rem; border-radius:11px; margin-bottom:1rem; font-size:0.86rem; display:flex; align-items:flex-start; gap:0.5rem; }
        .alert i { margin-top:0.05rem; flex-shrink:0; }
        .alert-success { background:rgba(34,197,94,0.1);  border:1px solid rgba(34,197,94,0.25);  color:#4ade80; }
        .alert-danger  { background:rgba(239,68,68,0.1);  border:1px solid rgba(239,68,68,0.25);  color:#f87171; }
        .alert-info    { background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.25); color:#60a5fa; }
        .alert-warning { background:rgba(234,179,8,0.1);  border:1px solid rgba(234,179,8,0.25);  color:#facc15; }

        /* ─── LINKS ─────────────────────────────────────────────────────── */
        .auth-links {
            text-align:center; margin-top:1.4rem;
            font-size:0.87rem; color:#475569; line-height:1.9;
        }
        .auth-links a { color:#ff8c00; text-decoration:none; font-weight:600; transition:color 0.2s; }
        .auth-links a:hover { color:#ffd700; }

        /* ─── FORM UTILITY ──────────────────────────────────────────────── */
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; }
        select.form-control option { background:#12121a; }
        textarea.form-control { resize:vertical; min-height:70px; }

        /* ─── MOBILE ────────────────────────────────────────────────────── */
        @media (max-width:480px) {
            body { padding:1rem; align-items:flex-start; padding-top:1.5rem; }
            .auth-card { padding:1.6rem; border-radius:20px; }
            .logo-icon-wrap { width:58px; height:58px; font-size:1.7rem; border-radius:16px; }
            .auth-logo h1 { font-size:1.55rem; }
            .form-row { grid-template-columns:1fr; gap:0; }
            .form-control { font-size:16px; } /* prevent iOS zoom */
            .btn-primary { font-size:0.95rem; padding:0.85rem; }
        }
    </style>
</head>
<body>
    <div class="auth-center">
        {{-- Logo --}}
        <div class="auth-logo">
            <div class="logo-icon-wrap">💪</div>
            <h1>Umah Dauh GYM</h1>
            <p>Wujudkan Tubuh Ideal Anda</p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        {{-- Form Content --}}
        @yield('content')
    </div>
</body>
</html>
