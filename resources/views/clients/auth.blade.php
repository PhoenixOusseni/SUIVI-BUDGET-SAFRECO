<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Portail Recrutement - AUXFIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    @include('partials.style')
</head>

<body>
    <!-- NAVBAR -->
    @include('clients.require.auth-connect')

    <!-- FORMULAIRE -->
    <div class="container my-5" style="margin-top: 50px !important;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="container mt-3 mb-3">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Fermer"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Fermer"></button>
                                </div>
                            @endif
                        </div>
                        <h4 class="card-title text-center">AUTHENTIFICATION</h4>
                        <p class="text-center">Bienvenue sur le portail de recrutement d'AUXFIN !</p>
                        <small class="text-dark">Saisir Email / Mot de passe</small>

                        <form class="mt-3" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email (*)</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="exemple@exemple.com" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe (*)</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-red w-100" style="margin-top: 50px !important;">Se
                                connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <p>Copyright © {{ date('Y') }} <span class="highlight">AUXFIN</span>. Tous droits réservés.</p>
        <p>Tel : +226 61 34 65 54 | Mail : recrutement@auxfin.bf</p>
        <a href="{{ route('auth_admin') }}" class="admin-btn">Connexion Admin</a>
    </footer>

    <!-- Bootstrap JS -->
    @include('partials.script')
</body>

</html>
