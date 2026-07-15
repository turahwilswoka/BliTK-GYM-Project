@extends('layouts.app')
@section('title', 'Status Pembayaran')
@section('page-title', '💳 Status Pembayaran')
@section('content')

{{-- Summary Card --}}
@php
    $latestPending = $subscriptions->firstWhere('status', 'pending');
    $latestActive  = $subscriptions->firstWhere('status', 'active');
@endphp

{{-- Top Banner sesuai status terkini --}}
@if($latestPending)
<div style="background:linear-gradient(135deg,rgba(234,179,8,0.12),rgba(234,179,8,0.04));border:1px solid rgba(234,179,8,0.3);border-radius:20px;padding:1.75rem 2rem;margin-bottom:1.75rem;display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
    <div style="width:56px;height:56px;background:rgba(234,179,8,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.75rem;flex-shrink:0;">⏳</div>
    <div style="flex:1;">
        <div style="font-size:1.05rem;font-weight:800;margin-bottom:0.2rem;color:#facc15;">Pembayaran Sedang Diproses</div>
        <div style="font-size:0.88rem;color:#94a3b8;">
            Paket <strong style="color:#fef08a;">{{ $latestPending->membership->name ?? '-' }}</strong> — Admin akan mengkonfirmasi dalam 1×24 jam.
        </div>
    </div>
    <span style="background:rgba(234,179,8,0.2);color:#facc15;font-size:0.72rem;font-weight:700;padding:0.3rem 0.9rem;border-radius:20px;border:1px solid rgba(234,179,8,0.3);">MENUNGGU</span>
</div>
@elseif($latestActive)
<div style="background:linear-gradient(135deg,rgba(34,197,94,0.12),rgba(34,197,94,0.04));border:1px solid rgba(34,197,94,0.3);border-radius:20px;padding:1.75rem 2rem;margin-bottom:1.75rem;display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
    <div style="width:56px;height:56px;background:rgba(34,197,94,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.75rem;flex-shrink:0;">✅</div>
    <div style="flex:1;">
        <div style="font-size:1.05rem;font-weight:800;margin-bottom:0.2rem;color:#4ade80;">Membership Aktif</div>
        <div style="font-size:0.88rem;color:#94a3b8;">
            Paket <strong style="color:#86efac;">{{ $latestActive->membership->name ?? '-' }}</strong> berlaku hingga
            <strong style="color:#4ade80;">{{ $latestActive->end_date?->format('d M Y') }}</strong>
            ({{ $latestActive->days_remaining }} hari lagi)
        </div>
    </div>
    <a href="{{ route('customer.dashboard') }}" class="btn btn-success">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
</div>
@else
<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:1.75rem 2rem;margin-bottom:1.75rem;display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
    <div style="width:56px;height:56px;background:rgba(255,255,255,0.06);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.75rem;flex-shrink:0;">🎫</div>
    <div style="flex:1;">
        <div style="font-size:1.05rem;font-weight:800;margin-bottom:0.2rem;">Belum Ada Membership Aktif</div>
        <div style="font-size:0.88rem;color:#94a3b8;">Beli paket membership untuk mengakses fasilitas gym.</div>
    </div>
    <a href="{{ route('customer.membership') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> Beli Membership
    </a>
</div>
@endif

