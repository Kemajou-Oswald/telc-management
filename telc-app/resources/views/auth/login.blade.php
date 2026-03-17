<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Tara Institut et Der Beste Betreuer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #1A1A1A;
            --navy-mid: #2A2A2A;
            --red: #C41B1B;
            --white: #FFFFFF;
            --bg: #F5F5F5;
            --border: #E2E2E2;
            --text: #1A1A1A;
            --muted: #7A7A7A;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f1f1f1 0%, #f7f7f7 45%, #ffffff 100%);
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .shell {
            width: min(880px, 100%);
            display: grid;
            grid-template-columns: 360px 1fr;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(26, 26, 26, 0.12);
            background: var(--white);
        }

        .left {
            background: radial-gradient(circle at top right, rgba(196, 27, 27, 0.18), transparent 35%), var(--navy);
            color: #fff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .brand-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 4px;
        }

        .intro-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: rgba(255, 255, 255, 0.62);
            margin-bottom: 16px;
        }

        .intro-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red);
        }

        .intro h1 {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .intro p {
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.72);
        }

        .footer-note {
            font-size: 11px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.46);
        }

        .right {
            padding: 56px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-kicker {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 6px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .form-copy {
            font-size: 14px;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 26px;
        }

        .error-msg {
            padding: 11px 13px;
            border-radius: 10px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            font-size: 12.5px;
            margin-bottom: 20px;
        }

        .fields {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #C4CADB;
            pointer-events: none;
            display: flex;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 46px;
            padding: 0 14px 0 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font: inherit;
            font-size: 14px;
            color: var(--text);
            background: var(--white);
            outline: none;
            transition: border-color 130ms, box-shadow 130ms;
        }

        input::placeholder { color: #C4CADB; }

        input:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            margin-bottom: 22px;
        }

        .remember input[type="checkbox"] {
            width: 13px;
            height: 13px;
            accent-color: var(--navy);
            cursor: pointer;
        }

        .remember span {
            font-size: 13px;
            color: var(--muted);
        }

        .btn {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #fff;
            background: var(--red);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 130ms, transform 100ms, box-shadow 130ms;
        }

        .btn:hover {
            background: #a81717;
            box-shadow: 0 6px 18px rgba(196, 27, 27, 0.28);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 760px) {
            .shell { grid-template-columns: 1fr; }
            .left { padding: 34px 28px; }
            .right { padding: 38px 28px; }
        }
    </style>
</head>
<body>
<div class="shell">
    <section class="left">
        <div>
            <div class="brand">
                <svg width="42" height="42" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="17" cy="7" rx="4" ry="6.5" fill="rgba(255,255,255,0.85)" transform="rotate(-25 17 7)"/>
                    <ellipse cx="24" cy="5" rx="4" ry="6.5" fill="rgba(255,255,255,0.85)" transform="rotate(10 24 5)"/>
                    <ellipse cx="11" cy="12" rx="4" ry="6" fill="rgba(255,255,255,0.85)" transform="rotate(-55 11 12)"/>
                    <ellipse cx="19" cy="34" rx="4" ry="6.5" fill="rgba(255,255,255,0.5)" transform="rotate(155 19 34)"/>
                    <ellipse cx="26" cy="36" rx="4" ry="6.5" fill="rgba(255,255,255,0.5)" transform="rotate(170 26 36)"/>
                    <ellipse cx="31" cy="29" rx="4" ry="5.5" fill="rgba(255,255,255,0.5)" transform="rotate(120 31 29)"/>
                    <circle cx="20" cy="20" r="7" fill="rgba(255,255,255,0.9)"/>
                    <circle cx="20" cy="21.5" r="5.5" fill="rgba(255,255,255,0.35)"/>
                </svg>
                <div>
                    <div class="brand-name">Tara Institut et Der Beste Betreuer</div>
                    <div class="brand-sub">Espace securise</div>
                </div>
            </div>
        </div>

        <div class="intro">
            <div class="intro-label"><span class="intro-dot"></span> Connexion</div>
            <h1>Bienvenue.</h1>
            <p>Connectez-vous avec votre adresse e-mail et votre mot de passe pour acceder a votre espace.</p>
        </div>

        <div class="footer-note">Acces reserve aux comptes autorises.</div>
    </section>

    <section class="right">
        <div class="form-kicker">Connexion</div>
        <h2 class="form-title">Acces utilisateur</h2>
        <p class="form-copy">Entrez vos identifiants pour ouvrir votre session.</p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            @if ($errors->any())
                <div class="error-msg">{{ $errors->first() }}</div>
            @endif

            <div class="fields">
                <div class="field">
                    <label for="email">E-mail</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="adresse@exemple.com" autocomplete="email" required autofocus>
                    </div>
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password" placeholder="********" autocomplete="current-password" required>
                    </div>
                </div>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Rester connecte</span>
            </label>

            <button class="btn" type="submit">
                Se connecter
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </form>
    </section>
</div>
</body>
</html>

