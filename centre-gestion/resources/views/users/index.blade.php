<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Gestion du personnel
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Super Admin & Directeurs (création, modification, suppression)
        </p>
    </x-slot>

    <style>
        .kpi-card {
            background:white; border:1px solid rgba(15,14,61,0.07); border-radius:18px;
            padding:20px 22px; box-shadow:0 2px 12px rgba(15,14,61,0.05);
            transition:all 0.2s cubic-bezier(0.25,0.46,0.45,0.94);
            position:relative; overflow:hidden;
        }
        .kpi-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(15,14,61,0.1); }
        .kpi-card::after {
            content:''; position:absolute; top:0; left:0; right:0;
            height:3px; border-radius:18px 18px 0 0;
        }
        .kpi-blue::after  { background:linear-gradient(90deg,#1D1B84,#4f46e5); }
        .kpi-green::after { background:linear-gradient(90deg,#059669,#34d399); }
        .kpi-violet::after{ background:linear-gradient(90deg,#7c3aed,#a78bfa); }
        .kpi-slate::after { background:linear-gradient(90deg,#475569,#94a3b8); }

        .section-card {
            background:white; border:1px solid rgba(15,14,61,0.07);
            border-radius:20px; box-shadow:0 2px 16px rgba(15,14,61,0.05); overflow:hidden;
        }
        .section-header {
            padding:18px 22px 16px; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between;
        }
        .section-title { display:flex; align-items:center; gap:10px; }
        .section-icon {
            width:34px; height:34px; border-radius:10px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }

        .erp-table { width:100%; border-collapse:collapse; }
        .erp-table thead tr { border-bottom:2px solid #f1f5f9; }
        .erp-table thead th {
            padding:10px 18px; font-size:10px; font-weight:800;
            color:#94a3b8; text-transform:uppercase; letter-spacing:0.14em; text-align:left; white-space:nowrap;
        }
        .erp-table tbody tr { border-bottom:1px solid #f8fafc; transition:background 0.15s; }
        .erp-table tbody tr:hover { background:#fafbff; }
        .erp-table tbody tr:last-child { border-bottom:none; }
        .erp-table tbody td { padding:14px 18px; vertical-align:middle; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            padding:10px 18px;
            background:linear-gradient(135deg,#1D1B84 0%,#2d2bb0 100%);
            color:white; font-size:12.5px; font-weight:800; font-family:inherit;
            border:none; border-radius:11px; cursor:pointer;
            transition:all 0.2s; box-shadow:0 3px 14px rgba(29,27,132,0.3);
            text-decoration:none; white-space:nowrap;
        }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(29,27,132,0.38); }

        .btn-edit {
            display:inline-flex; align-items:center; gap:5px;
            padding:6px 13px; font-size:11.5px; font-weight:700;
            color:#1D1B84; background:rgba(29,27,132,0.05);
            border:1.5px solid rgba(29,27,132,0.15); border-radius:8px;
            text-decoration:none; transition:all 0.15s;
        }
        .btn-edit:hover { background:rgba(29,27,132,0.1); border-color:rgba(29,27,132,0.3); }

        .btn-delete {
            display:inline-flex; align-items:center; gap:5px;
            padding:6px 13px; font-size:11.5px; font-weight:700;
            color:#E31E24; background:rgba(227,30,36,0.05);
            border:1.5px solid rgba(227,30,36,0.15); border-radius:8px;
            cursor:pointer; font-family:inherit; transition:all 0.15s;
        }
        .btn-delete:hover { background:rgba(227,30,36,0.1); border-color:rgba(227,30,36,0.3); }

        /* Modal */
        .modal-backdrop {
            position:fixed; inset:0; z-index:50;
            display:flex; align-items:center; justify-content:center;
            background:rgba(13,12,43,0.5); backdrop-filter:blur(4px);
        }
        .modal-card {
            background:white; border-radius:20px;
            box-shadow:0 24px 60px rgba(13,12,43,0.25), 0 4px 16px rgba(0,0,0,0.1);
            border:1px solid rgba(255,255,255,0.8);
            width:100%; max-width:400px; padding:28px;
        }

        .role-badge {
            font-size:11px; font-weight:800; padding:3px 10px;
            border-radius:99px; white-space:nowrap;
        }
        .role-super_admin { background:rgba(29,27,132,0.08); color:#1D1B84; border:1px solid rgba(29,27,132,0.15); }
        .role-directeur   { background:rgba(124,58,237,0.08); color:#6d28d9; border:1px solid rgba(124,58,237,0.15); }
        .role-secretaire  { background:rgba(5,150,105,0.08); color:#065f46; border:1px solid rgba(5,150,105,0.15); }
        .role-professeur  { background:rgba(245,158,11,0.1); color:#92400e; border:1px solid rgba(245,158,11,0.2); }
        .role-default     { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; }

        .empty-state { padding:56px 24px; text-align:center; color:#94a3b8; font-size:13px; font-weight:600; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .a1{animation:fadeUp 0.4s 0.05s cubic-bezier(0.22,1,0.36,1) both}
        .a2{animation:fadeUp 0.4s 0.12s cubic-bezier(0.22,1,0.36,1) both}
        .a3{animation:fadeUp 0.4s 0.20s cubic-bezier(0.22,1,0.36,1) both}
    </style>

    <div style="display:flex; flex-direction:column; gap:22px; margin-top:4px;">

        {{-- KPI --}}
        <div class="a1" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px;">

            <div class="kpi-card kpi-blue">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#1D1B84; background:rgba(29,27,132,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Total</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">{{ $users->total() }}</p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Membres au total</p>
            </div>

            <div class="kpi-card kpi-violet">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(124,58,237,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#7c3aed" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#7c3aed; background:rgba(124,58,237,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Admins</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $users->getCollection()->whereIn('role',['super_admin','directeur'])->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Admins & Directeurs</p>
            </div>

            <div class="kpi-card kpi-green">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(5,150,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#059669; background:rgba(5,150,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Staff</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $users->getCollection()->where('role','secretaire')->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Secrétaires</p>
            </div>

            <div class="kpi-card kpi-slate">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(71,85,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#475569; background:rgba(71,85,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Profs</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $users->getCollection()->where('role','professeur')->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Professeurs</p>
            </div>
        </div>

        {{-- TABLEAU PERSONNEL --}}
        <div class="section-card a2">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(29,27,132,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Équipe</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">{{ $users->total() }} compte(s) enregistré(s)</p>
                    </div>
                </div>
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter un membre
                </a>
            </div>

            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Centre</th>
                            <th style="text-align:right; padding-right:22px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:11px;">
                                        <div style="width:38px; height:38px; border-radius:11px; background:linear-gradient(135deg,#E31E24,#1D1B84); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:900; color:white; flex-shrink:0; box-shadow:0 3px 10px rgba(29,27,132,0.2);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p style="font-size:13px; font-weight:800; color:#0f172a; margin:0; line-height:1.2;">{{ $user->name }}</p>
                                            <p style="font-size:10px; color:#94a3b8; font-weight:600; margin:2px 0 0;">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap:6px; font-size:12.5px; color:#475569; font-weight:600;">
                                        <svg width="13" height="13" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $user->email }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        // Correction : Remplacement de match par des tableaux (compatibilité PHP < 8.0)
                                        $classes = [
                                            'super_admin' => 'role-super_admin',
                                            'directeur'   => 'role-directeur',
                                            'secretaire'  => 'role-secretaire',
                                            'professeur'  => 'role-professeur',
                                        ];
                                        $labels = [
                                            'super_admin' => 'Super Admin',
                                            'directeur'   => 'Directeur',
                                            'secretaire'  => 'Secrétaire',
                                            'professeur'  => 'Professeur',
                                        ];
                                        
                                        $roleClass = isset($classes[$user->role]) ? $classes[$user->role] : 'role-default';
                                        $roleLabel = isset($labels[$user->role]) ? $labels[$user->role] : $user->role;
                                    @endphp
                                    <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                                </td>
                                <td>
                                    @if(optional($user->centre)->name)
                                        <span style="font-size:12px; color:#475569; font-weight:600; background:#f8fafc; border:1px solid #e2e8f0; padding:3px 9px; border-radius:7px;">
                                            {{ $user->centre->name }}
                                        </span>
                                    @else
                                        <span style="font-size:12px; color:#cbd5e1; font-weight:600;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:right; padding-right:22px;">
                                    <div style="display:inline-flex; align-items:center; gap:8px;">
                                        <a href="{{ route('users.edit', $user) }}" class="btn-edit">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Modifier
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <div x-data="{ open: false }" class="relative">
                                                <button type="button" @click="open = true" class="btn-delete">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Supprimer
                                                </button>

                                                <div x-show="open" x-cloak x-transition class="modal-backdrop">
                                                    <div class="modal-card" @click.stop>
                                                        <div style="width:52px; height:52px; border-radius:15px; background:rgba(227,30,36,0.08); border:1px solid rgba(227,30,36,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:18px;">
                                                            <svg width="24" height="24" fill="none" stroke="#E31E24" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                        </div>
                                                        <p style="font-size:16px; font-weight:900; color:#0f172a; margin:0 0 8px; letter-spacing:-0.02em;">Confirmer la suppression</p>
                                                        <p style="font-size:13px; color:#64748b; font-weight:500; margin:0 0 22px; line-height:1.55;">
                                                            Voulez-vous vraiment supprimer le compte de <strong style="color:#0f172a;">{{ $user->name }}</strong> ?
                                                            Cette action est <strong style="color:#E31E24;">irréversible</strong>.
                                                        </p>
                                                        <div style="display:flex; justify-content:flex-end; gap:10px;">
                                                            <button type="button" @click="open = false" style="padding:9px 18px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; font-weight:700; color:#475569; background:white; cursor:pointer; font-family:inherit; transition:all 0.15s;">Annuler</button>
                                                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="margin:0;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" style="padding:9px 18px; border-radius:10px; background:linear-gradient(135deg,#E31E24,#dc2626); color:white; font-size:13px; font-weight:800; border:none; cursor:pointer; font-family:inherit; box-shadow:0 3px 14px rgba(227,30,36,0.3); transition:all 0.15s; display:inline-flex; align-items:center; gap:7px;">
                                                                    Supprimer définitivement
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div style="width:52px; height:52px; border-radius:16px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                                            <svg width="24" height="24" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        Aucun membre enregistré pour l'instant.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div style="padding:16px 22px; border-top:1px solid #f1f5f9;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>