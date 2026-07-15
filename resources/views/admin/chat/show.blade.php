@extends('layouts.admin')
@section('title', 'Chat – ' . $session->user->name)
@section('page-title', '💬 Chat dengan ' . $session->user->name)

@push('styles')
<style>
    /* Full-height chat layout */
    .chat-wrap {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 160px);
        min-height: 400px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        overflow: hidden;
    }

    /* Header */
    .chat-head {
        padding: 1rem 1.25rem;
        background: rgba(255,255,255,0.04);
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex; align-items: center; gap: 0.9rem;
        flex-shrink: 0;
    }
    .chat-user-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .chat-head-info { flex: 1; min-width: 0; }
    .chat-head-name { font-size: 0.95rem; font-weight: 700; }
    .chat-head-meta { font-size: 0.75rem; color: #64748b; margin-top: 0.1rem; }
    .chat-head-actions { display: flex; gap: 0.5rem; flex-shrink: 0; flex-wrap: wrap; }

    /* Messages */
    .chat-body {
        flex: 1; overflow-y: auto; padding: 1.25rem;
        display: flex; flex-direction: column; gap: 0.7rem;
        scroll-behavior: smooth;
    }
    .chat-body::-webkit-scrollbar { width: 4px; }
    .chat-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

    .msg-wrap { display: flex; flex-direction: column; }
    .msg-wrap.me    { align-items: flex-end; }
    .msg-wrap.other { align-items: flex-start; }

    .msg-bubble {
        max-width: 72%; padding: 0.65rem 1rem;
        border-radius: 16px; font-size: 0.88rem; line-height: 1.55;
        word-break: break-word;
    }
    .msg-bubble.me {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff; border-bottom-right-radius: 4px;
    }
    .msg-bubble.other {
        background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08);
        color: #e2e8f0; border-bottom-left-radius: 4px;
    }
    .msg-time { font-size: 0.65rem; color: #475569; margin-top: 0.25rem; padding: 0 0.1rem; }
    .msg-sender { font-size: 0.68rem; color: #64748b; margin-bottom: 0.15rem; }

    .system-msg {
        text-align: center; font-size: 0.72rem; color: #475569;
        padding: 0.3rem 0.75rem; background: rgba(255,255,255,0.03);
        border-radius: 50px; margin: 0.3rem auto; max-width: 300px;
    }

    /* Input */
    .chat-footer {
        padding: 0.85rem 1.1rem;
        border-top: 1px solid rgba(255,255,255,0.08);
        background: rgba(255,255,255,0.02);
        display: flex; gap: 0.6rem; align-items: flex-end;
        flex-shrink: 0;
    }
    #adminMsgInput {
        flex: 1; background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px; color: #e2e8f0;
        font-family: 'Inter', sans-serif; font-size: 0.88rem;
        padding: 0.65rem 1rem;
        resize: none; max-height: 100px;
        transition: border-color 0.2s;
    }
    #adminMsgInput::placeholder { color: #374151; }
    #adminMsgInput:focus { outline: none; border-color: #6366f1; }
    #adminMsgInput:disabled { opacity: 0.4; }
    #adminSendBtn {
        padding: 0.65rem 1.1rem; border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none; cursor: pointer; color: #fff;
        font-size: 0.85rem; font-weight: 600;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s; display: flex; align-items: center; gap: 0.4rem;
    }
    #adminSendBtn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }
    #adminSendBtn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

    @media (max-width: 640px) {
        .chat-wrap { height: calc(100vh - 200px); border-radius: 14px; }
        .chat-head { flex-wrap: wrap; }
        .chat-head-actions { flex-basis: 100%; }
        .msg-bubble { max-width: 88%; }
    }
</style>
@endpush

@section('content')

