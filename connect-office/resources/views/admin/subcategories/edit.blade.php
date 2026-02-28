@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z"/></svg>
                Édition / {{ $subcategory->name }}
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Modifier la sous-catégorie
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Ajustez les paramètres de cet élément du catalogue.</p>
        </div>

        <a href="{{ route('admin.subcategories.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- COLONNE PRINCIPALE (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
                <form action="{{ route('admin.subcategories.update', $subcategory) }}" method="POST" class="p-8 md:p-12 space-y-10">
                    @csrf
                    @method('PUT')

                    <!-- Section Formulaire -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Informations de l'élément</h2>
                        </div>

                        <div class="grid gap-8">
                            <!-- Champ Nom -->
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom de la sous-catégorie</label>
                                <input type="text" name="name" value="{{ old('name', $subcategory->name) }}" required
                                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                                @error('name')
                                    <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Champ Catégorie Parente -->
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Déplacer vers une autre catégorie</label>
                                <div class="relative">
                                    <select name="category_id" required
                                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bouton Submit -->
                    <div class="pt-6 border-t border-slate-50 dark:border-slate-800 flex items-center justify-end">
                        <button type="submit" 
                                class="group flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                            <span>Mettre à jour les données</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- COLONNE INFOS (1/3) -->
        <div class="space-y-6">
            
            <!-- Carte Statut / Recap -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-brand-green/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-700"></div>
                
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Emplacement Actuel</h3>
                
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-brand-green"></div>
                        <span class="text-sm font-bold text-slate-300">Catégorie :</span>
                        <span class="text-sm font-black text-white">{{ $subcategory->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-3 pl-5 border-l-2 border-slate-700">
                        <div class="w-2 h-2 rounded-full bg-brand-blue shadow-[0_0_8px_#1E98C5]"></div>
                        <span class="text-sm font-bold text-slate-300">Sous-cat :</span>
                        <span class="text-sm font-black text-white">{{ $subcategory->name }}</span>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-white/5 text-[10px] text-slate-500 italic">
                    Dernière modification le {{ $subcategory->updated_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <!-- Aide Contextuelle -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider mb-2">Note sur le déplacement</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                    Si vous changez la catégorie parente, tous les produits liés à cette sous-catégorie seront automatiquement déplacés vers la nouvelle structure.
                </p>
            </div>

        </div>

    </div>
</div>
@endsection