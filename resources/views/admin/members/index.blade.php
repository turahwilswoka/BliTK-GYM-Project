@extends('layouts.admin')
@section('title', 'Data Member')
@section('page-title', '👥 Data Member')
@section('content')

<div class="card">
    <form method="GET" style="display:flex;gap:0.75rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, HP..." class="form-control">
        <select name="status" class="form-control" style="max-width:180px;">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>No. HP</th>
                    <th>Status Membership</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                @php $sub = $member->activeSubscription; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <img src="{{ $member->photo_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.1);">
                            <div>
                                <div style="font-weight:600;">{{ $member->name }}</div>
                                <div style="font-size:0.78rem;color:#64748b;">{{ $member->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.85rem;color:#94a3b8;">{{ $member->phone ?? '-' }}</td>
                    <td>
                        @if($sub)
                            <span class="badge badge-active">Aktif</span>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">{{ $sub->membership->name ?? '' }} – s/d {{ $sub->end_date?->format('d/m/Y') }}</div>
                        @else
                            <span class="badge badge-expired">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;color:#64748b;">{{ $member->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary" style="font-size:0.78rem;padding:0.35rem 0.75rem;">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:3rem;color:#374151;">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">👥</div>
                        Tidak ada member ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $members->appends(request()->query())->links() }}</div>
</div>
@endsection
