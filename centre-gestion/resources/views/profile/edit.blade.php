<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Mon profil
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Informations du compte et mot de passe
        </p>
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-xs text-emerald-800 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-800 font-semibold">
                    Veuillez corriger les erreurs ci-dessous.
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('name') border-rose-400 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('email') border-rose-400 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nouveau mot de passe</label>
                        <input type="password" name="password"
                               class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 @error('password') border-rose-400 @enderror"
                               placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Confirmation</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2"
                               placeholder="Répéter le mot de passe">
                    </div>
                    @error('password')
                        <div class="md:col-span-2">
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-sm text-slate-600">
            <p class="font-semibold text-slate-800 mb-1">Rôle actuel :</p>
            <p class="text-xs inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-extrabold uppercase tracking-wide">
                {{ $user->role ?? 'N/A' }}
            </p>
        </div>
    </div>
</x-app-layout>

