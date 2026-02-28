<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-3">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-10 bg-blue-600 rounded-full block"></span>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-none">Annuaire Étudiants</h2>
                    <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-widest">Base de données des apprenants</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 mt-6" x-data="{ showFilters: {{ request()->hasAny(['centre_id','level_id','group_id','start_date','end_date']) ? 'true' : 'false' }} }">

        {{-- BARRE DE RECHERCHE ET ACTIONS --}}
        <form action="{{ route('students.index') }}" method="GET" id="filterForm">
            @if(request('centre_id'))
                <input type="hidden" name="centre_id" value="{{ request('centre_id') }}">
            @endif

            <div class="bg-white rounded-[1.75rem] border border-slate-100 shadow-sm px-4 py-3 flex flex-wrap gap-3 items-center">
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nom, matricule, téléphone…"
                        class="w-full pl-11 pr-4 py-3 text-sm bg-slate-50 rounded-xl border-none focus:ring-2 focus:ring-blue-500 text-slate-700 placeholder:text-slate-300 transition" />
                </div>

                <div class="hidden sm:block w-px h-8 bg-slate-100"></div>

                <button type="button" @click="showFilters = !showFilters"
                    class="inline-flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all border"
                    :class="showFilters ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-slate-50 text-slate-500 border-slate-100 hover:bg-slate-100'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 8.293A1 1 0 013 7.586V4z" /></svg>
                    <span x-text="showFilters ? 'Masquer' : 'Filtres'"></span>
                </button>

                <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-xl text-sm font-black hover:bg-slate-700 transition shadow-sm">
                    Rechercher
                </button>

                @if(auth()->user()->role !== 'professeur')
                <a href="{{ route('students.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-xl text-sm font-black hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    Nouvel Étudiant
                </a>
                @endif
            </div>

            <div x-show="showFilters" x-cloak x-transition class="mt-3 bg-white border border-slate-100 shadow-lg rounded-[1.75rem] p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'directeur')
                    <div class="space-y-1.5">
                        <label class="st-label">Centre</label>
                        <select name="centre_id" class="st-select">
                            <option value="">Tous les centres</option>
                            @foreach($centers as $center)
                            <option value="{{ $center->id }}" {{ request('centre_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="space-y-1.5">
                        <label class="st-label">Niveau Académique</label>
                        <select name="level_id" class="st-select">
                            <option value="">Tous les niveaux</option>
                            @foreach($levels ?? [] as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="st-label">Groupe d'étude</label>
                        <select name="group_id" class="st-select">
                            <option value="">Tous les groupes</option>
                            @foreach($groups ?? [] as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="st-label">Inscrit depuis</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="st-select" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="st-label">Jusqu'au</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="st-select" />
                    </div>
                </div>
            </div>
        </form>

        {{-- TABS CENTRES --}}
        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'directeur')
        <div class="flex items-center gap-2 flex-wrap px-1">
            @php $currentC = request('centre_id'); @endphp
            <a href="{{ route('students.index', array_merge(request()->except('centre_id','page'), [])) }}"
               class="st-tab {{ !$currentC ? 'is-active' : '' }}">Tous les centres</a>
            @foreach($centers as $center)
            <a href="{{ route('students.index', array_merge(request()->except('page'), ['centre_id' => $center->id])) }}"
               class="st-tab {{ $currentC == $center->id ? 'is-active' : '' }}">
                {{ $center->name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- TABLEAU --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50 bg-slate-50/30">
                            <th class="px-7 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[.2em] whitespace-nowrap">Apprenant</th>
                            <th class="px-7 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[.2em] whitespace-nowrap">Position Académique</th>
                            <th class="px-7 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[.2em] whitespace-nowrap">Cycle Financier</th>
                            <th class="px-7 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[.2em] whitespace-nowrap">Temps / Présence</th>
                            <th class="px-7 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[.2em] whitespace-nowrap text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/80">
                        @forelse($students as $student)
                        @php 
                            $isExpired = $student->isCycleExpired(); 
                            $balance = $student->getTuitionBalance();
                            $hasInsc = $student->hasPaidRegistration();
                            $timeProgress = $student->getCycleProgressPercentage();
                        @endphp
                        <tr class="group hover:bg-blue-50/20 transition-all duration-200">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-11 w-11 rounded-2xl flex items-center justify-center text-white text-sm font-black shadow-md shrink-0"
                                        style="background: linear-gradient(135deg, {{ $isExpired ? '#f59e0b' : '#1d4ed8' }} 0%, {{ $isExpired ? '#ea580c' : '#3b82f6' }} 100%);">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="text-[14px] font-black text-slate-800 tracking-tight truncate">{{ $student->first_name }} {{ $student->last_name }}</p>
                                            @if($isExpired)
                                                <span class="px-1.5 py-0.5 bg-rose-100 text-rose-600 text-[7px] font-black rounded uppercase animate-pulse">Cycle Fini</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $student->phone }}</p>
                                        @if(optional($student->centre)->name)
                                        <div class="flex items-center gap-1 mt-1 text-blue-500">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                            <span class="text-[8px] font-black uppercase">{{ $student->centre->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-7 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 border border-blue-100/80">
                                        {{ optional($student->group)->name ?? 'Sans groupe' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold ml-0.5 italic">{{ optional($student->level)->name }}</span>
                                </div>
                            </td>

                            <td class="px-7 py-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-1.5">
                                        @if($hasInsc)
                                            <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded text-[8px] font-black uppercase tracking-tighter">Insc. OK ✓</span>
                                        @else
                                            <span class="px-1.5 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[8px] font-black uppercase tracking-tighter">Insc. À payer ⚠</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] font-black {{ $balance > 0 ? 'text-slate-800' : 'text-emerald-600' }}">
                                        {{ $balance > 0 ? number_format($balance, 0, ',', ' ').' F' : 'Dossier Soldé' }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-7 py-4">
                                <div class="w-28">
                                    <div class="flex justify-between items-center mb-1.5 text-[9px] font-black text-slate-400 uppercase">
                                        <span>Temps : {{ $timeProgress }}%</span>
                                    </div>
                                    <div class="bg-slate-100 rounded-full h-1 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $timeProgress }}%; background-color: {{ $isExpired ? '#ef4444' : '#3b82f6' }};"></div>
                                    </div>
                                    <p class="mt-2 text-[8px] font-bold text-slate-300 uppercase">Présence : {{ $student->attendanceRate() }}%</p>
                                </div>
                            </td>

                            <td class="px-7 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('students.show', $student) }}" class="st-action-btn hover:bg-blue-50 hover:text-blue-600" title="Profil"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    <a href="{{ route('students.payments.show', $student->id) }}" class="st-action-btn hover:bg-emerald-50 hover:text-emerald-600" title="Paiements"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></a>
                                    <a href="{{ route('students.edit', $student->id) }}" class="st-action-btn hover:bg-amber-50 hover:text-amber-600" title="Éditer"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                    
                                    {{-- MODAL DE SUPPRESSION AVEC CONFIRMATION VISUELLE --}}
                                    <div x-data="{ openDelete: false }" class="inline">
                                        <button @click="openDelete = true" class="st-action-btn hover:bg-rose-50 hover:text-rose-500" title="Supprimer">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>

                                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
                                            <div @click.away="openDelete = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-8 text-center border border-slate-100">
                                                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </div>
                                                <h3 class="text-xl font-black text-slate-900 mb-2">Supprimer l'étudiant ?</h3>
                                                <p class="text-slate-500 text-sm mb-8 leading-relaxed">Êtes-vous sûr de vouloir supprimer <br><strong class="text-slate-800">{{ $student->first_name }} {{ $student->last_name }}</strong> ? Cette action est irréversible.</p>
                                                <div class="flex flex-col gap-3">
                                                    <form action="{{ route('students.destroy', $student) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-full py-4 bg-rose-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-rose-200">Confirmer la suppression</button>
                                                    </form>
                                                    <button @click="openDelete = false" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-sm font-black uppercase tracking-widest">Annuler</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-bold">Aucun apprenant trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="flex items-center justify-between pb-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $students->firstItem() }}-{{ $students->lastItem() }} sur {{ $students->total() }}</p>
            {{ $students->links() }}
        </div>
    </div>

    <style>
        .st-label { display: inline-flex; align-items: center; gap: 5px; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .18em; color: #94a3b8; margin-left: .25rem; }
        .st-select { width: 100%; padding: .65rem .9rem; background: #f8fafc; border: 1.5px solid #f1f5f9; border-radius: .75rem; font-size: .8rem; font-weight: 600; color: #334155; outline: none; transition: border-color .15s; }
        .st-select:focus { border-color: #93c5fd; }
        .st-tab { display: inline-flex; align-items: center; padding: .5rem 1.2rem; border-radius: .9rem; font-size: .65rem; font-weight: 900; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; border: 1px solid transparent; text-decoration: none; transition: all .2s; }
        .st-tab:hover { background: #f8fafc; color: #475569; }
        .st-tab.is-active { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; box-shadow: 0 4px 15px rgba(37,99,235,0.08); }
        .st-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: .85rem; color: #94a3b8; background: #f8fafc; transition: all .2s; border:none; cursor:pointer; }
        .st-action-btn:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>