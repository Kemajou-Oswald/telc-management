<x-app-layout>
    <x-slot name="header">
        <div style="padding: 10px 0;">
            <h1 style="font-size:20px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
                Validation des présences professeurs
            </h1>
            <p style="font-size:12px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
                {{ $pending->count() }} pointage(s) en attente de confirmation
            </p>
        </div>
    </x-slot>

    <div class="mt-6">
        <div style="background:white; border-radius:24px; border:1px solid #f1f5f9; box-shadow:0 4px 15px rgba(0,0,0,0.02); overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:1.5px solid #f1f5f9;">
                            <th style="padding:16px 24px; font-size:10px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em;">Professeur</th>
                            <th style="padding:16px 24px; font-size:10px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em;">Groupe / Cours</th>
                            <th style="padding:16px 24px; font-size:10px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em;">Date & Heure d'arrivée</th>
                            <th style="padding:16px 24px; font-size:10px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pending as $item)
                            <tr style="transition: background 0.2s;" onmouseenter="this.style.background='#fcfdfe'" onmouseleave="this.style.background='transparent'">
                                <td style="padding:16px 24px;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:38px; height:38px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:900; color:#1e40af; font-size:14px;">
                                            {{ strtoupper(substr($item->teacher->name, 0, 1)) }}
                                        </div>
                                        <p style="font-size:14px; font-weight:800; color:#0f172a; margin:0;">{{ $item->teacher->name }}</p>
                                    </div>
                                </td>
                                <td style="padding:16px 24px;">
                                    <p style="font-size:13px; font-weight:700; color:#475569; margin:0;">{{ $item->group->name }}</p>
                                    <p style="font-size:11px; color:#94a3b8; font-weight:600;">{{ $item->group->language }}</p>
                                </td>
                                <td style="padding:16px 24px;">
                                    <p style="font-size:13px; font-weight:800; color:#0f172a; margin:0;">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</p>
                                    <span style="font-size:11px; font-weight:900; color:#1e40af; background:#eff6ff; padding:2px 8px; border-radius:6px; display:inline-block; margin-top:4px;">
                                        🕒 {{ date('H:i', strtotime($item->arrival_time)) }}
                                    </span>
                                </td>
                                <td style="padding:16px 24px; text-align:right;">
                                    <form action="{{ route('teacher.validate', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:12px; background:#10b981; color:white; font-size:12px; font-weight:800; border:none; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 12px rgba(16,185,129,0.2);"
                                                onmouseenter="this.style.transform='translateY(-1px)'; this.style.background='#059669'" onmouseleave="this.style.transform='translateY(0)'; this.style.background='#10b981'">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                            Valider la présence
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:80px 24px; text-align:center;">
                                    <div style="width:60px; height:60px; background:#f8fafc; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                                        <svg width="30" height="30" style="color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p style="font-size:15px; color:#94a3b8; font-weight:700; margin:0;">Aucun pointage en attente.</p>
                                    <p style="font-size:12px; color:#cbd5e1; margin-top:5px;">Tous les professeurs du centre sont à jour.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>