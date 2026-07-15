@extends('layouts.admin')
@section('title', 'Live Chat')
@section('page-title', '💬 Live Chat')

@push('styles')
<style>
    .chat-tabs { display:flex; gap:0.5rem; margin-bottom:1.25rem; flex-wrap:wrap; }
    .chat-tab {
        padding:0.5rem 1.1rem; border-radius:50px; font-size:0.82rem; font-weight:600;
        cursor:pointer; border:1px solid rgba(255,255,255,0.1);
        background:rgba(255,255,255,0.04); color:#94a3b8; transition:all 0.2s;
    }
    .chat-tab.active, .chat-tab:hover {
        background:rgba(99,102,241,0.15); border-color:rgba(99,102,241,0.4); color:#818cf8;
    }
    .tab-pane { display:none; }
    .tab-pane.active { display:block; }

    .session-list { display:flex; flex-direction:column; gap:0.65rem; }
    .session-card {
        background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);
        border-radius:14px; padding:1rem 1.1rem;
        display:flex; align-items:center; gap:0.9rem; transition:all 0.2s;
    }
    .session-card:hover { border-color:rgba(99,102,241,0.3); background:rgba(99,102,241,0.05); }
    .session-avatar {
        width:42px; height:42px; border-radius:50%; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:1.1rem;
    }
    .session-avatar.waiting { background:rgba(250,204,21,0.15); border:1px solid rgba(250,204,21,0.3); }
    .session-avatar.active  { background:rgba(74,222,128,0.15); border:1px solid rgba(74,222,128,0.3); }
    .session-avatar.completed { background:rgba(100,116,139,0.15); border:1px solid rgba(100,116,139,0.3); }
    .session-info { flex:1; min-width:0; }
    .session-name { font-size:0.9rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .session-meta { font-size:0.75rem; color:#64748b; margin-top:0.15rem; }
    .session-actions { display:flex; gap:0.4rem; flex-shrink:0; flex-wrap:wrap; justify-content:flex-end; }

    .badge-unread {
        background:#ef4444; color:#fff; border-radius:50px;
        font-size:0.65rem; font-weight:700; padding:0.15rem 0.5rem;
        min-width:20px; text-align:center;
    }

    .empty-state { text-align:center; padding:3rem 1rem; color:#475569; }
    .empty-state .icon { font-size:2.5rem; opacity:0.3; margin-bottom:0.75rem; }
    .empty-state p { font-size:0.88rem; }

    /* Status badges */
    .status-pill {
        font-size:0.68rem; font-weight:700; padding:0.2rem 0.65rem;
        border-radius:50px;
    }
    .status-waiting  { background:rgba(250,204,21,0.15); color:#facc15; border:1px solid rgba(250,204,21,0.3); }
    .status-active   { background:rgba(74,222,128,0.15); color:#4ade80; border:1px solid rgba(74,222,128,0.3); }
    .status-completed{ background:rgba(100,116,139,0.15); color:#94a3b8; border:1px solid rgba(100,116,139,0.3); }
</style>
@endpush

@section('content')

{{-- Tab Navigation --}}
<div class="chat-tabs" id="chatTabs">
    <button class="chat-tab active" data-tab="waiting">
        ⏳ Menunggu
        @if($waiting->count() > 0)
            <span class="badge-unread" style="margin-left:4px;">{{ $waiting->count() }}</span>
        @endif
    </button>
    <button class="chat-tab" data-tab="active">
        ✅ Aktif (Saya)
        @php $myActive = $active->where('admin_id', auth()->id()); @endphp
        @if($myActive->count() > 0)
            <span class="badge-unread" style="margin-left:4px;">{{ $myActive->count() }}</span>
        @endif
    </button>
    <button class="chat-tab" data-tab="all-active">
        🌐 Semua Aktif
    </button>
    <button class="chat-tab" data-tab="completed">
        🏁 Selesai
    </button>
</div>

{{-- Tab: Waiting --}}
<div class="tab-pane active" id="tab-waiting">
    <div class="session-list" id="waitingList">
        @forelse($waiting as $session)
        <div class="session-card" id="session-{{ $session->id }}">
            <div class="session-avatar waiting">👤</div>
            <div class="session-info">
                <div class="session-name">
                    {{ $session->user->name }}
                    @if($session->unread_count > 0)
                        <span class="badge-unread">{{ $session->unread_count }}</span>
                    @endif
                </div>
                <div class="session-meta">
                    <span class="status-pill status-waiting">Menunggu</span>
                    &nbsp;· {{ $session->started_at->diffForHumans() }}
                </div>
            </div>
            <div class="session-actions">
                <form method="POST" action="{{ route('admin.chat.claim', $session) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" style="font-size:0.78rem;padding:0.4rem 0.85rem;">
                        <i class="fas fa-hand-pointer"></i> Ambil
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>Tidak ada percakapan yang menunggu</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Tab: My Active --}}
<div class="tab-pane" id="tab-active">
    <div class="session-list">
        @forelse($myActive as $session)
        <div class="session-card">
            <div class="session-avatar active">💬</div>
            <div class="session-info">
                <div class="session-name">
                    {{ $session->user->name }}
                    @if($session->unread_count > 0)
                        <span class="badge-unread">{{ $session->unread_count }}</span>
                    @endif
                </div>
                <div class="session-meta">
                    <span class="status-pill status-active">Aktif</span>
                    &nbsp;· Diambil {{ $session->claimed_at->diffForHumans() }}
                </div>
            </div>
            <div class="session-actions">
                <a href="{{ route('admin.chat.show', $session) }}" class="btn btn-primary" style="font-size:0.78rem;padding:0.4rem 0.85rem;">
                    <i class="fas fa-comment"></i> Buka
                </a>
                <form method="POST" action="{{ route('admin.chat.complete', $session) }}" onsubmit="return confirm('Selesaikan percakapan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="font-size:0.78rem;padding:0.4rem 0.85rem;">
                        <i class="fas fa-check"></i> Selesai
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon">💤</div>
            <p>Anda tidak memiliki percakapan aktif</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Tab: All Active --}}
<div class="tab-pane" id="tab-all-active">
    <div class="session-list">
        @forelse($active as $session)
        <div class="session-card">
            <div class="session-avatar active">💬</div>
            <div class="session-info">
                <div class="session-name">{{ $session->user->name }}</div>
                <div class="session-meta">
                    <span class="status-pill status-active">Aktif</span>
                    &nbsp;· Admin: <strong>{{ $session->admin->name ?? '-' }}</strong>
                    &nbsp;· {{ $session->claimed_at?->diffForHumans() }}
                </div>
            </div>
            <div class="session-actions">
                @if($session->admin_id === auth()->id())
                <a href="{{ route('admin.chat.show', $session) }}" class="btn btn-primary" style="font-size:0.78rem;padding:0.4rem 0.85rem;">
                    <i class="fas fa-comment"></i> Buka
                </a>
                @else
                <span style="font-size:0.75rem;color:#64748b;">Ditangani admin lain</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon">✨</div>
            <p>Tidak ada percakapan aktif</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Tab: Completed --}}
