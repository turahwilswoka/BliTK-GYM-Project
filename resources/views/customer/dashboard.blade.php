@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '🏠 Dashboard')

@push('styles')
<style>
    /* ─── HERO BANNER ─────────────────────────────────────────────────── */
    .dash-hero {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        min-height: 260px;
        display: flex;
        align-items: flex-end;
    }

    .dash-hero-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 40%;
        transition: transform 8s ease;
    }

    .dash-hero:hover .dash-hero-img {
        transform: scale(1.04);
    }

    /* Multi-layer gradient overlay */
    .dash-hero-overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(to top,  rgba(0,0,0,0.92) 0%,  rgba(0,0,0,0.55) 45%, rgba(0,0,0,0.15) 100%),
            linear-gradient(to right, rgba(0,0,0,0.4) 0%,  transparent 60%);
    }

    /* Animated grain texture for premium feel */
    .dash-hero-overlay::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        opacity: 0.3;
        mix-blend-mode: overlay;
    }

    .dash-hero-content {
        position: relative;
        z-index: 2;
        padding: 1.75rem 2rem;
        width: 100%;
    }

    .dash-hero-greeting {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #ff8c00;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .dash-hero-name {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    .dash-hero-name span {
        background: linear-gradient(135deg, #ff8c00, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Pill badges on hero */
    .hero-badges {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(12px);
    }

    .hero-badge-active {
        background: rgba(34,197,94,0.2);
        border: 1px solid rgba(34,197,94,0.4);
        color: #4ade80;
    }

    .hero-badge-pkg {
        background: rgba(255,140,0,0.2);
        border: 1px solid rgba(255,140,0,0.4);
        color: #ffa533;
    }

    .hero-badge-days {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.85);
    }

    /* Decorative corner accent */
    .hero-corner-accent {
        position: absolute;
        top: 1.5rem;
        right: 1.75rem;
        z-index: 3;
        text-align: right;
    }

    .hero-qr-mini {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        border: 2px solid rgba(255,140,0,0.6);
        box-shadow: 0 8px 24px rgba(0,0,0,0.5);
        display: block;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hero-qr-mini:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 32px rgba(255,140,0,0.4);
    }

    .hero-qr-label {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.5);
        margin-top: 0.3rem;
        letter-spacing: 0.04em;
    }

    /* ─── STATS ─────────────────────────────────────────────────────────── */
    .dash-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.85rem;
        margin-bottom: 1.5rem;
    }

    .dash-stat {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1.1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .dash-stat:hover {
        border-color: rgba(255,140,0,0.3);
        background: rgba(255,140,0,0.05);
        transform: translateY(-2px);
    }

    .dash-stat::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: var(--stat-color, #ff8c00);
        opacity: 0.06;
    }

    .dash-stat-icon {
        font-size: 1.6rem;
        margin-bottom: 0.4rem;
        display: block;
    }

    .dash-stat-val {
        font-size: 1.7rem;
        font-weight: 900;
        color: var(--stat-color, #ff8c00);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .dash-stat-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 500;
    }

    /* ─── QUICK ACTIONS ─────────────────────────────────────────────────── */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .quick-action-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1.1rem 0.75rem;
        text-align: center;
        text-decoration: none;
        color: #e2e8f0;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }

    .quick-action-card:hover {
        border-color: rgba(255,140,0,0.35);
        background: rgba(255,140,0,0.07);
        transform: translateY(-3px);
        color: #ffa533;
        box-shadow: 0 8px 24px rgba(255,140,0,0.12);
    }

    .qa-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255,140,0,0.1);
        border: 1px solid rgba(255,140,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.25s;
    }

    .quick-action-card:hover .qa-icon {
        background: rgba(255,140,0,0.2);
        border-color: rgba(255,140,0,0.4);
    }

    .qa-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* ─── ATTENDANCE ──────────────────────────────────────────────────── */
    .attendance-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: background 0.2s;
    }

    .attendance-item:last-child { border-bottom: none; }

    .attendance-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: #4ade80;
        flex-shrink: 0;
    }

    /* ─── NO MEMBERSHIP ─────────────────────────────────────────────────── */
    .no-membership-hero {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        min-height: 220px;
        display: flex;
        align-items: center;
    }

    .no-mem-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        filter: brightness(0.35);
    }

    .no-mem-content {
        position: relative;
        z-index: 2;
        padding: 2rem;
        text-align: center;
        width: 100%;
    }

    /* ─── RESPONSIVE ──────────────────────────────────────────────────── */
    @media (max-width: 640px) {
        .dash-hero { min-height: 200px; }
        .dash-hero-content { padding: 1.25rem; }
        .dash-hero-name { font-size: 1.4rem; }
        .hero-corner-accent { display: none; }
        .dash-stats { grid-template-columns: repeat(3, 1fr); gap: 0.6rem; }
        .dash-stat { padding: 0.85rem 0.5rem; }
        .dash-stat-val { font-size: 1.4rem; }
        .quick-actions { grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
        .qa-icon { width: 38px; height: 38px; font-size: 1rem; }
        .qa-label { font-size: 0.65rem; }
    }

    @media (max-width: 380px) {
        .quick-actions { grid-template-columns: repeat(2, 1fr); }
        .dash-stats { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

@if($subscription)
{{-- ─── HERO BANNER (with image background) ─── --}}
<div class="dash-hero">
    <img
        src="{{ asset('images/gym-dashboard-bg.png') }}"
        alt="Umah Dauh GYM"
        class="dash-hero-img"
        loading="eager"
    >
    <div class="dash-hero-overlay"></div>

    {{-- QR Mini (top right) --}}
    <div class="hero-corner-accent">
        <a href="{{ route('customer.qrcode') }}">
            <img
                src="{{ route('qrcode.generate', $subscription->qr_token) }}"
                alt="QR Code"
                class="hero-qr-mini"
            >
        </a>
        <div class="hero-qr-label">Tap QR Code</div>
    </div>

    {{-- Content (bottom) --}}
    <div class="dash-hero-content">
        <div class="dash-hero-greeting">
            <i class="fas fa-sun"></i>
            Selamat Datang Kembali
        </div>
        <div class="dash-hero-name">
            {{ auth()->user()->name }}<br>
            <span>{{ $subscription->membership->name }}</span>
        </div>
        <div class="hero-badges">
            <span class="hero-badge hero-badge-active">
                <i class="fas fa-circle" style="font-size:0.5rem;"></i> AKTIF
            </span>
            <span class="hero-badge hero-badge-days">
                <i class="fas fa-clock" style="font-size:0.7rem;"></i>
                {{ $subscription->days_remaining }} hari lagi
            </span>
            <span class="hero-badge hero-badge-pkg">
                <i class="fas fa-calendar-alt" style="font-size:0.7rem;"></i>
                s/d {{ $subscription->end_date->format('d M Y') }}
            </span>
        </div>
    </div>
</div>

@else
{{-- ─── NO MEMBERSHIP HERO ─── --}}
<div class="no-membership-hero">
    <img
        src="{{ asset('images/gym-dashboard-bg.png') }}"
        alt="Umah Dauh GYM"
        class="no-mem-img"
    >
    <div class="no-mem-content">
        <div style="font-size:2.5rem;margin-bottom:0.6rem;">🎟️</div>
        <h3 style="font-size:1.25rem;font-weight:800;color:#fff;margin-bottom:0.4rem;">Belum Ada Membership Aktif</h3>
        <p style="color:rgba(255,255,255,0.55);font-size:0.88rem;margin-bottom:1.25rem;">
            Beli paket membership untuk mengakses fasilitas gym
        </p>
        <a href="{{ route('customer.membership') }}" style="
            display:inline-flex;align-items:center;gap:0.5rem;
            background:linear-gradient(135deg,#ff8c00,#ff4500);
            color:#fff;font-weight:700;font-size:0.9rem;
            padding:0.7rem 1.5rem;border-radius:50px;
            text-decoration:none;
            box-shadow:0 8px 24px rgba(255,140,0,0.4);
            transition:all 0.3s ease;
        ">
            <i class="fas fa-fire"></i> Beli Membership Sekarang
        </a>
    </div>
</div>
@endif

{{-- ─── STATS ─── --}}
<div class="dash-stats">
    <div class="dash-stat" style="--stat-color:#ff8c00;">
        <span class="dash-stat-icon">🏋️</span>
        <div class="dash-stat-val">{{ $subscription ? $subscription->days_remaining : 0 }}</div>
        <div class="dash-stat-label">Hari Tersisa</div>
    </div>
    <div class="dash-stat" style="--stat-color:#4ade80;">
        <span class="dash-stat-icon">📅</span>
        <div class="dash-stat-val" style="color:#4ade80;">{{ $attendanceCount }}</div>
        <div class="dash-stat-label">Kunjungan Bulan Ini</div>
    </div>
    <div class="dash-stat" style="--stat-color:#60a5fa;">
        <span class="dash-stat-icon">⭐</span>
        <div class="dash-stat-val" style="color:#60a5fa;font-size:{{ $subscription ? '1rem' : '1.2rem' }};">
            {{ $subscription ? explode(' ', $subscription->membership->name)[1] ?? 'VIP' : '–' }}
        </div>
        <div class="dash-stat-label">Paket</div>
    </div>
</div>

{{-- ─── QUICK ACTIONS ─── --}}
<div class="quick-actions">
    <a href="{{ route('customer.profile') }}" class="quick-action-card">
        <div class="qa-icon"><i class="fas fa-user"></i></div>
        <span class="qa-label">Profil</span>
    </a>
    <a href="{{ route('customer.qrcode') }}" class="quick-action-card">
        <div class="qa-icon"><i class="fas fa-qrcode"></i></div>
        <span class="qa-label">QR Code</span>
    </a>
    <a href="{{ route('customer.membership') }}" class="quick-action-card">
        <div class="qa-icon"><i class="fas fa-id-card"></i></div>
        <span class="qa-label">Membership</span>
    </a>
    <a href="{{ route('customer.payment.status') }}" class="quick-action-card">
        <div class="qa-icon"><i class="fas fa-receipt"></i></div>
        <span class="qa-label">Status Bayar</span>
    </a>
</div>

{{-- ─── RECENT ATTENDANCE ─── --}}
@if($recentAttendance->count() > 0)
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <div class="card-title" style="margin:0;display:flex;align-items:center;gap:0.5rem;">
            <span style="width:28px;height:28px;background:rgba(74,222,128,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;">✅</span>
            Riwayat Kunjungan Terbaru
        </div>
    </div>
    @foreach($recentAttendance as $log)
    <div class="attendance-item">
        <div class="attendance-dot">
            <i class="fas fa-check"></i>
        </div>
        <div style="flex:1;">
            <div style="font-size:0.88rem;font-weight:600;">{{ $log->scanned_at->format('d M Y') }}</div>
            <div style="font-size:0.75rem;color:#64748b;">{{ $log->scanned_at->format('H:i') }} WIB &middot; {{ $log->subscription->membership->name ?? '-' }}</div>
        </div>
        <div style="font-size:0.72rem;color:#4ade80;font-weight:600;">
            <i class="fas fa-check-circle"></i> Masuk
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card" style="text-align:center;padding:2.5rem 1rem;">
    <div style="font-size:2.5rem;margin-bottom:0.75rem;opacity:0.35;">🚶</div>
    <div style="color:#475569;font-size:0.88rem;">Belum ada riwayat kunjungan bulan ini.</div>
</div>
@endif

@endsection
