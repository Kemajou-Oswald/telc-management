<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Historique présence — {{ $group->name }}
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Dernières séances enregistrées
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        @forelse($attendances as $date => $items)
            <div class="mb-6">
                <h3 class="text-sm font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}</h3>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-400 text-[11px] uppercase tracking-widest">
                                <th class="py-2 pr-4">Étudiant</th>
                                <th class="py-2 pr-4">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($items as $att)
                                <tr>
                                    <td class="py-2 pr-4 font-bold text-slate-800">{{ optional($att->student)->first_name }} {{ optional($att->student)->last_name }}</td>
                                    <td class="py-2 pr-4">
                                        @if($att->present)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">Présent</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-700">Absent</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="py-10 text-center">
                <p class="text-sm font-extrabold text-slate-700">Aucun historique</p>
                <p class="text-[12px] text-slate-400 font-semibold mt-1">Les présences apparaîtront ici après enregistrement.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>

