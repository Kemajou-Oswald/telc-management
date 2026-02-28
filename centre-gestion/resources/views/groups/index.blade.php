<x-app-layout>
    <x-slot name="header">
        <div style="padding: 20px 0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="width:5px; height:44px; background:#dc2626; border-radius:99px; display:block; flex-shrink:0;"></span>
                <div>
                    <h2 style="font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.03em; line-height:1.15; margin:0;">
                        Gestion des Groupes
                    </h2>
                    <p style="font-size:12px; color:#94a3b8; font-weight:500; margin:3px 0 0 0;">
                        {{ $groups->count() }} classes actives dans votre centre
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes barGrow {
            from {
                width: 0;
            }

            to {
                width: var(--w);
            }
        }

        .group-row {
            transition: background 0.15s;
        }

        .group-row:hover td {
            background: #f8fafc;
        }

        .group-row:hover .group-avatar {
            transform: scale(1.07);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.28);
        }

        .group-avatar {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .bar-fill {
            height: 100%;
            border-radius: 99px;
            width: 0;
            animation: barGrow 0.8s 0.2s ease forwards;
        }

        .btn-see {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-see:hover {
            background: #1e40af;
            border-color: #1e40af;
            color: white;
        }

        .btn-manage {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 13px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            color: #1e40af;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-manage:hover {
            background: #1e40af;
            color: white;
            border-color: #1e40af;
        }

        .day-pip {
            font-size: 9px;
            font-weight: 800;
            padding: 4px 7px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Responsive */
        @media(max-width:768px) {
            .groups-table thead {
                display: none;
            }

            .groups-table tr {
                display: flex;
                flex-direction: column;
                padding: 16px;
                border-bottom: 2px solid #f1f5f9;
                gap: 10px;
            }

            .groups-table td {
                display: flex;
                align-items: center;
                padding: 0;
                border: none;
                white-space: normal;
            }

            .groups-table td::before {
                content: attr(data-label);
                font-size: 10px;
                font-weight: 800;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                width: 80px;
                flex-shrink: 0;
            }

            .groups-table td:first-child::before {
                content: "";
                width: 0;
            }

            .groups-table td:last-child {
                justify-content: flex-end;
            }

            .groups-table td:last-child::before {
                content: "";
                width: 0;
            }
        }

        @media(max-width:540px) {
            .search-bar {
                flex-direction: column !important;
            }
        }
    </style>

    <div class="space-y-5 mt-4">

        {{-- ===== BARRE : Recherche + Filtres + Nouveau Groupe ===== --}}
        {{-- ===== BARRE : Recherche + Filtres + Nouveau Groupe ===== --}}
        <form action="{{ route('groups.index') }}" method="GET">
            <div class="search-bar" style="background:white; padding:14px 18px; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.05); border:1px solid #f1f5f9; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

                {{-- Recherche (Champ textuel) --}}
                <div style="position:relative;flex:1;min-width:220px;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{-- On garde la valeur tapée grâce à value="{{ request('search') }}" --}}
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un groupe…"
                        style="width:100%;padding:10px 14px 10px 36px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:11px;font-size:13px;color:#0f172a;font-family:inherit;outline:none;transition:all 0.2s;box-sizing:border-box;">
                </div>

                {{-- Filtre niveau (Liste déroulante) --}}
                <div style="position:relative;flex-shrink:0;">
                    <select name="level_id"
                        style="padding:10px 32px 10px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:11px;font-size:12px;font-weight:700;color:#334155;font-family:inherit;outline:none;appearance:none;cursor:pointer;transition:border-color 0.2s;">
                        <option value="">Tous les niveaux</option>
                        @foreach(\App\Models\Level::all() as $level)
                        {{-- On garde le niveau sélectionné grâce à la condition ternary --}}
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                        @endforeach
                    </select>
                    <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8;" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Bouton Appliquer (Filtrer) --}}
                <button type="submit"
                    style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:11px;font-size:12px;font-weight:800;background:#0f172a;color:white;border:none;cursor:pointer;transition:all 0.15s;"
                    onmouseenter="this.style.background='#1e293b'" onmouseleave="this.style.background='#0f172a'">
                    Appliquer
                </button>

                {{-- Bouton Réinitialiser (optionnel mais utile) --}}
                @if(request('search') || request('level_id'))
                <a href="{{ route('groups.index') }}" style="font-size:11px; color:#ef4444; font-weight:700; text-decoration:none;">Effacer</a>
                @endif

                {{-- Nouveau Groupe --}}
                @if(auth()->user()->role !== 'professeur')
                <a href="{{ route('groups.create') }}"
                    style="margin-left:auto;background-color:#1e40af;color:#ffffff;text-decoration:none;display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:11px;font-weight:800;font-size:13px;box-shadow:0 4px 14px rgba(30,64,175,0.28);transition:all 0.2s;white-space:nowrap;">
                    <span>+</span> Nouveau Groupe
                </a>
                @endif

            </div>
        </form>

        {{-- ===== TABLEAU ===== --}}
        <div style="background:white;border-radius:18px;border:1px solid #f1f5f9;box-shadow:0 2px 12px rgba(0,0,0,0.04);overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="groups-table" style="width:100%;border-collapse:collapse;text-align:left;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                            <th style="padding:14px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Groupe & Langue</th>
                            <th style="padding:14px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Planning</th>
                            <th style="padding:14px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Niveau</th>
                            <th style="padding:14px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Effectif</th>
                            <th style="padding:14px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                        @php
                        $count = $group->students->count();
                        $max = max($group->capacity, 1);
                        $pct = round(($count / $max) * 100);
                        $barColor = $pct >= 90 ? '#ef4444' : ($pct >= 70 ? '#f59e0b' : '#10b981');
                        $days = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true);
                        @endphp
                        <tr class="group-row" style="border-bottom:1px solid #f8fafc;">

                            {{-- Identité --}}
                            <td style="padding:15px 22px;white-space:nowrap;" data-label="Groupe">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div class="group-avatar" style="width:42px;height:42px;border-radius:13px;background:linear-gradient(135deg,#1e40af,#3b82f6);color:white;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 10px rgba(30,64,175,0.22);">
                                        {{ strtoupper(substr($group->language ?? 'GR', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;line-height:1.2;">{{ $group->name }}</p>
                                        <span style="font-size:10px;font-weight:800;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;padding:2px 8px;border-radius:6px;display:inline-block;margin-top:3px;text-transform:uppercase;letter-spacing:0.04em;">
                                            {{ $group->language ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Planning --}}
                            <td style="padding:15px 22px;white-space:nowrap;" data-label="Planning">
                                <div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:6px;">
                                    @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $d)
                                    <span class="day-pip" style="{{ in_array($d, $days ?? []) ? 'background:#1e40af;color:white;' : 'background:#f1f5f9;color:#cbd5e1;' }}">
                                        {{ $d }}
                                    </span>
                                    @endforeach
                                </div>
                                <p style="font-size:11px;font-weight:700;color:#64748b;display:flex;align-items:center;gap:4px;margin:0;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $group->start_time ? date('H:i', strtotime($group->start_time)) : '--:--' }}
                                    →
                                    {{ $group->end_time ? date('H:i', strtotime($group->end_time)) : '--:--' }}
                                </p>
                            </td>

                            {{-- Niveau --}}
                            <td style="padding:15px 22px;white-space:nowrap;" data-label="Niveau">
                                <span style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:8px;font-size:10px;font-weight:800;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;text-transform:uppercase;letter-spacing:0.06em;">
                                    {{ $group->level->name ?? 'N/A' }}
                                </span>
                                @if($group->type)
                                <p style="font-size:10px;font-weight:600;color:#94a3b8;margin:4px 0 0;text-transform:uppercase;letter-spacing:0.06em;">{{ $group->type }}</p>
                                @endif
                            </td>

                            {{-- Effectif --}}
                            <td style="padding:15px 22px;white-space:nowrap;" data-label="Effectif">
                                <div style="width:110px;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                                        <span style="font-size:12px;font-weight:900;color:#0f172a;">{{ $count }}<span style="color:#94a3b8;font-weight:600;">/{{ $group->capacity }}</span></span>
                                        <span style="font-size:10px;font-weight:800;color:{{ $barColor }};">{{ $pct }}%</span>
                                    </div>
                                    <div style="background:#f1f5f9;border-radius:99px;height:5px;overflow:hidden;">
                                        <div class="bar-fill" style="background:{{ $barColor }};--w:{{ $pct }}%;"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td style="padding:15px 22px;white-space:nowrap;text-align:right;" data-label="">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:7px;">
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn-see" title="Voir les détails">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if(auth()->user()->role !== 'professeur')
                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn-manage">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Gérer
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding:70px 22px;text-align:center;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                                    <div style="width:60px;height:60px;background:#f8fafc;border-radius:16px;display:flex;align-items:center;justify-content:center;border:1px solid #f1f5f9;">
                                        <svg width="28" height="28" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <p style="font-size:14px;font-weight:700;color:#64748b;margin:0;">Aucun groupe trouvé</p>
                                    <a href="{{ route('groups.create') }}"
                                        style="font-size:12px;font-weight:700;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;padding:7px 16px;border-radius:9px;text-decoration:none;">
                                        Créer le premier groupe
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>