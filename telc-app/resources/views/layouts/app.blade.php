<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Preinscription TELC' }} - Tara Institut et Der Beste Betreuer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:         #1A1A1A;
            --navy-mid:     #2A2A2A;
            --navy-soft:    #F2F2F2;
            --red:          #D94B43;
            --red-soft:     #FEF2F2;
            --white:        #FFFFFF;
            --bg:           #F7F7F7;
            --border:       #E1E1E1;
            --text:         #1A1A1A;
            --muted:        #7A7A7A;
            --success:      #15803D;
            --success-bg:   #F0FDF4;
            --success-bdr:  #BBF7D0;
            --warning:      #B45309;
            --warning-bg:   #FFFBEB;
            --warning-bdr:  #FDE68A;
            --danger:       #991B1B;
            --danger-bg:    #FEF2F2;
            --danger-bdr:   #FECACA;
            --sidebar-w:    224px;
            --topbar-h:     58px;
            --radius:       12px;
            --radius-lg:    16px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
            color: var(--text);
            background: linear-gradient(180deg, #f8faff 0%, #eef3fb 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }

        /* ══════════════════════════════════════
           APP SHELL
        ══════════════════════════════════════ */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 300;
            overflow: hidden;
            transition: transform 220ms ease;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: -130px; right: -130px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(90,90,90,0.22) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Logo */
        .sidebar-logo {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .sidebar-logo-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .sidebar-logo-img {
            width: 30px; height: 30px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            flex-shrink: 0;
        }
        .sidebar-logo-name {
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 700;
            color: #fff; line-height: 1; letter-spacing: 0.03em;
        }
        .sidebar-logo-sub {
            font-size: 9px; color: rgba(255,255,255,0.32);
            text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
            position: relative; z-index: 1;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-group { margin-bottom: 4px; }
        .nav-group-label {
            font-size: 9px; font-weight: 600;
            letter-spacing: 0.13em; text-transform: uppercase;
            color: rgba(255,255,255,0.28);
            padding: 10px 10px 6px;
            display: block;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 8px;
            color: rgba(255,255,255,0.52);
            font-size: 12.5px;
            font-weight: 400;
            transition: background 130ms, color 130ms;
            margin-bottom: 1px;
            position: relative;
        }
        .nav-link svg { flex-shrink: 0; color: rgba(255,255,255,0.38); transition: color 130ms; }
        .nav-link:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.82); }
        .nav-link:hover svg { color: rgba(255,255,255,0.7); }
        .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; font-weight: 500; }
        .nav-link.active svg { color: #fff; }

        .nav-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff; font-size: 9px; font-weight: 700;
            padding: 2px 6px; border-radius: 100px;
            letter-spacing: 0.04em;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
            position: relative; z-index: 1;
            flex-shrink: 0;
        }
        .user-card {
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 8px;
        }
        .user-card-top {
            display: flex; align-items: center; gap: 9px; margin-bottom: 4px;
        }
        .user-avatar {
            width: 26px; height: 26px; border-radius: 6px;
            background: rgba(196,27,27,0.4);
            display: grid; place-items: center;
            font-size: 10px; font-weight: 700; color: #fff;
            flex-shrink: 0;
            text-transform: uppercase;
            overflow: hidden;
        }
        .user-avatar.has-image { background: transparent; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .user-name { font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.8); }
        .user-meta {
            font-size: 10.5px;
            color: rgba(255,255,255,0.32);
            line-height: 1.5;
            padding-left: 35px;
            /* Empêche l'affichage sur plusieurs lignes */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-actions { display: flex; flex-direction: column; gap: 8px; }

        .logout-btn {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 8px 12px; border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
            background: transparent;
            color: rgba(255,255,255,0.45);
            font-size: 12px;
            cursor: pointer;
            transition: background 130ms, color 130ms;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); }
        .logout-btn.is-active { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.18); }
        .logout-btn svg { flex-shrink: 0; }

        /* ══════════════════════════════════════
           MAIN + TOPBAR
        ══════════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        .topbar {
            background: rgba(255,255,255,0.88);
            border-bottom: 1px solid rgba(216,227,244,0.9);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            height: var(--topbar-h);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(15,23,42,0.05);
        }

        .topbar-hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(216,227,244,0.9);
            border-radius: 9px;
            background: #fff;
            color: var(--navy);
            cursor: pointer;
            flex-shrink: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }
        .topbar-breadcrumb-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }
        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .topbar-breadcrumb span { color: var(--navy); font-weight: 600; }
        .topbar-breadcrumb-sep { color: #cdd5e0; font-size: 13px; }
        .topbar-scope {
            font-size: 11px;
            color: #a0abc0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .topbar-userlink {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid rgba(154,171,208,0.55);
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(244,247,255,0.96));
            color: var(--navy);
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(15,23,42,0.06);
            transition: transform 130ms, background 130ms, border-color 130ms, box-shadow 130ms;
            white-space: nowrap;
        }
        .topbar-userlink:hover {
            background: linear-gradient(180deg, #ffffff, #eef4ff);
            border-color: #bfd0ef;
            box-shadow: 0 6px 16px rgba(31,75,143,0.10);
            transform: translateY(-1px);
        }
        .topbar-useravatar {
            width: 24px; height: 24px; border-radius: 999px;
            display: grid; place-items: center;
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            overflow: hidden;
            flex-shrink: 0;
        }
        .topbar-useravatar.has-image { background: transparent; }
        .topbar-useravatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .topbar-logout-form { margin: 0; }

        /* ══════════════════════════════════════
           OVERLAY MOBILE
        ══════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 299;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* GLOBAL LOADER */
        .global-loader {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(15,23,42,0.35);
            z-index: 500;
            backdrop-filter: blur(2px);
        }
        .global-loader.active { display: flex; }
        .global-loader__card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 14px;
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(226,232,240,0.9);
            box-shadow: 0 16px 30px rgba(15,23,42,0.12);
        }
        .global-loader__spinner {
            width: 20px; height: 20px;
            border-radius: 999px;
            border: 2px solid rgba(100,116,139,0.25);
            border-top-color: rgba(30,41,59,0.9);
            animation: global-spin 0.9s linear infinite;
        }
        .global-loader__text {
            font-size: 12.5px;
            font-weight: 600;
            color: #1f2937;
            letter-spacing: 0.01em;
        }
        @keyframes global-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ══════════════════════════════════════
           PAGE
        ══════════════════════════════════════ */
        .page {
            padding: 24px 28px;
            flex: 1;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
            padding: 22px 24px;
            border-radius: 20px;
            border: 1px solid rgba(214,224,243,0.95);
            background: rgba(255,255,255,0.92);
            box-shadow: 0 18px 38px rgba(15,23,42,0.06);
        }
        .page-header-left { min-width: 0; }
        .page-kicker {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.72);
            border: 1px solid rgba(214,224,243,0.95);
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.14em; text-transform: uppercase;
            color: #7380a1; margin-bottom: 8px;
        }
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 22px; font-weight: 700; color: var(--navy);
            line-height: 1.15;
        }
        .page-subtitle {
            font-size: 12.75px; color: #72809f;
            margin-top: 6px; line-height: 1.65;
            max-width: 760px;
        }
        .page-actions {
            display: flex; align-items: center; gap: 8px;
            flex-shrink: 0; flex-wrap: wrap;
        }

        /* ══════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0 15px; height: 36px; border-radius: 10px;
            font-size: 12.5px; font-weight: 600;
            cursor: pointer; border: none;
            transition: transform 120ms, box-shadow 120ms, background 120ms, border-color 120ms, color 120ms;
            white-space: nowrap;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(15,23,42,0.05);
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { flex-shrink: 0; }

        .btn-primary {
            background: linear-gradient(135deg, #d94b43, #bf2f39);
            color: #fff;
            box-shadow: 0 12px 24px rgba(191,47,57,0.24);
        }
        .btn-primary:hover { background: linear-gradient(135deg, #cf403a, #a91d2a); }

        .btn-secondary {
            background: linear-gradient(180deg, #ffffff, #f4f7ff); color: var(--navy);
            border: 1px solid rgba(195,208,236,0.95);
        }
        .btn-secondary:hover { background: linear-gradient(180deg, #ffffff, #eaf1ff); border-color: #bfd0ef; }

        .btn-ghost {
            background: rgba(255,255,255,0.7); color: var(--muted);
            border: 1px solid rgba(216,227,244,0.9);
            box-shadow: none;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.96); color: var(--navy); border-color: #cad7ee; }

        .btn-danger { background: linear-gradient(180deg, #fff5f5, #fee2e2); color: var(--danger); border: 1px solid var(--danger-bdr); box-shadow: none; }
        .btn-danger:hover { background: linear-gradient(180deg, #fff1f1, #fecaca); }

        .btn-sm { height: 29px; padding: 0 11px; font-size: 11.5px; border-radius: 7px; }
        .btn-lg { height: 42px; padding: 0 20px; font-size: 14px; border-radius: 12px; }

        /* ══════════════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════════════ */
        .flash {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-radius: 12px;
            font-size: 14px; font-weight: 500;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .flash svg { flex-shrink: 0; }
        .flash.success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            color: #166534;
        }
        .flash.error   {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fca5a5;
            color: #dc2626;
        }
        .flash.warning {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 1px solid #fcd34d;
            color: #d97706;
        }

        /* ══════════════════════════════════════
           LAYOUT HELPERS
        ══════════════════════════════════════ */
        .split        { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        .split.thirds { grid-template-columns: 1fr 1fr 1fr; }
        .split.wide   { grid-template-columns: 1.6fr 1fr; }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        /* KPI */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 16px; }
        .kpi-grid.cols-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }
        .kpi-card {
            display: flex; flex-direction: column; gap: 12px;
            min-height: 142px; padding: 18px 18px 16px;
            border-radius: 20px;
            border: 1px solid rgba(216,227,244,0.95);
            background: rgba(255,255,255,0.98);
            box-shadow: 0 14px 28px rgba(15,23,42,0.05);
        }
        .kpi-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .kpi-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #7a86a2; }
        .kpi-icon { width: 40px; height: 40px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-icon.navy  { color: #1f4b8f; background: rgba(31,75,143,0.10); }
        .kpi-icon.green { color: #15803d; background: rgba(21,128,61,0.10); }
        .kpi-icon.amber { color: #b45309; background: rgba(180,83,9,0.10); }
        .kpi-value { font-family: 'Syne', sans-serif; font-size: 32px; line-height: 1; font-weight: 700; color: var(--navy); letter-spacing: -0.03em; }
        .kpi-value.sm { font-size: 28px; }
        .kpi-footer { margin-top: auto; padding-top: 10px; border-top: 1px solid rgba(216,227,244,0.82); }
        .kpi-footer-text { font-size: 12.5px; color: #6f7d99; }

        /* TABLE CARD */
        .table-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(251,253,255,0.98));
            border: 1px solid rgba(216,227,244,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 36px rgba(15,23,42,0.05);
        }
        .table-card-header {
            padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid rgba(216,227,244,0.9);
            background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(246,249,255,0.92));
        }
        .table-card-title { font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; color: var(--navy); }
        .table-card-action { font-size: 11.5px; color: #72809f; display: inline-flex; align-items: center; gap: 4px; transition: color 120ms; }
        .table-card-action:hover { color: var(--navy); }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            font-size: 10px; font-weight: 700; letter-spacing: 0.09em; text-transform: uppercase;
            color: #7f89a2; padding: 11px 20px;
            text-align: left; border-bottom: 1px solid rgba(216,227,244,0.95);
            background: linear-gradient(180deg, #fbfcff, #f2f6ff); white-space: nowrap;
        }
        tbody tr { transition: background 100ms; }
        tbody tr:hover { background: #f8fbff; }
        tbody td { padding: 12px 20px; font-size: 12.5px; color: var(--text); border-bottom: 1px solid #edf2fb; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        .td-muted { color: #79859f; font-size: 12px; }
        .td-mono  { font-variant-numeric: tabular-nums; }

        /* TOOLBAR */
        .toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
        .toolbar-left  { display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; }
        .toolbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        .search-wrap { position: relative; flex: 1; max-width: 320px; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #b8c2da; pointer-events: none; display: flex; }
        .search-input {
            width: 100%; height: 38px; padding: 0 12px 0 36px;
            border: 1.5px solid rgba(216,227,244,0.95); border-radius: 11px;
            font-size: 12.5px; background: linear-gradient(180deg, #ffffff, #f8faff); color: var(--text); outline: none;
            transition: border-color 130ms, box-shadow 130ms, background 130ms;
        }
        .search-input:focus { border-color: #6b8fd6; background: #ffffff; box-shadow: 0 0 0 4px rgba(59,92,165,0.08); }
        .search-input::placeholder { color: #b5bfd5; }

        /* BADGES */
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 10px; border-radius: 999px;
            font-size: 10.5px; font-weight: 700; white-space: nowrap;
            border: 1px solid transparent;
        }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .badge.success { background: linear-gradient(180deg, #f3fff7, #e8faef); color: var(--success); border-color: #cdeed8; }
        .badge.success::before { background: var(--success); }
        .badge.warning { background: linear-gradient(180deg, #fffdf3, #fff3cd); color: var(--warning); border-color: #f5dd98; }
        .badge.warning::before { background: var(--warning); }
        .badge.danger  { background: linear-gradient(180deg, #fff5f5, #fee2e2); color: var(--danger); border-color: #f5c2c7; }
        .badge.danger::before  { background: var(--danger); }
        .badge.neutral { background: linear-gradient(180deg, #f7f9ff, #ebf1ff); color: #667085; border-color: #dbe5f8; }
        .badge.neutral::before { background: #7b849e; }

        /* ALERT CARD */
        .alert-card {
            display: flex; align-items: center; gap: 13px;
            padding: 14px 18px; border-radius: var(--radius-lg);
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,252,255,0.98));
            border: 1px solid var(--border);
            margin-bottom: 16px;
            box-shadow: 0 14px 28px rgba(15,23,42,0.05);
        }
        .alert-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; flex-shrink: 0; }
        .alert-icon.amber { background: var(--warning-bg); color: var(--warning); }
        .alert-icon.green { background: var(--success-bg); color: var(--success); }
        .alert-icon.red   { background: var(--red-soft);   color: var(--red); }
        .alert-body { flex: 1; min-width: 0; }

        /* FORM INPUTS */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%; padding: 10px 12px;
            border: 1.5px solid rgba(216,227,244,0.95);
            border-radius: 11px; font: inherit; font-size: 13.5px;
            color: var(--text); background: linear-gradient(180deg, #ffffff, #f8faff);
            outline: none; transition: border-color 130ms, box-shadow 130ms, background 130ms;
        }
        input::placeholder, textarea::placeholder { color: #b8c2da; }
        input:focus, select:focus, textarea:focus {
            border-color: #6b8fd6; background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59,92,165,0.08);
        }
        textarea { min-height: 90px; resize: vertical; }
        .field-hint  { font-size: 11px; color: var(--muted); }
        .field-error { font-size: 11px; color: var(--danger); }
        .review-select   { height: 32px; padding: 0 10px; font-size: 12px; }
        .review-textarea { min-height: 64px; font-size: 12px; }

        /* PROGRESS BAR */
        .progress-row  { display: flex; align-items: center; gap: 8px; }
        .progress-wrap { flex: 1; height: 5px; background: var(--border); border-radius: 100px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 100px; background: var(--navy); }
        .progress-pct  { font-size: 10.5px; color: var(--muted); width: 30px; text-align: right; flex-shrink: 0; }

        /* META BOX */
        .meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 10px; margin-bottom: 16px; }
        .meta-box { background: linear-gradient(180deg, #f8faff 0%, #eef3fb 100%); border: 1px solid var(--border); border-radius: var(--radius); padding: 12px 14px; }
        .meta-label { font-size: 10px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 4px; }
        .meta-value { font-size: 14px; color: var(--navy); font-weight: 500; }

        /* PAGINATION */
        .pagination { padding: 16px 20px; border-top: 1px solid var(--border); }
        .pagination-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .page-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .page-link {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 12px;
            border-radius: 9px; border: 1px solid var(--border);
            color: var(--muted); background: var(--white);
            font-size: 12px; font-weight: 500; text-decoration: none; transition: all 120ms;
        }
        .page-link:hover { color: var(--navy); border-color: #c8cfe8; background: var(--navy-soft); }
        .page-link.active { background: var(--navy); border-color: var(--navy); color: #fff; }
        .page-link.is-disabled { opacity: 0.45; cursor: default; pointer-events: none; }
        .page-info { font-size: 12px; color: var(--muted); }

        /* EMPTY STATE */
        .empty-state { padding: 48px 20px; display: flex; flex-direction: column; align-items: center; gap: 10px; text-align: center; }
        .empty-state-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--navy-soft); color: var(--muted); display: grid; place-items: center; margin-bottom: 4px; }
        .empty-state-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: var(--navy); }
        .empty-state-desc  { font-size: 12.5px; color: var(--muted); max-width: 280px; line-height: 1.6; }

        /* SHELL HELPERS */
        .page-shell  { width: min(1280px,100%); margin: 0 auto; }
        .form-shell  { width: min(920px,100%);  margin: 0 auto; }
        .form-shell.wide { width: min(1180px,100%); }
        .table-wrap  { width: 100%; overflow-x: auto; overflow-y: visible; }
        .table-wrap table { min-width: 860px; }

        .toolbar-grid {
            display: grid;
            grid-template-columns: minmax(260px,1.4fr) repeat(4, minmax(150px,1fr));
            gap: 10px;
            align-items: center;
        }
        .toolbar-grid .search-wrap { max-width: none; }
        .toolbar-actions { display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
        .stack-grid { display: grid; gap: 16px; }
        .card-section { padding: 20px 24px 24px; }

        .surface-muted {
            background: linear-gradient(135deg, #fbfcff 0%, #eef4ff 60%, #fff7df 100%);
            border: 1px solid rgba(216,227,244,0.95);
            border-radius: 16px; padding: 18px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
        }

        .page-intro {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 16px; margin-bottom: 16px; padding: 20px 22px;
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(216,227,244,0.95);
            border-radius: 20px;
            box-shadow: 0 18px 34px rgba(15,23,42,0.05);
        }
        .page-intro-copy  { max-width: 720px; }
        .page-intro-title { font-family: 'Syne', sans-serif; font-size: 17px; color: var(--navy); margin-bottom: 6px; }
        .page-intro-text  { color: #75819d; font-size: 12.75px; line-height: 1.65; }

        /* THEMES */
        .theme-page {
            --theme-start: rgba(26,26,26,0.10); --theme-end: rgba(26,26,26,0.06);
            --theme-soft: rgba(248,248,248,0.92); --theme-border: rgba(210,210,210,0.78);
            --theme-accent: #1A1A1A; --theme-ink: var(--navy);
        }
        .theme-coral {
            --theme-start: rgba(217,75,67,0.14); --theme-end: rgba(26,26,26,0.06);
            --theme-soft: rgba(255,246,247,0.95); --theme-border: rgba(229,201,201,0.76);
            --theme-accent: #d94b43; --theme-ink: #8f2d2d;
        }
        .theme-amber {
            --theme-start: rgba(26,26,26,0.08); --theme-end: rgba(217,75,67,0.12);
            --theme-soft: rgba(255,247,247,0.95); --theme-border: rgba(229,201,201,0.76);
            --theme-accent: #d94b43; --theme-ink: #8f2d2d;
        }
        .theme-emerald { --theme-accent: #1A1A1A; --theme-ink: #1A1A1A; }
        .theme-ink { --theme-accent: #1A1A1A; --theme-ink: #1A1A1A; }

        .theme-page .page-intro { position: relative; border-color: var(--theme-border); }
        .theme-page .page-intro::before {
            content: ""; position: absolute; top: 0; left: 22px; right: 22px;
            height: 4px; border-radius: 999px; background: var(--theme-accent);
        }
        .theme-page .table-card, .theme-page .data-panel, .theme-page .meta-box, .theme-page .kpi-card {
            border-color: var(--theme-border); background: rgba(255,255,255,0.98);
            box-shadow: 0 12px 28px rgba(15,23,42,0.04);
        }
        .theme-page .table-card-header, .theme-page .data-panel .table-card-header {
            border-bottom-color: var(--theme-border); background: rgba(255,255,255,0.96);
        }
        .theme-page .table-card-title, .theme-page .page-intro-title, .theme-page .meta-value { color: var(--theme-ink); }
        .theme-page .surface-muted { background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(249,251,255,0.94)); border-color: var(--theme-border); }
        .theme-page .field input, .theme-page .field select, .theme-page .field textarea,
        .theme-page .search-input, .theme-page .review-select, .theme-page .review-textarea { border-color: var(--theme-border); background: rgba(255,255,255,0.88); }
        .theme-page .field input:focus, .theme-page .field select:focus, .theme-page .field textarea:focus,
        .theme-page .search-input:focus, .theme-page .review-select:focus, .theme-page .review-textarea:focus {
            border-color: var(--theme-ink); box-shadow: 0 0 0 4px rgba(255,255,255,0.55);
        }
        .theme-page .empty-state { background: rgba(255,255,255,0.92); border-radius: 16px; }

        /* DATA PANEL */
        .data-panel {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,252,255,0.98));
            border: 1px solid rgba(216,227,244,0.95);
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 16px 32px rgba(15,23,42,0.05);
        }

        .actions-inline { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .table-actions  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar-hamburger { display: flex; }
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .toolbar-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .table-wrap table { min-width: 760px; }
            .topbar-logout-form { display: none; }
        }

        @media (max-width: 640px) {
            .page { padding: 12px; }
            .topbar { padding: 0 14px; height: 52px; }
            .topbar-userlink .topbar-username { display: none; }
            .kpi-grid { grid-template-columns: 1fr 1fr; }
            .split, .toolbar-grid { grid-template-columns: 1fr; }
            .page-shell, .form-shell, .form-shell.wide { width: 100%; }
            .page-header { flex-direction: column; }
            .page-actions { width: 100%; }
            .page-intro { flex-direction: column; }
            .toolbar-actions { justify-content: stretch; }
            .toolbar-actions .btn { flex: 1; justify-content: center; }
            .table-wrap table { min-width: 600px; }
        }

        @media (max-width: 400px) {
            .kpi-grid { grid-template-columns: 1fr; }
            .page { padding: 8px; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $user = auth()->user();
    $userAvatarUrl = $user && $user->avatar_path ? Storage::url($user->avatar_path) : null;
    $assignedCentersLabel = ($layoutAccessibleCenters ?? collect())->pluck('name')->implode(', ');
    $scopeCenter = $layoutSelectedCenter ?: $user->center;
    $isExportArea = request()->routeIs('registrations.export*');
    $isRegistrationArea = request()->routeIs('registrations.*') && ! $isExportArea;
@endphp

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<div class="global-loader" id="globalLoader" role="status" aria-live="polite" aria-busy="true">
    <div class="global-loader__card">
        <div class="global-loader__spinner"></div>
        <div class="global-loader__text" id="globalLoaderText">Traitement en cours...</div>
    </div>
</div>

<div class="app-shell">

    <!-- ══ SIDEBAR ══ -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-logo">
            <div class="sidebar-logo-badges">
                <img src="{{ asset('images/logo_tara.png') }}" alt="Logo TARA" class="sidebar-logo-img">
                <img src="{{ asset('images/logo_derbeste_betreuer.png') }}" alt="Logo Der Beste Betreuer" class="sidebar-logo-img">
            </div>
            <div>
                <div class="sidebar-logo-name">Tara Institut et Der Beste Betreuer</div>
                <div class="sidebar-logo-sub">Préinscription TELC</div>
            </div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-group">
                <span class="nav-group-label">Principal</span>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Tableau de bord
                </a>
            </div>

            <div class="nav-group">
                <span class="nav-group-label">Operations</span>
                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Etudiants
                </a>
                <a class="nav-link {{ $isRegistrationArea ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Inscriptions
                </a>
                <a class="nav-link {{ request()->routeIs('deposits.*') ? 'active' : '' }}" href="{{ route('deposits.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    Depots bancaires
                    @if (isset($pendingDepositsCount) && $pendingDepositsCount > 0)
                        <span class="nav-badge">{{ $pendingDepositsCount }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-group">
                <span class="nav-group-label">Pilotage</span>
                <a class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Sessions
                </a>
                @if (! $user->isSecretary())
                    <a class="nav-link {{ request()->routeIs('centers.*') ? 'active' : '' }}" href="{{ route('centers.index') }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Centres
                    </a>
                @endif
            </div>

            @if ($user->isSuperAdmin() || $user->isCenterAdmin())
            <div class="nav-group">
                <span class="nav-group-label">Systeme</span>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    Utilisateurs
                </a>
                <a class="nav-link {{ request()->routeIs('activity.*') ? 'active' : '' }}" href="{{ route('activity.index') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Historique
                </a>
                <a class="nav-link {{ $isExportArea ? 'active' : '' }}" href="{{ route('registrations.export') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                    Rapports & Export
                </a>
            </div>
            @endif

        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-card-top">
                    <div class="user-avatar {{ $userAvatarUrl ? 'has-image' : '' }}">
                        @if ($userAvatarUrl)
                            <img src="{{ $userAvatarUrl }}" alt="Photo de profil">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="user-name">{{ $user->name }}</div>
                </div>
                {{-- ✅ CORRIGÉ : Super admin affiche "Tous les centres" au lieu de lister tous les noms --}}
                <div class="user-meta">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    @if ($user->isSuperAdmin())
                        · Tous les centres
                    @elseif ($assignedCentersLabel)
                        · {{ $assignedCentersLabel }}
                    @elseif ($user->center)
                        · {{ $user->center->name }}
                    @endif
                </div>
            </div>
            <div class="sidebar-actions">
                <a href="{{ route('profile.show') }}" class="logout-btn {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Se deconnecter
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- ══ MAIN ══ -->
    <div class="main">

        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Ouvrir le menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="topbar-breadcrumb-wrap">
                    <div class="topbar-breadcrumb">
                        <span style="color:var(--muted);font-weight:400">Préinscription TELC</span>
                        <span class="topbar-breadcrumb-sep">/</span>
                        <span>@yield('page_title', 'Tableau de bord')</span>
                    </div>
                    <div class="topbar-scope">
                        @if ($user->isSuperAdmin())
                            Vue globale — Tous les centres
                        @elseif ($scopeCenter)
                            {{ $scopeCenter->name }}{{ $scopeCenter->city ? ' · ' . $scopeCenter->city : '' }}
                        @elseif (($layoutAccessibleCenters ?? collect())->count() > 1)
                            {{ ($layoutAccessibleCenters ?? collect())->count() }} centres autorisés
                        @endif
                    </div>
                    @if ($user->isSecretary() && ($layoutAccessibleCenters ?? collect())->count() > 1)
                        <div class="center-switcher-wrapper" style="display:flex;align-items:center;gap:8px;margin-top:8px;padding-top:8px;border-top:1px dashed #d1d5db;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            <form method="POST" action="{{ route('center.switch') }}" style="display:flex;flex:1;">
                                @csrf
                                <select name="center_id" onchange="this.form.submit()" style="flex:1;height:32px;padding:0 10px;font-size:12px;border:1px solid #d1d5db;border-radius:8px;background:#fff;color:#374151;cursor:pointer;outline:none;">
                                    @foreach ($layoutAccessibleCenters as $center)
                                        @php $selectedId = $layoutSelectedCenter ? $layoutSelectedCenter->id : $user->center_id; @endphp
                                        <option value="{{ $center->id }}" {{ $selectedId == $center->id ? 'selected' : '' }}>
                                            {{ $center->name }} {{ $center->city ? '- '.$center->city : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('profile.show') }}" class="topbar-userlink {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="topbar-useravatar {{ $userAvatarUrl ? 'has-image' : '' }}">
                        @if ($userAvatarUrl)
                            <img src="{{ $userAvatarUrl }}" alt="Photo de profil">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </span>
                    <span class="topbar-username">{{ $user->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </header>

        <main class="page">

            <div class="page-header">
                <div class="page-header-left">
                    @hasSection('page_kicker')
                        <div class="page-kicker">@yield('page_kicker')</div>
                    @endif
                    <h1 class="page-title">@yield('page_title', 'Tableau de bord')</h1>
                    @hasSection('page_subtitle')
                        <p class="page-subtitle">@yield('page_subtitle')</p>
                    @endif
                </div>
                @hasSection('page_actions')
                    <div class="page-actions">@yield('page_actions')</div>
                @endif
            </div>

            @if (session('success'))
                <div class="flash success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flash error">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="flash error">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')

        </main>
    </div>

</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
    function showGlobalLoader(message) {
        var loader = document.getElementById('globalLoader');
        var text = document.getElementById('globalLoaderText');
        if (!loader) return;
        if (text && message) {
            text.textContent = message;
        }
        loader.classList.add('active');
        document.body.style.cursor = 'wait';
    }
    function hideGlobalLoader() {
        var loader = document.getElementById('globalLoader');
        if (!loader) return;
        loader.classList.remove('active');
        document.body.style.cursor = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('form[data-loading="true"]');
        if (forms.length) {
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    if (form.dataset.loadingActive === '1') return;
                    form.dataset.loadingActive = '1';
                    var message = form.dataset.loadingText || 'Traitement en cours...';
                    showGlobalLoader(message);
                    var buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    buttons.forEach(function(button) {
                        button.disabled = true;
                    });
                });
            });
        }

        document.addEventListener('click', function(e) {
            var link = e.target.closest('a[data-loading="true"]');
            if (!link) return;
            var message = link.dataset.loadingText || 'Chargement en cours...';
            showGlobalLoader(message);
        });
    });
</script>

@stack('scripts')
</body>
</html>





