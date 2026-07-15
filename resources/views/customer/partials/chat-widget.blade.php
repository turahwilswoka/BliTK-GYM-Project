{{-- ═══════════════════════════════════════════════════════════════════
     LIVE CHAT WIDGET – Customer Side
     Diinclude di layouts/app.blade.php
══════════════════════════════════════════════════════════════════ --}}
<style>
/* ─── CHAT BUTTON ─────────────────────────────────────────────────── */
#chatToggleBtn {
    position: fixed;
    bottom: 80px; /* above bottom-nav on mobile */
    right: 1.25rem;
    z-index: 998;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff8c00, #ff4500);
    border: none; cursor: pointer;
    box-shadow: 0 6px 24px rgba(255,140,0,0.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    transition: all 0.3s ease;
    animation: chatPulse 3s infinite;
}
#chatToggleBtn:hover { transform: scale(1.1); box-shadow: 0 10px 32px rgba(255,140,0,0.7); }
@keyframes chatPulse {
    0%,100% { box-shadow: 0 6px 24px rgba(255,140,0,0.5); }
    50%      { box-shadow: 0 6px 36px rgba(255,140,0,0.8), 0 0 0 8px rgba(255,140,0,0.1); }
}

/* Badge unread */
#chatUnreadBadge {
    position: absolute; top: -4px; right: -4px;
    width: 20px; height: 20px; border-radius: 50%;
    background: #ef4444; color: #fff;
    font-size: 0.68rem; font-weight: 700;
    display: none; align-items: center; justify-content: center;
    border: 2px solid #0a0a0f;
    animation: badgeBounce 0.4s ease;
}
@keyframes badgeBounce { 0%{transform:scale(0)} 70%{transform:scale(1.2)} 100%{transform:scale(1)} }

/* Desktop position */
@media (min-width: 769px) {
    #chatToggleBtn { bottom: 1.5rem; }
}

/* ─── CHAT POPUP ──────────────────────────────────────────────────── */
#chatPopup {
    position: fixed;
    bottom: 148px; right: 1.25rem;
    z-index: 999;
    width: min(360px, calc(100vw - 2rem));
    height: min(520px, calc(100vh - 180px));
    border-radius: 20px;
    background: #0d0d1a;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 24px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,140,0,0.12);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: chatSlideIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
    transform-origin: bottom right;
}
#chatPopup.open { display: flex; }
@keyframes chatSlideIn {
    from { opacity:0; transform: scale(0.85) translateY(16px); }
    to   { opacity:1; transform: scale(1)    translateY(0); }
}
@media (min-width: 769px) {
    #chatPopup { bottom: 90px; }
}

