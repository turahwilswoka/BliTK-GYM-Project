@extends('layouts.admin')
@section('title', 'Pembayaran')
@section('page-title', '💰 Manajemen Pembayaran')
@section('content')

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Tgl. Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $payment->user->name }}</div>
                        <div style="font-size:0.78rem;color:#64748b;">{{ $payment->user->email }}</div>
                    </td>
                    <td>{{ $payment->membership->name }}</td>
                    <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->status === 'active')
                            <span class="badge badge-active">Aktif</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($payment->status === 'expired')
                            <span class="badge badge-expired">Expired</span>
                        @else
                            <span class="badge badge-cancelled">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="btn btn-secondary" style="font-size:0.75rem;padding:0.3rem 0.6rem;">
                                <i class="fas fa-image"></i> Lihat
                            </a>
                        @else
                            <span style="color:#374151;font-size:0.8rem;">-</span>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;color:#64748b;">{{ $payment->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($payment->status === 'pending')
                        <div style="display:flex;gap:0.5rem;">
                            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                                @csrf
                                <button type="submit" class="btn btn-success" style="font-size:0.78rem;padding:0.35rem 0.7rem;"
                                    onclick="return confirm('Konfirmasi pembayaran {{ $payment->user->name }}?')">
                                    <i class="fas fa-check"></i> Konfirmasi
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="font-size:0.78rem;padding:0.35rem 0.7rem;"
                                    onclick="return confirm('Tolak pembayaran ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        </div>
                        @elseif($payment->status === 'active')
                            <span style="font-size:0.78rem;color:#4ade80;">
                                <i class="fas fa-check-circle"></i> {{ $payment->confirmed_at?->format('d/m/Y') }}
                            </span>
                        @else
                            <span style="color:#374151;font-size:0.8rem;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:#374151;">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">📋</div>
                        Belum ada data pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $payments->links() }}</div>
</div>
@endsection
