<x-app-layout>
    <x-slot name="header">
        <div style="padding: 20px 0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="width:5px; height:44px; background:#1e40af; border-radius:99px; display:block; flex-shrink:0;"></span>
                <div>
                    <h2 style="font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.03em; line-height:1.15; margin:0;">
                        Nouveau Groupe
                    </h2>
                    <p style="font-size:12px; color:#94a3b8; font-weight:500; margin:3px 0 0 0;">
                        Configuration complète de la section d'apprentissage
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mt-6 mx-auto px-4 pb-12">
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf

            <div style="display:grid; grid-template-columns: 1fr 380px; gap:25px; align-items: start;">
                
                {{-- COLONNE GAUCHE : CONFIGURATION --}}
                <div class="space-y-6">
                    
                    {{-- Section 1 : Informations Générales --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Configuration du cours</h3>
                        </div>

                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Langue (Placée en premier pour la clarté) --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Langue enseignée</label>
                                    <select name="language" required id="languageSelect" class="form-select-premium">
                                        <option value="" disabled selected>Choisir une langue...</option>
                                        <option value="Allemand">Allemand</option>
                                        <option value="Anglais">Anglais</option>
                                        <option value="Italien">Italien</option>
                                        <option value="Espagnol">Espagnol</option>
                                        <option value="Français">Français</option>
                                    </select>
                                </div>

                                {{-- Nom du Groupe --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nom du groupe / Classe</label>
                                    <input type="text" name="name" required placeholder="Ex: Deutsch Intensiv A1"
                                        class="form-input-premium" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Niveau --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Niveau Académique</label>
                                    <select name="level_id" required id="levelSelect" class="form-select-premium">
                                        <option value="" disabled selected>Choisir...</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Type --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Type de cours</label>
                                    <select name="type" required id="courseType" class="form-select-premium">
                                        <option value="Standard">Cours Standard</option>
                                        <option value="Intensif">Cours Intensif</option>
                                        <option value="Vorbereitung" id="vorbereitungOption" style="display:none;">Vorbereitung</option>
                                    </select>
                                </div>
                                {{-- Capacité --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Effectif Max</label>
                                    <input type="number" name="capacity" value="20" required class="form-input-premium" />
                                </div>
                            </div>

                            {{-- Professeur --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Professeur Responsable</label>
                                <select name="teacher_id" required class="form-select-premium">
                                    <option value="">-- Sélectionner un professeur --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2 : Planning & Horaires --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Planning & Horaires</h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jours de cours</label>
                                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                                        <label style="cursor:pointer;">
                                            <input type="checkbox" name="days[]" value="{{ $day }}" class="hidden-check">
                                            <span class="day-pill">{{ $day }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Heure de début</label>
                                    <input type="time" name="start_time" class="form-input-premium" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Heure de fin</label>
                                    <input type="time" name="end_time" class="form-input-premium" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="flex items-center justify-between px-2">
                        <a href="{{ route('groups.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Annuler</a>
                        <button type="submit" style="background:#1e40af; color:white; padding:16px 35px; border-radius:16px; font-weight:800; border:none; cursor:pointer; box-shadow:0 4px 15px rgba(30,64,175,0.3); display:flex; align-items:center; gap:10px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Créer le groupe
                        </button>
                    </div>
                </div>

                {{-- COLONNE DROITE : ÉTUDIANTS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col" style="max-height: 800px; position:sticky; top:20px;">
                    <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Assigner des étudiants</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Élèves sans groupe actuellement</p>
                    </div>

                    <div class="p-4 border-b border-slate-50">
                        <input type="text" id="studentSearch" placeholder="Rechercher un élève..." 
                            class="w-full px-4 py-2 text-xs bg-slate-50 border border-slate-100 rounded-xl focus:outline-none focus:border-blue-400 transition" />
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2" id="studentList">
                        @forelse($students as $student)
                            <label class="student-item flex items-center gap-3 p-3 rounded-xl border border-slate-50 hover:border-blue-200 hover:bg-blue-50/30 transition cursor-pointer">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                    class="w-4 h-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                                <div style="min-width:0;">
                                    <p class="text-sm font-bold text-slate-700 leading-tight truncate">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-[10px] text-slate-400 font-semibold">{{ $student->level->name ?? 'Niveau N/A' }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="py-12 text-center">
                                <p class="text-[11px] font-bold text-slate-400 uppercase">Aucun élève disponible</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .form-input-premium {
            width:100%; padding:12px 16px; background:#f8fafc; border:1.5px solid #e2e8e0; border-radius:12px; font-size:14px; color:#0f172a; font-weight:600; outline:none; transition:all 0.2s;
        }
        .form-input-premium:focus { border-color:#1e40af; box-shadow:0 0 0 3px rgba(30,64,175,0.1); background:white; }

        .form-select-premium {
            width:100%; padding:12px 16px; background:#f8fafc; border:1.5px solid #e2e8e0; border-radius:12px; font-size:14px; color:#0f172a; font-weight:600; outline:none; cursor:pointer;
        }

        .hidden-check { display: none; }
        .day-pill {
            display: inline-block; padding: 10px 18px; border-radius: 12px; background: #f8fafc;
            border: 1.5px solid #e2e8f0; font-size: 13px; font-weight: 700; color: #64748b; transition: all 0.2s;
        }
        .hidden-check:checked + .day-pill {
            background: #1e40af; color: white; border-color: #1e40af; box-shadow: 0 4px 10px rgba(30,64,175,0.2);
        }

        #studentList::-webkit-scrollbar { width: 5px; }
        #studentList::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>

    <script>
        const languageSelect = document.getElementById('languageSelect');
        const levelSelect = document.getElementById('levelSelect');
        const vorbereitungOption = document.getElementById('vorbereitungOption');
        const courseType = document.getElementById('courseType');

        function checkVorbereitung() {
            const lang = languageSelect.value;
            const levelText = levelSelect.options[levelSelect.selectedIndex]?.text || '';
            
            // Logique spécifique : Vorbereitung s'affiche seulement pour l'allemand B1 ou B2
            if (lang === 'Allemand' && (levelText.includes('B1') || levelText.includes('B2'))) {
                vorbereitungOption.style.display = 'block';
            } else {
                vorbereitungOption.style.display = 'none';
                if (courseType.value === 'Vorbereitung') courseType.value = 'Standard';
            }
        }

        languageSelect.addEventListener('change', checkVorbereitung);
        levelSelect.addEventListener('change', checkVorbereitung);

        document.getElementById('studentSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.student-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(term) ? 'flex' : 'none';
            });
        });
    </script>
</x-app-layout>