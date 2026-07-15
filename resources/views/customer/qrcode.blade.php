@extends('layouts.app')
@section('title', 'QR Code Saya')
@section('page-title', '📲 QR Code Saya')
@section('content')

<div style="max-width:520px;margin:0 auto;">
    {{-- QR Card --}}
    <div style="background:linear-gradient(135deg,rgba(255,140,0,0.12),rgba(255,69,0,0.08));border:1px solid rgba(255,140,0,0.3);border-radius:24px;padding:2rem 1.5rem;text-align:center;margin-bottom:1.25rem;">
        {{-- Avatar + Name --}}
        <img src="{{ auth()->user()->photo_url }}" alt="Avatar"
            style="width:72px;height:72px;border-radius:50%;border:3px solid #ff8c00;object-fit:cover;margin-bottom:0.85rem;">
        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:0.4rem;">{{ auth()->user()->name }}</h2>
        <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
            <span style="background:{{ $subscription->membership->badge_color }};color:#000;padding:0.2rem 0.8rem;border-radius:20px;font-size:0.75rem;font-weight:700;">
                {{ strtoupper($subscription->membership->name) }}
            </span>
            <span class="badge badge-active">● AKTIF</span>
        </div>

        {{-- QR Code --}}
        <div style="background:white;border-radius:18px;padding:1.25rem;display:inline-block;margin-bottom:1.5rem;box-shadow:0 0 40px rgba(255,140,0,0.2);">
            <img src="{{ route('qrcode.generate', $subscription->qr_token) }}"
                alt="QR Code Member"
                style="width:min(220px, 70vw);height:min(220px, 70vw);display:block;">
        </div>

        {{-- Info Grid --}}
        <div style="background:rgba(0,0,0,0.3);border-radius:12px;padding:1rem;margin-bottom:1.25rem;text-align:left;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;font-size:0.83rem;">
                <div style="color:#94a3b8;">Berlaku Mulai:</div>
                <div style="font-weight:600;">{{ $subscription->start_date->format('d M Y') }}</div>
                <div style="color:#94a3b8;">Berlaku Hingga:</div>
                <div style="font-weight:600;color:#ff8c00;">{{ $subscription->end_date->format('d M Y') }}</div>
                <div style="color:#94a3b8;">Sisa Hari:</div>
                <div style="font-weight:700;color:#4ade80;">{{ $subscription->days_remaining }} hari</div>
            </div>
        </div>

        <p style="color:#94a3b8;font-size:0.8rem;">
            <i class="fas fa-info-circle"></i>
            Tunjukkan QR Code ini kepada petugas gym saat masuk
        </p>
    </div>

    {{-- Instructions --}}
    <div class="card">
        <div class="card-title"><i class="fas fa-lightbulb" style="color:#ffd700;"></i> Cara Penggunaan</div>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach([
                ['1', 'Buka halaman QR Code ini di smartphone Anda'],
                ['2', 'Tunjukkan QR Code kepada petugas atau dekatkan ke scanner'],
                ['3', 'Kunjungan Anda akan tercatat otomatis di sistem'],
            ] as [$num, $text])
            <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <div style="width:28px;height:28px;background:rgba(255,140,0,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;color:#ff8c00;flex-shrink:0;">{{ $num }}</div>
                <p style="font-size:0.88rem;color:#94a3b8;line-height:1.5;">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
