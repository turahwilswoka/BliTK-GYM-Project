@extends('layouts.admin')
@section('title', 'Detail Member')
@section('page-title', '👤 Detail Member')
@section('content')

<div class="members-detail-layout">
    <!-- Profile Card -->
    <div class="card" style="text-align:center;">
        <img src="{{ $user->photo_url }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #6366f1;margin-bottom:1rem;">
        <h3 style="font-weight:700;margin-bottom:0.25rem;">{{ $user->name }}</h3>
        <p style="color:#64748b;font-size:0.85rem;margin-bottom:1rem;">{{ $user->email }}</p>
        @if($activeSubscription)
            <span class="badge badge-active" style="margin-bottom:0.5rem;">Membership Aktif</span>
            <div style="font-size:0.8rem;color:#94a3b8;margin-top:0.25rem;">{{ $activeSubscription->membership->name }}</div>
        @else
            <span class="badge badge-expired">Tidak Aktif</span>
        @endif
        <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:1.25rem 0;">
        <div style="text-align:left;font-size:0.85rem;">
            <div style="display:grid;grid-template-columns:auto 1fr;gap:0.5rem 1rem;">
                <span style="color:#64748b;">HP:</span><span>{{ $user->phone ?? '-' }}</span>
                <span style="color:#64748b;">Gender:</span><span>{{ $user->gender === 'male' ? 'Laki-laki' : ($user->gender === 'female' ? 'Perempuan' : '-') }}</span>
                <span style="color:#64748b;">Lahir:</span><span>{{ $user->birth_date?->format('d M Y') ?? '-' }}</span>
                <span style="color:#64748b;">Bergabung:</span><span>{{ $user->created_at->format('d M Y') }}</span>
            </div>
        </div>
        @if($activeSubscription)
        <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:1.25rem 0;">
        <div style="background:rgba(255,255,255,0.04);border-radius:12px;padding:1rem;text-align:left;">
            <div style="font-size:0.8rem;color:#64748b;margin-bottom:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Membership Aktif</div>
            <img src="{{ route('qrcode.generate', $activeSubscription->qr_token) }}" style="width:150px;height:150px;background:white;border-radius:10px;padding:8px;display:block;margin:0 auto 0.75rem;">
            <div style="font-size:0.82rem;display:grid;grid-template-columns:auto 1fr;gap:0.4rem 0.75rem;">
                <span style="color:#64748b;">Paket:</span><span style="font-weight:600;">{{ $activeSubscription->membership->name }}</span>
                <span style="color:#64748b;">Mulai:</span><span>{{ $activeSubscription->start_date?->format('d/m/Y') }}</span>
                <span style="color:#64748b;">Selesai:</span><span style="color:#ff8c00;font-weight:600;">{{ $activeSubscription->end_date?->format('d/m/Y') }}</span>
                <span style="color:#64748b;">Sisa:</span><span style="color:#4ade80;font-weight:700;">{{ $activeSubscription->days_remaining }} hari</span>
            </div>
        </div>
        @endif
        <div style="margin-top:1rem;">
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <!-- Detail Content -->
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <!-- Subscription History -->
        <div class="card">
            <div class="card-title"><i class="fas fa-history" style="color:#6366f1;margin-right:0.5rem;"></i>Riwayat Membership</div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Paket</th><th>Harga</th><th>Status</th><th>Mulai</th><th>Selesai</th></tr></thead>
                    <tbody>
                        @forelse($user->subscriptions as $sub)
                        <tr>
                            <td>{{ $sub->membership->name ?? '-' }}</td>
                            <td>Rp {{ number_format($sub->amount_paid, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span></td>
                            <td>{{ $sub->start_date?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $sub->end_date?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:#64748b;">Belum ada riwayat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attendance Logs -->
        <div class="card">
            <div class="card-title"><i class="fas fa-door-open" style="color:#4ade80;margin-right:0.5rem;"></i>Riwayat Kunjungan ({{ $user->attendanceLogs->count() }}x)</div>
            <div class="table-wrap" style="max-height:300px;overflow-y:auto;">
                <table>
                    <thead><tr><th>Waktu Masuk</th><th>Paket</th><th>Dicatat oleh</th></tr></thead>
                    <tbody>
                        @forelse($user->attendanceLogs as $log)
                        <tr>
                            <td>{{ $log->scanned_at->format('d M Y, H:i') }}</td>
                            <td>{{ $log->subscription->membership->name ?? '-' }}</td>
                            <td style="color:#64748b;font-size:0.82rem;">{{ $log->scannedBy?->name ?? 'Sistem' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:#64748b;">Belum ada riwayat kunjungan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.members-detail-layout { display:grid; grid-template-columns:1fr; gap:1rem; }
@media (min-width:700px) { .members-detail-layout { grid-template-columns:280px 1fr; gap:1.5rem; align-items:start; } }
</style>
@endpush
@endsection