{{-- Riwayat Pembayaran --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
        <div style="font-size:1rem;font-weight:700;display:flex;align-items:center;gap:0.6rem;">
            <span style="width:32px;height:32px;background:rgba(255,140,0,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;">📋</span>
            Riwayat Transaksi
        </div>
        <a href="{{ route('customer.membership') }}" class="btn btn-primary" style="font-size:0.82rem;padding:0.45rem 0.9rem;">
            <i class="fas fa-plus"></i> Beli Membership
        </a>
    </div>

    @if($subscriptions->isEmpty())
    <div style="text-align:center;padding:4rem 1rem;">
        <div style="font-size:3rem;margin-bottom:1rem;opacity:0.4;">📭</div>
        <div style="color:#64748b;font-size:0.9rem;">Belum ada riwayat pembayaran.</div>
        <a href="{{ route('customer.membership') }}" class="btn btn-primary" style="margin-top:1.25rem;">
            <i class="fas fa-shopping-cart"></i> Beli Membership Sekarang
        </a>
    </div>
    @else

    {{-- Desktop table (hidden on mobile) --}}
    <div class="table-wrap" style="display:none;" id="desktopTable">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Bukti Bayar</th>
                    <th>Tgl. Daftar</th>
                    <th>Berlaku</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $i => $sub)
                <tr>
                    <td style="color:#64748b;font-size:0.8rem;">{{ $subscriptions->firstItem() + $i }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $sub->membership->name ?? '-' }}</div>
                        <div style="font-size:0.75rem;color:#64748b;">{{ $sub->membership->duration_days ?? '-' }} hari</div>
                    </td>
                    <td style="font-weight:600;color:#ff8c00;">Rp {{ number_format($sub->amount_paid, 0, ',', '.') }}</td>
                    <td>
                        @if($sub->status === 'active')
                            <span class="badge badge-active"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Aktif</span>
                        @elseif($sub->status === 'pending')
                            <span class="badge badge-pending"><i class="fas fa-clock" style="margin-right:3px;"></i>Menunggu</span>
                        @elseif($sub->status === 'expired')
                            <span class="badge badge-expired"><i class="fas fa-times-circle" style="margin-right:3px;"></i>Expired</span>
                        @else
                            <span class="badge" style="background:rgba(100,116,139,0.15);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);">
                                <i class="fas fa-ban" style="margin-right:3px;"></i>Ditolak
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($sub->payment_proof)
                            <a href="{{ asset('storage/' . $sub->payment_proof) }}" target="_blank" class="btn btn-secondary" style="font-size:0.74rem;padding:0.28rem 0.6rem;">
                                <i class="fas fa-image"></i> Lihat
                            </a>
                        @else
                            <span style="color:#374151;font-size:0.8rem;">–</span>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;color:#64748b;">{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-size:0.82rem;">
                        @if($sub->start_date && $sub->end_date)
                            <div style="color:#94a3b8;">{{ $sub->start_date->format('d/m/Y') }}</div>
                            <div style="color:#94a3b8;">s/d {{ $sub->end_date->format('d/m/Y') }}</div>
                        @elseif($sub->status === 'pending')
                            <span style="color:#facc15;font-size:0.78rem;">Menunggu konfirmasi</span>
                        @else
                            <span style="color:#64748b;">–</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div id="mobileCards">
        @foreach($subscriptions as $sub)
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:1.1rem;margin-bottom:0.85rem;position:relative;">
            {{-- Status badge top-right --}}
            <div style="position:absolute;top:1rem;right:1rem;">
                @if($sub->status === 'active')
                    <span class="badge badge-active" style="font-size:0.68rem;"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Aktif</span>
                @elseif($sub->status === 'pending')
                    <span class="badge badge-pending" style="font-size:0.68rem;"><i class="fas fa-clock" style="margin-right:3px;"></i>Menunggu</span>
                @elseif($sub->status === 'expired')
                    <span class="badge badge-expired" style="font-size:0.68rem;">Expired</span>
                @else
                    <span class="badge" style="background:rgba(100,116,139,0.15);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);font-size:0.68rem;">Ditolak</span>
                @endif
            </div>

            <div style="font-weight:700;font-size:0.95rem;margin-bottom:0.2rem;padding-right:80px;">{{ $sub->membership->name ?? '-' }}</div>
            <div style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">{{ $sub->created_at->format('d M Y, H:i') }}</div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;margin-bottom:0.75rem;">
                <div>
                    <div style="font-size:0.68rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.15rem;">Harga</div>
                    <div style="font-weight:700;color:#ff8c00;font-size:0.95rem;">Rp {{ number_format($sub->amount_paid, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div style="font-size:0.68rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.15rem;">Durasi</div>
                    <div style="font-size:0.88rem;">{{ $sub->membership->duration_days ?? '-' }} hari</div>
                </div>
                @if($sub->start_date && $sub->end_date)
                <div>
                    <div style="font-size:0.68rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.15rem;">Mulai</div>
                    <div style="font-size:0.88rem;">{{ $sub->start_date->format('d M Y') }}</div>
                </div>
                <div>
                    <div style="font-size:0.68rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.15rem;">Berakhir</div>
                    <div style="font-size:0.88rem;">{{ $sub->end_date->format('d M Y') }}</div>
                </div>
                @elseif($sub->status === 'pending')
                <div style="grid-column:span 2;">
                    <div style="font-size:0.78rem;color:#facc15;">
                        <i class="fas fa-info-circle" style="margin-right:0.3rem;"></i>
                        Menunggu konfirmasi admin (1×24 jam)
                    </div>
                </div>
                @endif
            </div>

            @if($sub->payment_proof)
            <a href="{{ asset('storage/' . $sub->payment_proof) }}" target="_blank" class="btn btn-secondary" style="font-size:0.78rem;padding:0.35rem 0.75rem;width:100%;justify-content:center;">
                <i class="fas fa-image"></i> Lihat Bukti Pembayaran
            </a>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($subscriptions->hasPages())
    <div style="margin-top:1rem;">
        {{ $subscriptions->links() }}
    </div>
    @endif

    @endif
</div>

{{-- Info Box --}}
<div style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.2);border-radius:14px;padding:1.1rem 1.25rem;margin-top:1.25rem;">
    <div style="font-size:0.82rem;color:#60a5fa;font-weight:600;margin-bottom:0.5rem;">
        <i class="fas fa-info-circle" style="margin-right:0.4rem;"></i>Informasi Status
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem 1.5rem;">
        <div style="font-size:0.78rem;color:#94a3b8;display:flex;align-items:center;gap:0.4rem;">
            <span class="badge badge-pending" style="font-size:0.68rem;">Menunggu</span>
            Pembayaran diterima, menunggu verifikasi admin
        </div>
        <div style="font-size:0.78rem;color:#94a3b8;display:flex;align-items:center;gap:0.4rem;">
            <span class="badge badge-active" style="font-size:0.68rem;">Aktif</span>
            Membership aktif & dapat digunakan
        </div>
        <div style="font-size:0.78rem;color:#94a3b8;display:flex;align-items:center;gap:0.4rem;">
            <span class="badge badge-expired" style="font-size:0.68rem;">Expired</span>
            Masa berlaku membership habis
        </div>
        <div style="font-size:0.78rem;color:#94a3b8;display:flex;align-items:center;gap:0.4rem;">
            <span class="badge" style="background:rgba(100,116,139,0.15);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);font-size:0.68rem;">Ditolak</span>
            Pembayaran ditolak admin
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Switch between mobile cards and desktop table based on screen width
    function adaptLayout() {
        const isDesktop = window.innerWidth >= 768;
        document.getElementById('desktopTable').style.display = isDesktop ? 'block' : 'none';
        document.getElementById('mobileCards').style.display  = isDesktop ? 'none' : 'block';
    }
    adaptLayout();
    window.addEventListener('resize', adaptLayout);
</script>
@endpush
@endsection