<div class="tab-pane" id="tab-completed">
    <div class="session-list">
        @forelse($completed as $session)
        <div class="session-card">
            <div class="session-avatar completed">✅</div>
            <div class="session-info">
                <div class="session-name">{{ $session->user->name }}</div>
                <div class="session-meta">
                    <span class="status-pill status-completed">Selesai</span>
                    &nbsp;· Admin: {{ $session->admin->name ?? '-' }}
                    &nbsp;· {{ $session->completed_at?->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon">📋</div>
            <p>Belum ada percakapan yang diselesaikan</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    // ─── Tab switcher ──────────────────────────────────────────────────────
    document.querySelectorAll('.chat-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.chat-tab').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // ─── Auto-refresh waiting list every 5s ───────────────────────────────
    function refreshList() {
        fetch('{{ route("admin.chat.poll") }}', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            // Update waiting tab badge
            const waitingTab = document.querySelector('[data-tab="waiting"]');
            const count = data.waiting.length;
            const existingBadge = waitingTab.querySelector('.badge-unread');
            if (count > 0) {
                if (existingBadge) { existingBadge.textContent = count; }
                else { waitingTab.insertAdjacentHTML('beforeend', `<span class="badge-unread" style="margin-left:4px;">${count}</span>`); }
            } else if (existingBadge) { existingBadge.remove(); }
        })
        .catch(() => {});
    }

    setInterval(refreshList, 5000);
</script>
@endpush

@endsection
