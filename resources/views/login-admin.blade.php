<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Suivi Budget</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap 5 --}}
    <link href="{{ asset('asset/css/styles.css') }}" rel="stylesheet" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --brand-blue:   #003d82;
            --brand-dark:   #002560;
            --brand-orange: #d54e14;
            --brand-orange-light: #f07044;
            --text-muted:   #6b7280;
            --input-border: #d1d5db;
            --input-focus:  #003d82;
            --radius:       14px;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f0f4f8;
        }

        /* ── PAGE WRAPPER ── */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* ── LEFT PANEL ── */
        .auth-panel-left {
            flex: 1;
            background: linear-gradient(145deg, var(--brand-dark) 0%, var(--brand-blue) 55%, #1a5bb0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .auth-panel-left::before,
        .auth-panel-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: .08;
        }
        .auth-panel-left::before {
            width: 520px; height: 520px;
            background: white;
            top: -160px; left: -160px;
        }
        .auth-panel-left::after {
            width: 360px; height: 360px;
            background: var(--brand-orange);
            bottom: -120px; right: -80px;
        }

        .brand-icon {
            width: 80px; height: 80px;
            background: rgba(255,255,255,.15);
            border: 2px solid rgba(255,255,255,.25);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem;
            color: #fff;
            margin-bottom: 1.75rem;
            backdrop-filter: blur(6px);
        }

        .brand-title {
            color: #fff;
            font-size: 1.85rem;
            font-weight: 700;
            letter-spacing: -.5px;
            margin-bottom: .5rem;
            text-align: center;
        }

        .brand-subtitle {
            color: rgba(255,255,255,.65);
            font-size: .92rem;
            text-align: center;
            max-width: 280px;
            line-height: 1.6;
        }

        .brand-badge {
            margin-top: 2.5rem;
            display: flex;
            gap: 1rem;
        }

        .brand-badge-item {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 8px;
            padding: .5rem .9rem;
            color: rgba(255,255,255,.85);
            font-size: .78rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        /* ── RIGHT PANEL ── */
        .auth-panel-right {
            width: 480px;
            min-width: 420px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.75rem;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 360px;
        }

        .login-greeting {
            font-size: 1.55rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: .35rem;
            letter-spacing: -.4px;
        }

        .login-hint {
            color: var(--text-muted);
            font-size: .88rem;
            margin-bottom: 2rem;
        }

        /* ── ALERT ── */
        .auth-alert {
            background: #fff1f1;
            border: 1px solid #fca5a5;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: .75rem 1rem;
            color: #b91c1c;
            font-size: .85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ── FLOATING LABEL FIELD ── */
        .field-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .field-group .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
            transition: color .2s;
        }

        .field-group input {
            width: 100%;
            height: 52px;
            padding: 0 44px 0 42px;
            border: 1.5px solid var(--input-border);
            border-radius: 10px;
            font-size: .92rem;
            font-family: inherit;
            color: #111827;
            background: #f9fafb;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            -webkit-appearance: none;
        }

        .field-group input::placeholder { color: #9ca3af; }

        .field-group input:focus {
            border-color: var(--input-focus);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0,61,130,.1);
        }

        .field-group input:focus + .field-icon-label,
        .field-group input:not(:placeholder-shown) + .field-icon-label {
            top: -8px;
            font-size: .72rem;
            color: var(--brand-blue);
            background: #fff;
            padding: 0 4px;
        }

        .field-group input.is-invalid {
            border-color: #ef4444;
            background: #fff8f8;
        }

        .field-group input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: .8rem;
            margin-top: .3rem;
            display: block;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1;
            transition: color .2s;
        }
        .pw-toggle:hover { color: var(--brand-blue); }

        /* ── SUBMIT BUTTON ── */
        .btn-login {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--brand-blue) 0%, #1a5bb0 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            margin-top: 1.75rem;
            transition: transform .15s, box-shadow .2s, filter .2s;
            box-shadow: 0 4px 18px rgba(0,61,130,.28);
            letter-spacing: .3px;
        }

        .btn-login:hover {
            filter: brightness(1.08);
            box-shadow: 0 6px 22px rgba(0,61,130,.38);
            transform: translateY(-1px);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login .btn-arrow {
            background: rgba(255,255,255,.18);
            border-radius: 6px;
            width: 26px; height: 26px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
        }

        /* ── DIVIDER ── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .auth-divider span {
            font-size: .75rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ── FOOTER ── */
        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: .78rem;
            color: #9ca3af;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .auth-panel-left { display: none; }
            .auth-panel-right {
                width: 100%;
                min-width: unset;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
<div class="auth-wrapper">

    {{-- ─── LEFT BRANDING PANEL ─── --}}
    <div class="auth-panel-left">
        <div class="brand-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div class="brand-title">Suivi Budget</div>
        <div class="brand-subtitle">
            Pilotez vos prévisions et réalisations budgétaires en toute clarté.
        </div>
        <div class="brand-badge">
            <div class="brand-badge-item">
                <i class="bi bi-shield-check"></i> Sécurisé
            </div>
            <div class="brand-badge-item">
                <i class="bi bi-graph-up-arrow"></i> Analytique
            </div>
            <div class="brand-badge-item">
                <i class="bi bi-clock-history"></i> Temps réel
            </div>
        </div>
    </div>

    {{-- ─── RIGHT FORM PANEL ─── --}}
    <div class="auth-panel-right">
        <div class="login-form-wrapper">

            <p class="login-greeting">Bon retour 👋</p>
            <p class="login-hint">Connectez-vous pour accéder à votre espace de gestion.</p>

            {{-- Erreurs globales --}}
            @if ($errors->any())
                <div class="auth-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login_admin') }}" method="POST" autocomplete="off" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <i class="bi bi-envelope field-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Adresse e-mail"
                        value="{{ old('email') }}"
                        class="@error('email') is-invalid @enderror"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="field-group">
                    <i class="bi bi-lock field-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Mot de passe"
                        class="@error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="pw-toggle" id="pwToggle" aria-label="Afficher le mot de passe">
                        <i class="bi bi-eye" id="pwToggleIcon"></i>
                    </button>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    Se connecter
                    <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
                </button>

            </form>

            <div class="auth-divider"><span>Suivi Budget — {{ date('Y') }}</span></div>
            <div class="auth-footer">
                Accès réservé aux utilisateurs autorisés.<br>
                Toute tentative non autorisée est enregistrée.
            </div>

        </div>
    </div>

</div>

{{-- Bootstrap JS (pour les utilitaires) --}}
<script src="{{ asset('asset/js/scripts.js') }}"></script>
<script>
    // Toggle password visibility
    const pwToggle = document.getElementById('pwToggle');
    const pwInput  = document.getElementById('password');
    const pwIcon   = document.getElementById('pwToggleIcon');

    pwToggle.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type = isHidden ? 'text' : 'password';
        pwIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>
</body>

</html>
