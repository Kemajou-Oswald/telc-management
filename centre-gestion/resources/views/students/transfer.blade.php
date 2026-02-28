<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('students.show', $student) }}" style="color:#94a3b8; text-decoration:none;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 style="font-size:20px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Promotion & Transfert</h1>
                <p style="font-size:12px; color:#94a3b8; font-weight:600;">{{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        </div>
    </x-slot>

    <div style="max-width:600px; margin:30px auto;">
        
        {{-- ALERTE WORKFLOW FINANCIER --}}
        <div style="background:#fff7ed; border:1px solid #ffedd5; border-radius:15px; padding:15px; margin-bottom:20px; display:flex; gap:12px; align-items:flex-start;">
            <div style="color:#f97316; margin-top:2px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p style="font-size:12px; color:#9a3412; font-weight:700; margin:0; line-height:1.5;">
                Attention : Ce transfert réinitialise le cycle financier de l'élève. 
                Une nouvelle inscription sera exigée pour valider son entrée dans le nouveau programme.
            </p>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; box-shadow:0 10px 25px rgba(0,0,0,0.05); overflow:hidden;">
            
            {{-- État Actuel --}}
            <div style="padding:20px 25px; background:#f8fafc; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:15px;">
                <div style="width:40px; height:40px; background:linear-gradient(135deg, #1e40af, #3b82f6); color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                </div>
                <div>
                    <p style="font-size:10px; color:#94a3b8; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; margin:0;">Cycle actuel terminé</p>
                    <p style="font-size:13px; font-weight:700; color:#0f172a; margin:0;">{{ $student->level->name ?? 'N/A' }} — {{ $student->group->name ?? 'Sans groupe' }}</p>
                </div>
            </div>

            <form action="{{ route('students.transfer', $student) }}" method="POST" style="padding:25px;">
                @csrf
                <div style="display:flex; flex-direction:column; gap:22px;">
                    
                    {{-- Nouveau Niveau --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:8px;">Nouveau Niveau Académique</label>
                        <select name="new_level_id" required style="width:100%; padding:12px; border-radius:11px; border:1.5px solid #e2e8f0; font-size:13px; font-weight:700; color:#0f172a; outline:none; cursor:pointer;">
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $student->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NOUVEAU : Tarif et Programme (Obligatoire pour réinitialiser le solde) --}}
                    <div style="padding:15px; background:#f0f9ff; border:1.5px dashed #bae6fd; border-radius:12px;">
                        <label style="display:block; font-size:11px; font-weight:800; color:#0369a1; text-transform:uppercase; margin-bottom:8px;">Nouveau Tarif & Cursus associé <span style="color:#ef4444;">*</span></label>
                        <select name="new_tuition_fee_id" required style="width:100%; padding:10px; border-radius:10px; border:1.5px solid #7dd3fc; font-size:13px; font-weight:800; color:#0c4a6e; cursor:pointer; outline:none;">
                            <option value="">-- Sélectionner le tarif du nouveau niveau --</option>
                            @foreach($tuitionFees as $fee)
                                <option value="{{ $fee->id }}">
                                    {{ $fee->label }} ({{ number_format($fee->total_amount, 0, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nouveau Groupe --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Assignation Nouveau Groupe</label>
                        <select name="new_group_id" required style="width:100%; padding:12px; border-radius:11px; border:1.5px solid #e2e8f0; font-size:13px; font-weight:700; color:#0f172a; outline:none;">
                            <option value="">-- Choisir le groupe de destination --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->name }} ({{ $group->centre->name ?? '...' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Motif --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Justificatif du transfert</label>
                        <textarea name="reason" rows="2" required placeholder="Ex: Réussite niveau A1, promotion vers A2..." 
                                  style="width:100%; padding:12px; border-radius:11px; border:1.5px solid #e2e8f0; font-size:13px; font-weight:600; outline:none; resize:none;"></textarea>
                    </div>

                    <button type="submit" style="width:100%; padding:16px; background:#0f172a; color:white; border-radius:12px; font-size:13px; font-weight:900; text-transform:uppercase; letter-spacing:0.05em; border:none; cursor:pointer; margin-top:5px; box-shadow:0 10px 15px -3px rgba(15, 23, 42, 0.2);">
                        Valider la promotion et réinitialiser le cycle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>