<x-app-layout>
    <x-slot name="header">
        <div style="padding: 10px 0;">
            <h1 style="font-size:20px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
                Suivi des présences & Planning
            </h1>
            <p style="font-size:12px; color:#94a3b8; font-weight:600; margin:4px 0 0;">
                Jour actuel : <span style="color:#1e40af">{{ now()->translatedFormat('l d F') }} ({{ $today }})</span>
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 mt-6">

        {{-- SECTION 1 : MON PLANNING HEBDOMADAIRE (Seulement pour les profs) --}}
        @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'professeur')
        <div style="background:white; border-radius:24px; border:1px solid #f1f5f9; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
            <h3 style="font-size:15px; font-weight:900; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#1e40af;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Mon Planning de la semaine
            </h3>

            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:15px;">
                @forelse($myGroups as $group)
                    @php 
                        $groupDays = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true);
                        $isToday = in_array($today, $groupDays);
                    @endphp
                    <div style="border:1.5px solid {{ $isToday ? '#1e40af' : '#f1f5f9' }}; background:{{ $isToday ? '#fcfdfe' : 'white' }}; border-radius:22px; padding:20px; transition:all 0.2s;">
                        
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <div>
                                <p style="font-size:15px; font-weight:900; color:#0f172a; margin:0;">{{ $group->name }}</p>
                                <p style="font-size:11px; font-weight:700; color:#94a3b8; margin:2px 0 12px 0;">{{ $group->language }} · Niv. {{ $group->level->name ?? 'N/A' }}</p>
                            </div>
                            @if($isToday)
                                <span style="background:#1e40af; color:white; font-size:9px; font-weight:900; padding:3px 10px; border-radius:8px; text-transform:uppercase;">En cours aujourd'hui</span>
                            @endif
                        </div>
                        
                        <div style="display:flex; gap:5px; margin-bottom:15px;">
                            @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $d)
                                <span style="font-size:9px; font-weight:800; padding:4px 7px; border-radius:6px; {{ in_array($d, $groupDays) ? 'background:#1e40af; color:white;' : 'background:#f8fafc; color:#cbd5e1;' }}">
                                    {{ $d }}
                                </span>
                            @endforeach
                        </div>

                        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; padding-top:15px; border-top:1px solid #f8fafc;">
                            <div style="font-size:13px; font-weight:800; color:#475569; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $group->start_time ? date('H:i', strtotime($group->start_time)) : '--:--' }}
                            </div>
                            
                            @if($isToday)
                                <form action="{{ route('teacher.checkin', $group->id) }}" method="POST">
                                    @csrf
                                    <button style="background:#1e40af; color:white; border:none; padding:10px 18px; border-radius:12px; font-size:12px; font-weight:800; cursor:pointer; box-shadow:0 4px 10px rgba(30,64,175,0.2);">
                                        Signaler ma présence
                                    </button>
                                </form>
                            @else
                                <span style="font-size:11px; font-weight:700; color:#cbd5e1;">Prochain cours : {{ $groupDays[0] ?? '' }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="grid-column: span 2; text-align:center; padding:30px; color:#94a3b8; font-weight:600;">Aucun groupe assigné pour le moment.</p>
                @endforelse
            </div>
        </div>
        @endif

        {{-- SECTION 2 : RÉCAPITULATIF DE L'ASSIDUITÉ --}}
        <div style="background:white; border-radius:24px; border:1px solid #f1f5f9; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
            <h3 style="font-size:15px; font-weight:900; color:#0f172a; margin:0 0 20px 0;">
                {{ auth()->user()->role === 'teacher' ? 'Mes Statistiques' : 'Suivi de tous les professeurs' }}
            </h3>
            <div class="overflow-x-auto">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <th style="padding:15px; font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Professeur</th>
                            <th style="padding:15px; font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Total Séances</th>
                            <th style="padding:15px; font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Séances Validées</th>
                            <th style="padding:15px; font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Taux de présence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($stats as $row)
                            <tr>
                                <td style="padding:18px 15px;">
                                    <p style="font-size:14px; font-weight:800; color:#0f172a; margin:0;">{{ $row['teacher']->name }}</p>
                                    <p style="font-size:11px; color:#94a3b8; margin:0;">Enseignant</p>
                                </td>
                                <td style="padding:18px 15px; font-size:14px; font-weight:700; color:#475569;">{{ $row['totalDays'] }}</td>
                                <td style="padding:18px 15px; font-size:14px; font-weight:700; color:#16a34a;">{{ $row['validatedDays'] }}</td>
                                <td style="padding:18px 15px;">
                                    @php 
                                        $rate = $row['rate']; 
                                        $color = $rate >= 90 ? '#10b981' : ($rate >= 75 ? '#f59e0b' : '#ef4444');
                                    @endphp
                                    <div style="width:160px;">
                                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                            <span style="font-size:12px; font-weight:900; color:{{ $color }}">{{ $rate }}%</span>
                                        </div>
                                        <div style="height:6px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                                            <div style="width:{{ $rate }}%; height:100%; background:{{ $color }}; border-radius:10px; transition:width 1s;"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>