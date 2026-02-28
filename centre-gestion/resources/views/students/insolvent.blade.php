<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Étudiants en retard de paiement
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Liste automatique des insolvables (délai &gt;= 7 jours)
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mt-4">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 text-[11px] uppercase tracking-widest border-b border-slate-100">
                        <th class="py-2 pr-4">Étudiant</th>
                        <th class="py-2 pr-4">Contact</th>
                        <th class="py-2 pr-4">Niveau</th>
                        <th class="py-2 pr-4">Programme</th>
                        <th class="py-2 pr-4">Inscription</th>
                        <th class="py-2 pr-4">Total payé</th>
                        <th class="py-2 pr-4">Montant dû</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        @php
                            $fee = $student->getActiveTuitionFee();
                            $due = $fee ? (float) $fee->total_amount : 0;
                            $paid = $student->totalPaid();
                        @endphp
                        <tr>
                            <td class="py-2 pr-4 text-slate-800 font-semibold">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>
                            <td class="py-2 pr-4 text-xs text-slate-500">
                                {{ $student->phone }}<br>
                                {{ $student->email }}
                            </td>
                            <td class="py-2 pr-4 text-xs text-slate-500">
                                {{ optional($student->level)->name ?? '-' }}
                            </td>
                            <td class="py-2 pr-4 text-xs text-slate-500">
                                @if($fee)
                                    {{ optional($fee->level)->name }} · {{ $fee->language }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($student->registration_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-2 pr-4 font-bold text-amber-700">
                                {{ number_format($paid, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="py-2 pr-4 font-bold text-rose-700">
                                {{ number_format(max(0, $due - $paid), 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-sm text-slate-500 font-semibold">
                                Aucun étudiant en retard de paiement selon les critères actuels.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

