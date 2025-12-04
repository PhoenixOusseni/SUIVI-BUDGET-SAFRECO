<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">
                    <div class="sidenav-menu-heading">Pages</div>
                    <a class="nav-link collapsed" href="">
                        <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        TABLEAU DE BORD
                    </a>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#pagesCollapseError1" aria-expanded="false" aria-controls="pagesCollapseError">
                        <div class="nav-link-icon"><i data-feather="repeat"></i></div>
                        MEMBRES
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseError1" data-bs-parent="#accordionSidenavPagesMenu">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="">Membre actifs</a>
                            <a class="nav-link" href="">Membre en attents</a>
                        </nav>
                    </div>
                        <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                            data-bs-target="#pagesCollapseError2" aria-expanded="false"
                            aria-controls="pagesCollapseError">
                            <div class="nav-link-icon"><i data-feather="package"></i></div>
                            COTISATION
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError2" data-bs-parent="#accordionSidenavPagesMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" href="">Liste cotisation</a>
                                <a class="nav-link" href="">Ajouter cotisation</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                            data-bs-target="#collapsePages345" aria-expanded="false" aria-controls="collapsePages">
                            <div class="nav-link-icon"><i data-feather="users"></i></div>
                            ADMINISTRATION
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages345" data-bs-parent="#accordionSidenav">
                            <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                                <a class="nav-link collapsed" href="">
                                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                                    Gestion des administrateur
                                </a>
                                <a class="nav-link collapsed" href="">
                                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                                    Ajouter administrateur
                                </a>
                                <a class="nav-link collapsed" href="">
                                    <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                                    Année budgetaire
                                </a>
                            </nav>
                        </div>
                </div>
            </div>
            <img class="text-center" src="{{ asset('assets/img/22_a9ad743c.jpg') }}" alt="logo" width="100%"
                style="margin: auto">
            <!-- Sidenav Footer-->
            <div class="sidenav-footer">
                <div class="sidenav-footer-content">
                    <div class="sidenav-footer-subtitle">Utilisateur connecté(e):</div>
                    <div class="sidenav-footer-title">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</div>
                    <h6 class="text-center text-primary">Role : Admin</h6>
                </div>
            </div>
        </nav>
    </div>
