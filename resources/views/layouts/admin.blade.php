<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – Umah Dauh GYM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #0f0f1a;
            --bg-card: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent: #6366f1;
            --accent-glow: rgba(99,102,241,0.3);
            --sidebar-w: 250px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; }

        /* ── OVERLAY ── */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 99; }
        .sidebar-overlay.active { display: block; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .sidebar-logo { padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.65rem; }
        .sidebar-logo .icon { width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .sidebar-logo h2 { font-size: 0.95rem; font-weight: 800; background: linear-gradient(135deg, #6366f1, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sidebar-logo small { display: block; font-size: 0.65rem; color: var(--text-secondary); }
        .sidebar-close { margin-left: auto; background: none; border: none; color: var(--text-secondary); font-size: 1rem; cursor: pointer; display: none; padding: 0.25rem; }
        .sidebar-nav { flex: 1; padding: 0.6rem 0; overflow-y: auto; }
        .nav-section { padding: 0.5rem 1.25rem 0.2rem; font-size: 0.65rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; }
        .nav-link { display: flex; align-items: center; gap: 0.7rem; padding: 0.65rem 1rem; color: var(--text-secondary); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; margin: 0.1rem 0.75rem; border-radius: 10px; min-height: 44px; }
        .nav-link:hover { background: rgba(99,102,241,0.08); color: #a5b4fc; }
        .nav-link.active { background: rgba(99,102,241,0.15); color: #818cf8; }
        .nav-link i { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
        .sidebar-admin { padding: 0.85rem 1.25rem; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 0.65rem; }
        .sidebar-admin .admin-avatar { width: 34px; height: 34px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
        .sidebar-admin .admin-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0; }
        .sidebar-admin .admin-role { font-size: 0.65rem; color: #818cf8; }

        /* ── MAIN ── */
        .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }
        .topbar { background: rgba(15,15,26,0.95); border-bottom: 1px solid var(--border); padding: 0.85rem 1.5rem; display: flex; align-items: center; gap: 1rem; position: sticky; top: 0; z-index: 50; backdrop-filter: blur(20px); }
        .topbar-hamburger { background: none; border: none; color: var(--text-secondary); font-size: 1.2rem; cursor: pointer; display: none; padding: 0.25rem; min-width: 36px; min-height: 36px; }
        .topbar h1 { font-size: 1.05rem; font-weight: 700; flex: 1; }
        .topbar-date { font-size: 0.78rem; color: #64748b; white-space: nowrap; }
        .page-content { flex: 1; padding: 1.5rem; }

        /* ── CARDS & STATS ── */
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem; }
        .card-title { font-size: 0.9rem; font-weight: 700; margin-bottom: 1rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.85rem; margin-bottom: 1.5rem; }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem; }
        .stat-icon { font-size: 1.4rem; margin-bottom: 0.5rem; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: #818cf8; line-height: 1; }
        .stat-label { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; }

        /* ── ALERTS ── */
        .alert { padding: 0.7rem 1rem; border-radius: 10px; margin-bottom: 0.85rem; font-size: 0.85rem; }
        .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
        .alert-danger  { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
        .alert-info    { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); color: #60a5fa; }

        /* ── BUTTONS ── */
        .btn { padding: 0.6rem 1.1rem; border-radius: 8px; font-size: 0.83rem; font-weight: 600; cursor: pointer; border: none; font-family: 'Inter', sans-serif; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; min-height: 38px; }
        .btn-primary   { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 20px rgba(99,102,241,0.4); color: white; }
        .btn-success   { background: rgba(34,197,94,0.2);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .btn-danger    { background: rgba(239,68,68,0.2);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .btn-secondary { background: rgba(255,255,255,0.07); color: var(--text-secondary); border: 1px solid var(--border); }

        /* ── BADGES ── */
        .badge { padding: 0.25rem 0.65rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
        .badge-active    { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .badge-pending   { background: rgba(234,179,8,0.15);  color: #facc15; }
        .badge-expired   { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-cancelled { background: rgba(100,116,139,0.15);color: #94a3b8; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 480px; }
        th { padding: 0.65rem 0.85rem; text-align: left; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 0.8rem 0.85rem; font-size: 0.83rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── FORM ── */
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-control { width: 100%; padding: 0.65rem 0.85rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 0.88rem; font-family: 'Inter', sans-serif; transition: all 0.2s; -webkit-appearance: none; }
        .form-control:focus { outline: none; border-color: var(--accent); }
        select.form-control option { background: #0f0f1a; }
        .pagination { margin-top: 1rem; display: flex; justify-content: center; }
        .pagination nav { display: flex; gap: 0.25rem; flex-wrap: wrap; justify-content: center; }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-close { display: flex; align-items: center; }
            .main-content { margin-left: 0; }
            .topbar-hamburger { display: flex; align-items: center; justify-content: center; }
            .topbar-date { display: none; }
            .page-content { padding: 1rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .stat-value { font-size: 1.4rem; }
        }
        @media (max-width: 480px) {
            .page-content { padding: 0.85rem; }
        }

        /* ── BOTTOM NAV (Admin Mobile) ── */
        .bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            z-index: 90;
            padding: 0.35rem 0 calc(0.35rem + env(safe-area-inset-bottom));
        }
        .bottom-nav-inner { display: flex; justify-content: space-around; }
        .bottom-nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.2rem; padding: 0.35rem 0.5rem; text-decoration: none; color: var(--text-secondary); font-size: 0.6rem; font-weight: 500; transition: color 0.2s; min-width: 52px; }
        .bottom-nav-item i { font-size: 1.1rem; }
        .bottom-nav-item.active, .bottom-nav-item:hover { color: #818cf8; }
        @media (max-width: 768px) {
            .bottom-nav { display: block; }
            .main-content { padding-bottom: 60px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Admin Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="icon">🛡️</div>
            <div>
                <h2>Umah Dauh Admin</h2>
                <small>Panel Admin</small>
            </div>
            <button class="sidebar-close" onclick="closeSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Dashboard</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-chart-line"></i> Overview
            </a>
            <div class="nav-section">Operasional</div>
            <a href="{{ route('admin.scanner') }}" class="nav-link {{ request()->routeIs('admin.scanner') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-qrcode"></i> Scanner QR
            </a>
            <a href="{{ route('admin.attendance') }}" class="nav-link {{ request()->routeIs('admin.attendance') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-door-open"></i> Log Kunjungan
            </a>
            <div class="nav-section">Manajemen</div>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-credit-card"></i> Pembayaran
                @php $pendingCount = \App\Models\MemberSubscription::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span style="margin-left:auto;background:#ff8c00;color:white;border-radius:20px;font-size:0.65rem;padding:0.1rem 0.45rem;font-weight:700;">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-users"></i> Data Member
            </a>
            <div class="nav-section">Support</div>
            <a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-comments"></i> Live Chat
                @php $chatWaiting = \App\Models\ChatSession::where('status','waiting')->notExpired()->count(); @endphp
                @if($chatWaiting > 0)
                    <span style="margin-left:auto;background:#ef4444;color:white;border-radius:20px;font-size:0.65rem;padding:0.1rem 0.45rem;font-weight:700;">{{ $chatWaiting }}</span>
                @endif
            </a>
        </nav>
        <div class="sidebar-admin">
            <div class="admin-avatar">👨‍💼</div>
            <div style="flex:1;min-width:0;">
                <div class="admin-name">{{ auth()->user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:0.95rem;padding:0.25rem;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <div class="topbar">
            <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <h1>@yield('page-title', 'Dashboard')</h1>
            <span class="topbar-date">{{ now()->format('d M Y') }}</span>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav (Mobile) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Overview</span>
            </a>
            <a href="{{ route('admin.scanner') }}" class="bottom-nav-item {{ request()->routeIs('admin.scanner') ? 'active' : '' }}">
                <i class="fas fa-qrcode"></i><span>Scanner</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i><span>Bayar</span>
            </a>
            <a href="{{ route('admin.chat.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" style="position:relative;">
                <i class="fas fa-comments"></i><span>Chat</span>
                @php $chatWaitingMob = \App\Models\ChatSession::where('status','waiting')->notExpired()->count(); @endphp
                @if($chatWaitingMob > 0)
                    <span style="position:absolute;top:4px;right:6px;width:16px;height:16px;border-radius:50%;background:#ef4444;color:#fff;font-size:0.58rem;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $chatWaitingMob }}</span>
                @endif
            </a>
            <a href="{{ route('admin.members.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Member</span>
            </a>
        </div>
    </nav>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>

    @stack('scripts')
</body>
</html>
