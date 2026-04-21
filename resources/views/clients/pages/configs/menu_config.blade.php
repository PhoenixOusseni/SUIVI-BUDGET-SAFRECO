<style>
    .cfg-nav-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: .45rem .55rem;
        display: flex;
        align-items: center;
        gap: .3rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        margin-bottom: 1.5rem;
    }
    .cfg-nav-item {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .48rem .95rem;
        border-radius: 9px;
        font-size: .8rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        border: 1px solid transparent;
        transition: background .15s, color .15s, border-color .15s, box-shadow .15s;
        white-space: nowrap;
        letter-spacing: .2px;
    }
    .cfg-nav-item i {
        font-size: .95rem;
        transition: color .15s;
    }
    .cfg-nav-item:hover {
        background: #fffbeb;
        color: #c47800;
        border-color: #f5d580;
    }
    .cfg-nav-item:hover i { color: #c47800; }
    .cfg-nav-item.active {
        background: linear-gradient(135deg, #f0a800, #c47800);
        color: #0f172a;
        border-color: transparent;
        box-shadow: 0 3px 10px rgba(240,168,0,.3);
    }
    .cfg-nav-item.active i { color: #0f172a; }
    .cfg-nav-sep {
        width: 1px; height: 22px;
        background: #e2e8f0;
        flex-shrink: 0;
        margin: 0 .1rem;
    }
    @media (max-width: 640px) {
        .cfg-nav-wrap { gap: .25rem; padding: .35rem .4rem; }
        .cfg-nav-item { font-size: .75rem; padding: .4rem .7rem; }
        .cfg-nav-sep { display: none; }
    }
</style>

<div class="col-12 mb-1">
    <nav class="cfg-nav-wrap" aria-label="Navigation configuration" style="background: #c6c4c479">

        <a href="{{ route('config.page') }}"
           class="cfg-nav-item {{ request()->routeIs('config.page') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            Info société
        </a>

        <div class="cfg-nav-sep"></div>

        <a href="{{ route('gestion_rubriques.index') }}"
           class="cfg-nav-item {{ request()->routeIs('gestion_rubriques.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            Rubriques
        </a>

        <a href="{{ route('gestion_code_budgets.index') }}"
           class="cfg-nav-item {{ request()->routeIs('gestion_code_budgets.*') ? 'active' : '' }}">
            <i class="bi bi-upc-scan"></i>
            Codes budgétaires
        </a>

        <a href="{{ route('gestion_ligne_budgets.index') }}"
           class="cfg-nav-item {{ request()->routeIs('gestion_ligne_budgets.*') ? 'active' : '' }}">
            <i class="bi bi-list-columns-reverse"></i>
            Lignes budgétaires
        </a>

        <div class="cfg-nav-sep"></div>

        <a href="{{ route('gestion_users.index') }}"
           class="cfg-nav-item {{ request()->routeIs('gestion_users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Utilisateurs
        </a>

        <a href="{{ route('gestion_adherants.index') }}"
           class="cfg-nav-item {{ request()->routeIs('gestion_adherants.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            Adhérants
        </a>

        <a href="{{ route('engagement.gestion_fournisseurs') }}"
           class="cfg-nav-item {{ request()->routeIs('engagement.gestion_fournisseurs') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            Fournisseurs
        </a>

    </nav>
</div>
