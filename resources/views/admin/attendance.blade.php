@extends('layouts.admin')
@section('title', 'Log Kunjungan')
@section('page-title', '📋 Log Kunjungan')
@section('content')

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Paket</th>
                    <th>Waktu Masuk</th>
                    <th>Dicatat oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $log->user->name }}</div>
                        <div style="font-size:0.78rem;color:#64748b;">{{ $log->user->email }}</div>
                    </td>
                    <td style="font-size:0.85rem;">{{ $log->subscription->membership->name ?? '-' }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $log->scanned_at->format('d M Y') }}</div>
                        <div style="font-size:0.78rem;color:#64748b;">{{ $log->scanned_at->format('H:i:s') }}</div>
                    </td>
                    <td style="font-size:0.85rem;color:#94a3b8;">{{ $log->scannedBy?->name ?? 'Sistem' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:3rem;color:#374151;">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">📋</div>
                        Belum ada log kunjungan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $logs->links() }}</div>
</div>
@endsection
