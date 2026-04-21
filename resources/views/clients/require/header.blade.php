<style>
    :root {
        --hdr-blue:     #f0a800;
        --hdr-dark:     #c47800;
        --hdr-orange:   #003d82;
        --hdr-border:   #e8edf4;
        --hdr-text:     #0f172a;
        --hdr-muted:    #64748b;
        --hdr-hover-bg: #fffbeb;
    }
    .hdr-ribbon {
        background: linear-gradient(90deg, var(--hdr-dark) 0%, var(--hdr-blue) 100%);
        padding: .28rem 0; font-size: .75rem; color: rgba(26,18,0,.75);
    }
    .hdr-ribbon a { color: rgba(26,18,0,.8); text-decoration: none; transition: color .15s; }
    .hdr-ribbon a:hover { color: #1a1200; }
    .hdr-ribbon .ribbon-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--hdr-orange); display: inline-block;
        margin: 0 .55rem; vertical-align: middle;
    }
    .hdr-ribbon .ribbon-right { display: flex; align-items: center; gap: 1rem; }
    .hdr-ribbon .ribbon-badge {
        background: rgba(0,0,0,.1); border: 1px solid rgba(0,0,0,.15);
        border-radius: 20px; padding: .1rem .6rem; font-size: .7rem; font-weight: 500;
        color: rgba(26,18,0,.85); display: flex; align-items: center; gap: .3rem;
    }
    .hdr-navbar {
        background: #fff; border-bottom: 1px solid var(--hdr-border);
        padding: 0; position: sticky; top: 0; z-index: 1050;
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
    }
    .hdr-navbar .hdr-inner { padding: 0 1.5rem; display: flex; align-items: center; gap: .75rem; min-height: 58px; }
    .hdr-brand { display: flex; align-items: center; gap: .65rem; text-decoration: none; padding: .6rem 0; flex-shrink: 0; }
    .hdr-brand-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, var(--hdr-blue), var(--hdr-dark));
        border-radius: 9px; display: flex; align-items: center; justify-content: center;
        color: #0f172a; font-size: 1rem; flex-shrink: 0;
    }
    .hdr-brand-text { line-height: 1.15; }
    .hdr-brand-name { font-size: .9rem; font-weight: 700; color: var(--hdr-text); letter-spacing: -.3px; display: block; }
    .hdr-brand-sub  { font-size: .67rem; color: var(--hdr-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .5px; display: block; }
    .hdr-divider { width: 1px; height: 28px; background: var(--hdr-border); flex-shrink: 0; }
    .hdr-nav { display: flex; align-items: stretch; gap: 0; padding: 0; margin: 0; list-style: none; height: 100%; }
    .hdr-nav .hdr-item { position: relative; display: flex; align-items: center; }
    .hdr-nav .hdr-link {
        display: flex; align-items: center; gap: .35rem;
        padding: 1.05rem .82rem; font-size: .78rem; font-weight: 600;
        color: var(--hdr-muted); text-decoration: none; letter-spacing: .3px;
        white-space: nowrap; border-bottom: 2px solid transparent;
        transition: color .15s, border-color .15s, background .15s; height: 100%;
    }
    .hdr-nav .hdr-link:hover, .hdr-nav .hdr-link.active {
        color: #0f172a; border-bottom-color: var(--hdr-blue); background: var(--hdr-hover-bg);
    }
    .hdr-nav .hdr-link .hdr-link-icon { font-size: .9rem; opacity: .8; }
    .hdr-nav .hdr-link .hdr-chevron { font-size: .65rem; opacity: .6; margin-left: .15rem; transition: transform .2s; }
    .hdr-item:hover .hdr-chevron { transform: rotate(180deg); }
    .hdr-dropdown {
        position: absolute; top: calc(100% + 1px); left: 0; min-width: 230px;
        background: #fff; border: 1px solid var(--hdr-border); border-radius: 10px;
        box-shadow: 0 8px 30px rgba(0,0,0,.1); padding: .4rem 0;
        opacity: 0; visibility: hidden; transform: translateY(6px);
        transition: opacity .18s, transform .18s, visibility .18s; z-index: 1100;
    }
    .hdr-item:hover .hdr-dropdown,
    .hdr-item:focus-within .hdr-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
    .hdr-dropdown-header {
        font-size: .65rem; font-weight: 700; color: var(--hdr-muted);
        text-transform: uppercase; letter-spacing: .6px; padding: .45rem .9rem .2rem;
    }
    .hdr-dropdown-item {
        display: flex; align-items: center; gap: .55rem; padding: .55rem .9rem;
        font-size: .8rem; font-weight: 500; color: var(--hdr-text); text-decoration: none;
        transition: background .12s, color .12s; border-radius: 6px; margin: 0 .3rem;
    }
    .hdr-dropdown-item i { font-size: .85rem; color: var(--hdr-muted); width: 16px; text-align: center; }
    .hdr-dropdown-item:hover { background: var(--hdr-hover-bg); color: #0f172a; }
    .hdr-dropdown-item:hover i { color: var(--hdr-dark); }
    .hdr-dropdown-sep { height: 1px; background: var(--hdr-border); margin: .3rem .5rem; }
    .hdr-actions { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; margin-left: auto; }
    .hdr-user-btn {
        display: flex; align-items: center; gap: .55rem; background: none;
        border: 1px solid var(--hdr-border); border-radius: 8px; padding: .38rem .7rem .38rem .45rem;
        cursor: pointer; transition: background .15s, border-color .15s; text-decoration: none;
    }
    .hdr-user-btn:hover { background: var(--hdr-hover-bg); border-color: #c7d4e8; }
    .hdr-avatar {
        width: 30px; height: 30px; border-radius: 7px;
        background: linear-gradient(135deg, var(--hdr-blue), var(--hdr-dark));
        display: flex; align-items: center; justify-content: center;
        color: #0f172a; font-size: .8rem; font-weight: 700; flex-shrink: 0;
    }
    .hdr-user-info { line-height: 1.2; text-align: left; }
    .hdr-user-name  { font-size: .78rem; font-weight: 600; color: var(--hdr-text); display: block; }
    .hdr-user-role  { font-size: .67rem; color: var(--hdr-muted); display: block; }
    .hdr-user-chevron { font-size: .65rem; color: var(--hdr-muted); margin-left: .1rem; }
    .hdr-user-wrap { position: relative; }
    .hdr-user-dropdown {
        position: absolute; top: calc(100% + 8px); right: 0; min-width: 200px;
        background: #fff; border: 1px solid var(--hdr-border); border-radius: 10px;
        box-shadow: 0 8px 30px rgba(0,0,0,.1); padding: .4rem 0;
        opacity: 0; visibility: hidden; transform: translateY(6px);
        transition: opacity .18s, transform .18s, visibility .18s; z-index: 1200;
    }
    .hdr-user-wrap:hover .hdr-user-dropdown,
    .hdr-user-wrap:focus-within .hdr-user-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
    .hdr-user-dd-header { padding: .6rem .9rem .4rem; border-bottom: 1px solid var(--hdr-border); margin-bottom: .3rem; }
    .hdr-user-dd-name  { font-size: .82rem; font-weight: 700; color: var(--hdr-text); }
    .hdr-user-dd-email { font-size: .72rem; color: var(--hdr-muted); }
    .hdr-user-dd-item {
        display: flex; align-items: center; gap: .55rem; padding: .52rem .9rem;
        font-size: .8rem; font-weight: 500; color: var(--hdr-text); text-decoration: none;
        transition: background .12s; border-radius: 6px; margin: 0 .3rem;
        cursor: pointer; background: none; border: none; width: calc(100% - .6rem);
        text-align: left; font-family: inherit;
    }
    .hdr-user-dd-item i { width: 15px; text-align: center; color: var(--hdr-muted); }
    .hdr-user-dd-item:hover { background: var(--hdr-hover-bg); color: #0f172a; }
    .hdr-user-dd-item.danger { color: #ef4444; }
    .hdr-user-dd-item.danger i { color: #ef4444; }
    .hdr-user-dd-item.danger:hover { background: #fff5f5; }
    .hdr-login-link {
        display: flex; align-items: center; gap: .4rem; padding: .4rem .85rem;
        font-size: .8rem; font-weight: 600; color: var(--hdr-orange); text-decoration: none;
        border: 1px solid var(--hdr-orange); border-radius: 8px; transition: background .15s, color .15s;
    }
    .hdr-login-link:hover { background: var(--hdr-orange); color: #fff; }
    .hdr-toggler {
        background: none; border: 1px solid var(--hdr-border); border-radius: 7px;
        padding: .4rem .55rem; cursor: pointer; display: none; align-items: center;
        justify-content: center; color: var(--hdr-text); font-size: 1.1rem; transition: background .15s;
    }
    .hdr-toggler:hover { background: var(--hdr-hover-bg); }
    @media (max-width: 991px) {
        .hdr-toggler { display: flex; }
        .hdr-nav-wrap { display: none !important; }
        .hdr-nav-wrap.open {
            display: block !important; position: fixed; top: 0; left: 0; bottom: 0;
            width: 285px; background: #fff; z-index: 2000; overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0,0,0,.13); padding: 1.25rem 0 2rem;
            animation: drawerIn .22s ease;
        }
        @keyframes drawerIn {
            from { transform: translateX(-100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        .hdr-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1999; }
        .hdr-overlay.open { display: block; }
        .hdr-nav { flex-direction: column; align-items: stretch; gap: 0; }
        .hdr-nav .hdr-link { border-bottom: none; border-radius: 8px; margin: 0 .75rem; padding: .75rem .85rem; }
        .hdr-dropdown {
            position: static; opacity: 1; visibility: visible; transform: none;
            box-shadow: none; border: none; border-radius: 0; padding: 0 0 0 1rem; display: none;
        }
        .hdr-item.open .hdr-dropdown { display: block; }
        .hdr-item.open .hdr-chevron  { transform: rotate(180deg); }
        .hdr-divider { display: none; }
        .hdr-nav-close {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1rem 1rem; border-bottom: 1px solid var(--hdr-border); margin-bottom: .5rem;
        }
        .hdr-nav-close .close-title { font-size: .88rem; font-weight: 700; color: var(--hdr-text); display: flex; align-items: center; gap: .45rem; }
        .hdr-nav-close .close-btn {
            background: none; border: 1px solid var(--hdr-border); border-radius: 7px;
            padding: .25rem .5rem; cursor: pointer; color: var(--hdr-muted); font-size: 1rem;
        }
    }
    @media (min-width: 992px) {
        .hdr-nav-close { display: none; }
        .hdr-nav-wrap  { display: flex !important; align-items: center; justify-content: center; flex: 1; gap: 0; }
    }
</style>

<div class="hdr-overlay" id="hdrOverlay" onclick="closeDrawer()"></div>

<div class="hdr-ribbon">
    <div class="container-fluid px-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-lightning-charge-fill" style="color:var(--hdr-orange);font-size:.8rem;"></i>
            <span>Analyses budgetaires en temps reel</span>
            <span class="ribbon-dot"></span>
            <span>Exercice {{ date('Y') }}</span>
        </div>
        <div class="ribbon-right d-none d-md-flex">
            <span class="ribbon-badge"><i class="bi bi-shield-check"></i> Espace securise</span>
            <span class="ribbon-badge"><i class="bi bi-telephone"></i> +226 61 34 65 54</span>
        </div>
    </div>
</div>

<nav class="hdr-navbar" role="navigation" aria-label="Navigation principale">
    <div class="hdr-inner container-fluid">

        <a class="hdr-brand" href="{{ route('home') }}">
            <div class="hdr-brand-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
            <div class="hdr-brand-text">
                <span class="hdr-brand-name">Suivi Budget</span>
                <span class="hdr-brand-sub">SAFRECO</span>
            </div>
        </a>

        <div class="hdr-divider"></div>

        <button class="hdr-toggler ms-auto me-1" id="hdrToggler" onclick="openDrawer()" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>

        <div class="hdr-nav-wrap" id="hdrNavWrap">

            <div class="hdr-nav-close">
                <span class="close-title">
                    <i class="bi bi-bar-chart-line-fill" style="color:var(--hdr-blue)"></i> Navigation
                </span>
                <button class="close-btn" onclick="closeDrawer()"><i class="bi bi-x-lg"></i></button>
            </div>

            <ul class="hdr-nav" id="hdrNav">

                <li class="hdr-item">
                    <a class="hdr-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-grid-1x2 hdr-link-icon"></i> Tableau de bord
                    </a>
                </li>

                <li class="hdr-item" onclick="toggleMobileDD(this)">
                    <a class="hdr-link {{ request()->routeIs('budget.*') ? 'active' : '' }}" href="#" onclick="return false;">
                        <i class="bi bi-bar-chart hdr-link-icon"></i>
                        Suivi Budget
                        <i class="bi bi-chevron-down hdr-chevron"></i>
                    </a>
                    <ul class="hdr-dropdown">
                        <div class="hdr-dropdown-header">Budgetaire</div>
                        <a class="hdr-dropdown-item" href="{{ route('budget.index') }}">
                            <i class="bi bi-table"></i> Suivi budgetaire
                        </a>
                        <a class="hdr-dropdown-item" href="{{ route('budget.execution') }}">
                            <i class="bi bi-percent"></i> Suivi d'execution
                        </a>
                        <a class="hdr-dropdown-item" href="{{ route('budget.sous_budget') }}">
                            <i class="bi bi-graph-up"></i> Sous budget initial
                        </a>
                    </ul>
                </li>

                <li class="hdr-item" onclick="toggleMobileDD(this)">
                    <a class="hdr-link {{ request()->routeIs('tresorerie.*') || request()->routeIs('gestion_operations.*') ? 'active' : '' }}"
                       href="#" onclick="return false;">
                        <i class="bi bi-cash-stack hdr-link-icon"></i>
                        Tresorerie
                        <i class="bi bi-chevron-down hdr-chevron"></i>
                    </a>
                    <ul class="hdr-dropdown">
                        <div class="hdr-dropdown-header">Tresorerie</div>
                        {{-- <a class="hdr-dropdown-item" href="{{ route('tresorerie.depenses_rations') }}">
                            <i class="bi bi-receipt"></i> Depenses en rations
                        </a>
                        <a class="hdr-dropdown-item" href="{{ route('tresorerie.situation_financiere') }}">
                            <i class="bi bi-wallet2"></i> Situation financiere
                        </a> --}}
                        <div class="hdr-dropdown-sep"></div>
                        <a class="hdr-dropdown-item" href="{{ route('gestion_operations.index') }}">
                            <i class="bi bi-arrow-left-right"></i> Saisie des operations
                        </a>
                    </ul>
                </li>

                <li class="hdr-item" onclick="toggleMobileDD(this)">
                    <a class="hdr-link {{ request()->routeIs('engagement.*') || request()->routeIs('gestion_taches.*') ? 'active' : '' }}"
                       href="#" onclick="return false;">
                        <i class="bi bi-file-earmark-text hdr-link-icon"></i>
                        Engagements
                        <i class="bi bi-chevron-down hdr-chevron"></i>
                    </a>
                    <ul class="hdr-dropdown">
                        <div class="hdr-dropdown-header">Gestion</div>
                        <a class="hdr-dropdown-item" href="{{ route('gestion_taches.index') }}">
                            <i class="bi bi-kanban"></i> Taches projet
                        </a>
                        {{-- <a class="hdr-dropdown-item" href="{{ route('engagement.suivi_fournisseurs') }}">
                            <i class="bi bi-truck"></i> Suivi fournisseurs
                        </a>
                        <a class="hdr-dropdown-item" href="{{ route('engagement.suivi_audits') }}">
                            <i class="bi bi-clipboard-check"></i> Audits traites
                        </a> --}}
                    </ul>
                </li>

                <li class="hdr-item">
                    <a class="hdr-link {{ request()->routeIs('gestion_previsions.*') || request()->routeIs('data.prevision*') ? 'active' : '' }}"
                       href="{{ route('gestion_previsions.index') }}">
                        <i class="bi bi-bullseye hdr-link-icon"></i> Previsions
                    </a>
                </li>

                <li class="hdr-item">
                    <a class="hdr-link {{ request()->routeIs('gestion_realisations.*') || request()->routeIs('data.realisation*') ? 'active' : '' }}"
                       href="{{ route('gestion_realisations.index') }}">
                        <i class="bi bi-check2-circle hdr-link-icon"></i> Realisations
                    </a>
                </li>

                <li class="hdr-item">
                    <a class="hdr-link {{ request()->routeIs('config.page') ? 'active' : '' }}"
                       href="{{ route('config.page') }}">
                        <i class="bi bi-gear hdr-link-icon"></i> Config.
                    </a>
                </li>

            </ul>
        </div>

        <div class="hdr-actions">
            @guest
                <a href="{{ route('login') }}" class="hdr-login-link">
                    <i class="bi bi-box-arrow-in-right"></i> Connexion
                </a>
            @endguest

            @auth
                <div class="hdr-user-wrap">
                    <button class="hdr-user-btn" type="button">
                        <div class="hdr-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="hdr-user-info d-none d-lg-block">
                            <span class="hdr-user-name">{{ Str::limit(Auth::user()->name ?? 'Utilisateur', 16) }}</span>
                            <span class="hdr-user-role">Administrateur</span>
                        </div>
                        <i class="bi bi-chevron-down hdr-user-chevron d-none d-lg-inline"></i>
                    </button>
                    <div class="hdr-user-dropdown">
                        <div class="hdr-user-dd-header">
                            <div class="hdr-user-dd-name">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                            <div class="hdr-user-dd-email">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="hdr-user-dd-item">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a href="{{ route('home') }}" class="hdr-user-dd-item">
                            <i class="bi bi-grid-1x2"></i> Tableau de bord
                        </a>
                        <div class="hdr-dropdown-sep"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hdr-user-dd-item danger">
                                <i class="bi bi-box-arrow-right"></i> Deconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

    </div>
</nav>

<script>
    function openDrawer() {
        document.getElementById('hdrNavWrap').classList.add('open');
        document.getElementById('hdrOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        document.getElementById('hdrNavWrap').classList.remove('open');
        document.getElementById('hdrOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    function toggleMobileDD(item) {
        if (window.innerWidth >= 992) return;
        item.classList.toggle('open');
    }
</script>
