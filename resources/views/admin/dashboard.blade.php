@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', '📊 Admin Dashboard')
@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(2,1fr);">
    <div class="stat-card" style="border-left:3px solid #6366f1;">
        <div style="font-size:1.4rem;margin-bottom:0.4rem;">👥</div>
        <div class="stat-value">{{ $totalMembers }}</div>
        <div class="stat-label">Total Member</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #4ade80;">
        <div style="font-size:1.4rem;margin-bottom:0.4rem;">✅</div>
        <div class="stat-value" style="color:#4ade80;">{{ $activeMembers }}</div>
        <div class="stat-label">Member Aktif</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #facc15;">
        <div style="font-size:1.4rem;margin-bottom:0.4rem;">⏳</div>
        <div class="stat-value" style="color:#facc15;">{{ $pendingPayments }}</div>
        <div class="stat-label">Pembayaran Pending</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #60a5fa;">
        <div style="font-size:1.4rem;margin-bottom:0.4rem;">🚪</div>
        <div class="stat-value" style="color:#60a5fa;">{{ $todayAttendance }}</div>
        <div class="stat-label">Kunjungan Hari Ini</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #ff8c00;grid-column:span 2;">
        <div style="font-size:1.4rem;margin-bottom:0.4rem;">💰</div>
        <div class="stat-value" style="color:#ff8c00;font-size:clamp(1rem,3vw,1.4rem);">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Bulan Ini</div>
    </div>
</div>

<div class="admin-dash-grid">
    <!-- Pending Payments -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="card-title" style="margin:0;"><i class="fas fa-clock" style="color:#facc15;margin-right:0.5rem;"></i>Pembayaran Menunggu</div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary" style="font-size:0.8rem;padding:0.4rem 0.8rem;">Lihat Semua</a>
        </div>
        @forelse($recentPayments as $payment)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div>
                <div style="font-weight:600;font-size:0.88rem;">{{ $payment->user->name }}</div>
                <div style="font-size:0.78rem;color:#94a3b8;">{{ $payment->membership->name }} – Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</div>
            </div>
            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                @csrf
                <button type="submit" class="btn btn-success" style="font-size:0.75rem;padding:0.35rem 0.7rem;">
                    <i class="fas fa-check"></i> Konfirmasi
                </button>
            </form>
        </div>
        @empty
        <p style="color:#64748b;text-align:center;padding:1.5rem;font-size:0.88rem;">Tidak ada pembayaran pending</p>
        @endforelse
    </div>

    <!-- Recent Attendance -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="card-title" style="margin:0;"><i class="fas fa-door-open" style="color:#4ade80;margin-right:0.5rem;"></i>Kunjungan Terbaru</div>
            <a href="{{ route('admin.attendance') }}" class="btn btn-secondary" style="font-size:0.8rem;padding:0.4rem 0.8rem;">Lihat Semua</a>
        </div>
        @forelse($recentAttendance as $log)
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="width:32px;height:32px;background:rgba(74,222,128,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;color:#4ade80;">✓</div>
            <div style="flex:1;">
                <div style="font-size:0.85rem;font-weight:600;">{{ $log->user->name }}</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $log->subscription->membership->name ?? '-' }}</div>
            </div>
            <div style="font-size:0.75rem;color:#64748b;">{{ $log->scanned_at->format('H:i') }}</div>
        </div>
        @empty
        <p style="color:#64748b;text-align:center;padding:1.5rem;font-size:0.88rem;">Belum ada kunjungan hari ini</p>
        @endforelse
    </div>
</div>

<!-- Quick Actions -->
<div style="display:flex;gap:0.75rem;margin-top:1.25rem;flex-wrap:wrap;">
    <a href="{{ route('admin.scanner') }}" class="btn btn-primary"><i class="fas fa-qrcode"></i> Scanner QR</a>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary"><i class="fas fa-money-bill"></i> Pembayaran</a>
    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary"><i class="fas fa-users"></i> Semua Member</a>
</div>

@push('styles')
<style>
.admin-dash-grid { display:grid; grid-template-columns:1fr; gap:1rem; }
@media (min-width:700px) { .admin-dash-grid { grid-template-columns:1fr 1fr; gap:1.5rem; } }
</style>
@endpush
@endsection
