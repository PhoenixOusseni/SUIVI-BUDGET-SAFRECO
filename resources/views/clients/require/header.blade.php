<!-- Bandeau supérieur -->
<div class="top-bar text-center p-2">
    <a href="#" class="text-white" style="font-size: 13px;">FAITES VOS ANALYSES RAPIDEMENT</a>
    <span class="mx-2">|</span>
    <a href="#" class="text-white" style="font-size: 13px;">A PROPOS DE NOS ANALYSES</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom" style="background-color: #ffffff !important;">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/images.jpg') }}" alt="Logo" width="100" height="50">
        </a>
        <!-- Menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        SUIVI BUDGET
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="nav-link" href="{{ route('budget.index') }}">Suivi budgétaire</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('budget.execution') }}">Suivi du taux
                                d’exécution du
                                budgétaire</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('budget.consommation') }}">Suivi du taux
                                de consommation des subventions</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        SUIVI TRESORERIE
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="nav-item"><a class="nav-link" href="{{ route('tresorerie.depenses_rations') }}">Suivi
                                des dépenses en termes de rations</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="{{ route('tresorerie.situation_financiere') }}">Suivi de la situation
                                financière</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('gestion_operations.index') }}">Saisie
                                des operations</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        SUIVI ENGAGEMENTS
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="nav-item"><a class="nav-link" href="{{ route('gestion_taches.index') }}">Saisi des taches</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('engagement.gestion_fournisseurs') }}">Saisi fournisseurs</a></li>

                        <li class="nav-item"><a class="nav-link"
                                href="{{ route('engagement.suivi_fournisseurs') }}">Suivi des fournisseurs</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('engagement.suivi_audits') }}">Suivi
                                des
                                audits traitées</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('data.page') }}">DONNEES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('config.page') }}">CONFIGURATION</a>
                </li>
            </ul>

            <!-- Profil utilisateur -->
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="font-weight: bold;">
                            <i data-feather="user"></i> &nbsp;&nbsp;Mon Compte
                        </a>
                    </li>
                @endguest
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            style="font-weight: bold;">
                            {{ Auth::user()->nom }} {{ Auth::user()->prenom }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profil</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
