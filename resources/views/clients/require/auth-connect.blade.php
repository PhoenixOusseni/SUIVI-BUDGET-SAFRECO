<nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-3">
    <a class="navbar-brand" href="#">
        <img src="{{ asset('images/auxfin.png') }}" alt="Logo" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="#">Pourquoi travailler à AUXFIN</a></li>
            <li class="nav-item"><a class="nav-link">|</a></li>
            <li class="nav-item"><a class="nav-link" href="#">A propos de AUXFIN</a></li>
        </ul>
        <div class="d-flex">
            <button class="btn btn-light me-2"><a class="connect-btn" href="{{ route('auth') }}"><i data-feather="key"></i> &nbsp;&nbsp;
                    Connexion</a></button>
            <button class="btn btn-outline-light"><a class="connect-btn" href="{{ route('inscription') }}"><i data-feather="user"></i> &nbsp;&nbsp; Créer un
                    compte</a></button>
        </div>
    </div>
</nav>
