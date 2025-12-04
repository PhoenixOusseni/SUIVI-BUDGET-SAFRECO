<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
    id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
            data-feather="menu"></i></button>

    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('dashboard') }}">Ordre Pharmacien</a>
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">

        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-success p-3" type="submit">
                    <i data-feather="log-out"></i>
                    {{ __('Se déconnecter') }}
                </button>
            </form>
        </li>
    </ul>
</nav>
