<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CFPL TARA ERP') }} — Connexion</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { blue: '#1D1B84', red: '#E31E24', dark: '#0F0E3D' }
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { letter-spacing: -0.01em; }

        /* ── Côté gauche sombre ── */
        .panel-left {
            background: #0d0c2b;
            position: relative;
            overflow: hidden;
        }
        /* Lueur bleue haut-gauche */
        .panel-left::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(29,27,132,0.55) 0%, transparent 65%);
            pointer-events: none;
        }
        /* Lueur rouge bas-droite */
        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(227,30,36,0.28) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Grille de points décorative */
        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ── Côté droit clair ── */
        .panel-right {
            background: linear-gradient(145deg, #f0f2fb 0%, #f8f5fb 50%, #fdf0f0 100%);
            position: relative;
            overflow: hidden;
        }
        .panel-right::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(29,27,132,0.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .panel-right::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(227,30,36,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Carte formulaire glass ── */
        .form-card {
            background: rgba(255,255,255,0.82);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.75);
            box-shadow: 0 20px 60px rgba(15,14,61,0.1), 0 4px 16px rgba(15,14,61,0.06);
        }

        /* ── Inputs ── */
        .form-input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(15,14,61,0.03);
            border: 1.5px solid rgba(15,14,61,0.1);
            border-radius: 11px;
            font-size: 13.5px;
            font-family: inherit;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-input::placeholder { color: #94a3b8; }
        .form-input:focus {
            background: white;
            border-color: #1D1B84;
            box-shadow: 0 0 0 3px rgba(29,27,132,0.1);
        }

        /* ── Label ── */
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ── Bouton connexion ── */
        .btn-connect {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1D1B84 0%, #2d2bb0 100%);
            color: white;
            font-size: 14px;
            font-weight: 800;
            font-family: inherit;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 4px 20px rgba(29,27,132,0.35), 0 1px 4px rgba(29,27,132,0.2);
            position: relative;
            overflow: hidden;
        }
        .btn-connect::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        .btn-connect:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(29,27,132,0.45), 0 2px 8px rgba(29,27,132,0.2);
        }
        .btn-connect:active { transform: translateY(0); }

        /* ── Checkbox ── */
        .custom-checkbox {
            width: 16px; height: 16px;
            border-radius: 5px;
            border: 1.5px solid rgba(15,14,61,0.2);
            cursor: pointer;
            accent-color: #1D1B84;
            flex-shrink: 0;
        }

        /* ── Logo hover ── */
        .logo-wrap {
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
        }
        .logo-wrap:hover {
            transform: scale(1.06) rotate(-1deg);
        }

        /* ── Stat cards côté gauche ── */
        .stat-card {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 16px 20px;
            backdrop-filter: blur(8px);
            transition: all 0.2s;
        }
        .stat-card:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* ── Animation entrée ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp 0.5s 0.1s cubic-bezier(0.22,1,0.36,1) both; }
        .anim-2 { animation: fadeUp 0.5s 0.2s cubic-bezier(0.22,1,0.36,1) both; }
        .anim-3 { animation: fadeUp 0.5s 0.3s cubic-bezier(0.22,1,0.36,1) both; }
        .anim-4 { animation: fadeUp 0.5s 0.4s cubic-bezier(0.22,1,0.36,1) both; }
        .anim-5 { animation: fadeUp 0.5s 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    </style>
</head>

<body class="h-full antialiased overflow-hidden">

<div class="flex h-full">

    {{-- ══════════ PANNEAU GAUCHE — Sombre ══════════ --}}
    <div class="panel-left hidden lg:flex lg:w-[52%] flex-col justify-between p-12 xl:p-16">

        <div class="dot-grid"></div>

        {{-- Header gauche --}}
        <div class="relative z-10 anim-1">
            <div class="logo-wrap inline-block"
                 style="background:white; padding:12px 16px; border-radius:18px; box-shadow:0 8px 36px rgba(13,12,43,0.6), 0 0 0 1px rgba(255,255,255,0.08);">
                <img src="{{ asset('images/logo_tara.png') }}" alt="CFPL TARA"
                     style="height:52px; width:auto; display:block;">
            </div>

            <div style="margin-top:18px; display:flex; gap:4px; align-items:center;">
                <div style="width:28px; height:3px; border-radius:2px; background:#1D1B84; box-shadow:0 0 10px rgba(29,27,132,0.8);"></div>
                <div style="width:6px; height:3px; border-radius:2px; background:rgba(255,255,255,0.12);"></div>
                <div style="width:28px; height:3px; border-radius:2px; background:#E31E24; box-shadow:0 0 10px rgba(227,30,36,0.8);"></div>
            </div>
        </div>

        {{-- Texte central --}}
        <div class="relative z-10">
            <div class="anim-2">
                <span style="font-size:10px; font-weight:800; color:rgba(227,30,36,0.9); background:rgba(227,30,36,0.12); border:1px solid rgba(227,30,36,0.3); padding:4px 12px; border-radius:99px; letter-spacing:0.18em; text-transform:uppercase;">
                    Plateforme ERP
                </span>
            </div>

            <h2 class="anim-3"
                style="color:white; font-size:38px; font-weight:900; line-height:1.1; margin:18px 0 16px; letter-spacing:-0.03em;">
                Gérez votre<br>
                <span style="background: linear-gradient(90deg, #6e88ff, #c5b8ff); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
                    centre
                </span>
                en toute<br>
                <span style="background: linear-gradient(90deg, #ff8a8a, #ffb3b3); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
                    sérénité.
                </span>
            </h2>

            <p class="anim-4"
               style="color:rgba(200,205,240,0.6); font-size:14px; font-weight:500; line-height:1.7; max-width:340px;">
                Scolarité, présences, paiements, réclamations — tout centralisé dans un seul espace sécurisé.
            </p>
        </div>

        {{-- Stats cards --}}
        <div class="relative z-10 anim-5">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                <div class="stat-card">
                    <p style="font-size:22px; font-weight:900; color:white; margin:0; line-height:1;">360°</p>
                    <p style="font-size:11px; color:rgba(200,205,240,0.5); font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.08em;">Vision</p>
                </div>

                <div class="stat-card">
                    <p style="font-size:22px; font-weight:900; color:white; margin:0; line-height:1;">100%</p>
                    <p style="font-size:11px; color:rgba(200,205,240,0.5); font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.08em;">Sécurisé</p>
                </div>

                <div class="stat-card" style="grid-column:span 2;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:8px; height:8px; border-radius:50%; background:#4ade80; box-shadow:0 0 8px rgba(74,222,128,0.7); flex-shrink:0;"></div>
                        <p style="font-size:12.5px; color:rgba(200,205,240,0.7); font-weight:600; margin:0;">
                            Système opérationnel — Tous les modules actifs
                        </p>
                    </div>
                </div>
            </div>

            <p style="font-size:10.5px; color:rgba(255,255,255,0.18); font-weight:600; margin-top:24px; text-transform:uppercase; letter-spacing:0.12em;">
                &copy; {{ date('Y') }} CFPL TARA — ERP Linguistique
            </p>
        </div>
    </div>

    {{-- ══════════ PANNEAU DROIT — Clair ══════════ --}}
    <div class="panel-right flex-1 flex flex-col items-center justify-center px-6 py-12 overflow-y-auto">

        {{-- Logo mobile only --}}
        <div class="lg:hidden mb-8 flex flex-col items-center anim-1">
            <div style="background:white; padding:10px 14px; border-radius:16px; box-shadow:0 6px 24px rgba(15,14,61,0.15); margin-bottom:12px;">
                <img src="{{ asset('images/logo_tara.png') }}" alt="CFPL TARA" style="height:44px; width:auto;">
            </div>
            <div style="display:flex; gap:3px; align-items:center;">
                <div style="width:20px; height:2.5px; border-radius:2px; background:#1D1B84;"></div>
                <div style="width:5px; height:2.5px; border-radius:2px; background:rgba(15,14,61,0.15);"></div>
                <div style="width:20px; height:2.5px; border-radius:2px; background:#E31E24;"></div>
            </div>
        </div>

        <div class="w-full max-w-sm relative z-10">

            {{-- Titre --}}
            <div class="anim-2 mb-8">
                <h1 style="font-size:26px; font-weight:900; color:#0f172a; margin:0 0 6px; letter-spacing:-0.03em;">
                    Bon retour 👋
                </h1>
                <p style="font-size:13.5px; color:#64748b; font-weight:500; margin:0;">
                    Connectez-vous à votre espace de gestion
                </p>
            </div>

            {{-- Flash status --}}
            @if (session('status'))
                <div class="anim-2 mb-4"
                     style="background:rgba(74,222,128,0.1); border:1px solid rgba(74,222,128,0.3); border-radius:10px; padding:10px 14px; font-size:13px; color:#16a34a; font-weight:600;">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Erreurs de validation --}}
            @if ($errors->any())
                <div class="anim-2 mb-4"
                     style="background:rgba(227,30,36,0.07); border:1px solid rgba(227,30,36,0.2); border-radius:10px; padding:10px 14px; font-size:13px; color:#E31E24; font-weight:600;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- ── CARTE FORMULAIRE ── --}}
            <div class="form-card rounded-2xl p-8 anim-3">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div style="margin-bottom:18px;">
                        <label class="form-label" for="email">Adresse email</label>
                        <div style="position:relative;">
                            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none;"
                                 width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="vous@cfpltara.com"
                                   required autocomplete="email"
                                   class="form-input"
                                   style="padding-left: 38px;">
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div style="margin-bottom:20px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label class="form-label" for="password" style="margin-bottom:0;">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   style="font-size:11.5px; font-weight:700; color:#1D1B84; text-decoration:none; transition:color 0.15s;"
                                   onmouseenter="this.style.color='#E31E24'"
                                   onmouseleave="this.style.color='#1D1B84'">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div style="position:relative;">
                            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none;"
                                 width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input id="password" type="password" name="password"
                                   placeholder="••••••••"
                                   required autocomplete="current-password"
                                   class="form-input"
                                   style="padding-left:38px;">
                        </div>
                    </div>

                    {{-- Se souvenir --}}
                    <div style="display:flex; align-items:center; gap:9px; margin-bottom:24px;">
                        <input type="checkbox" id="remember_me" name="remember"
                               class="custom-checkbox">
                        <label for="remember_me"
                               style="font-size:12.5px; font-weight:600; color:#475569; cursor:pointer; user-select:none;">
                            Rester connecté
                        </label>
                    </div>

                    {{-- Bouton --}}
                    <button type="submit" class="btn-connect">
                        <span style="display:flex; align-items:center; justify-content:center; gap:8px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Se connecter
                        </span>
                    </button>
                </form>
            </div>

            {{-- Bas de page --}}
            <div class="anim-4" style="text-align:center; margin-top:28px;">
                <p style="font-size:11px; font-weight:600; color:rgba(15,14,61,0.28); text-transform:uppercase; letter-spacing:0.14em;">
                    &copy; {{ date('Y') }} CFPL TARA · ERP 360°
                </p>
                <div style="display:flex; justify-content:center; gap:4px; margin-top:10px; align-items:center;">
                    <div style="width:18px; height:2px; border-radius:2px; background:#1D1B84; opacity:0.4;"></div>
                    <div style="width:5px; height:2px; border-radius:2px; background:rgba(15,14,61,0.1);"></div>
                    <div style="width:18px; height:2px; border-radius:2px; background:#E31E24; opacity:0.4;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>