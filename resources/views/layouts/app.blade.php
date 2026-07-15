<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Umah Dauh GYM') – Member Area</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent: #ff8c00;
            --accent-dark: #ff4500;
            --gold: #ffd700;
            --sidebar-w: 250px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; }

        /* ── OVERLAY (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 99;
        }
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
        .sidebar-logo {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .sidebar-logo .icon { width: 38px; height: 38px; background: linear-gradient(135deg, #ff8c00, #ff4500); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 0 15px rgba(255,140,0,0.3); flex-shrink: 0; }
        .sidebar-logo h2 { font-size: 1rem; font-weight: 800; background: linear-gradient(135deg, #ff8c00, #ffd700); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sidebar-logo small { display: block; font-size: 0.68rem; color: var(--text-secondary); }
        .sidebar-close { margin-left: auto; background: none; border: none; color: var(--text-secondary); font-size: 1.1rem; cursor: pointer; display: none; padding: 0.25rem; }
        .sidebar-nav { flex: 1; padding: 0.75rem 0; overflow-y: auto; }
        .nav-section { padding: 0.5rem 1.25rem 0.2rem; font-size: 0.68rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; }
        .nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 1rem; color: var(--text-secondary); text-decoration: none; font-size: 0.88rem; font-weight: 500; transition: all 0.2s; margin: 0.1rem 0.75rem; border-radius: 10px; min-height: 44px; }
        .nav-link:hover { background: rgba(255,140,0,0.08); color: var(--accent); }
        .nav-link.active { background: rgba(255,140,0,0.15); color: var(--accent); }
        .nav-link i { width: 20px; text-align: center; font-size: 0.95rem; flex-shrink: 0; }
        .sidebar-user { padding: 0.85rem 1.25rem; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 0.65rem; }
        .sidebar-user img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent); flex-shrink: 0; }
        .sidebar-user .user-info { flex: 1; min-width: 0; }
        .sidebar-user .user-name { font-size: 0.82rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user .user-role { font-size: 0.68rem; color: var(--accent); font-weight: 500; }

        /* ── MAIN CONTENT ── */
        .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }

        /* ── TOPBAR ── */
        .topbar { background: rgba(18,18,26,0.95); border-bottom: 1px solid var(--border); padding: 0.85rem 1.5rem; display: flex; align-items: center; gap: 1rem; position: sticky; top: 0; z-index: 50; backdrop-filter: blur(20px); }
        .topbar-hamburger { background: none; border: none; color: var(--text-secondary); font-size: 1.2rem; cursor: pointer; display: none; padding: 0.25rem; min-width: 36px; min-height: 36px; }
        .topbar h1 { font-size: 1.1rem; font-weight: 700; flex: 1; }
        .topbar-date { font-size: 0.8rem; color: #64748b; white-space: nowrap; }

        /* ── PAGE ── */
        .page-content { flex: 1; padding: 1.5rem; }

        /* ── CARDS ── */
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem; backdrop-filter: blur(10px); }
        .card-title { font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem; }

        /* ── STATS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.85rem; margin-bottom: 1.5rem; }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 70px; height: 70px; border-radius: 50%; background: var(--accent); opacity: 0.05; transform: translate(20px, -20px); }
        .stat-icon { font-size: 1.5rem; margin-bottom: 0.6rem; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--accent); line-height: 1; }
        .stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 0.3rem; }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 0.85rem; font-size: 0.88rem; }
        .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
        .alert-danger  { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
        .alert-info    { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); color: #60a5fa; }
        .alert-warning { background: rgba(234,179,8,0.1);  border: 1px solid rgba(234,179,8,0.3);  color: #facc15; }

        /* ── BUTTONS ── */
        .btn { padding: 0.6rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; border: none; font-family: 'Inter', sans-serif; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem; min-height: 38px; }
        .btn-primary { background: linear-gradient(135deg, #ff8c00, #ff4500); color: white; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 20px rgba(255,140,0,0.4); color: white; }
        .btn-success  { background: rgba(34,197,94,0.2);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .btn-danger   { background: rgba(239,68,68,0.2);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .btn-secondary{ background: rgba(255,255,255,0.08); color: var(--text-secondary); border: 1px solid var(--border); }

        /* ── BADGE ── */
        .badge { padding: 0.28rem 0.7rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .badge-active  { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .badge-pending { background: rgba(234,179,8,0.15);  color: #facc15; border: 1px solid rgba(234,179,8,0.3); }
        .badge-expired { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 400px; }
        th { padding: 0.7rem 0.85rem; text-align: left; font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 0.8rem 0.85rem; font-size: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── FORM ── */
        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; font-size: 0.78rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-control { width: 100%; padding: 0.7rem 0.9rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; font-family: 'Inter', sans-serif; transition: all 0.2s; -webkit-appearance: none; }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(255,140,0,0.1); }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #ef4444; font-size: 0.78rem; margin-top: 0.3rem; }
        select.form-control option { background: #12121a; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        /* ── MOBILE (≤768px) ── */
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

        /* ── MOBILE (≤480px) ── */
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .page-content { padding: 0.85rem; }
        }

        /* ── BOTTOM NAV (mobile) ── */
        .bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            z-index: 90;
            padding: 0.4rem 0 calc(0.4rem + env(safe-area-inset-bottom));
        }
        .bottom-nav-inner { display: flex; justify-content: space-around; }
        .bottom-nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.2rem; padding: 0.4rem 0.75rem; text-decoration: none; color: var(--text-secondary); font-size: 0.62rem; font-weight: 500; transition: color 0.2s; min-width: 56px; }
        .bottom-nav-item i { font-size: 1.15rem; }
        .bottom-nav-item.active, .bottom-nav-item:hover { color: var(--accent); }
        @media (max-width: 768px) {
            .bottom-nav { display: block; }
            .main-content { padding-bottom: 64px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="icon">💪</div>
            <div>
                <h2>Umah Dauh GYM</h2>
                <small>Member Area</small>
            </div>
            <button class="sidebar-close" onclick="closeSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-user"></i> Profil Saya
            </a>
            <a href="{{ route('customer.membership') }}" class="nav-link {{ request()->routeIs('customer.membership') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-id-card"></i> Membership
            </a>
            <a href="{{ route('customer.payment.status') }}" class="nav-link {{ request()->routeIs('customer.payment.status') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-receipt"></i> Status Pembayaran
            </a>
            <a href="{{ route('customer.qrcode') }}" class="nav-link {{ request()->routeIs('customer.qrcode') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-qrcode"></i> QR Code Saya
            </a>
        </nav>
        <div class="sidebar-user">
            <img src="{{ auth()->user()->photo_url }}" alt="Avatar">
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Member</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:1rem;padding:0.25rem;" title="Logout">
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
            @if(session('warning'))
                <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav (Mobile Only) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Home</span>
            </a>
            <a href="{{ route('customer.membership') }}" class="bottom-nav-item {{ request()->routeIs('customer.membership') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i><span>Member</span>
            </a>
            <a href="{{ route('customer.payment.status') }}" class="bottom-nav-item {{ request()->routeIs('customer.payment.status') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i><span>Status</span>
            </a>
            <a href="{{ route('customer.qrcode') }}" class="bottom-nav-item {{ request()->routeIs('customer.qrcode') ? 'active' : '' }}">
                <i class="fas fa-qrcode"></i><span>QR Code</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="bottom-nav-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i><span>Profil</span>
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

    {{-- Live Chat Widget --}}
    @include('customer.partials.chat-widget')

    @stack('scripts')
</body>
</html>
