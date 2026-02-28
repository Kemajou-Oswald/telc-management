<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Caisse du {{ $cashBook->date->translatedFormat('d F Y') }}
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Centre : {{ $cashBook->centre->name ?? '-' }}
        </p>
    </x-slot>

    <div class="space-y-6">
        {{-- Récap haut --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Solde veille</p>
                <p class="mt-2 text-2xl font-black text-slate-900">
                    {{ number_format($cashBook->solde_veille, 0, ',', ' ') }} <span class="text-xs font-bold text-slate-400">FCFA</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest">Entrées</p>
                <p class="mt-2 text-2xl font-black text-emerald-700">
                    {{ number_format($totalEntrees, 0, ',', ' ') }} <span class="text-xs font-bold text-emerald-400">FCFA</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-[10px] font-extrabold text-rose-600 uppercase tracking-widest">Sorties</p>
                <p class="mt-2 text-2xl font-black text-rose-700">
                    {{ number_format($totalSorties, 0, ',', ' ') }} <span class="text-xs font-bold text-rose-400">FCFA</span>
                </p>
            </div>

            <div class="rounded-2xl border border-slate-100 shadow-sm p-5 {{ $soldeFinal >= 0 ? 'bg-emerald-50' : 'bg-rose-50' }}">
                <p class="text-[10px] font-extrabold uppercase tracking-widest {{ $soldeFinal >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">Solde final théorique</p>
                <p class="mt-2 text-2xl font-black {{ $soldeFinal >= 0 ? 'text-emerald-800' : 'text-rose-800' }}">
                    {{ number_format($soldeFinal, 0, ',', ' ') }} <span class="text-xs font-bold opacity-70">FCFA</span>
                </p>
                <p class="mt-1 text-[11px] font-semibold {{ $cashBook->is_closed ? 'text-slate-600' : 'text-amber-700' }}">
                    @if($cashBook->is_closed)
                        Journée clôturée le {{ optional($cashBook->date_cloture)->translatedFormat('d/m/Y H:i') }}
                    @else
                        Journée ouverte — vous pouvez encore ajouter des mouvements.
                    @endif
                </p>
            </div>
        </div>

        {{-- Formulaire d'ajout de transaction --}}
        @if(!$cashBook->is_closed)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center justify-between gap-4 flex-wrap mb-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Ajouter un mouvement de caisse</h3>
                    <form method="POST" action="{{ route('cash.close', $cashBook) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition"
                                onclick="return confirm('Clôturer définitivement la caisse du jour ?')">
                            Clôturer la caisse
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mb-3 rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-xs text-emerald-800 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-3 rounded-xl bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-800 font-semibold">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('cash.transactions.store', $cashBook) }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Type</label>
                        <select name="direction" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2">
                            <option value="entree">Entrée</option>
                            <option value="sortie">Sortie</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Montant</label>
                        <input type="number" step="0.01" name="amount" required class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Libellé</label>
                        <input type="text" name="label" required class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2">
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Mode</label>
                            <input type="text" name="mode" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2" placeholder="Espèces / Mobile Money / Banque">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Référence</label>
                            <input type="text" name="reference" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2" placeholder="N° reçu / transaction">
                        </div>
                    </div>
                    <div class="md:col-span-5 flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-500 transition">
                            Ajouter à la caisse
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-amber-50 rounded-2xl border border-amber-200 shadow-sm p-4 text-sm text-amber-800 font-semibold">
                Journée clôturée — aucune nouvelle transaction ne peut être ajoutée ou modifiée.
            </div>
        @endif

        {{-- Liste des transactions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-sm font-extrabold text-slate-900 mb-3">Mouvements de la journée</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 text-[11px] uppercase tracking-widest">
                            <th class="py-2 pr-4">Heure</th>
                            <th class="py-2 pr-4">Type</th>
                            <th class="py-2 pr-4">Libellé</th>
                            <th class="py-2 pr-4">Mode</th>
                            <th class="py-2 pr-4">Montant</th>
                            <th class="py-2 pr-4">Statut</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $t)
                            <tr>
                                <td class="py-2 pr-4 text-xs text-slate-500">
                                    {{ $t->created_at->format('H:i') }}
                                </td>
                                <td class="py-2 pr-4 font-semibold {{ $t->direction === 'entree' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $t->direction === 'entree' ? 'Entrée' : 'Sortie' }}
                                </td>
                                <td class="py-2 pr-4 text-slate-800">
                                    {{ $t->label }}
                                </td>
                                <td class="py-2 pr-4 text-xs text-slate-500">
                                    {{ $t->mode ?? '-' }}
                                </td>
                                <td class="py-2 pr-4 font-bold {{ $t->direction === 'entree' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ number_format($t->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="py-2 pr-4">
                                    @if($t->is_cancelled)
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-500">
                                            Annulée
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 pr-4 text-right">
                                    @if(!$cashBook->is_closed && !$t->is_cancelled)
                                        <form method="POST" action="{{ route('cash.transactions.cancel', $t) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-bold text-rose-600 hover:text-rose-500"
                                                    onclick="return confirm('Annuler cette transaction (sans suppression) ?')">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-slate-500 font-semibold">
                                    Aucun mouvement enregistré pour cette journée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

