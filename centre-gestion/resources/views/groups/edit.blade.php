<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ route('groups.index') }}" style="color:#94a3b8; text-decoration:none;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 style="font-size:20px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
                Configuration du Groupe : {{ $group->name }}
            </h1>
        </div>
    </x-slot>

    <div style="max-width:1100px; margin:0 auto; padding: 20px; animation: fadeUp 0.4s ease forwards;">

        {{-- AFFICHAGE DES ERREURS DE VALIDATION --}}
        @if ($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:15px; border-radius:12px; margin-bottom:20px; font-size:13px;">
            <p style="font-weight:800; margin-bottom:5px;">Attention, des erreurs sont survenues :</p>
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('groups.update', $group) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns: 1fr 350px; gap:25px; align-items: start;">

                <div style="display:flex; flex-direction:column; gap:25px;">

                    {{-- 1. INFOS GÉNÉRALES --}}
                    <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; padding:25px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <h3 style="font-size:14px; font-weight:900; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                            <span style="width:8px; height:8px; background:#4f46e5; border-radius:50%;"></span>
                            Informations du cours
                        </h3>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                            {{-- Langue --}}
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Langue enseignée</label>
                                <select name="language" required id="languageSelect" style="width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600; background:white;">
                                    <option value="Allemand" {{ $group->language == 'Allemand' ? 'selected' : '' }}>Allemand</option>
                                    <option value="Anglais" {{ $group->language == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                    <option value="Italien" {{ $group->language == 'Italien' ? 'selected' : '' }}>Italien</option>
                                    <option value="Espagnol" {{ $group->language == 'Espagnol' ? 'selected' : '' }}>Espagnol</option>
                                </select>
                            </div>

                            {{-- Nom --}}
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Nom de la classe</label>
                                <input type="text" name="name" value="{{ old('name', $group->name) }}" required
                                    style="width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600;">
                            </div>

                            {{-- Niveau --}}
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Niveau Académique</label>
                                <select name="level_id" id="levelSelect" required style="width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600; background:white;">
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ $group->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Type de cours --}}
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Type de programme</label>
                                <select name="type" required id="courseType" style="width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600; background:white;">
                                    <option value="Standard" {{ $group->type == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="Intensif" {{ $group->type == 'Intensif' ? 'selected' : '' }}>Intensif</option>
                                    <option value="Vorbereitung" id="vorbereitungOption" {{ $group->type == 'Vorbereitung' ? 'selected' : '' }}>Vorbereitung</option>
                                </select>
                            </div>

                            {{-- Professeur --}}
                            <div style="grid-column: span 2;">
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Professeur Responsable</label>
                                <select name="teacher_id" required style="width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600; background:white;">
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $group->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 2. GESTION DES HORAIRES --}}
                    <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; padding:25px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <h3 style="font-size:14px; font-weight:900; color:#0f172a; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Planning & Horaires
                        </h3>

                        <div style="margin-bottom:20px;">
                            <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:10px;">Jours de cours</label>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                @php $currentDays = is_array($group->days) ? $group->days : json_decode($group->days ?? '[]', true); @endphp
                                @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="days[]" value="{{ $day }}" class="hidden-check"
                                        {{ in_array($day, $currentDays) ? 'checked' : '' }}>
                                    <span class="day-pill">{{ $day }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Heure Début</label>
                                <input type="time" name="start_time" value="{{ $group->start_time ? substr($group->start_time, 0, 5) : '' }}"
                                    style="width:100%; padding:12px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600;">
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Heure Fin</label>
                                <input type="time" name="end_time" value="{{ $group->end_time ? substr($group->end_time, 0, 5) : '' }}"
                                    style="width:100%; padding:12px; border-radius:12px; border:1.5px solid #e2e8f0; font-weight:600;">
                            </div>
                        </div>
                    </div>

                    {{-- 3. GESTION DES ÉLÈVES --}}
                    <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; padding:25px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <h3 style="font-size:14px; font-weight:900; color:#0f172a; margin:0 0 15px 0;">Étudiants dans ce groupe</h3>
                        <input type="text" id="searchStudent" placeholder="Filtrer les élèves..." style="width:100%; padding:10px 15px; border-radius:10px; border:1px solid #f1f5f9; background:#f8fafc; font-size:13px; margin-bottom:20px; outline:none;">

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;" id="studentList">
                            @foreach($students as $student)
                            @php $isSelected = $student->group_id == $group->id; @endphp
                            <label class="student-item" style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:12px; border:1.5px solid {{ $isSelected ? '#4f46e5' : '#f1f5f9' }}; background:{{ $isSelected ? '#f5f3ff' : 'white' }}; cursor:pointer;">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" {{ $isSelected ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#4f46e5;">
                                <div style="min-width:0;">
                                    <p style="font-size:13px; font-weight:800; color:#0f172a; margin:0;">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p style="font-size:10px; color:#94a3b8; margin:0;">{{ $isSelected ? 'Membre actuel' : 'Disponible' }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- COLONNE DROITE : RÉSUMÉ --}}
                <div style="position:sticky; top:20px; display:flex; flex-direction:column; gap:20px;">
                    <div style="background:#0f172a; border-radius:20px; padding:25px; color:white;">
                        <h3 style="font-size:13px; font-weight:800; color:rgba(255,255,255,0.6); text-transform:uppercase; margin:0 0 20px 0;">Résumé</h3>

                        <div style="display:flex; flex-direction:column; gap:15px;">
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
                                <span style="font-size:12px; color:rgba(255,255,255,0.7);">Effectif Max</span>
                                <input type="number" name="capacity" value="{{ $group->capacity }}" style="width:60px; background:transparent; border:none; color:white; font-weight:900; text-align:right;">
                            </div>
                            <div style="display:flex; justify-content:space-between;">
                                <span style="font-size:12px; color:rgba(255,255,255,0.7);">Statut</span>
                                <select name="status" style="background:transparent; border:none; color:white; font-weight:800; cursor:pointer;">
                                    <option value="active" {{ $group->status == 'active' ? 'selected' : '' }} style="color:black;">Actif</option>
                                    <option value="finished" {{ $group->status == 'finished' ? 'selected' : '' }} style="color:black;">Terminé</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" style="width:100%; margin-top:25px; padding:14px; border-radius:12px; background:#4f46e5; color:white; border:none; font-weight:800; cursor:pointer; box-shadow:0 4px 15px rgba(79,70,229,0.4);">
                            Mettre à jour le groupe
                        </button>
                        <a href="{{ route('groups.index') }}" style="display:block; text-align:center; margin-top:15px; font-size:12px; color:rgba(255,255,255,0.5); text-decoration:none;">Annuler</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .hidden-check { display: none; }
        .day-pill {
            display: inline-block; padding: 8px 15px; border-radius: 10px; background: #f8fafc;
            border: 1.5px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; transition: all 0.2s;
        }
        .hidden-check:checked+.day-pill {
            background: #4f46e5; color: white; border-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }
    </style>

    <script>
        // Logique pour l'affichage conditionnel du Vorbereitung
        const languageSelect = document.getElementById('languageSelect');
        const levelSelect = document.getElementById('levelSelect');
        const vorbereitungOption = document.getElementById('vorbereitungOption');
        const courseType = document.getElementById('courseType');

        function checkVorbereitung() {
            const lang = languageSelect.value;
            const levelText = levelSelect.options[levelSelect.selectedIndex]?.text || '';
            
            // Si c'est Allemand et que le niveau est B1 ou B2
            if (lang === 'Allemand' && (levelText.includes('B1') || levelText.includes('B2'))) {
                vorbereitungOption.style.display = 'block';
            } else {
                vorbereitungOption.style.display = 'none';
                if (courseType.value === 'Vorbereitung') courseType.value = 'Standard';
            }
        }

        languageSelect.addEventListener('change', checkVorbereitung);
        levelSelect.addEventListener('change', checkVorbereitung);
        
        // Exécuter au chargement
        window.addEventListener('DOMContentLoaded', checkVorbereitung);

        // Recherche Étudiants
        document.getElementById('searchStudent').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            document.querySelectorAll('.student-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(search) ? 'flex' : 'none';
            });
        });
    </script>
</x-app-layout>