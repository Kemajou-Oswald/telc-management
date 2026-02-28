<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CFPL TARA | ERP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { blue: '#1D1B84', red: '#E31E24', dark: '#0F0E3D' },
                        brandBlue: '#1e40af',
                        brandRed:  '#dc2626',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { letter-spacing: -0.01em; }

        /* ─── Scrollbar sidebar ─── */
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* ─── Sidebar ─── */
        .sidebar {
            /* Fond bleu nuit profond */
            background: #0d0c2b;
            position: relative;
            overflow: hidden;
        }
        /* Lueur bleue en haut */
        .sidebar::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(29,27,132,0.45) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        /* Lueur rouge en bas */
        .sidebar::after {
            content: '';
            position: absolute;
            bottom: -50px; right: -50px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(227,30,36,0.22) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .sidebar > * { position: relative; z-index: 1; }

        /* ─── Nav items ─── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            /* Blanc lisible sur fond sombre */
            color: rgba(200, 205, 240, 0.72);
            text-decoration: none;
            transition: all 0.18s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-left: 2.5px solid transparent;
            position: relative;
            margin-bottom: 1px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: #ffffff;
            border-left-color: rgba(255,255,255,0.25);
            transform: translateX(3px);
        }
        .nav-item-active {
            background: linear-gradient(90deg, rgba(227,30,36,0.22) 0%, rgba(227,30,36,0.06) 100%);
            color: #ffffff !important;
            border-left-color: #E31E24 !important;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(227,30,36,0.2), 0 2px 16px rgba(227,30,36,0.12);
            transform: none !important;
        }
        /* Point lumineux élément actif */
        .nav-item-active::after {
            content: '';
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #E31E24;
            box-shadow: 0 0 0 3px rgba(227,30,36,0.2), 0 0 10px rgba(227,30,36,0.7);
        }

        /* ─── Section labels ─── */
        .nav-section {
            font-size: 9.5px;
            font-weight: 800;
            color: rgba(255,255,255,0.22);
            text-transform: uppercase;
            letter-spacing: 0.18em;
            padding: 20px 12px 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-section::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        /* ─── Logo hover ─── */
        .logo-wrap {
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
        }
        .logo-wrap:hover {
            transform: scale(1.07) rotate(-1.5deg);
            box-shadow: 0 16px 44px rgba(13,12,43,0.55), 0 0 0 3px rgba(227,30,36,0.25) !important;
        }

        /* ─── Header glassmorphism ─── */
        .glass-header {
            background: rgba(255,255,255,0.78) !important;
            backdrop-filter: blur(22px) saturate(180%);
            -webkit-backdrop-filter: blur(22px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.65) !important;
            box-shadow: 0 1px 0 rgba(15,14,61,0.05), 0 4px 30px rgba(15,14,61,0.05);
        }

        /* ─── Search ─── */
        .search-input {
            background: rgba(15,14,61,0.04);
            border: 1.5px solid rgba(15,14,61,0.09);
            transition: all 0.2s ease;
        }
        .search-input:focus {
            background: white;
            border-color: #1D1B84;
            box-shadow: 0 0 0 3px rgba(29,27,132,0.09);
            outline: none;
        }

        /* ─── Avatar ─── */
        .avatar-gradient {
            background: linear-gradient(135deg, #E31E24 0%, #1D1B84 100%);
        }

        /* ─── Page animation ─── */
        @keyframes pageIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .page-enter { animation: pageIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) both; }
    </style>
</head>

<body class="antialiased text-slate-900 h-full" x-data="{ sidebarOpen: false }"
      style="background: linear-gradient(135deg, #eef0f8 0%, #f4f5fb 55%, #fdf0f0 100%); background-attachment: fixed;">

<div class="flex h-full overflow-hidden">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="sidebar fixed inset-y-0 left-0 z-50 w-64 flex flex-col
               lg:translate-x-0 lg:static lg:inset-0
               transition-transform duration-300 ease-in-out"
        style="box-shadow: 4px 0 48px rgba(13,12,43,0.45), 1px 0 0 rgba(255,255,255,0.04);">

        {{-- ── Logo ── --}}
        <div class="flex flex-col items-center px-5 pt-7 pb-6"
             style="border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.18);">

            {{-- Badge ERP --}}
            <div style="width:100%; display:flex; justify-content:flex-end; margin-bottom:10px;">
                <span style="font-size:8px; font-weight:900; color:#fc8a8d; background:rgba(227,30,36,0.15); border:1px solid rgba(227,30,36,0.35); padding:3px 9px; border-radius:99px; letter-spacing:0.14em; text-transform:uppercase; box-shadow:0 0 14px rgba(227,30,36,0.18);">
                    ERP
                </span>
            </div>

            {{-- Logo --}}
            <div class="logo-wrap"
                 style="background:white; padding:11px 15px; border-radius:18px; margin-bottom:14px;
                        box-shadow: 0 8px 32px rgba(13,12,43,0.6), 0 2px 8px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.08);">
                <img src="{{ asset('images/logo_tara.png') }}" alt="Logo CFPL TARA"
                     style="width:50px; height:auto; display:block;">
            </div>

            <h2 style="color:#ffffff; font-size:15px; font-weight:900; letter-spacing:0.06em; margin:0; text-transform:uppercase; text-shadow:0 2px 12px rgba(0,0,0,0.4);">
                CFPL TARA
            </h2>

            <p style="font-size:9px; font-weight:600; color:rgba(255,255,255,0.28); margin:4px 0 0; letter-spacing:0.1em; text-transform:uppercase;">
                Système de Gestion Intégré
            </p>

            {{-- Barre déco bleu · rouge --}}
            <div style="margin-top:14px; display:flex; gap:3px; align-items:center;">
                <div style="width:26px; height:2.5px; border-radius:2px; background:#1D1B84; box-shadow:0 0 8px rgba(29,27,132,0.6);"></div>
                <div style="width:5px; height:2.5px; border-radius:2px; background:rgba(255,255,255,0.12);"></div>
                <div style="width:26px; height:2.5px; border-radius:2px; background:#E31E24; box-shadow:0 0 8px rgba(227,30,36,0.6);"></div>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <nav class="sidebar-nav flex-1 overflow-y-auto py-2 px-3">
            @include('layouts.sidebar-navigation')
        </nav>

        {{-- ── Profil ── --}}
        <div class="px-3 pb-4 pt-3"
             style="border-top: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.18);">
            <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08); border-radius:13px; padding:11px 13px; display:flex; align-items:center; gap:11px; transition: all 0.2s; cursor:default;"
                 onmouseenter="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.14)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.25)'"
                 onmouseleave="this.style.background='rgba(255,255,255,0.06)'; this.style.borderColor='rgba(255,255,255,0.08)'; this.style.boxShadow='none'">

                <div class="avatar-gradient"
                     style="width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:15px; color:white; flex-shrink:0; box-shadow:0 4px 16px rgba(227,30,36,0.35);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:800; color:#ffffff; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; line-height:1.3;">
                        {{ Auth::user()->name }}
                    </p>
                    <p style="font-size:9.5px; color:rgba(255,255,255,0.3); font-weight:700; margin:2px 0 6px; text-transform:uppercase; letter-spacing:0.07em;">
                        {{ Auth::user()->role ?? 'Directeur Centre' }}
                    </p>
                    <div style="display:flex; gap:10px; font-size:11px; font-weight:700; align-items:center;">
                        <a href="{{ route('profile.edit') }}"
                           style="color:rgba(147,197,253,0.85); text-decoration:none; transition:color 0.15s;"
                           onmouseenter="this.style.color='#93c5fd'"
                           onmouseleave="this.style.color='rgba(147,197,253,0.85)'">
                            Mon profil
                        </a>
                        <span style="color:rgba(255,255,255,0.14); font-size:14px; line-height:1;">·</span>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit"
                                    style="color:rgba(252,165,165,0.8); background:none; border:none; padding:0; font-weight:700; cursor:pointer; font-size:11px; font-family:inherit; transition:color 0.15s;"
                                    onmouseenter="this.style.color='#fca5a5'"
                                    onmouseleave="this.style.color='rgba(252,165,165,0.8)'">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══════════════ ZONE CONTENU ═══════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden" style="background:transparent;">

        {{-- ── HEADER glassmorphism ── --}}
        <header class="glass-header"
                style="height:64px; display:flex; align-items:center; justify-content:space-between; padding:0 1.75rem; position:sticky; top:0; z-index:30; flex-shrink:0;">

            <div style="display:flex; align-items:center; gap:14px;">

                {{-- Burger mobile --}}
                <button @click="sidebarOpen = true" class="lg:hidden"
                        style="width:37px; height:37px; background:white; border:1.5px solid rgba(15,14,61,0.1); border-radius:9px; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 1px 4px rgba(0,0,0,0.06); transition:all 0.15s;"
                        onmouseenter="this.style.boxShadow='0 3px 12px rgba(15,14,61,0.12)'"
                        onmouseleave="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.06)'">
                    <svg width="16" height="16" fill="none" stroke="#334155" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>

                {{-- Séparateur vertical dégradé --}}
                <div class="hidden lg:block"
                     style="width:1px; height:28px; background:linear-gradient(180deg, transparent, rgba(15,14,61,0.1), transparent);"></div>

                {{-- Titre de page --}}
                <div>
                    <p style="font-size:9px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.2em; margin:0 0 2px;">
                        Espace Gestion
                    </p>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em; line-height:1;">
                            Dashboard
                        </h1>
                    @endisset
                </div>
            </div>

            {{-- Droite --}}
            <div style="display:flex; align-items:center; gap:8px;">

                {{-- Barre de recherche --}}
                <div class="hidden md:flex" style="position:relative;">
                    <svg style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none;"
                         width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Rechercher..." class="search-input"
                           style="padding:8px 14px 8px 34px; border-radius:10px; font-size:12px; width:210px; font-family:inherit; color:#0f172a;">
                </div>

                {{-- Cloche notifications --}}
                <button style="width:38px; height:38px; background:white; border:1.5px solid rgba(15,14,61,0.09); border-radius:10px; display:flex; align-items:center; justify-content:center; cursor:pointer; position:relative; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.15s;"
                        onmouseenter="this.style.boxShadow='0 3px 14px rgba(15,14,61,0.1)'; this.style.borderColor='rgba(29,27,132,0.2)'"
                        onmouseleave="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'; this.style.borderColor='rgba(15,14,61,0.09)'">
                    <svg width="16" height="16" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span style="position:absolute; top:8px; right:8px; width:7px; height:7px; background:#E31E24; border-radius:50%; border:1.5px solid white; box-shadow:0 0 7px rgba(227,30,36,0.55);"></span>
                </button>

                @include('layouts.navigation-dropdown')
            </div>
        </header>

        {{-- ── MAIN ── --}}
        <main class="flex-1 overflow-y-auto" style="padding:24px 1.75rem 40px;">
            <div class="page-enter" style="max-width:1280px; margin:0 auto;">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

{{-- ── OVERLAY MOBILE ── --}}
<div x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 lg:hidden"
     style="background:rgba(13,12,43,0.6); backdrop-filter:blur(3px);"></div>

</body>
</html>