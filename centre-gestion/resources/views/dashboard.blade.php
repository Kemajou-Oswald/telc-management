<x-app-layout>
<style>
@media (max-width: 1024px) {
  .dash-bottom-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 640px) {
  .dash-stats-grid  { grid-template-columns: 1fr 1fr !important; gap: 12px !important; }
  .dash-bottom-grid { grid-template-columns: 1fr !important; }
  .dash-header-row  { flex-direction: column !important; align-items: flex-start !important; gap: 10px !important; }
  .qa-item          { padding: 10px 12px !important; }
}
@media (max-width: 400px) {
  .dash-stats-grid  { grid-template-columns: 1fr !important; }
}
</style>
    <x-slot name="header">
        <div style="padding: 20px 0;">
            <div class="dash-header-row" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="width:5px; height:44px; background:#dc2626; border-radius:99px; display:block; flex-shrink:0;"></span>
                    <div>
                        <h2 style="font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.03em; line-height:1.15; margin:0;">
                            Tableau de Bord
                        </h2>
                        <p style="font-size:12px; color:#94a3b8; font-weight:500; margin:3px 0 0 0;">
                            Vue d'ensemble · {{ now()->translatedFormat('l d F Y') }}
                        </p>
                    </div>
                </div>
                {{-- Badge statut --}}
                <div style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:99px;">
                    <span style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:block;"></span>
                    <span style="font-size:11px;font-weight:700;color:#16a34a;">Système opérationnel</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 mt-4">

        {{-- ===== CARDS STATS ===== --}}
        <div class="dash-stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Étudiants --}}
            <div style="background:white; border-radius:18px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:22px; position:relative; overflow:hidden; transition:box-shadow 0.2s;"
                 onmouseenter="this.style.boxShadow='0 6px 24px rgba(30,64,175,0.12)'"
                 onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,0.04)'">
                {{-- Fond décoratif --}}
                <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:#eff6ff;border-radius:50%;"></div>
                <div style="position:relative;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                        <div style="width:44px;height:44px;background:linear-gradient(135deg,#1e40af,#3b82f6);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(30,64,175,0.3);">
                            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span style="font-size:11px;font-weight:800;color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:3px 10px;border-radius:99px;">+12%</span>
                    </div>
                    <p style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">Étudiants Actifs</p>
                    <p style="font-size:32px;font-weight:900;color:#0f172a;margin:0;line-height:1;">1</p>
                    <div style="margin-top:12px;height:3px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:12%;background:linear-gradient(90deg,#1e40af,#3b82f6);border-radius:99px;"></div>
                    </div>
                    <p style="font-size:10px;color:#94a3b8;margin:5px 0 0;font-weight:500;">12% de l'objectif mensuel</p>
                </div>
            </div>

            {{-- Revenus --}}
            <div style="background:white; border-radius:18px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:22px; position:relative; overflow:hidden; transition:box-shadow 0.2s;"
                 onmouseenter="this.style.boxShadow='0 6px 24px rgba(5,150,105,0.12)'"
                 onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,0.04)'">
                <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:#f0fdf4;border-radius:50%;"></div>
                <div style="position:relative;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                        <div style="width:44px;height:44px;background:linear-gradient(135deg,#059669,#34d399);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(5,150,105,0.3);">
                            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span style="font-size:11px;font-weight:800;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;padding:3px 10px;border-radius:99px;">Ce mois</span>
                    </div>
                    <p style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">Revenus Totaux</p>
                    <p style="font-size:28px;font-weight:900;color:#0f172a;margin:0;line-height:1;">0 <span style="font-size:14px;font-weight:600;color:#94a3b8;">FCFA</span></p>
                    <div style="margin-top:12px;height:3px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:0%;background:linear-gradient(90deg,#059669,#34d399);border-radius:99px;"></div>
                    </div>
                    <p style="font-size:10px;color:#94a3b8;margin:5px 0 0;font-weight:500;">Aucun paiement reçu</p>
                </div>
            </div>

            {{-- Dépenses --}}
            <div style="background:white; border-radius:18px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:22px; position:relative; overflow:hidden; transition:box-shadow 0.2s;"
                 onmouseenter="this.style.boxShadow='0 6px 24px rgba(220,38,38,0.1)'"
                 onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,0.04)'">
                <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:#fef2f2;border-radius:50%;"></div>
                <div style="position:relative;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                        <div style="width:44px;height:44px;background:linear-gradient(135deg,#dc2626,#f87171);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(220,38,38,0.3);">
                            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span style="font-size:11px;font-weight:800;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;padding:3px 10px;border-radius:99px;">↑ Actif</span>
                    </div>
                    <p style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">Dépenses</p>
                    <p style="font-size:28px;font-weight:900;color:#0f172a;margin:0;line-height:1;">30 000 <span style="font-size:14px;font-weight:600;color:#94a3b8;">FCFA</span></p>
                    <div style="margin-top:12px;height:3px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:65%;background:linear-gradient(90deg,#dc2626,#f87171);border-radius:99px;"></div>
                    </div>
                    <p style="font-size:10px;color:#dc2626;margin:5px 0 0;font-weight:600;">Dépasse les revenus</p>
                </div>
            </div>

            {{-- Bénéfice Net --}}
            <div style="background:linear-gradient(135deg,#1e3a8a 0%,#1e40af 60%,#2563eb 100%); border-radius:18px; border:none; box-shadow:0 6px 24px rgba(30,64,175,0.35); padding:22px; position:relative; overflow:hidden; transition:box-shadow 0.2s;"
                 onmouseenter="this.style.boxShadow='0 10px 32px rgba(30,64,175,0.45)'"
                 onmouseleave="this.style.boxShadow='0 6px 24px rgba(30,64,175,0.35)'">
                <div style="position:absolute;top:-30px;right:-30px;width:100px;height:100px;background:rgba(255,255,255,0.06);border-radius:50%;"></div>
                <div style="position:absolute;bottom:-20px;left:-20px;width:70px;height:70px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
                <div style="position:relative;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                        <div style="width:44px;height:44px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,0.2);">
                            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span style="font-size:11px;font-weight:800;color:rgba(255,255,255,0.7);background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);padding:3px 10px;border-radius:99px;">Bilan</span>
                    </div>
                    <p style="font-size:10px;font-weight:800;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">Bénéfice Net</p>
                    <p style="font-size:28px;font-weight:900;color:#fca5a5;margin:0;line-height:1;">-30 000 <span style="font-size:14px;font-weight:600;color:rgba(255,255,255,0.5);">FCFA</span></p>
                    <div style="margin-top:12px;height:3px;background:rgba(255,255,255,0.15);border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:100%;background:linear-gradient(90deg,#fca5a5,#f87171);border-radius:99px;"></div>
                    </div>
                    <p style="font-size:10px;color:rgba(255,255,255,0.5);margin:5px 0 0;font-weight:500;">Déficit à combler</p>
                </div>
            </div>
        </div>

        {{-- ===== GRILLE INFÉRIEURE : Inscriptions + Activité rapide ===== --}}
        <div class="dash-bottom-grid grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Tableau dernières inscriptions (2/3) --}}
            <div class="lg:col-span-2" style="background:white; border-radius:18px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,0.04); overflow:hidden;">
                <div style="padding:18px 24px; border-bottom:1px solid #f8fafc; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <h3 style="font-size:14px;font-weight:800;color:#0f172a;margin:0;">Dernières Inscriptions</h3>
                        <p style="font-size:11px;color:#94a3b8;font-weight:500;margin:2px 0 0;">Nouveaux apprenants enregistrés</p>
                    </div>
                    <a href="{{ route('students.index') }}"
                       style="font-size:12px;font-weight:700;color:#1e40af;text-decoration:none;display:inline-flex;align-items:center;gap:4px;padding:6px 12px;background:#eff6ff;border-radius:8px;border:1px solid #bfdbfe;transition:all 0.15s;"
                       onmouseenter="this.style.background='#1e40af';this.style.color='white';this.style.borderColor='#1e40af'"
                       onmouseleave="this.style.background='#eff6ff';this.style.color='#1e40af';this.style.borderColor='#bfdbfe'">
                        Voir tout
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                {{-- État vide illustré --}}
                <div style="padding:60px 24px;text-align:center;">
                    <div style="width:64px;height:64px;background:#f8fafc;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;border:1px solid #f1f5f9;">
                        <svg width="28" height="28" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p style="font-size:13px;font-weight:700;color:#64748b;margin:0 0 4px;">Aucune inscription récente</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Les nouvelles inscriptions apparaîtront ici</p>
                    @if(auth()->user()->role !== 'professeur')
                        <a href="{{ route('students.create') }}"
                           style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;padding:8px 16px;background:#1e40af;color:white;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none;box-shadow:0 3px 10px rgba(30,64,175,0.25);"
                           onmouseenter="this.style.background='#dc2626'"
                           onmouseleave="this.style.background='#1e40af'">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Ajouter un étudiant
                        </a>
                    @endif
                </div>
            </div>

            {{-- Sidebar : Actions rapides + Alertes (1/3) --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Actions rapides --}}
                <div style="background:white; border-radius:18px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,0.04); padding:20px;">
                    <h3 style="font-size:13px;font-weight:800;color:#0f172a;margin:0 0 14px;">Accès Rapides</h3>
                    <div style="display:flex;flex-direction:column;gap:8px;">

                        @if(auth()->user()->role !== 'professeur')
                        <a href="{{ route('students.create') }}"
                           style="display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:12px;text-decoration:none;background:#f8fafc;border:1px solid #f1f5f9;transition:all 0.15s;"
                           onmouseenter="this.style.background='#eff6ff';this.style.borderColor='#bfdbfe'"
                           onmouseleave="this.style.background='#f8fafc';this.style.borderColor='#f1f5f9'">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#1e40af,#3b82f6);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#334155;">Nouvel étudiant</span>
                        </a>
                        @endif

                        <a href="{{ route('students.index') }}"
                           style="display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:12px;text-decoration:none;background:#f8fafc;border:1px solid #f1f5f9;transition:all 0.15s;"
                           onmouseenter="this.style.background='#f0fdf4';this.style.borderColor='#bbf7d0'"
                           onmouseleave="this.style.background='#f8fafc';this.style.borderColor='#f1f5f9'">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#059669,#34d399);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#334155;">Voir l'annuaire</span>
                        </a>

                        <a href="#"
                           style="display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:12px;text-decoration:none;background:#f8fafc;border:1px solid #f1f5f9;transition:all 0.15s;"
                           onmouseenter="this.style.background='#fef2f2';this.style.borderColor='#fecaca'"
                           onmouseleave="this.style.background='#f8fafc';this.style.borderColor='#f1f5f9'">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#dc2626,#f87171);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#334155;">Rapport financier</span>
                        </a>

                    </div>
                </div>

                {{-- Alerte déficit --}}
                <div style="background:linear-gradient(135deg,#fef2f2,#fff7ed); border-radius:18px; border:1px solid #fecaca; padding:18px;">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="width:32px;height:32px;background:#dc2626;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                            <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:12px;font-weight:800;color:#dc2626;margin:0 0 3px;">Attention — Déficit détecté</p>
                            <p style="font-size:11px;color:#92400e;margin:0;line-height:1.5;">Les dépenses dépassent les revenus de <strong>30 000 FCFA</strong>. Vérifiez vos finances.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
