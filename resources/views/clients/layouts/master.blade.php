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
            background: #f0a800;
            color: #1a1200;
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
            background: #003d82;
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
            border-color: #c47800;
            box-shadow: 0 0 0 0.2rem rgba(240, 168, 0, 0.2);
        }

        .form-select {
            padding: 9px 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #c47800;
            box-shadow: 0 0 0 0.2rem rgba(240, 168, 0, 0.2);
        }
    </style>
</head>

<body>
    @include('clients.require.header')

    <!-- Contenu principal -->
    <div class="container" style="min-height: 100vh; height: auto;">

        @if (session('success') || session('error'))
        <style>
            .sfy-toast {
                position: fixed;
                top: 1.25rem;
                right: 1.25rem;
                z-index: 9999;
                min-width: 320px;
                max-width: 420px;
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 8px 32px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.08);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                animation: sfy-slide-in .35s cubic-bezier(.22,1,.36,1) both;
            }
            @keyframes sfy-slide-in {
                from { opacity: 0; transform: translateX(60px) scale(.95); }
                to   { opacity: 1; transform: translateX(0)   scale(1);    }
            }
            .sfy-toast.hiding {
                animation: sfy-slide-out .25s ease forwards;
            }
            @keyframes sfy-slide-out {
                to { opacity: 0; transform: translateX(60px) scale(.95); }
            }
            .sfy-toast-bar {
                height: 4px;
                width: 100%;
            }
            .sfy-toast-bar.success { background: linear-gradient(90deg, #16a34a, #22c55e); }
            .sfy-toast-bar.error   { background: linear-gradient(90deg, #dc2626, #f87171); }
            .sfy-toast-body {
                display: flex;
                align-items: flex-start;
                gap: .85rem;
                padding: 1rem 1.1rem 1rem 1rem;
            }
            .sfy-toast-icon {
                flex-shrink: 0;
                width: 40px;
                height: 40px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }
            .sfy-toast-icon.success { background: #f0fdf4; color: #16a34a; }
            .sfy-toast-icon.error   { background: #fef2f2; color: #dc2626; }
            .sfy-toast-content { flex: 1; min-width: 0; }
            .sfy-toast-title {
                font-size: .88rem;
                font-weight: 700;
                margin: 0 0 .2rem;
                color: #0f172a;
            }
            .sfy-toast-title.success { color: #15803d; }
            .sfy-toast-title.error   { color: #b91c1c; }
            .sfy-toast-msg {
                font-size: .82rem;
                color: #475569;
                margin: 0;
                line-height: 1.5;
                word-break: break-word;
            }
            .sfy-toast-close {
                background: none;
                border: none;
                padding: .5rem;
                cursor: pointer;
                color: #94a3b8;
                font-size: 1rem;
                line-height: 1;
                flex-shrink: 0;
                align-self: flex-start;
                transition: color .15s;
            }
            .sfy-toast-close:hover { color: #475569; }
            .sfy-toast-progress {
                height: 3px;
                background: #e2e8f0;
                position: relative;
                overflow: hidden;
            }
            .sfy-toast-progress-bar {
                position: absolute;
                left: 0; top: 0; bottom: 0;
                width: 100%;
                animation: sfy-progress 5s linear forwards;
            }
            .sfy-toast-progress-bar.success { background: #16a34a; }
            .sfy-toast-progress-bar.error   { background: #dc2626; }
            @keyframes sfy-progress {
                from { width: 100%; }
                to   { width: 0%;   }
            }
        </style>

        @php $isSuccess = session('success'); $sfyType = $isSuccess ? 'success' : 'error'; @endphp

        <div class="sfy-toast" id="sfyToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="sfy-toast-bar {{ $sfyType }}"></div>
            <div class="sfy-toast-body">
                <div class="sfy-toast-icon {{ $sfyType }}">
                    @if($isSuccess)
                        <i class="bi bi-check-circle-fill"></i>
                    @else
                        <i class="bi bi-x-circle-fill"></i>
                    @endif
                </div>
                <div class="sfy-toast-content">
                    <p class="sfy-toast-title {{ $sfyType }}">
                        {{ $isSuccess ? 'Opération réussie' : 'Une erreur est survenue' }}
                    </p>
                    <p class="sfy-toast-msg">
                        {{ $isSuccess ? session('success') : session('error') }}
                    </p>
                </div>
                <button class="sfy-toast-close" onclick="sfyDismiss()" aria-label="Fermer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="sfy-toast-progress">
                <div class="sfy-toast-progress-bar {{ $sfyType }}"></div>
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

    <!-- Toast auto-dismiss -->
    <script>
        function sfyDismiss() {
            var t = document.getElementById('sfyToast');
            if (!t) return;
            t.classList.add('hiding');
            setTimeout(function() { t.remove(); }, 280);
        }
        document.addEventListener('DOMContentLoaded', function() {
            var t = document.getElementById('sfyToast');
            if (t) { setTimeout(sfyDismiss, 5000); }
        });
    </script>

    {{-- Stacks de scripts depuis les vues enfants --}}
    @stack('scripts')
</body>

</html>
