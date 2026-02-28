<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
                Finances — {{ $student->first_name }} {{ $student->last_name }}
            </h1>
        </div>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Module d'encaissement intelligent et ventilation automatique
        </p>
    </x-slot>

    <div class="space-y-6">
        {{-- Message de succès avec bouton de génération PDF --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3 text-emerald-800 font-bold text-sm">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    {{ session('success') }}
                </div>
                
                @if(session('open_receipt'))
                    <a href="{{ route('payments.receipt', session('open_receipt')) }}" 
                       target="_blank" 
                       class="flex items-center gap-2 bg-slate-900 px-5 py-2.5 rounded-xl text-white text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Générer le Reçu Officiel
                    </a>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-100 p-4 text-rose-800 font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Résumé Financier --}}
        @php
            $activeFee = $student->tuitionFee;
            $required = $activeFee ? (float) $activeFee->total_amount : 0;
            $totalTuitionPaid = $student->totalTuitionPaid();
            $remaining = $student->getTuitionBalance();
            $isRegistrationPaid = $student->hasPaidRegistration();
            $regRequired = optional($activeFee)->inscription_fee ?? 10000;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">Cursus Actuel</p>
                </div>
                <p class="text-sm font-bold text-slate-900">{{ $activeFee ? $activeFee->label : 'Aucun cours assigné' }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-2 text-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Versé (Cycle)</p>
                </div>
                <p class="text-2xl font-black text-emerald-700">
                    {{ number_format($student->payments()->where('tuition_fee_id', $student->tuition_fee_id)->sum('amount'), 0, ',', ' ') }} 
                    <span class="text-xs font-bold text-slate-300">FCFA</span>
                </p>
            </div>

            <div class="rounded-2xl border border-slate-100 shadow-sm p-5 {{ $remaining <= 0 ? 'bg-emerald-50' : 'bg-rose-50' }}">
                <div class="flex items-center gap-2 mb-2 {{ $remaining <= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Reste à solder</p>
                </div>
                <p class="text-2xl font-black {{ $remaining <= 0 ? 'text-emerald-800' : 'text-rose-800' }}">
                    {{ number_format($remaining, 0, ',', ' ') }} 
                    <span class="text-xs font-bold opacity-50">FCFA</span>
                </p>
            </div>
        </div>

        {{-- FORMULAIRE D'ENCAISSEMENT --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Nouvel Encaissement</h3>
                    <p class="text-[11px] text-slate-400 font-bold mt-1">Ventilation automatique (Inscription / Scolarité).</p>
                </div>
                @if($isRegistrationPaid)
                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Inscription OK
                    </div>
                @else
                    <div class="flex items-center gap-2 px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-100 animate-pulse">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Inscription Prioritaire
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('students.payments.store', $student) }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Programme de destination</label>
                    <select name="tuition_fee_id" required class="w-full border-slate-200 rounded-2xl text-sm px-4 py-4 bg-slate-50 font-black text-slate-700 outline-none">
                        @if($activeFee)
                            <option value="{{ $activeFee->id }}" selected>{{ $activeFee->label }} (Actuel)</option>
                        @endif
                        @foreach($tuitionFees as $fee)
                            @if(!$activeFee || $fee->id != $activeFee->id)
                                <option value="{{ $fee->id }}">{{ $fee->label }} ({{ number_format($fee->total_amount, 0, ',', ' ') }} F)</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Somme versée (FCFA)</label>
                    <input type="number" name="amount" required class="w-full border-slate-200 rounded-2xl text-lg px-4 py-3.5 font-black text-blue-600 bg-blue-50/30 outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mode de règlement</label>
                    <select name="mode" class="w-full border-slate-200 rounded-2xl text-sm px-4 py-4 bg-slate-50 font-bold text-slate-700 outline-none">
                        <option value="Espèces">Espèces</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Virement / Banque">Virement / Banque</option>
                    </select>
                </div>

                <div class="md:col-span-3 flex items-center justify-end mt-4 pt-6 border-t border-slate-50">
                    <button type="submit" class="px-10 py-4 rounded-2xl bg-slate-900 text-white text-xs font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition shadow-xl">
                        Confirmer l'encaissement
                    </button>
                </div>
            </form>
        </div>

        {{-- JOURNAL DES TRANSACTIONS --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight mb-6">Journal des transactions du cycle</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                            <th class="pb-4">Réf. Reçu</th>
                            <th class="pb-4">Date</th>
                            <th class="pb-4">Ventilation (Split)</th>
                            <th class="pb-4 text-right">Montant</th>
                            <th class="pb-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 font-black text-slate-800 text-xs tracking-tighter uppercase">{{ $payment->reference }}</td>
                                <td class="py-5 text-[11px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                <td class="py-5">
                                    <div class="flex gap-2">
                                        @if($payment->amount_registration > 0)
                                            <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 text-[8px] font-black uppercase">Insc: {{ number_format($payment->amount_registration, 0, ',', ' ') }}</span>
                                        @endif
                                        @if($payment->amount_tuition > 0)
                                            <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase">Scol: {{ number_format($payment->amount_tuition, 0, ',', ' ') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-5 text-right font-black text-slate-900 text-sm">{{ number_format($payment->amount, 0, ',', ' ') }} F</td>
                                <td class="py-5 text-right">
                                    <a href="{{ route('payments.receipt', $payment->id) }}" target="_blank" class="p-2.5 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Télécharger le reçu">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-slate-300 font-bold italic">Aucun mouvement financier.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>