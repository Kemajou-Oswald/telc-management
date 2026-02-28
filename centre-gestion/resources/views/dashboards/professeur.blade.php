<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px;">
            <div>
                <h1 style="font-size:20px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
                    Dashboard Enseignant
                </h1>
                <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:3px 0 0;">
                    Emploi du temps & Gestion des cours
                </p>
            </div>
            <div style="background:#eff6ff; border:1px solid #bfdbfe; padding:8px 15px; border-radius:12px;">
                <p style="font-size:11px; color:#1e40af; font-weight:800; margin:0; text-transform:uppercase;">
                    Aujourd'hui : {{ now()->translatedFormat('l d F') }}
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .anim-1 { animation: fadeUp 0.35s 0.05s ease both; }
        .anim-2 { animation: fadeUp 0.35s 0.15s ease both; }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
            margin-top: 20px;
        }

        .day-column {
            background: #f8fafc;
            border-radius: 18px;
            padding: 12px;
            border: 1px solid #f1f5f9;
            min-height: 200px;
        }

        .day-title {
            font-size: 11px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8e0;
        }

        .day-column.is-today {
            background: #eff6ff;
            border-color: #bfdbfe;
        }
        .day-column.is-today .day-title {
            color: #1e40af;
            border-bottom-color: #1e40af;
        }

        .class-card {
            background: white;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .class-time {
            font-size: 9px;
            font-weight: 800;
            color: #1e40af;
            background: #eef2ff;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .class-name {
            font-size: 12px;
            font-weight: 800;
            color: #1e1b4b;
            line-height: 1.2;
        }

        @media (max-width: 1024px) {
            .schedule-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 640px) {
            .schedule-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="space-y-8">

        {{-- ===== SECTION 1 : EMPLOI DU TEMPS HEBDOMADAIRE ===== --}}
        <div class="anim-1">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:5px;">
                <div style="width:35px; height:35px; background:#0f172a; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">Mon Emploi du Temps Hebdomadaire</h3>
            </div>

            <div class="schedule-grid">
                @php
                    $weekDays = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                    $todayAbbr = ucfirst(substr(now()->translatedFormat('D'), 0, 3));
                    if(str_ends_with($todayAbbr, '.')) $todayAbbr = substr($todayAbbr, 0, -1);
                @endphp

                @foreach($weekDays as $day)
                    <div class="day-column {{ $todayAbbr == $day ? 'is-today' : '' }}">
                        <div class="day-title">{{ $day }}</div>
                        
                        @php
                            // On filtre les groupes qui ont cours ce jour-là
                            $classesForDay = $groups->filter(function($group) use ($day) {
                                $days = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true);
                                return in_array($day, $days);
                            })->sortBy('start_time');
                        @endphp

                        @forelse($classesForDay as $class)
                            <div class="class-card">
                                <span class="class-time">
                                    {{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}
                                </span>
                                <p class="class-name">{{ $class->name }}</p>
                                <p style="font-size:9px; color:#94a3b8; font-weight:700; margin-top:3px;">
                                    {{ $class->level->name ?? 'N/A' }} · {{ $class->language }}
                                </p>
                            </div>
                        @empty
                            <p style="font-size:9px; color:#cbd5e1; text-align:center; margin-top:20px; font-weight:600;">Aucun cours</p>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ===== SECTION 2 : ACTIONS DU JOUR (Pointage & Appels) ===== --}}
        <div class="anim-2">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                <div style="width:35px; height:35px; background:#4f46e5; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">Actions requises aujourd'hui</h3>
            </div>

            <div class="space-y-4">
                @php
                    $todayGroups = $groups->filter(function($group) use ($todayAbbr) {
                        $days = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true);
                        return in_array($todayAbbr, $days);
                    });
                @endphp

                @forelse($todayGroups as $group)
                    @php
                        $att = $todayAttendances[$group->id] ?? null;
                        $checkedIn = $att && $att->arrival_time;
                        $validated = $att ? (bool) $att->validated : false;
                    @endphp
                    
                    <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; padding:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
                        <div style="display:flex; align-items:center; gap:15px;">
                            <div style="width:45px; height:45px; background:#eff6ff; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#1e40af; font-weight:900;">
                                {{ date('H:i', strtotime($group->start_time)) }}
                            </div>
                            <div>
                                <p style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">{{ $group->name }}</p>
                                <div style="display:flex; gap:10px; margin-top:4px;">
                                    <span style="font-size:10px; font-weight:800; color:{{ $checkedIn ? '#0891b2' : '#94a3b8' }};">
                                        ● {{ $checkedIn ? 'Présence signalée' : 'Non pointé' }}
                                    </span>
                                    <span style="font-size:10px; font-weight:800; color:{{ $validated ? '#16a34a' : '#d97706' }};">
                                        ● {{ $validated ? 'Validé' : 'En attente' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:10px;">
                            @if(!$checkedIn)
                                <form method="POST" action="{{ route('teacher.checkin', $group->id) }}">
                                    @csrf
                                    <button type="submit" style="padding:10px 18px; border-radius:12px; background:#0f172a; color:white; font-size:12px; font-weight:800; border:none; cursor:pointer; transition:0.2s;">
                                        Pointer mon arrivée
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('student_attendances.create', $group) }}" 
                               style="padding:10px 18px; border-radius:12px; background:#4f46e5; color:white; font-size:12px; font-weight:800; text-decoration:none; {{ !$checkedIn ? 'opacity:0.5; pointer-events:none;' : '' }}">
                                Faire l'appel
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="padding:40px; text-align:center; background:#f8fafc; border-radius:20px; border:1px dashed #e2e8f0;">
                        <p style="font-size:13px; color:#94a3b8; font-weight:600;">Vous n'avez aucun cours prévu pour aujourd'hui.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>