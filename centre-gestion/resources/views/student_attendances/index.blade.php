<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Appel — Sélection du groupe
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Choisissez une classe pour prendre la présence
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-[12px] text-slate-500 font-semibold">
            Vous pouvez aussi passer par le dashboard professeur pour signaler votre présence.
        </p>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($groups as $group)
                <a href="{{ route('student_attendances.create', $group) }}"
                   class="block rounded-2xl border border-slate-100 p-4 hover:border-indigo-200 hover:bg-indigo-50 transition">
                    <p class="font-extrabold text-slate-900">{{ $group->name }}</p>
                    @if($group->level)
                        <p class="text-[12px] text-slate-500 font-semibold mt-1">{{ $group->level->name }}</p>
                    @endif
                    <div class="mt-3 flex gap-2 flex-wrap">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700">Prendre présence</span>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-50 text-slate-500">Aujourd’hui</span>
                    </div>
                </a>
            @empty
                <div class="py-10 text-center md:col-span-2 lg:col-span-3">
                    <p class="text-sm font-extrabold text-slate-700">Aucun groupe trouvé</p>
                    <p class="text-[12px] text-slate-400 font-semibold mt-1">Demandez une affectation au directeur.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

