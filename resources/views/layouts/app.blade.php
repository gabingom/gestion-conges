<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Congés UADB — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --dark:       #1a1a2e;
            --dark-2:     #16213e;
            --accent:     #e94560;
            --accent-h:   #c73652;
            --light-bg:   #f0f2f8;
            --text-muted: #8892a4;
            --sidebar-w:  250px;

            /* couleurs par type d'absence */
            --abs-maladie:       #c0392b;
            --abs-mariage:       #9b59b6;
            --abs-naissance:     #27ae60;
            --abs-bapteme:       #2ecc71;
            --abs-deces-asc:     #7f8c8d;
            --abs-deces-desc:    #2c3e50;
            --abs-grossesse:     #e91e8c;
            --abs-accident:      #e67e22;
            --abs-autorisation:  #3498db;
            --abs-autre:         #95a5a6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--light-bg);
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #2d3748;
        }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 62px;
            background: var(--dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.8rem;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,.3);
        }

        .topbar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .topbar .brand .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .topbar .brand span small {
            display: block;
            font-size: .65rem;
            font-weight: 400;
            color: var(--text-muted);
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .topbar .user-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.85);
            font-size: .88rem;
        }

        .topbar .role-badge {
            background: rgba(233,69,96,.2);
            color: var(--accent);
            border: 1px solid rgba(233,69,96,.4);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.8);
            padding: 6px 16px;
            border-radius: 8px;
            font-size: .83rem;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 62px;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--dark-2);
            overflow-y: auto;
            z-index: 999;
            padding-bottom: 2rem;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-section-label {
            padding: .9rem 1.4rem .3rem;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: .62rem 1.4rem;
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .88rem;
            border-left: 3px solid transparent;
            transition: all .2s;
        }

        .sidebar a i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        .sidebar a:hover {
            color: white;
            background: rgba(255,255,255,.05);
            border-left-color: rgba(233,69,96,.5);
        }

        .sidebar a.active {
            color: white;
            background: rgba(233,69,96,.12);
            border-left-color: var(--accent);
            font-weight: 600;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: 62px;
            padding: 2rem 2.2rem;
            min-height: calc(100vh - 62px);
        }

        .page-header {
            margin-bottom: 1.8rem;
        }

        .page-header h2 {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--dark);
        }

        .page-header .breadcrumb {
            font-size: .82rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 8px rgba(0,0,0,.07);
            background: white;
        }

        .card-header {
            border-radius: 14px 14px 0 0 !important;
            border-bottom: 1px solid rgba(0,0,0,.06);
            font-weight: 600;
            font-size: .92rem;
            padding: .9rem 1.2rem;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            border-radius: 14px;
            padding: 1.4rem 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,.12);
        }

        .stat-card .bg-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: .12;
        }

        .stat-card .stat-label {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            opacity: .85;
            margin-bottom: .3rem;
        }

        .stat-card .stat-value {
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1;
        }

        .sc-dark    { background: linear-gradient(135deg, #1a1a2e, #16213e); }
        .sc-accent  { background: linear-gradient(135deg, #e94560, #c73652); }
        .sc-teal    { background: linear-gradient(135deg, #00897b, #00695c); }
        .sc-amber   { background: linear-gradient(135deg, #f59e0b, #d97706); }

        /* ── BUTTONS ── */
        .btn-primary {
            background: var(--dark);
            border-color: var(--dark);
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: var(--dark-2);
            border-color: var(--dark-2);
        }

        .btn-danger {
            background: var(--accent);
            border-color: var(--accent);
            border-radius: 8px;
        }
        .btn-danger:hover {
            background: var(--accent-h);
            border-color: var(--accent-h);
        }

        .btn-warning { border-radius: 8px; }
        .btn-secondary { border-radius: 8px; }
        .btn-info { border-radius: 8px; }
        .btn-success { border-radius: 8px; }

        /* ── TABLE ── */
        .table thead th {
            background: #f8f9fd;
            color: var(--dark);
            font-weight: 700;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #eef0f7;
        }

        .table tbody tr:hover { background: #fafbff; }

        /* ── BADGES MOTIF ABSENCE ── */
        .badge-maladie      { background: var(--abs-maladie);      color: white; }
        .badge-mariage      { background: var(--abs-mariage);      color: white; }
        .badge-naissance    { background: var(--abs-naissance);    color: white; }
        .badge-bapteme      { background: var(--abs-bapteme);      color: white; }
        .badge-deces_ascendant  { background: var(--abs-deces-asc);  color: white; }
        .badge-deces_descendant { background: var(--abs-deces-desc); color: white; }
        .badge-grossesse    { background: var(--abs-grossesse);    color: white; }
        .badge-accident_travail { background: var(--abs-accident); color: white; }
        .badge-autorisation_personnelle { background: var(--abs-autorisation); color: white; }
        .badge-autre        { background: var(--abs-autre);        color: white; }

        /* ── FORMS ── */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: .9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,.12);
        }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #4a5568;
            margin-bottom: .3rem;
        }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px;
            border: none;
            font-size: .9rem;
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <div class="brand-icon"><i class="bi bi-calendar2-check text-white"></i></div>
        <span>
            Gestion Congés
            <small>Université du Sine Saloum El-Hâdj Ibrahima NIASS</small>
        </span>
    </a>
    @auth
    <div class="user-area">
        <div class="user-chip">
            <i class="bi bi-person-circle" style="font-size:1.2rem"></i>
            {{ Auth::user()->name }}
            <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
        </form>
    </div>
    @endauth
</div>

<!-- SIDEBAR -->
@auth
<div class="sidebar">
    <div class="nav-section-label">Principal</div>
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Tableau de bord
    </a>

    <div class="nav-section-label">Gestion RH</div>
    <a href="{{ route('agents.index') }}" class="{{ request()->routeIs('agents.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Agents
    </a>
    <a href="{{ route('conges.index') }}" class="{{ request()->routeIs('conges.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Congés
    </a>
    <a href="{{ route('absences.index') }}" class="{{ request()->routeIs('absences.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-x"></i> Absences
    </a>
    <a href="{{ route('jours-feries.index') }}" class="{{ request()->routeIs('jours-feries.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> Jours Fériés
    </a>

    <div class="nav-section-label">Rapports</div>
    <a href="{{ route('rapports.index') }}" class="{{ request()->routeIs('rapports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph"></i> Rapports PDF
    </a>

    @if(Auth::user()->isAdmin())
    <div class="nav-section-label">Administration</div>
    <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">
        <i class="bi bi-person-plus"></i> Créer utilisateur
    </a>
    @endif
</div>
@endauth

<!-- CONTENU PRINCIPAL -->
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
