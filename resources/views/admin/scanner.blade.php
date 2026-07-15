@extends('layouts.admin')
@section('title', 'Scanner QR')
@section('page-title', '📷 Scanner QR Code')
@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
    <!-- Scanner Box -->
    <div class="card">
        <div class="card-title"><i class="fas fa-camera" style="color:#6366f1;margin-right:0.5rem;"></i>Kamera Scanner</div>
        <div id="reader" style="width:100%;border-radius:12px;overflow:hidden;background:#000;min-height:250px;"></div>
        <div style="display:flex;gap:0.75rem;margin-top:1rem;">
            <button id="startBtn" onclick="startScanner()" class="btn btn-primary"><i class="fas fa-play"></i> Mulai Scan</button>
            <button id="stopBtn" onclick="stopScanner()" class="btn btn-danger" style="display:none;"><i class="fas fa-stop"></i> Berhenti</button>
        </div>
        <!-- Manual Input -->
        <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid rgba(255,255,255,0.08);">
            <p style="font-size:0.82rem;color:#64748b;margin-bottom:0.75rem;">Atau masukkan token manual:</p>
            <div style="display:flex;gap:0.5rem;">
                <input type="text" id="manualToken" placeholder="Masukkan QR token..." class="form-control">
                <button onclick="validateManual()" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>

    <!-- Result Box -->
    <div>
        <div class="card" id="resultBox" style="min-height:300px;display:flex;align-items:center;justify-content:center;">
            <div style="text-align:center;color:#374151;">
                <div style="font-size:4rem;margin-bottom:1rem;">📷</div>
                <p style="font-size:0.9rem;">Arahkan kamera ke QR Code member<br>untuk memindai</p>
            </div>
        </div>

        <!-- Today Log -->
        @if($todayLogs->count() > 0)
        <div class="card" style="margin-top:1.5rem;">
            <div class="card-title"><i class="fas fa-list" style="color:#4ade80;margin-right:0.5rem;"></i>Kunjungan Hari Ini ({{ $todayLogs->count() }})</div>
            <div style="max-height:250px;overflow-y:auto;">
                @foreach($todayLogs as $log)
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                    <div style="width:30px;height:30px;background:rgba(74,222,128,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;">✓</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->user->name }}</div>
                        <div style="font-size:0.75rem;color:#64748b;">{{ $log->subscription->membership->name ?? '-' }}</div>
                    </div>
                    <div style="font-size:0.75rem;color:#64748b;white-space:nowrap;">{{ $log->scanned_at->format('H:i') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
#reader video { border-radius: 12px; }
.result-valid { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); border-radius: 16px; padding: 1.5rem; }
.result-invalid { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 16px; padding: 1.5rem; }
.result-expired { background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.3); border-radius: 16px; padding: 1.5rem; }
.scan-pulse { animation: pulse 1.5s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.6} }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const scanUrl = "{{ route('admin.scanner.scan') }}";

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        (decodedText) => {
            stopScanner();
            processQrResult(decodedText);
        },
        (error) => { /* Ignore scan errors */ }
    ).then(() => {
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('stopBtn').style.display = 'inline-flex';
    }).catch(err => {
        showResult('error', '❌', 'Kamera Tidak Dapat Diakses', 'Pastikan izin kamera sudah diberikan. ' + err);
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
        html5QrCode = null;
    }
    document.getElementById('startBtn').style.display = 'inline-flex';
    document.getElementById('stopBtn').style.display = 'none';
}

function processQrResult(decodedText) {
    // Extract token from URL if it's a full URL
    let token = decodedText;
    try {
        const url = new URL(decodedText);
        const pathParts = url.pathname.split('/');
        token = pathParts[pathParts.length - 1];
    } catch(e) { /* not a URL, use as-is */ }

    validateToken(token);
}

function validateManual() {
    const token = document.getElementById('manualToken').value.trim();
    if (!token) return alert('Masukkan token terlebih dahulu');
    validateToken(token);
}

