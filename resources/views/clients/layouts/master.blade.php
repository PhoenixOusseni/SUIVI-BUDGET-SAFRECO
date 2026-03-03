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

        .form-control {
            padding: 9px 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1d3f7f;
            box-shadow: 0 0 0 0.2rem rgba(29, 63, 127, 0.1);
        }

        .form-select {
            padding: 9px 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #1d3f7f;
            box-shadow: 0 0 0 0.2rem rgba(29, 63, 127, 0.1);
        }
    </style>
</head>

<body>
    @include('clients.require.header')

    <!-- Contenu principal -->
    <div class="container" style="min-height: 100vh; height: auto;">

        @if (session('success') || session('error'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0"
                    role="alert" aria-live="assertive" aria-atomic="true" id="statusToast" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body">
                            @if (session('success'))
                                <div class="text-center">
                                    <h5 class="mb-2 text-white">Succès !</h5>
                                    <p class="mt-3 text-white">
                                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                                    </p>
                                </div>
                            @else
                                <div class="text-center">
                                    <h5 class="mb-2 text-white">Erreur !</h5>
                                    <p class="mt-3 text-white">
                                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Yield the main content --}}
        @yield('content')

    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <p>Copyright © {{ date('Y') }} <span class="highlight">SAFRECO</span>. Tous droits réservés.</p>
        <p>Tel : +226 61 34 65 54 | Mail : infos@safreco.com</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('partials.script')

    <!-- Initialiser les toasts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('statusToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>

    {{-- Stacks de scripts depuis les vues enfants --}}
    @stack('scripts')
</body>

</html>
