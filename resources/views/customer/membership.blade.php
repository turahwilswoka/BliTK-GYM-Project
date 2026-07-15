@extends('layouts.app')
@section('title', 'Membership')
@section('page-title', '🎫 Membership')
@section('content')

@if($activeSubscription)
<!-- Active Membership Banner -->
<div style="background:linear-gradient(135deg,rgba(34,197,94,0.15),rgba(34,197,94,0.05));border:1px solid rgba(34,197,94,0.3);border-radius:20px;padding:2rem;margin-bottom:2rem;">
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
        <div style="font-size:3rem;">✅</div>
        <div style="flex:1;">
            <h3 style="font-weight:800;font-size:1.2rem;margin-bottom:0.25rem;">Membership Aktif</h3>
            <p style="color:#94a3b8;">Paket: <strong style="color:#4ade80;">{{ $activeSubscription->membership->name }}</strong></p>
            <p style="color:#94a3b8;">Berlaku hingga: <strong style="color:#4ade80;">{{ $activeSubscription->end_date->format('d M Y') }}</strong>
                ({{ $activeSubscription->days_remaining }} hari lagi)</p>
        </div>
        <a href="{{ route('customer.qrcode') }}" class="btn btn-success"><i class="fas fa-qrcode"></i> Lihat QR Code</a>
    </div>
</div>
@elseif($pendingSubscription)
<!-- Pending Payment Banner -->
<div style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.3);border-radius:20px;padding:2rem;margin-bottom:2rem;">
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
        <div style="font-size:3rem;">⏳</div>
        <div style="flex:1;">
            <h3 style="font-weight:700;margin-bottom:0.25rem;">Pembayaran Sedang Diproses</h3>
            <p style="color:#94a3b8;">Paket: <strong style="color:#facc15;">{{ $pendingSubscription->membership->name }}</strong></p>
            <p style="color:#94a3b8;font-size:0.85rem;margin-top:0.25rem;">Admin akan mengkonfirmasi pembayaran Anda dalam 1x24 jam</p>
        </div>
        <a href="{{ route('customer.payment.status') }}" class="btn btn-secondary" style="border-color:rgba(234,179,8,0.4);color:#facc15;">
            <i class="fas fa-receipt"></i> Lihat Status
        </a>
    </div>
</div>
@endif

@if(!$activeSubscription && !$pendingSubscription)
<!-- Package Selection -->
<h2 style="font-size:1.2rem;font-weight:700;margin-bottom:1.5rem;">Pilih Paket Membership</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;margin-bottom:2rem;">
    @foreach($memberships as $membership)
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;position:relative;transition:all 0.3s;cursor:pointer;"
        id="pkg-{{ $membership->id }}"
        onclick="selectPackage({{ $membership->id }})"
        class="pkg-card">
        @if($membership->name === 'Paket Gold')
        <div style="position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#ff8c00,#ff4500);color:white;font-size:0.72rem;font-weight:700;padding:0.25rem 1rem;border-radius:0 0 12px 12px;letter-spacing:0.1em;">POPULER</div>
        @endif
        <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;border:2px solid {{ $membership->badge_color }};">
            <span style="font-size:1.5rem;">
                @if(str_contains($membership->name, 'Silver')) 🥈
                @elseif(str_contains($membership->name, 'Gold')) 🥇
                @else 💎
                @endif
            </span>
        </div>
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.25rem;">{{ $membership->name }}</h3>
        <p style="color:#94a3b8;font-size:0.82rem;margin-bottom:1rem;">{{ $membership->description }}</p>
        <div style="font-size:1.8rem;font-weight:800;color:#ff8c00;margin-bottom:0.25rem;">{{ $membership->formatted_price }}</div>
        <div style="font-size:0.8rem;color:#94a3b8;margin-bottom:1.25rem;">/ {{ $membership->duration_label }}</div>
        @if($membership->features)
        <ul style="list-style:none;padding:0;margin-bottom:1.5rem;display:flex;flex-direction:column;gap:0.5rem;">
            @foreach($membership->features as $feature)
            <li style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:#94a3b8;">
                <i class="fas fa-check" style="color:#4ade80;font-size:0.75rem;"></i> {{ $feature }}
            </li>
            @endforeach
        </ul>
        @endif
        <button onclick="selectPackage({{ $membership->id }})" class="btn btn-primary" style="width:100%;">
            Pilih Paket Ini
        </button>
    </div>
    @endforeach
</div>

<!-- Purchase Form (hidden initially) -->
<div id="purchaseForm" style="display:none;background:rgba(255,255,255,0.04);border:1px solid rgba(255,140,0,0.3);border-radius:20px;padding:2rem;">
    <h3 style="font-weight:700;margin-bottom:1.5rem;"><i class="fas fa-credit-card" style="color:#ff8c00;margin-right:0.5rem;"></i>Upload Bukti Pembayaran</h3>
    <div id="selectedPkgInfo" style="background:rgba(255,140,0,0.08);border-radius:12px;padding:1rem;margin-bottom:1.5rem;"></div>
    <form method="POST" action="{{ route('customer.membership.buy') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="membership_id" id="selectedMembershipId">
        <div class="form-group">
            <label>Transfer ke:</label>
            <div style="background:rgba(0,0,0,0.3);border-radius:10px;padding:1rem;font-size:0.9rem;">
                <div>🏦 <strong>Bank BCA</strong> – 1234567890</div>
                <div style="color:#94a3b8;font-size:0.8rem;margin-top:0.25rem;">a.n. Umah Dauh GYM Indonesia</div>
            </div>
        </div>
        <div class="form-group">
            <label for="payment_proof"><i class="fas fa-image" style="margin-right:0.4rem;"></i>Bukti Transfer (JPG/PNG, max 5MB)</label>
            <input type="file" id="payment_proof" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror"
                accept="image/*" required style="cursor:pointer;">
            @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:1rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Kirim Bukti Bayar</button>
            <button type="button" onclick="cancelPurchase()" class="btn btn-secondary">Batal</button>
        </div>
    </form>
</div>
@endif

@push('scripts')
<script>
const memberships = @json($memberships->keyBy('id'));

function selectPackage(id) {
    document.querySelectorAll('.pkg-card').forEach(c => c.style.borderColor = 'rgba(255,255,255,0.08)');
    document.getElementById('pkg-' + id).style.borderColor = '#ff8c00';
    document.getElementById('selectedMembershipId').value = id;
    const pkg = memberships[id];
    document.getElementById('selectedPkgInfo').innerHTML = `
        <div style="font-weight:700;">${pkg.name}</div>
        <div style="color:#ff8c00;font-size:1.2rem;font-weight:800;">Rp ${Number(pkg.price).toLocaleString('id-ID')}</div>
        <div style="color:#94a3b8;font-size:0.82rem;">${pkg.duration_days} hari</div>
    `;
    document.getElementById('purchaseForm').style.display = 'block';
    document.getElementById('purchaseForm').scrollIntoView({behavior:'smooth'});
}
function cancelPurchase() {
    document.getElementById('purchaseForm').style.display = 'none';
    document.querySelectorAll('.pkg-card').forEach(c => c.style.borderColor = 'rgba(255,255,255,0.08)');
}
</script>
@endpush
@endsection