function validateToken(token) {
    document.getElementById('resultBox').innerHTML = `
        <div style="text-align:center;" class="scan-pulse">
            <div style="font-size:3rem;margin-bottom:1rem;">🔍</div>
            <p>Memvalidasi...</p>
        </div>`;

    fetch(scanUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ token })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valid) {
            const m = data.member;
            document.getElementById('resultBox').innerHTML = `
                <div class="result-valid">
                    <div style="text-align:center;margin-bottom:1.5rem;">
                        <img src="${m.photo_url}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #4ade80;margin-bottom:0.75rem;">
                        <div style="font-size:2rem;margin-bottom:0.25rem;">✅</div>
                        <h3 style="color:#4ade80;font-size:1.3rem;font-weight:800;">VALID – AKSES DIBERIKAN</h3>
                    </div>
                    <div style="background:rgba(0,0,0,0.3);border-radius:12px;padding:1rem;display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;font-size:0.85rem;">
                        <div style="color:#94a3b8;">Nama:</div><div style="font-weight:600;">${m.name}</div>
                        <div style="color:#94a3b8;">Paket:</div>
                        <div><span style="background:${m.badge_color};color:#000;padding:0.15rem 0.5rem;border-radius:8px;font-size:0.75rem;font-weight:700;">${m.membership}</span></div>
                        <div style="color:#94a3b8;">No. HP:</div><div>${m.phone || '-'}</div>
                        <div style="color:#94a3b8;">Berlaku s/d:</div><div style="color:#ff8c00;font-weight:600;">${m.end_date}</div>
                        <div style="color:#94a3b8;">Sisa:</div><div style="color:#4ade80;font-weight:700;">${m.days_remaining} hari</div>
                    </div>
                    <button onclick="resetScanner()" style="width:100%;margin-top:1rem;" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Scan Berikutnya
                    </button>
                </div>`;
            // Reload page after 5 seconds to update today log
            setTimeout(() => location.reload(), 5000);
        } else {
            const cls = data.status === 'EXPIRED' ? 'result-expired' : 'result-invalid';
            const icon = data.status === 'EXPIRED' ? '⏰' : '❌';
            const color = data.status === 'EXPIRED' ? '#facc15' : '#f87171';
            document.getElementById('resultBox').innerHTML = `
                <div class="${cls}" style="text-align:center;">
                    <div style="font-size:3rem;margin-bottom:0.75rem;">${icon}</div>
                    <h3 style="color:${color};font-size:1.2rem;font-weight:800;margin-bottom:0.5rem;">${data.status}</h3>
                    <p style="color:#94a3b8;font-size:0.9rem;margin-bottom:1.25rem;">${data.message}</p>
                    ${data.member ? `<div style="background:rgba(0,0,0,0.3);border-radius:10px;padding:0.75rem;font-size:0.85rem;text-align:left;">
                        <div><span style="color:#94a3b8;">Nama:</span> <strong>${data.member.name}</strong></div>
                        <div><span style="color:#94a3b8;">Paket:</span> ${data.member.membership}</div>
                        ${data.member.end_date ? `<div><span style="color:#94a3b8;">Berakhir:</span> ${data.member.end_date}</div>` : ''}
                    </div>` : ''}
                    <button onclick="resetScanner()" style="width:100%;margin-top:1rem;" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>`;
        }
    })
    .catch(err => {
        showResult('error', '⚠️', 'Terjadi Kesalahan', 'Gagal menghubungi server. Coba lagi.');
    });
}

function resetScanner() {
    document.getElementById('resultBox').innerHTML = `
        <div style="text-align:center;color:#374151;">
            <div style="font-size:4rem;margin-bottom:1rem;">📷</div>
            <p style="font-size:0.9rem;">Arahkan kamera ke QR Code member</p>
        </div>`;
    document.getElementById('manualToken').value = '';
}

function showResult(type, icon, title, message) {
    document.getElementById('resultBox').innerHTML = `
        <div style="text-align:center;padding:2rem;">
            <div style="font-size:3rem;margin-bottom:1rem;">${icon}</div>
            <h3 style="margin-bottom:0.5rem;">${title}</h3>
            <p style="color:#94a3b8;font-size:0.88rem;">${message}</p>
        </div>`;
}
</script>
@endpush
@endsection
