<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi-budget</title>
    <!-- Bootstrap 5 CSS -->
    @include('partials.style')
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 45vh !important;
            min-height: 45vh !important;
        }

        .top-bar {
            background: #003d82;
            color: #fff;
            font-weight: bold;
            padding: 5px 0;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
        }

        .page-title {
            text-align: center;
            margin: 30px 0;
            font-weight: bold;
            font-size: 1.8rem;
        }
        .page-title::after {
            content: "";
            display: block;
            width: 150px;
            height: 2px;
            background: #f09103;
            margin: 10px auto 0;
        }
    </style>
</head>

<body>
    @include('clients.require.header')

    <!-- Contenu principal -->
    <div class="container" style="min-height: 100vh; height: auto;">

        @yield('content')

    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <p>Copyright © {{ date('Y') }} <span class="highlight">SAFRECO</span>. Tous droits réservés.</p>
        <p>Tel : +226 61 34 65 54 | Mail : infos@safreco.bf</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('partials.script')
</body>

</html>
