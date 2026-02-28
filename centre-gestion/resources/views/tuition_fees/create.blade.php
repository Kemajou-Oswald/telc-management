<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Nouveau cours (Gestion cours)
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Langue, type (standard / Vorbereitung), durée et tarifs
        </p>
    </x-slot>

    <div class="max-w-xl bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        @if($errors->any())
            <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-800 font-semibold">
                Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif

        <form method="POST" action="{{ route('tuition_fees.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Centre</label>
                <select name="centre_id"
                        class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('centre_id') border-rose-400 @enderror">
                    <option value="">(Tous centres)</option>
                    @foreach($centres as $centre)
                        <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                            {{ $centre->name }} — {{ $centre->city }}
                        </option>
                    @endforeach
                </select>
                @error('centre_id')
                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Niveau</label>
                    <select name="level_id"
                            class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('level_id') border-rose-400 @enderror"
                            required>
                        <option value="">Sélectionner…</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Langue</label>
                    <select name="language"
                            class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('language') border-rose-400 @enderror"
                            required>
                        @foreach(\App\Models\TuitionFee::languageOptions() as $value => $label)
                            <option value="{{ $value }}" {{ old('language') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('language')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Type de cours</label>
                    <select name="course_type"
                            class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('course_type') border-rose-400 @enderror">
                        @foreach(\App\Models\TuitionFee::courseTypes() as $value => $label)
                            <option value="{{ $value }}" {{ old('course_type', 'standard') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('course_type')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Durée (semaines)</label>
                    <input type="number" min="1" name="duration_weeks" value="{{ old('duration_weeks') }}"
                           placeholder="Ex : 12 ou 3 pour Vorbereitung"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('duration_weeks') border-rose-400 @enderror">
                    @error('duration_weeks')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Libellé durée (optionnel)</label>
                <input type="text" name="duration_label" value="{{ old('duration_label') }}"
                       placeholder="Ex : 3 semaines, 2 mois"
                       class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('duration_label') border-rose-400 @enderror">
                @error('duration_label')
                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Libellé du cours</label>
                <input type="text" name="label" value="{{ old('label') }}"
                       placeholder="Ex : A1 Allemand intensif soir"
                       class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('label') border-rose-400 @enderror"
                       required>
                @error('label')
                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Frais d'inscription</label>
                    <input type="number" step="0.01" name="inscription_fee" value="{{ old('inscription_fee', 10000) }}"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('inscription_fee') border-rose-400 @enderror">
                    @error('inscription_fee')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Montant total</label>
                    <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount') }}"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('total_amount') border-rose-400 @enderror"
                           required>
                    @error('total_amount')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Devise</label>
                    <input type="text" name="currency" value="{{ old('currency', 'FCFA') }}"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('currency') border-rose-400 @enderror">
                    @error('currency')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-3">
                <a href="{{ route('tuition_fees.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                    Enregistrer le cours
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

