<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Prise de présence — {{ $group->name }}
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Cochez les étudiants présents (insolvables déjà filtrés)
        </p>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mt-4">
        <form method="POST" action="{{ route('student_attendances.store', $group->id) }}" class="space-y-3">
            @csrf

            @forelse($students as $student)
                <div class="flex items-center gap-2">
                    <input id="student_{{ $student->id }}" type="checkbox"
                           name="students[{{ $student->id }}]"
                           value="1"
                           class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="student_{{ $student->id }}" class="text-sm font-semibold text-slate-800">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </label>
                </div>
            @empty
                <p class="text-sm text-slate-500">
                    Aucun étudiant solvable pour ce groupe pour l’instant.
                </p>
            @endforelse

            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                    Enregistrer la présence
                </button>
            </div>
        </form>
    </div>
</x-app-layout>