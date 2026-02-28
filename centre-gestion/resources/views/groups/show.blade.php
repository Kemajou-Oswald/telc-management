<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ route('groups.index') }}"
               style="width:34px;height:34px;border-radius:9px;background:#f8fafc;border:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;flex-shrink:0;transition:all 0.15s;"
               onmouseenter="this.style.borderColor='#1e40af';this.style.color='#1e40af';this.style.background='#eff6ff'"
               onmouseleave="this.style.borderColor='#e2e8f0';this.style.color='#64748b';this.style.background='#f8fafc'">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 style="font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0;line-height:1.2;">
                    {{ $group->name }}
                </h2>
                <p style="font-size:11px;color:#94a3b8;font-weight:500;margin:2px 0 0;">
                    Créé le {{ $group->created_at->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);} }
        @keyframes barGrow { from{width:0;}to{width:var(--w);} }
        .card { background:white;border-radius:18px;border:1px solid #f1f5f9;box-shadow:0 2px 10px rgba(0,0,0,0.04);overflow:hidden; }
        .card-pad { padding:20px; }
        .section-label { font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.15em;margin:0 0 14px; }
        .day-pill { font-size:10px;font-weight:800;padding:5px 10px;border-radius:8px;display:inline-block;text-transform:uppercase;letter-spacing:0.05em; }
        .student-row { transition:background 0.15s; }
        .student-row:hover td { background:#f8fafc; }
        .bar-fill { height:100%;border-radius:99px;width:0;animation:barGrow 1s 0.3s ease forwards; }

        /* Action buttons top-right */
        .action-btn-secondary {
            display:inline-flex;align-items:center;gap:7px;padding:9px 16px;
            border-radius:11px;font-size:12px;font-weight:700;
            color:#64748b;background:white;border:1.5px solid #e2e8f0;
            text-decoration:none;transition:all 0.15s;white-space:nowrap;
        }
        .action-btn-secondary:hover { background:#f8fafc;border-color:#cbd5e1; }
        .action-btn-primary {
            display:inline-flex;align-items:center;gap:7px;padding:9px 18px;
            border-radius:11px;font-size:12px;font-weight:800;
            color:white;background:#1e40af;border:none;
            text-decoration:none;transition:all 0.2s;white-space:nowrap;
            box-shadow:0 4px 12px rgba(30,64,175,0.25);
        }
        .action-btn-primary:hover { background:#dc2626;box-shadow:0 4px 12px rgba(220,38,38,0.28);transform:translateY(-1px); }

        /* Responsive */
        @media(max-width:1024px){ .main-grid{grid-template-columns:1fr !important;} }
        @media(max-width:640px){
            .action-row{flex-direction:column !important;align-items:flex-start !important;}
            .action-btns{width:100%;justify-content:flex-end;}
        }
    </style>

    <div style="margin-top:20px;padding-bottom:50px;">

        {{-- ===== BARRE D'ACTIONS + BADGES ===== --}}
        <div class="action-row" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:20px;">

            {{-- Badges infos --}}
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;border-radius:8px;font-size:11px;font-weight:800;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    {{ $group->language ?? 'Langue N/A' }}
                </span>
                @if($group->teacher)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;border-radius:8px;font-size:11px;font-weight:800;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $group->teacher->name }}
                </span>
                @endif
                @php $pct = ($group->capacity > 0) ? round(($group->students->count() / $group->capacity) * 100) : 0; @endphp
                <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:{{ $pct >= 90 ? '#fef2f2' : '#f8fafc' }};color:{{ $pct >= 90 ? '#dc2626' : '#64748b' }};border:1px solid {{ $pct >= 90 ? '#fecaca' : '#e2e8f0' }};border-radius:8px;font-size:11px;font-weight:800;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $group->students->count() }} / {{ $group->capacity }} places
                </span>
            </div>

            {{-- Boutons d'action --}}
            <div class="action-btns" style="display:flex;align-items:center;gap:8px;">
                <a href="{{ route('groups.export', $group->id) }}" class="action-btn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Exporter
                </a>
                <a href="{{ route('groups.edit', $group) }}" class="action-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier le groupe
                </a>
            </div>
        </div>

        {{-- ===== GRILLE PRINCIPALE ===== --}}
        <div class="main-grid" style="display:grid;grid-template-columns:300px 1fr;gap:18px;align-items:start;">

            {{-- ===== COLONNE GAUCHE ===== --}}
            <div style="display:flex;flex-direction:column;gap:14px;">

                {{-- Effectif --}}
                <div class="card card-pad" style="animation:fadeUp 0.3s 0.05s ease both;">
                    <p class="section-label">Effectif</p>
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                        <div style="width:52px;height:52px;background:linear-gradient(135deg,#1e40af,#3b82f6);border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(30,64,175,0.25);flex-shrink:0;">
                            <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:36px;font-weight:900;color:#0f172a;margin:0;line-height:1;letter-spacing:-0.04em;">{{ $group->students->count() }}</p>
                            <p style="font-size:11px;color:#94a3b8;font-weight:600;margin:3px 0 0;">étudiants inscrits</p>
                        </div>
                    </div>
                    <div style="background:#f1f5f9;border-radius:99px;height:6px;overflow:hidden;margin-bottom:8px;">
                        <div class="bar-fill" style="background:linear-gradient(90deg,#1e40af,#3b82f6);--w:{{ $pct }}%;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11px;color:#94a3b8;font-weight:600;">Capacité : {{ $group->capacity }}</span>
                        <span style="font-size:12px;font-weight:900;color:{{ $pct >= 90 ? '#dc2626' : '#1e40af' }};">{{ $pct }}%</span>
                    </div>
                </div>

                {{-- Horaires --}}
                <div class="card card-pad" style="animation:fadeUp 0.3s 0.10s ease both;">
                    <p class="section-label">Horaires de formation</p>
                    @php $days = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true); @endphp
                    <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:14px;">
                        @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $day)
                            <span class="day-pill" style="{{ in_array($day,$days) ? 'background:#1e40af;color:white;' : 'background:#f8fafc;color:#cbd5e1;border:1px solid #f1f5f9;' }}">
                                {{ $day }}
                            </span>
                        @endforeach
                    </div>
                    <div style="background:#f8fafc;border:1px solid #f1f5f9;border-radius:12px;padding:14px;text-align:center;">
                        <p style="font-size:20px;font-weight:900;color:#0f172a;margin:0;letter-spacing:-0.02em;">
                            {{ $group->start_time ? date('H:i', strtotime($group->start_time)) : '--:--' }}
                            <span style="color:#cbd5e1;font-weight:400;margin:0 6px;">→</span>
                            {{ $group->end_time ? date('H:i', strtotime($group->end_time)) : '--:--' }}
                        </p>
                    </div>
                </div>

                {{-- Professeur --}}
                <div class="card card-pad" style="animation:fadeUp 0.3s 0.15s ease both;">
                    <p class="section-label">Professeur référent</p>
                    @if($group->teacher)
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#a78bfa);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($group->teacher->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0;">{{ $group->teacher->name }}</p>
                                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;font-weight:500;">Enseignant assigné</p>
                            </div>
                        </div>
                    @else
                        <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#f8fafc;border-radius:11px;border:1px dashed #e2e8f0;">
                            <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span style="font-size:12px;color:#94a3b8;font-weight:600;">Aucun professeur assigné</span>
                        </div>
                    @endif
                </div>

                {{-- Mini stat financière --}}
                <div class="card card-pad" style="animation:fadeUp 0.3s 0.20s ease both;">
                    <p class="section-label">Situation financière</p>
                    @php
                        $total    = $group->students->count();
                        $cleared  = $group->students->filter(fn($s) => $s->isFinanciallyCleared())->count();
                        $insolv   = $total - $cleared;
                        $clearPct = $total > 0 ? round(($cleared/$total)*100) : 0;
                    @endphp
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                        <div style="padding:12px;background:#f0fdf4;border-radius:12px;border:1px solid #bbf7d0;text-align:center;">
                            <p style="font-size:22px;font-weight:900;color:#16a34a;margin:0;">{{ $cleared }}</p>
                            <p style="font-size:10px;color:#16a34a;font-weight:700;margin:3px 0 0;text-transform:uppercase;letter-spacing:0.06em;">Soldés</p>
                        </div>
                        <div style="padding:12px;background:#fef2f2;border-radius:12px;border:1px solid #fecaca;text-align:center;">
                            <p style="font-size:22px;font-weight:900;color:#dc2626;margin:0;">{{ $insolv }}</p>
                            <p style="font-size:10px;color:#dc2626;font-weight:700;margin:3px 0 0;text-transform:uppercase;letter-spacing:0.06em;">Insolvables</p>
                        </div>
                    </div>
                    <div style="background:#f1f5f9;border-radius:99px;height:5px;overflow:hidden;">
                        <div class="bar-fill" style="background:linear-gradient(90deg,#16a34a,#4ade80);--w:{{ $clearPct }}%;"></div>
                    </div>
                    <p style="font-size:11px;color:#94a3b8;margin:6px 0 0;font-weight:600;text-align:right;">{{ $clearPct }}% en règle</p>
                </div>

            </div>

            {{-- ===== COLONNE DROITE : TABLE ===== --}}
            <div class="card" style="animation:fadeUp 0.3s 0.10s ease both;">
                <div style="padding:18px 22px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0;">Liste des apprenants</p>
                        <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;font-weight:500;">Nominative et détaillée</p>
                    </div>
                    <span style="font-size:11px;font-weight:800;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;padding:5px 12px;border-radius:8px;">
                        {{ $group->students->count() }} élèves
                    </span>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;text-align:left;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                                <th style="padding:13px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Identité</th>
                                <th style="padding:13px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Contact</th>
                                <th style="padding:13px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;">Statut</th>
                                <th style="padding:13px 22px;font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;text-align:right;">Dossier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($group->students as $student)
                                @php $isCleared = $student->isFinanciallyCleared(); @endphp
                                <tr class="student-row" style="border-bottom:1px solid #f8fafc;">
                                    <td style="padding:14px 22px;">
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            <div style="width:40px;height:40px;background:linear-gradient(135deg,#1e40af,#3b82f6);color:white;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:13px;flex-shrink:0;box-shadow:0 3px 8px rgba(30,64,175,0.2);">
                                                {{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
                                            </div>
                                            <div>
                                                <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                <p style="font-size:10px;color:#94a3b8;margin:2px 0 0;font-weight:600;">#{{ str_pad($student->id,4,'0',STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:14px 22px;">
                                        <p style="font-size:12px;font-weight:700;color:#475569;margin:0;">{{ $student->phone ?? '—' }}</p>
                                        <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $student->email }}</p>
                                    </td>
                                    <td style="padding:14px 22px;">
                                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:900;padding:5px 11px;border-radius:8px;{{ $isCleared ? 'background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;' : 'background:#fef2f2;color:#dc2626;border:1px solid #fecaca;' }}">
                                            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;"></span>
                                            {{ $isCleared ? 'Soldé' : 'Insolvable' }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 22px;text-align:right;">
                                        <a href="{{ route('students.show', $student) }}"
                                           style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:9px;font-size:11px;font-weight:700;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;text-decoration:none;transition:all 0.15s;"
                                           onmouseenter="this.style.background='#1e40af';this.style.color='white';this.style.borderColor='#1e40af'"
                                           onmouseleave="this.style.background='#eff6ff';this.style.color='#1e40af';this.style.borderColor='#bfdbfe'">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding:60px 22px;text-align:center;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                                            <div style="width:52px;height:52px;background:#f8fafc;border-radius:14px;display:flex;align-items:center;justify-content:center;border:1px solid #f1f5f9;">
                                                <svg width="24" height="24" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            <p style="font-size:13px;font-weight:700;color:#94a3b8;margin:0;">Aucun étudiant dans ce groupe</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
