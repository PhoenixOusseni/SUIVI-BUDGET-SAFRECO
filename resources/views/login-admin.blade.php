<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion DS423</title>
    @include('partials.style')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background: #f15d1d !important;
            /* background-image: url('{{ asset('images/premium.jpg') }}'); */
            /* Mets ici ton image */
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            min-height: 100vh !important;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 5px;
            padding: 2rem;
            width: 100%;
            height: 65vh;
            max-width: 350px;
            position: relative;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .login-button {
            position: absolute;
            right: -25px;
            bottom: 20px;
            background-color: #020a12;
            border: none;
            border-radius: 50%;
            width: 65px;
            height: 65px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #f15d1d;
            border: 2px solid #020a12;
        }

        @media (max-width: 768px) {
            .login-container {
                justify-content: center;
                padding: 1rem;
            }

            .login-button {
                position: static;
                margin-top: 1.5rem;
                width: 100%;
                border-radius: 8px;
                font-size: 1rem;
                height: 45px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <form class="login-card" action="{{ route('login_admin') }}" method="POST">
            @csrf
            <h5 class="text-dark fw-bold mb-3">AUXFIN BURKINA -- CONNEXION</h5>
            <div class="mb-4 text-center">
                <img src="{{ asset('images/auxfin.png') }}" alt="logo auxfin" style="width: 50%; margin-left: auto; margin-right: auto;">
            </div>
            <p class="fw-semibold mb-3 text-bolde">Entrez vos identifiants...</p>

            <div class="mb-4">
                <input type="email" name="email" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>

            <div class="mb-5">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="login-button">
                →
            </button>
        </form>
    </div>

    @include('partials.script')
</body>

</html>
