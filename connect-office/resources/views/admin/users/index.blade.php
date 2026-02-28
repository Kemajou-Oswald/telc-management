@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto animate-fade-in-up space-y-10">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-1.428A14.99 14.99 0 013 12V7l8-3 8 3v4c0 1.256-.233 2.457-.657 3.574m-4.757 1.413A11.042 11.042 0 0112 18a11.042 11.042 0 01-4.591-1.013M12 11a3 3 0 110-6 3 3 0 010 6z"/></svg>
                Gouvernance / Équipe
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Gestion des accès
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium text-lg">
                Supervisez les permissions et la sécurité de l'infrastructure Connect Office.
            </p>
        </div>

        {{-- SEUL LE SUPER ADMIN PEUT AJOUTER UN MEMBRE --}}
        @if(auth()->user()->role === 'super_admin')
        <a href="{{ route('admin.users.create') }}"
           class="group flex items-center gap-3 bg-gradient-to-r from-brand-blue to-brand-green text-white px-8 py-4 rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span class="font-bold tracking-wide">Ajouter un membre</span>
        </a>
        @endif
    </div>

    <!-- STATS CARDS (Visibles pour le Super Admin) -->
    @if(auth()->user()->role === 'super_admin')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Total -->
        <div class="bg-white dark:bg-darkCard p-8 rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden group">
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-[1.5rem] flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Équipe Totale</p>
                    <h4 class="text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $users->total() }}</h4>
                </div>
            </div>
        </div>

        <!-- Super Admins -->
        <div class="bg-white dark:bg-darkCard p-8 rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden group">
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-[1.5rem] flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Haut Commandement</p>
                    <h4 class="text-3xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ $users->where('role','super_admin')->count() }}</h4>
                </div>
            </div>
        </div>

        <!-- Actifs -->
        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-brand-green text-white rounded-[1.5rem] flex items-center justify-center shadow-lg shadow-brand-green/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Disponibilité</p>
                    <h4 class="text-3xl font-black text-brand-green mt-1">{{ $users->where('is_active',1)->count() }} <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Actifs</span></h4>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- TABLEAU D'ADMINS -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        
        <!-- Filtre -->
        <div class="px-8 py-6 border-b border-slate-50 dark:border-slate-800">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher par nom, email..."
                           class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl pl-12 pr-4 py-3.5 text-sm focus:ring-2 focus:ring-brand-blue/30 dark:text-white transition-all">
                </div>
                <button type="submit" class="bg-brand-blue hover:bg-brand-darkBlue text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-md">Filtrer</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50">
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">Profil Administrateur</th>
                        <th class="px-8 py-6">Privilèges</th>
                        <th class="px-8 py-6 text-center">Accès</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($users as $user)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-100 dark:border-slate-700">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-tr from-brand-blue to-brand-green flex items-center justify-center text-white font-bold">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                        {{ $user->name }}
                                        @if(auth()->id() === $user->id) <span class="px-2 py-0.5 bg-brand-green/10 text-brand-green text-[9px] font-black uppercase rounded-md">Moi</span> @endif
                                    </h3>
                                    <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <span class="text-[10px] font-black uppercase {{ $user->role === 'super_admin' ? 'text-purple-600' : 'text-brand-blue' }} tracking-widest">
                                {{ $user->role === 'super_admin' ? 'Propriétaire' : 'Manager' }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $user->is_active ? 'bg-green-50 text-brand-green border-brand-green/20' : 'bg-red-50 text-red-500 border-red-500/20' }}">
                                {{ $user->is_active ? 'Opérationnel' : 'Suspendu' }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                
                                {{-- MODIFIER : Super Admin OU Soi-même --}}
                                @if(auth()->user()->role === 'super_admin' || auth()->id() === $user->id)
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-brand-blue hover:text-white rounded-xl transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                @endif

                                {{-- SUPPRIMER : STRICTEMENT Super Admin (et pas soi-même) --}}
                                @if(auth()->user()->role === 'super_admin' && auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" onclick="openDeleteModal({{ $user->id }})" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-32 text-center text-slate-400">Aucun accès trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="mt-10 px-4">{{ $users->links() }}</div>
    @endif

</div>

<!-- MODAL DE SUPPRESSION -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
            <div class="px-8 pt-8 pb-6">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 mx-auto bg-red-100 rounded-2xl dark:bg-red-900/20 sm:mx-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-6 sm:text-left">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter">Révoquer l'accès</h3>
                        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">Voulez-vous vraiment supprimer ce membre ?</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 px-8 pb-8 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-4 text-sm font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl">Annuler</button>
                <button type="button" onclick="executeDelete()" class="px-8 py-4 text-sm font-black text-white uppercase bg-red-500 rounded-2xl shadow-lg active:scale-95 transition-all">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
    let userIdToDelete = null;
    function openDeleteModal(id) {
        userIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        userIdToDelete = null;
    }
    function executeDelete() {
        if (userIdToDelete) {
            const form = document.getElementById('delete-form-' + userIdToDelete);
            if (form) form.submit();
        }
    }
</script>
@endsection