<div style="margin-bottom:0.85rem;">
    <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary" style="font-size:0.8rem;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="chat-wrap">
    {{-- Header --}}
    <div class="chat-head">
        <div class="chat-user-avatar">👤</div>
        <div class="chat-head-info">
            <div class="chat-head-name">{{ $session->user->name }}</div>
            <div class="chat-head-meta">
                {{ $session->user->email }}
                &nbsp;·&nbsp;
                @if($session->isActive())
                    <span style="color:#4ade80;">● Aktif sejak {{ $session->claimed_at->diffForHumans() }}</span>
                @elseif($session->isWaiting())
                    <span style="color:#facc15;">⏳ Menunggu (belum diambil)</span>
                @else
                    <span style="color:#64748b;">Selesai</span>
                @endif
            </div>
        </div>
        <div class="chat-head-actions">
            @if($session->isActive() && $session->admin_id === auth()->id())
            <form method="POST" action="{{ route('admin.chat.complete', $session) }}"
                onsubmit="return confirm('Yakin ingin menyelesaikan percakapan ini? Customer perlu menunggu admin baru jika ingin chat lagi.')">
                @csrf
                <button type="submit" class="btn btn-success" style="font-size:0.78rem;">
                    <i class="fas fa-check-circle"></i> Selesaikan
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Messages --}}
    <div class="chat-body" id="adminChatBody">
        @if($session->messages->isEmpty())
        <div class="system-msg">Belum ada pesan</div>
        @else
        @php $prevDate = null; @endphp
        @foreach($session->messages as $msg)
            @php $msgDate = $msg->created_at->format('d M Y'); @endphp
            @if($msgDate !== $prevDate)
                <div class="system-msg">{{ $msgDate }}</div>
                @php $prevDate = $msgDate; @endphp
            @endif
            @php $isMe = $msg->sender_type === 'admin'; @endphp
            <div class="msg-wrap {{ $isMe ? 'me' : 'other' }}">
                @if(!$isMe)
                <div class="msg-sender">{{ $session->user->name }}</div>
                @endif
                <div class="msg-bubble {{ $isMe ? 'me' : 'other' }}">{{ $msg->message }}</div>
                <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        @endforeach
        @endif
        @if($session->isCompleted())
        <div class="system-msg">✅ Percakapan telah diselesaikan</div>
        @endif
    </div>

    {{-- Input --}}
    @if($session->isActive() && $session->admin_id === auth()->id())
    <div class="chat-footer">
        <textarea
            id="adminMsgInput"
            placeholder="Ketik balasan..."
            rows="1"
        ></textarea>
        <button id="adminSendBtn">
            <i class="fas fa-paper-plane"></i> Kirim
        </button>
    </div>
    @elseif($session->isCompleted())
    <div class="chat-footer" style="justify-content:center;color:#475569;font-size:0.85rem;">
        <i class="fas fa-lock" style="margin-right:0.4rem;"></i> Percakapan telah selesai
    </div>
    @else
    <div class="chat-footer" style="justify-content:center;color:#64748b;font-size:0.85rem;">
        <i class="fas fa-info-circle" style="margin-right:0.4rem;"></i> Anda belum mengambil percakapan ini
    </div>
    @endif
</div>

@push('scripts')
<script>
(function () {
    const sessionId = {{ $session->id }};
    const canReply  = {{ ($session->isActive() && $session->admin_id === auth()->id()) ? 'true' : 'false' }};
    let lastMsgId   = {{ $session->messages->last()?->id ?? 0 }};
    let pollTimer   = null;

    const body      = document.getElementById('adminChatBody');
    const input     = document.getElementById('adminMsgInput');
    const sendBtn   = document.getElementById('adminSendBtn');

    // Scroll to bottom on load
    if (body) body.scrollTop = body.scrollHeight;

    if (!canReply) return; // read-only view for non-owner or completed

    // ─── Send ──────────────────────────────────────────────────────────
    sendBtn.addEventListener('click', doSend);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); doSend(); }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 100) + 'px';
    });

    function doSend() {
        const text = input.value.trim();
        if (!text || sendBtn.disabled) return;
        sendBtn.disabled = true;
        input.value = '';
        input.style.height = 'auto';

        fetch('{{ route("admin.chat.send", $session) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: text }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.id) {
                renderMsg(data, true);
                lastMsgId = Math.max(lastMsgId, data.id);
            }
            sendBtn.disabled = false;
        })
        .catch(() => { sendBtn.disabled = false; });
    }

    // ─── Polling ───────────────────────────────────────────────────────
    function poll() {
        fetch(`{{ route("admin.chat.messages", $session) }}?last_id=${lastMsgId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(m => {
                    renderMsg(m, true);
                    lastMsgId = Math.max(lastMsgId, m.id);
                });
            }
            if (data.status === 'completed') {
                stopPolling();
                input.disabled = true;
                sendBtn.disabled = true;
            }
        })
        .catch(() => {});
    }

    function startPolling() { pollTimer = setInterval(poll, 3000); }
    function stopPolling()  { if (pollTimer) { clearInterval(pollTimer); pollTimer = null; } }

    startPolling();

    // ─── Render ────────────────────────────────────────────────────────
    function renderMsg(msg, animate) {
        const isMe = msg.sender_type === 'admin';
        const wrap = document.createElement('div');
        wrap.className = 'msg-wrap ' + (isMe ? 'me' : 'other');
        if (animate) wrap.style.animation = 'none';
        wrap.innerHTML = `
            <div class="msg-bubble ${isMe ? 'me' : 'other'}">${escHtml(msg.message)}</div>
            <div class="msg-time">${msg.time}</div>
        `;
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
    }

    function escHtml(t) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(t));
        return d.innerHTML;
    }

})();
</script>
@endpush

@endsection