/* Header */
.chat-header {
    padding: 0.9rem 1.1rem;
    background: linear-gradient(135deg, rgba(255,140,0,0.15), rgba(255,69,0,0.08));
    border-bottom: 1px solid rgba(255,255,255,0.08);
    display: flex; align-items: center; gap: 0.75rem;
    flex-shrink: 0;
}
.chat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #ff8c00, #ff4500);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.chat-header-info { flex: 1; min-width: 0; }
.chat-header-title { font-size: 0.88rem; font-weight: 700; color: #e2e8f0; }
.chat-header-status {
    font-size: 0.7rem; color: #64748b;
    display: flex; align-items: center; gap: 0.3rem; margin-top: 0.1rem;
}
.chat-status-dot {
    width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
    background: #facc15;
}
.chat-status-dot.online { background: #4ade80; animation: dotPulse 2s infinite; }
@keyframes dotPulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
.chat-close-btn {
    background: none; border: none; color: #64748b;
    font-size: 1rem; cursor: pointer; padding: 0.25rem;
    transition: color 0.2s;
}
.chat-close-btn:hover { color: #e2e8f0; }

/* Messages area */
.chat-messages {
    flex: 1; overflow-y: auto; padding: 1rem;
    display: flex; flex-direction: column; gap: 0.65rem;
    scroll-behavior: smooth;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

/* Bubbles */
.bubble-wrap { display: flex; flex-direction: column; }
.bubble-wrap.me { align-items: flex-end; }
.bubble-wrap.other { align-items: flex-start; }
.bubble {
    max-width: 82%; padding: 0.6rem 0.9rem;
    border-radius: 16px; font-size: 0.84rem; line-height: 1.5;
    word-break: break-word;
}
.bubble.me {
    background: linear-gradient(135deg, #ff8c00, #ff4500);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.bubble.other {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.08);
    color: #e2e8f0;
    border-bottom-left-radius: 4px;
}
.bubble-time { font-size: 0.65rem; color: #475569; margin-top: 0.2rem; padding: 0 0.2rem; }

/* System messages */
.chat-system-msg {
    text-align: center; font-size: 0.72rem; color: #475569;
    padding: 0.3rem 0.75rem; background: rgba(255,255,255,0.03);
    border-radius: 50px; margin: 0.3rem auto;
}

/* Empty state */
.chat-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; padding: 1.5rem; color: #475569;
}
.chat-empty .icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }
.chat-empty p { font-size: 0.82rem; line-height: 1.6; }

/* Input bar */
.chat-input-bar {
    padding: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.02);
    display: flex; gap: 0.5rem; align-items: flex-end;
    flex-shrink: 0;
}
#chatInput {
    flex: 1; background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px; color: #e2e8f0;
    font-family: 'Inter', sans-serif; font-size: 0.84rem;
    padding: 0.6rem 0.85rem;
    resize: none; max-height: 100px;
    transition: border-color 0.2s;
    line-height: 1.4;
}
#chatInput::placeholder { color: #374151; }
#chatInput:focus { outline: none; border-color: #ff8c00; }
#chatSendBtn {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #ff8c00, #ff4500);
    border: none; cursor: pointer; color: #fff; font-size: 0.9rem;
    transition: all 0.2s; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
#chatSendBtn:hover { transform: scale(1.1); box-shadow: 0 4px 16px rgba(255,140,0,0.5); }
#chatSendBtn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

/* Loading dots */
.typing-dots { display: flex; gap: 3px; align-items: center; padding: 0.5rem 0; }
.typing-dots span {
    width: 6px; height: 6px; border-radius: 50%;
    background: #64748b; animation: typingBounce 1.2s infinite;
}
.typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes typingBounce { 0%,80%,100%{transform:scale(0.6)} 40%{transform:scale(1)} }
</style>

{{-- ─── Toggle Button ─── --}}
<button id="chatToggleBtn" title="Chat dengan CS" aria-label="Buka Live Chat">
    <span id="chatBtnIcon">💬</span>
    <span id="chatUnreadBadge"></span>
</button>

{{-- ─── Chat Popup ─── --}}
<div id="chatPopup" role="dialog" aria-label="Live Chat Umah Dauh GYM">
    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-avatar">💬</div>
        <div class="chat-header-info">
            <div class="chat-header-title">Customer Service</div>
            <div class="chat-header-status">
                <span class="chat-status-dot" id="chatStatusDot"></span>
                <span id="chatStatusText">Menunggu admin...</span>
            </div>
        </div>
        <button class="chat-close-btn" id="chatCloseBtn" aria-label="Tutup chat">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Messages --}}
    <div class="chat-messages" id="chatMessages">
        <div class="chat-empty" id="chatEmpty">
            <div class="icon">💬</div>
            <p>Halo! Ada yang bisa kami bantu?<br>Kirim pesan untuk mulai chat.</p>
        </div>
    </div>

    {{-- Input --}}
    <div class="chat-input-bar">
        <textarea
            id="chatInput"
            placeholder="Ketik pesan..."
            rows="1"
            aria-label="Pesan"
        ></textarea>
        <button id="chatSendBtn" aria-label="Kirim pesan">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ─── State ───────────────────────────────────────────────────────────
    let sessionId    = null;
    let lastMsgId    = 0;
    let pollTimer    = null;
    let isOpen       = false;
    let unreadCount  = 0;
    let currentStatus = 'waiting';

    // ─── Elements ────────────────────────────────────────────────────────
    const popup       = document.getElementById('chatPopup');
    const toggleBtn   = document.getElementById('chatToggleBtn');
    const closeBtn    = document.getElementById('chatCloseBtn');
    const msgArea     = document.getElementById('chatMessages');
    const input       = document.getElementById('chatInput');
    const sendBtn     = document.getElementById('chatSendBtn');
    const statusDot   = document.getElementById('chatStatusDot');
    const statusText  = document.getElementById('chatStatusText');
    const emptyEl     = document.getElementById('chatEmpty');
    const badgeEl     = document.getElementById('chatUnreadBadge');

    // ─── CSRF Token ──────────────────────────────────────────────────────
    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    // ─── Toggle Popup ────────────────────────────────────────────────────
    toggleBtn.addEventListener('click', () => {
        isOpen ? closeChat() : openChat();
    });
    closeBtn.addEventListener('click', closeChat);

    function openChat() {
        isOpen = true;
        popup.classList.add('open');
        toggleBtn.querySelector('#chatBtnIcon').textContent = '✕';
        resetUnread();
        if (!sessionId) {
            initSession();
        } else {
            startPolling();
        }
    }

    function closeChat() {
        isOpen = false;
        popup.classList.remove('open');
        toggleBtn.querySelector('#chatBtnIcon').textContent = '💬';
        stopPolling();
    }

    // ─── Init Session ────────────────────────────────────────────────────
    function initSession() {
        fetch('{{ route("customer.chat.session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept':       'application/json',
            },
            body: JSON.stringify({}),
        })
        .then(r => r.json())
        .then(data => {
            sessionId = data.session_id;
            currentStatus = data.status;
            updateStatusUI(data.status, data.admin_name);
            loadHistory();
        })
        .catch(err => console.error('Chat init error:', err));
    }

    // ─── Load Full History ───────────────────────────────────────────────
    function loadHistory() {
        if (!sessionId) return;
        fetch(`{{ url('customer/chat/history') }}?session_id=${sessionId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            currentStatus = data.status;
            updateStatusUI(data.status, data.admin_name);
            if (data.messages.length > 0) {
                emptyEl.style.display = 'none';
                data.messages.forEach(m => {
                    renderBubble(m, false);
                    lastMsgId = Math.max(lastMsgId, m.id);
                });
                scrollBottom();
            }
            startPolling();
        })
        .catch(() => startPolling());
    }

    // ─── Polling ─────────────────────────────────────────────────────────
    function startPolling() {
        stopPolling();
        poll(); // immediate first poll
        pollTimer = setInterval(poll, 3000);
    }

    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    function poll() {
        if (!sessionId) return;
        fetch(`{{ url('customer/chat/messages') }}?session_id=${sessionId}&last_id=${lastMsgId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            currentStatus = data.status;
            updateStatusUI(data.status, data.admin_name);

            if (data.messages.length > 0) {
                emptyEl.style.display = 'none';
                data.messages.forEach(m => {
                    renderBubble(m, true);
                    lastMsgId = Math.max(lastMsgId, m.id);
                    if (!isOpen && m.sender_type === 'admin') incrementUnread();
                });
                scrollBottom();
            }

            // Session completed: disable input
            if (data.status === 'completed') {
                stopPolling();
                input.disabled = true;
                sendBtn.disabled = true;
                input.placeholder = 'Percakapan telah selesai.';
                appendSystemMsg('Percakapan ini telah diselesaikan oleh admin.');
            }
        })
        .catch(() => {}); // ignore polling errors silently
    }

    // ─── Send Message ────────────────────────────────────────────────────
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
        if (!text || !sessionId || sendBtn.disabled) return;

        sendBtn.disabled = true;
        input.value = '';
        input.style.height = 'auto';

        // Optimistic render
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
        renderBubble({ message: text, sender_type: 'customer', time: timeStr }, true);
        emptyEl.style.display = 'none';
        scrollBottom();

        fetch('{{ route("customer.chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept':       'application/json',
            },
            body: JSON.stringify({ session_id: sessionId, message: text }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.id) lastMsgId = Math.max(lastMsgId, data.id);
            sendBtn.disabled = false;
        })
        .catch(() => { sendBtn.disabled = false; });
    }

    // ─── Render Bubble ───────────────────────────────────────────────────
    function renderBubble(msg, animate) {
        const isMe = msg.sender_type === 'customer';
        const wrap = document.createElement('div');
        wrap.className = 'bubble-wrap ' + (isMe ? 'me' : 'other');
        if (animate) wrap.style.animation = 'chatSlideIn 0.25s ease';
        wrap.innerHTML = `
            <div class="bubble ${isMe ? 'me' : 'other'}">${escapeHtml(msg.message)}</div>
            <span class="bubble-time">${msg.time}</span>
        `;
        msgArea.appendChild(wrap);
    }

    function appendSystemMsg(text) {
        const el = document.createElement('div');
        el.className = 'chat-system-msg';
        el.textContent = text;
        msgArea.appendChild(el);
        scrollBottom();
    }

    // ─── UI Helpers ──────────────────────────────────────────────────────
    function updateStatusUI(status, adminName) {
        if (status === 'active') {
            statusDot.className = 'chat-status-dot online';
            statusText.textContent = adminName ? `${adminName} siap membantu` : 'Admin online';
            input.disabled = false;
            sendBtn.disabled = false;
            input.placeholder = 'Ketik pesan...';
        } else if (status === 'waiting') {
            statusDot.className = 'chat-status-dot';
            statusText.textContent = 'Menunggu admin...';
            input.disabled = false;
            sendBtn.disabled = false;
        } else if (status === 'completed') {
            statusDot.className = 'chat-status-dot';
            statusText.textContent = 'Selesai';
        }
    }

    function scrollBottom() {
        msgArea.scrollTop = msgArea.scrollHeight;
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(text));
        return d.innerHTML;
    }

    function incrementUnread() {
        unreadCount++;
        badgeEl.style.display = 'flex';
        badgeEl.textContent = unreadCount > 9 ? '9+' : unreadCount;
    }

    function resetUnread() {
        unreadCount = 0;
        badgeEl.style.display = 'none';
        badgeEl.textContent = '';
    }

})();
</script>
