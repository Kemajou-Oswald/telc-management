<x-app-layout>
    <x-slot name="header">
        <div style="padding: 20px 0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="width:5px; height:44px; background:#1e40af; border-radius:99px; display:block; flex-shrink:0;"></span>
                <div>
                    <h2 style="font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.03em; line-height:1.15; margin:0;">
                        Nouvel Étudiant
                    </h2>
                    <p style="font-size:12px; color:#94a3b8; font-weight:500; margin:3px 0 0 0;">
                        Initialisation du dossier et du cycle de formation
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mt-4" style="max-width:680px; margin-left:auto; margin-right:auto;">

        {{-- Erreurs de validation --}}
        @if($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:14px; padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; align-items:flex-start;">
                <div style="width:32px;height:32px;background:#dc2626;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p style="font-size:12px;font-weight:800;color:#dc2626;margin:0 0 6px;">Veuillez corriger les erreurs suivantes :</p>
                    @foreach($errors->all() as $error)
                        <p style="font-size:12px;color:#b91c1c;margin:2px 0;">· {{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- FORMULAIRE --}}
        <form method="POST" action="{{ route('students.store') }}">
            @csrf

            <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 2px 12px rgba(0,0,0,0.05); overflow:hidden;">

                {{-- Section : Identité --}}
                <div style="padding:20px 24px; border-bottom:1px solid #f8fafc; display:flex; align-items:center; gap:10px;">
                    <div style="width:30px;height:30px;background:linear-gradient(135deg,#1e40af,#3b82f6);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">Identité</p>
                        <p style="font-size:11px;color:#94a3b8;margin:0;">Informations de base de l'apprenant</p>
                    </div>
                </div>

                <div style="padding:20px 24px 0; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    {{-- Prénom --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">
                            Prénom <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               placeholder="Ex : Aminata"
                               style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid {{ $errors->has('first_name') ? '#fca5a5' : '#e2e8f0' }}; border-radius:11px; font-size:13px; color:#0f172a; font-family:inherit; outline:none; transition:border-color 0.2s; box-sizing:border-box;">
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">
                            Nom <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               placeholder="Ex : Diallo"
                               style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid {{ $errors->has('last_name') ? '#fca5a5' : '#e2e8f0' }}; border-radius:11px; font-size:13px; color:#0f172a; font-family:inherit; outline:none; transition:border-color 0.2s; box-sizing:border-box;">
                    </div>
                </div>

                {{-- Section Contact --}}
                <div style="padding:20px 24px 0;">
                    <div style="border-top:1px solid #f1f5f9; padding-top:20px; margin-bottom:16px; display:flex; align-items:center; gap:10px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#059669,#34d399);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">Contact</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">Coordonnées de communication</p>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">Téléphone <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   placeholder="+237 ..."
                                   style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="exemple@email.com"
                                   style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none;">
                        </div>
                    </div>
                </div>

                {{-- Section Académique & Financière --}}
                <div style="padding:20px 24px 24px;">
                    <div style="border-top:1px solid #f1f5f9; padding-top:20px; margin-bottom:16px; display:flex; align-items:center; gap:10px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0;">Parcours & Financement</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">Définition du tarif et du niveau</p>
                        </div>
                    </div>

                    {{-- RÈGLE : PROGRAMME OBLIGATOIRE --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; font-weight:800; color:#dc2626; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px; display:flex; align-items:center; gap:6px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Programme d'étude & Tarif associé <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="position:relative;">
                            <select name="tuition_fee_id" required
                                    style="width:100%; padding:12px 36px 12px 14px; background:#fef2f2; border:2px solid #fecaca; border-radius:11px; font-size:13px; color:#0f172a; font-weight:700; appearance:none; cursor:pointer; outline:none; box-sizing:border-box;">
                                <option value="">-- SÉLECTIONNER LE PROGRAMME (OBLIGATOIRE) --</option>
                                @foreach($tuitionFees as $fee)
                                    <option value="{{ $fee->id }}" {{ old('tuition_fee_id') == $fee->id ? 'selected' : '' }}>
                                        {{ $fee->label }} — {{ $fee->language }} · {{ number_format($fee->total_amount, 0, ',', ' ') }} {{ $fee->currency }} 
                                        @if($fee->duration_label) (Durée : {{ $fee->duration_label }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;color:#dc2626;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </div>
                        <p style="font-size:10px;color:#b91c1c;margin:6px 0 0;font-weight:700;text-transform:uppercase;letter-spacing:0.02em;">
                            Ce choix est définitif pour ce cycle. Il génère automatiquement les frais d'inscription et la durée du cursus.
                        </p>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        {{-- Niveau --}}
                        <div>
                            <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">Niveau <span style="color:#dc2626;">*</span></label>
                            <select name="level_id" required style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none;">
                                <option value="">-- Niveau --</option>
                                @foreach(\App\Models\Level::all() as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Groupe --}}
                        <div>
                            <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:7px;">Groupe</label>
                            <select name="group_id" style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; font-size:13px; color:#0f172a; outline:none;">
                                <option value="">-- En attente d'affectation --</option>
                                @foreach(\App\Models\Group::where('centre_id', auth()->user()->centre_id)->get() as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOUTONS ACTION --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('students.index') }}"
                   style="display:inline-flex; align-items:center; gap:7px; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; color:#64748b; background:white; border:1.5px solid #e2e8f0; text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Annuler
                </a>

                <button type="submit"
                        style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:12px; font-size:13px; font-weight:900; color:white; background:#1e40af; border:none; cursor:pointer; box-shadow:0 4px 14px rgba(30,64,175,0.3); text-transform:uppercase; letter-spacing:0.05em;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Initialiser le cycle et créer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>