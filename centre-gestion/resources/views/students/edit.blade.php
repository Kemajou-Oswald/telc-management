<x-app-layout>
    <x-slot name="header">
        <div style="padding: 20px 0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="width:5px; height:44px; background:#1e40af; border-radius:99px; display:block; flex-shrink:0;"></span>
                <div>
                    <h2 style="font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.03em; line-height:1.15; margin:0;">
                        Modifier l'Étudiant
                    </h2>
                    <p style="font-size:12px; color:#94a3b8; font-weight:500; margin:3px 0 0 0;">
                        Mise à jour du dossier de {{ $student->first_name }} {{ $student->last_name }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mt-4" style="max-width:680px; margin:0 auto; padding-bottom: 50px;">

        <form method="POST" action="{{ route('students.update', $student->id) }}">
            @csrf
            @method('PUT')

            <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 2px 12px rgba(0,0,0,0.05); overflow:hidden;">

                {{-- Section : Identité --}}
                <div style="padding:20px 24px; border-bottom:1px solid #f8fafc; display:flex; align-items:center; gap:10px;">
                    <div style="width:30px;height:30px;background:linear-gradient(135deg,#1e40af,#3b82f6);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">Informations Personnelles</p>
                </div>

                <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label class="label-premium">Prénom <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Nom <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" class="input-premium">
                    </div>
                </div>

                {{-- Section : Académique --}}
                <div style="padding:20px 24px; border-top:1px solid #f1f5f9; background:#fcfdfe;">
                    <div style="margin-bottom:16px; display:flex; align-items:center; gap:10px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                        </div>
                        <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">Affectation Actuelle</p>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <label class="label-premium">Niveau</label>
                            <select name="level_id" required class="select-premium">
                                @foreach(\App\Models\Level::all() as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $student->level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="label-premium">Groupe</label>
                            <select name="group_id" class="select-premium">
                                <option value="">Sans groupe</option>
                                @foreach(\App\Models\Group::where('centre_id', $student->centre_id)->get() as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id', $student->group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- Bouton spécial Transfert --}}
                    <div style="margin-top:20px; padding:12px; background:#eef2ff; border-radius:12px; display:flex; align-items:center; justify-content:space-between;">
                        <p style="font-size:11px; color:#4338ca; font-weight:700; margin:0;">Besoin de changer de niveau avec un motif ?</p>
                        <a href="{{ route('students.transfer.form', $student->id) }}" style="font-size:11px; font-weight:800; color:white; background:#4338ca; padding:6px 12px; border-radius:8px; text-decoration:none;">Transférer l'élève</a>
                    </div>
                </div>

                {{-- Boutons --}}
                <div style="padding:20px 24px; background:#f8fafc; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                    <a href="{{ route('students.index') }}" style="font-size:13px; font-weight:800; color:#64748b; text-decoration:none;">Annuler</a>
                    <button type="submit" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:12px; font-size:13px; font-weight:800; color:white; background:#1e40af; border:none; cursor:pointer; box-shadow:0 4px 14px rgba(30,64,175,0.3);">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Mettre à jour le dossier
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .label-premium { display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:7px; }
        .input-premium { width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none; transition:all 0.2s; box-sizing:border-box; }
        .input-premium:focus { border-color:#1e40af; box-shadow:0 0 0 3px rgba(30,64,175,0.1); background:#fff; }
        .select-premium { width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none; cursor:pointer; box-sizing:border-box; }
    </style>
</x-app-layout>