<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umah Dauh GYM – Wujudkan Tubuh Ideal Anda</title>
    <meta name="description" content="Umah Dauh GYM – Gym premium dengan fasilitas modern, personal trainer berpengalaman, dan sistem membership digital.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root { --accent:#ff8c00; --accent-dark:#ff4500; --bg:#0a0a0f; }
        html { scroll-behavior:smooth; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:#e2e8f0; overflow-x:hidden; }

        /* ─── NAVBAR ───────────────────────────────────────────────────────── */
        .navbar {
            position:fixed; top:0; left:0; right:0; z-index:1000;
            padding:0.9rem 5%;
            display:flex; align-items:center; justify-content:space-between;
            transition: background 0.4s, backdrop-filter 0.4s;
        }
        .navbar.scrolled {
            background:rgba(10,10,15,0.95);
            backdrop-filter:blur(24px);
            border-bottom:1px solid rgba(255,255,255,0.07);
            box-shadow:0 4px 30px rgba(0,0,0,0.4);
        }
        .nav-logo { display:flex; align-items:center; gap:0.65rem; text-decoration:none; }
        .nav-logo .logo-box {
            width:38px; height:38px;
            background:linear-gradient(135deg,#ff8c00,#ff4500);
            border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; box-shadow:0 0 20px rgba(255,140,0,0.4); flex-shrink:0;
        }
        .nav-logo .logo-text {
            font-size:1.05rem; font-weight:900;
            background:linear-gradient(135deg,#ff8c00,#ffd700);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .nav-links { display:flex; align-items:center; gap:0.5rem; }
        .btn-nav-ghost {
            padding:0.5rem 1.1rem; border-radius:9px;
            background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12);
            color:#e2e8f0; text-decoration:none; font-size:0.85rem; font-weight:600;
            transition:all 0.25s; white-space:nowrap;
        }
        .btn-nav-ghost:hover { background:rgba(255,255,255,0.14); }
        .btn-nav-primary {
            padding:0.5rem 1.2rem; border-radius:9px;
            background:linear-gradient(135deg,#ff8c00,#ff4500);
            color:#fff; text-decoration:none; font-size:0.85rem; font-weight:700;
            transition:all 0.25s; white-space:nowrap; box-shadow:0 4px 16px rgba(255,140,0,0.35);
        }
        .btn-nav-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(255,140,0,0.5); }

        /* ─── HERO ─────────────────────────────────────────────────────────── */
        .hero {
            position:relative; min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            overflow:hidden; text-align:center;
        }
        .hero-bg-img {
            position:absolute; inset:0; width:100%; height:100%;
            object-fit:cover; object-position:center;
            transform:scale(1.05);
            transition:transform 12s ease;
            z-index:0;
        }
        .hero-bg-img.loaded { transform:scale(1); }
        .hero-overlay {
            position:absolute; inset:0; z-index:1;
            background:
                linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.75) 80%, rgba(10,10,15,1) 100%),
                linear-gradient(to right, rgba(0,0,0,0.25) 0%, transparent 60%);
        }
        /* Animated gradient accent */
        .hero-overlay::after {
            content:'';
            position:absolute; inset:0;
            background:radial-gradient(ellipse at 50% 0%, rgba(255,140,0,0.15) 0%, transparent 65%);
        }
        .hero-content {
            position:relative; z-index:2;
            padding:7rem 1.25rem 3rem;
            width:100%; max-width:860px; margin:0 auto;
        }
        .hero-pill {
            display:inline-flex; align-items:center; gap:0.5rem;
            background:rgba(255,140,0,0.15); border:1px solid rgba(255,140,0,0.4);
            backdrop-filter:blur(8px); border-radius:50px;
            padding:0.4rem 1.1rem; font-size:0.78rem; font-weight:700;
            color:#ff8c00; margin-bottom:1.4rem; letter-spacing:0.06em;
            animation:fadeInDown 0.8s ease both;
        }
        .hero-title {
            font-size:clamp(2.2rem, 7vw, 5rem); font-weight:900;
            line-height:1.08; margin-bottom:1.3rem;
            animation:fadeInUp 0.9s ease 0.1s both;
        }
        .hero-title .line2 {
            background:linear-gradient(135deg, #ff8c00, #ffd700);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .hero-sub {
            font-size:clamp(0.9rem, 2vw, 1.08rem); color:rgba(255,255,255,0.65);
            max-width:560px; margin:0 auto 2.2rem; line-height:1.75;
            animation:fadeInUp 1s ease 0.2s both;
        }
        .hero-btns {
            display:flex; gap:0.85rem; justify-content:center; flex-wrap:wrap;
            margin-bottom:3.5rem;
            animation:fadeInUp 1s ease 0.3s both;
        }
        .btn-primary-lg {
            display:inline-flex; align-items:center; gap:0.6rem;
            padding:0.9rem 2.2rem; border-radius:14px;
            background:linear-gradient(135deg,#ff8c00,#ff4500);
            color:#fff; text-decoration:none; font-size:clamp(0.9rem,2vw,1.02rem); font-weight:700;
            box-shadow:0 0 40px rgba(255,140,0,0.4); transition:all 0.3s; position:relative; overflow:hidden;
        }
        .btn-primary-lg::before {
            content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,0.15),transparent);
            transition:left 0.5s;
        }
        .btn-primary-lg:hover::before { left:100%; }
        .btn-primary-lg:hover { transform:translateY(-3px); box-shadow:0 16px 48px rgba(255,140,0,0.55); }
        .btn-ghost-lg {
            display:inline-flex; align-items:center; gap:0.6rem;
            padding:0.9rem 2.2rem; border-radius:14px;
            background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2);
            color:#e2e8f0; text-decoration:none; font-size:clamp(0.9rem,2vw,1.02rem); font-weight:600;
            backdrop-filter:blur(8px); transition:all 0.3s;
        }
        .btn-ghost-lg:hover { background:rgba(255,255,255,0.15); transform:translateY(-2px); }

        /* Hero Stats */
        .hero-stats {
            display:flex; gap:clamp(1.5rem,4vw,4rem); justify-content:center; flex-wrap:wrap;
            padding-top:2.5rem; border-top:1px solid rgba(255,255,255,0.1);
            animation:fadeInUp 1s ease 0.4s both;
        }
        .hero-stat-item { text-align:center; }
        .hero-stat-item .val {
            font-size:clamp(1.8rem,4vw,2.6rem); font-weight:900; line-height:1;
            background:linear-gradient(135deg,#ff8c00,#ffd700);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .hero-stat-item .lbl { font-size:0.75rem; color:rgba(255,255,255,0.45); margin-top:0.25rem; letter-spacing:0.05em; }

        /* Scroll indicator */
        .scroll-down {
            position:absolute; bottom:2rem; left:50%; transform:translateX(-50%);
            z-index:3; display:flex; flex-direction:column; align-items:center; gap:0.4rem;
            animation:bounce 2s infinite;
        }
        .scroll-down span { font-size:0.65rem; color:rgba(255,255,255,0.35); letter-spacing:0.12em; text-transform:uppercase; }
        .scroll-down i { color:rgba(255,255,255,0.3); font-size:1rem; }
        @keyframes bounce { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(8px)} }

        /* ─── PHOTO STRIP – GYM ACTIVITY ───────────────────────────────────── */
        .photo-strip { padding:5rem 5% 4rem; }
        .photo-strip-inner {
            display:grid;
            grid-template-columns:1.4fr 1fr 1fr;
            grid-template-rows:auto auto;
            gap:1rem;
            max-width:1200px; margin:0 auto;
        }
        .photo-card {
            position:relative; overflow:hidden; border-radius:20px;
            background:#111;
        }
        .photo-card.tall { grid-row:span 2; }
        .photo-card img {
            width:100%; height:100%; object-fit:cover; display:block;
            transition:transform 0.6s ease;
        }
        .photo-card:hover img { transform:scale(1.06); }
        .photo-card .photo-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
            opacity:0; transition:opacity 0.35s;
            display:flex; align-items:flex-end; padding:1.25rem;
        }
        .photo-card:hover .photo-overlay { opacity:1; }
        .photo-label {
            font-size:0.78rem; font-weight:700; color:#fff;
            text-transform:uppercase; letter-spacing:0.1em;
        }
        /* Fixed heights */
        .photo-card.tall { min-height:460px; }
        .photo-card:not(.tall) { min-height:220px; }

        /* ─── SECTION COMMONS ───────────────────────────────────────────────── */
        .section { padding:5rem 5%; }
        .section-sm { padding:3.5rem 5%; }
        .container { max-width:1200px; margin:0 auto; }
        .section-header { text-align:center; margin-bottom:3rem; }
        .section-pill {
            display:inline-block; padding:0.35rem 1.1rem; border-radius:50px;
            background:rgba(255,140,0,0.1); border:1px solid rgba(255,140,0,0.25);
            font-size:0.72rem; font-weight:700; color:#ff8c00;
            text-transform:uppercase; letter-spacing:0.12em; margin-bottom:0.85rem;
        }
        .section-title {
            font-size:clamp(1.7rem,4vw,2.8rem); font-weight:900;
            line-height:1.15; margin-bottom:0.7rem;
        }
        .section-sub { color:#64748b; font-size:0.92rem; line-height:1.7; max-width:500px; margin:0 auto; }

        /* ─── FEATURES ─────────────────────────────────────────────────────── */
        .features-bg { background:rgba(255,255,255,0.015); }
        .features-grid {
            display:grid; grid-template-columns:repeat(auto-fit,minmax(270px,1fr)); gap:1.25rem;
        }
        .feature-card {
            background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
            border-radius:20px; padding:1.75rem;
            transition:all 0.3s ease; position:relative; overflow:hidden;
        }
        .feature-card::before {
            content:''; position:absolute; top:-40px; right:-40px;
            width:100px; height:100px; border-radius:50%;
            background:var(--accent); opacity:0;
            transition:opacity 0.3s;
        }
        .feature-card:hover { transform:translateY(-5px); border-color:rgba(255,140,0,0.3); background:rgba(255,140,0,0.04); box-shadow:0 12px 40px rgba(0,0,0,0.3); }
        .feature-card:hover::before { opacity:0.05; }
        .feat-icon {
            width:54px; height:54px; border-radius:14px;
            background:linear-gradient(135deg,rgba(255,140,0,0.18),rgba(255,69,0,0.08));
            border:1px solid rgba(255,140,0,0.25);
            display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; margin-bottom:1.1rem;
        }
        .feature-card h3 { font-size:1rem; font-weight:700; margin-bottom:0.45rem; }
        .feature-card p { color:#64748b; font-size:0.84rem; line-height:1.65; }

        /* ─── PRICING ───────────────────────────────────────────────────────── */
        .pricing-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(270px,1fr)); gap:1.5rem; }
        .pricing-card {
            background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08);
            border-radius:24px; padding:2rem 1.75rem; text-align:center;
            position:relative; transition:all 0.3s;
        }
        .pricing-card:hover { transform:translateY(-5px); box-shadow:0 20px 50px rgba(0,0,0,0.4); }
        .pricing-card.popular {
            border-color:rgba(255,140,0,0.5); background:rgba(255,140,0,0.06);
            box-shadow:0 0 60px rgba(255,140,0,0.1);
        }
        .pop-badge {
            position:absolute; top:-15px; left:50%; transform:translateX(-50%);
            background:linear-gradient(135deg,#ff8c00,#ff4500); color:#fff;
            font-size:0.68rem; font-weight:800; padding:0.3rem 1rem; border-radius:50px;
            letter-spacing:0.1em; white-space:nowrap; box-shadow:0 4px 16px rgba(255,140,0,0.4);
        }
        .pricing-emoji { font-size:2.6rem; margin-bottom:1rem; }
        .pricing-name { font-size:1.1rem; font-weight:800; margin-bottom:0.35rem; }
        .pricing-desc { color:#64748b; font-size:0.82rem; margin-bottom:1.3rem; }
        .pricing-price {
            font-size:2.5rem; font-weight:900; line-height:1;
            background:linear-gradient(135deg,#ff8c00,#ffd700);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            margin-bottom:0.2rem;
        }
        .pricing-period { color:#64748b; font-size:0.82rem; margin-bottom:1.6rem; }
        .pricing-list { list-style:none; text-align:left; margin-bottom:1.75rem; display:flex; flex-direction:column; gap:0.6rem; }
        .pricing-list li { display:flex; align-items:center; gap:0.5rem; font-size:0.84rem; color:#94a3b8; }
        .pricing-list li i { color:#4ade80; font-size:0.75rem; flex-shrink:0; }
        .btn-buy {
            display:block; padding:0.85rem; border-radius:12px; text-decoration:none;
            background:linear-gradient(135deg,#ff8c00,#ff4500); color:#fff;
            font-weight:700; font-size:0.92rem; transition:all 0.3s;
        }
        .btn-buy:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(255,140,0,0.45); }
        .btn-buy-ghost {
            display:block; padding:0.85rem; border-radius:12px; text-decoration:none;
            background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);
            color:#e2e8f0; font-weight:600; font-size:0.92rem; transition:all 0.3s;
        }
        .btn-buy-ghost:hover { background:rgba(255,255,255,0.12); }

        /* ─── GYM PHOTO BANNER (mid-page) ──────────────────────────────────── */
        .gym-banner {
            position:relative; overflow:hidden;
            margin:0 5%; border-radius:24px;
            min-height:340px; display:flex; align-items:center;
        }
        .gym-banner img {
            position:absolute; inset:0; width:100%; height:100%;
            object-fit:cover; object-position:center 40%;
        }
        .gym-banner-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to right, rgba(0,0,0,0.88) 40%, rgba(0,0,0,0.3) 100%);
        }
        .gym-banner-content {
            position:relative; z-index:2; padding:3rem;
            max-width:560px;
        }
        .gym-banner-content h2 {
            font-size:clamp(1.5rem,3.5vw,2.4rem); font-weight:900; margin-bottom:0.75rem; line-height:1.2;
        }
        .gym-banner-content p { color:rgba(255,255,255,0.6); font-size:0.92rem; line-height:1.7; margin-bottom:1.5rem; }

        /* ─── CTA SECTION ───────────────────────────────────────────────────── */
        .cta-wrap {
            text-align:center; padding:5rem 5%;
            background:radial-gradient(ellipse at 50% 0%, rgba(255,140,0,0.1) 0%, transparent 70%);
        }
        .cta-wrap h2 { font-size:clamp(1.7rem,4vw,2.8rem); font-weight:900; margin-bottom:0.85rem; }
        .cta-wrap p { color:#64748b; font-size:0.95rem; margin-bottom:2rem; }
        .cta-btns { display:flex; gap:0.85rem; justify-content:center; flex-wrap:wrap; }

        /* ─── FOOTER ────────────────────────────────────────────────────────── */
        footer {
            border-top:1px solid rgba(255,255,255,0.06);
            padding:2.5rem 5%; text-align:center;
        }
        .footer-logo { font-size:1.3rem; font-weight:900; background:linear-gradient(135deg,#ff8c00,#ffd700); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.6rem; }

        /* ─── LOKASI / MAP SECTION ────────────────────────────────────────── */
        .location-grid {
            display: grid;
            grid-template-columns: 1fr 1.8fr;
            gap: 2rem;
            align-items: stretch;
            max-width: 1100px;
            margin: 0 auto;
        }
        .location-info-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .map-iframe-container {
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            min-height: 380px;
            position: relative;
            background: #12121a;
            box-shadow: 0 16px 40px rgba(0,0,0,0.5);
        }
        .btn-map {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .google-maps-btn {
            background: linear-gradient(135deg, #ff8c00, #ff4500);
            color: #fff;
            box-shadow: 0 4px 16px rgba(255,140,0,0.3);
        }
        .google-maps-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,140,0,0.55);
        }
        .apple-maps-btn {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            color: #e2e8f0;
        }
        .apple-maps-btn:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        /* ─── ANIMATIONS ────────────────────────────────────────────────────── */
        @keyframes fadeInDown { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeInUp   { from{opacity:0;transform:translateY(20px)}  to{opacity:1;transform:translateY(0)} }

        /* ─── MOBILE ────────────────────────────────────────────────────────── */
        @media (max-width:768px) {
            .navbar { padding:0.8rem 4%; }
            .btn-nav-ghost { display:none; }
            .hero-content { padding:6rem 1rem 2.5rem; }
            .hero-stats { gap:1.5rem; }
            .photo-strip { padding:3rem 4%; }
            .photo-strip-inner {
                grid-template-columns:1fr 1fr;
                grid-template-rows:auto;
            }
            .photo-card.tall { grid-row:span 1; min-height:200px; }
            .photo-card:not(.tall) { min-height:160px; }
            .section { padding:3.5rem 4%; }
            .features-grid { grid-template-columns:1fr; }
            .pricing-grid { grid-template-columns:1fr; max-width:380px; margin:0 auto; }
            .gym-banner { margin:0 4%; min-height:260px; }
            .gym-banner-content { padding:2rem; }
            .location-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .map-iframe-container {
                min-height: 280px;
            }
        }

        @media (max-width:480px) {
            .hero-btns { flex-direction:column; align-items:stretch; }
            .btn-primary-lg, .btn-ghost-lg { justify-content:center; padding:0.9rem 1.5rem; }
            .hero-stats { gap:1rem 1.5rem; }
            .photo-strip-inner { grid-template-columns:1fr; }
            .photo-card.tall, .photo-card:not(.tall) { min-height:200px; }
            .cta-btns { flex-direction:column; align-items:stretch; }
            .cta-btns a { justify-content:center; }
            .gym-banner-content { padding:1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ─── NAVBAR ─── --}}
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-logo">
            <div class="logo-box">💪</div>
            <span class="logo-text">Umah Dauh GYM</span>
        </a>
        <div class="nav-links">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-nav-primary">
                        <i class="fas fa-shield-alt"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="btn-nav-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-nav-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn-nav-primary">Daftar Gratis</a>
            @endauth
        </div>
    </nav>

    {{-- ─── HERO ─── --}}
    <section class="hero">
        <img
            src="{{ asset('images/gym-hero-bg.png') }}"
            alt="Umah Dauh GYM Interior"
            class="hero-bg-img"
            id="heroBg"
            loading="eager"
        >
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="hero-pill">
                <i class="fas fa-fire"></i> #1 Gym Premium di Bali
            </div>
            <h1 class="hero-title">
                Wujudkan Tubuh Ideal<br>
                <span class="line2">Mulai Hari Ini</span>
            </h1>
            <p class="hero-sub">
                Bergabung dengan ribuan member Umah Dauh GYM. Fasilitas premium, trainer berpengalaman, dan sistem membership digital dengan QR Code eksklusif.
            </p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-primary-lg">
                    <i class="fas fa-bolt"></i> Mulai Sekarang
                </a>
                <a href="#pricing" class="btn-ghost-lg">
                    <i class="fas fa-tag"></i> Lihat Harga
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat-item">
                    <div class="val">500+</div>
                    <div class="lbl">Member Aktif</div>
                </div>
                <div class="hero-stat-item">
                    <div class="val">5★</div>
                    <div class="lbl">Rating Google</div>
                </div>
                <div class="hero-stat-item">
                    <div class="val">10+</div>
                    <div class="lbl">Tahun Berdiri</div>
                </div>
                <div class="hero-stat-item">
                    <div class="val">24/7</div>
                    <div class="lbl">Support</div>
                </div>
            </div>
        </div>

        <div class="scroll-down">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    {{-- ─── PHOTO STRIP ─── --}}
    <div class="photo-strip">
        <div class="photo-strip-inner">
            {{-- Big left card --}}
            <div class="photo-card tall">
                <img src="{{ asset('images/gym-dashboard-bg.png') }}" alt="Gym Training">
                <div class="photo-overlay"><span class="photo-label">💪 Training Zone</span></div>
            </div>
            {{-- Top right --}}
            <div class="photo-card">
                <img src="{{ asset('images/gym-login-bg.png') }}" alt="Gym Workout" style="object-position:center 20%;">
                <div class="photo-overlay"><span class="photo-label">🏋️ Power Lifting</span></div>
            </div>
            {{-- Bottom right --}}
            <div class="photo-card">
                <img src="{{ asset('images/gym-hero-bg.png') }}" alt="Gym Facility">
                <div class="photo-overlay"><span class="photo-label">✨ Premium Facility</span></div>
            </div>
        </div>
    </div>

    {{-- ─── FEATURES ─── --}}
    <section class="section features-bg" id="features">
        <div class="container">
            <div class="section-header">
                <div class="section-pill">Fasilitas & Keunggulan</div>
                <h2 class="section-title">Mengapa Memilih Umah Dauh GYM?</h2>
                <p class="section-sub">Kami menyediakan pengalaman gym terbaik dengan teknologi modern dan fasilitas premium</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feat-icon">📲</div>
                    <h3>QR Code Digital</h3>
                    <p>Setiap member mendapat QR Code unik sebagai kartu anggota digital yang bisa di-scan langsung di pintu masuk.</p>
                </div>
                <div class="feature-card">
                    <div class="feat-icon">🏋️</div>
                    <h3>Alat Gym Lengkap</h3>
                    <p>Lebih dari 200 unit alat gym premium dengan perawatan rutin untuk pengalaman workout terbaik.</p>
                </div>
                <div class="feature-card">
                    <div class="feat-icon">👨‍🏫</div>
                    <h3>Personal Trainer</h3>
                    <p>Tim trainer bersertifikat siap membantu program latihan Anda sesuai target yang ingin dicapai.</p>
                </div>
                <div class="feature-card">
                    <div class="feat-icon">📊</div>
                    <h3>Tracking Kunjungan</h3>
                    <p>Monitor riwayat kunjungan gym Anda secara real-time melalui dashboard member yang informatif.</p>
                </div>
                <div class="feature-card">
                    <div class="feat-icon">🧘</div>
                    <h3>Kelas Yoga & Zumba</h3>
                    <p>Akses ke berbagai kelas group fitness termasuk yoga, zumba, dan spinning untuk paket Gold ke atas.</p>
                </div>
                <div class="feature-card">
                    <div class="feat-icon">🔒</div>
                    <h3>Keamanan 24 Jam</h3>
                    <p>Sistem keamanan CCTV dan loker dengan kunci elektronik untuk menjaga barang bawaan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── GYM PHOTO BANNER ─── --}}
    <div class="gym-banner" style="margin-bottom:0;">
        <img src="{{ asset('images/gym-dashboard-bg.png') }}" alt="Umah Dauh GYM">
        <div class="gym-banner-overlay"></div>
        <div class="gym-banner-content">
            <div class="section-pill" style="margin-bottom:0.75rem;">🔥 Fasilitas Premium</div>
            <h2>Lebih dari Sekadar Tempat Olahraga</h2>
            <p>Umah Dauh GYM hadir dengan konsep gym modern yang mengutamakan kenyamanan, keamanan, dan hasil nyata bagi setiap member kami.</p>
            <a href="{{ route('register') }}" class="btn-primary-lg" style="display:inline-flex;">
                <i class="fas fa-user-plus"></i> Bergabung Sekarang
            </a>
        </div>
    </div>

    {{-- ─── PRICING ─── --}}
    <section class="section" id="pricing">
        <div class="container">
            <div class="section-header">
                <div class="section-pill">Paket Membership</div>
                <h2 class="section-title">Pilih Paket Yang Sesuai</h2>
                <p class="section-sub">Harga terjangkau dengan fasilitas premium. Tidak ada biaya tersembunyi.</p>
            </div>
            <div class="pricing-grid">
                {{-- Silver --}}
                <div class="pricing-card">
                    <div class="pricing-emoji">🥈</div>
                    <div class="pricing-name">Paket Silver</div>
                    <div class="pricing-desc">Cocok untuk pemula yang baru mulai berolahraga</div>
                    <div class="pricing-price">Rp 150K</div>
                    <div class="pricing-period">/ 30 hari</div>
                    <ul class="pricing-list">
                        <li><i class="fas fa-check"></i> Akses gym unlimited</li>
                        <li><i class="fas fa-check"></i> Loker gratis</li>
                        <li><i class="fas fa-check"></i> QR Code digital</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-buy-ghost">Pilih Silver</a>
                </div>
                {{-- Gold (Popular) --}}
                <div class="pricing-card popular">
                    <div class="pop-badge">⭐ PALING POPULER</div>
                    <div class="pricing-emoji">🥇</div>
                    <div class="pricing-name">Paket Gold</div>
                    <div class="pricing-desc">Pilihan terbaik untuk fitness enthusiast serius</div>
                    <div class="pricing-price">Rp 400K</div>
                    <div class="pricing-period">/ 90 hari</div>
                    <ul class="pricing-list">
                        <li><i class="fas fa-check"></i> Semua fitur Silver</li>
                        <li><i class="fas fa-check"></i> Handuk gratis</li>
                        <li><i class="fas fa-check"></i> Personal training 1×</li>
                        <li><i class="fas fa-check"></i> QR Code digital</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-buy">Pilih Gold</a>
                </div>
                {{-- Platinum --}}
                <div class="pricing-card">
                    <div class="pricing-emoji">💎</div>
                    <div class="pricing-name">Paket Platinum</div>
                    <div class="pricing-desc">Nilai terbaik untuk komitmen jangka panjang</div>
                    <div class="pricing-price">Rp 1.2JT</div>
                    <div class="pricing-period">/ 365 hari</div>
                    <ul class="pricing-list">
                        <li><i class="fas fa-check"></i> Semua fitur Gold</li>
                        <li><i class="fas fa-check"></i> Personal training 4×</li>
                        <li><i class="fas fa-check"></i> Kelas zumba & yoga</li>
                        <li><i class="fas fa-check"></i> Suplemen gratis 1 bulan</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-buy-ghost">Pilih Platinum</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── LOKASI KAMI ─── --}}
    <section class="section" id="location" style="background:rgba(255,255,255,0.015); border-top:1px solid rgba(255,255,255,0.05);">
        <div class="container">
            <div class="section-header">
                <div class="section-pill">Lokasi Kami</div>
                <h2 class="section-title">Kunjungi Umah Dauh GYM</h2>
                <p class="section-sub">Temukan kami dengan mudah dan mulai sesi workout Anda hari ini</p>
            </div>
            
            <div class="location-grid">
                <div class="location-info-card">
                    <div style="font-size: 2.2rem; margin-bottom: 1rem;">📍</div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem; color: #fff;">Alamat Gym</h3>
                    <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;">
                        Banjar Dangin Yeh, Bali, Indonesia
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="https://maps.app.goo.gl/csdENYPFMX1A8NZF9" target="_blank" class="btn-map google-maps-btn">
                            <i class="fab fa-google" style="margin-right: 0.5rem;"></i> Buka di Google Maps
                        </a>
                        <a href="http://maps.apple.com/?q=-8.5950911,115.17674" target="_blank" class="btn-map apple-maps-btn">
                            <i class="fab fa-apple" style="margin-right: 0.5rem;"></i> Buka di Apple Maps
                        </a>
                    </div>
                </div>
                
                <div class="map-iframe-container">
                    <iframe 
                        src="https://maps.google.com/maps?q=-8.5950911,115.17674&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Umah Dauh GYM Map"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── CTA ─── --}}
    <div class="cta-wrap">
        <div class="section-pill" style="margin-bottom:1rem;">Bergabung Sekarang</div>
        <h2>Siap Memulai Perjalanan<br>Fitness Anda?</h2>
        <p>Daftar sekarang dan dapatkan QR Code member eksklusif Anda</p>
        <div class="cta-btns">
            <a href="{{ route('register') }}" class="btn-primary-lg">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn-ghost-lg">
                <i class="fas fa-sign-in-alt"></i> Sudah Punya Akun?
            </a>
        </div>
    </div>

    {{-- ─── FOOTER ─── --}}
    <footer>
        <div class="footer-logo">💪 Umah Dauh GYM</div>
        <p>Jl. Gym Raya No. 1, Bali, Indonesia &nbsp;|&nbsp; Tel: +62 812-3456-7890</p>
        <p>© {{ date('Y') }} Umah Dauh GYM. All rights reserved.</p>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });

        // Hero image subtle zoom on load
        const heroBg = document.getElementById('heroBg');
        heroBg.addEventListener('load', () => heroBg.classList.add('loaded'));
        if (heroBg.complete) heroBg.classList.add('loaded');

        // Detect iOS to optimize Apple Maps protocol natively
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (isIOS) {
            const appleMapBtn = document.querySelector('.apple-maps-btn');
            if (appleMapBtn) {
                appleMapBtn.setAttribute('href', 'maps://?q=-8.5950911,115.17674');
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
