<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:8px;">
            <h2 style="font-size:16px;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin:0;">
                {{ $student->first_name }} {{ $student->last_name }}
            </h2>
            <span style="font-size:10px;color:#94a3b8;font-weight:500;">#{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </x-slot>

    @php 
        $rate = $student->attendanceRate();
        $balance = $student->getTuitionBalance();
        $progress = $student->getCycleProgressPercentage();
        $expiration = $student->getCycleExpirationDate();
        $isExpired = $student->isCycleExpired();
        $paidReg = $student->hasPaidRegistration();
        $regRequired = optional($student->tuitionFee)->inscription_fee ?? 10000;
    @endphp

    <style>
        @keyframes fadeUp   { from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);} }
        @keyframes barGrow  { from{width:0;}to{width:var(--w);} }
        
        .hero {
            position:relative; overflow:hidden;
            background:linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #1e40af 100%);
            margin:-24px -1.75rem 0; padding:32px 1.75rem 80px;
        }
        .hero-avatar-ring {
            width:80px;height:80px;border-radius:22px;
            background:linear-gradient(135deg,{{ $isExpired ? '#f59e0b' : '#1e40af' }},{{ $isExpired ? '#d97706' : '#3b82f6' }});
            display:flex;align-items:center;justify-content:center;
            color:white;font-size:26px;font-weight:900;flex-shrink:0;
            box-shadow:0 8px 28px rgba(30,64,175,0.3);
        }
        .hero-chip {
            display:inline-flex;align-items:center;gap:5px;
            padding:5px 10px;border-radius:7px;font-size:10px;font-weight:700;
            background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);
            color:rgba(255,255,255,0.8);
        }
        .stat-strip {
            position:relative;z-index:10; background:white;border-radius:18px;
            box-shadow:0 8px 32px rgba(0,0,0,0.08); border:1px solid #f1f5f9;
            display:grid;grid-template-columns:repeat(4,1fr);
            overflow:hidden; margin:-40px 0 24px; animation:fadeUp 0.35s ease both;
        }
        .strip-item { padding:16px 20px;display:flex;align-items:center;gap:12px;border-right:1px solid #f1f5f9; }
        .card { background:white;border-radius:20px;border:1px solid #f1f5f9;box-shadow:0 2px 12px rgba(0,0,0,0.03);overflow:hidden; }
        .mini-cell { padding:15px;background:#f8fafc;border-radius:14px;border:1px solid #f1f5f9; }
        .mini-label { font-size:9px;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:6px; }
    </style>

    {{-- HERO SECTION --}}
    <div class="hero">
        <div style="margin-bottom:20px;position:relative;z-index:1;">
            <a href="{{ route('students.index') }}" style="display:inline-flex;padding:8px;background:rgba(255,255,255,0.1);border-radius:10px;color:white;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;">
            <div style="display:flex;align-items:center;gap:24px;">
                <div class="hero-avatar-ring">
                    {{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
                </div>
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                        @if($isExpired)
                            <span style="font-size:9px;font-weight:900;padding:4px 10px;background:#f59e0b;color:white;border-radius:6px;text-transform:uppercase;letter-spacing:0.05em;">Cycle Terminé</span>
                        @else
                            <span style="font-size:9px;font-weight:900;padding:4px 10px;background:#16a34a;color:white;border-radius:6px;text-transform:uppercase;letter-spacing:0.05em;">Cycle en cours</span>
                        @endif
                        
                        @if($balance <= 0 && $paidReg)
                            <span style="font-size:9px;font-weight:900;padding:4px 10px;background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.2);border-radius:6px;text-transform:uppercase;">Soldé ✓</span>
                        @endif
                    </div>
                    <h1 style="font-size:32px;font-weight:900;color:white;letter-spacing:-0.03em;margin:0 0 12px;line-height:1;">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>
                    <div style="display:flex;gap:8px;">
                        <span class="hero-chip">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                            {{ optional($student->centre)->name }}
                        </span>
                        <span class="hero-chip">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                            {{ optional($student->level)->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <a href="{{ route('students.transfer.form', $student) }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:rgba(255,255,255,0.1);color:white;border-radius:14px;font-size:12px;font-weight:800;text-decoration:none;border:1px solid rgba(255,255,255,0.15);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Promouvoir / Transférer
                </a>
                <a href="{{ route('students.edit', $student) }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:white;color:#1e40af;border-radius:14px;font-size:12px;font-weight:900;text-decoration:none;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>

    {{-- STAT STRIP --}}
    <div class="stat-strip">
        <div class="strip-item">
            <div style="width:36px;height:36px;border-radius:10px;background:#10b981;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="mini-label">Présence</p>
                <p style="font-size:16px;font-weight:900;margin:0;">{{ $rate }}%</p>
            </div>
        </div>
        <div class="strip-item">
            <div style="width:36px;height:36px;border-radius:10px;background:#3b82f6;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="mini-label">Groupe Actuel</p>
                <p style="font-size:16px;font-weight:900;margin:0;">{{ optional($student->group)->name ?? 'Non assigné' }}</p>
            </div>
        </div>
        <div class="strip-item">
            <div style="width:36px;height:36px;border-radius:10px;background:#f59e0b;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
            </div>
            <div>
                <p class="mini-label">Dette Scolarité</p>
                <p style="font-size:16px;font-weight:900;color:{{ $balance > 0 ? '#dc2626' : '#16a34a' }};margin:0;">{{ number_format($balance, 0, ',', ' ') }} F</p>
            </div>
        </div>
        <div class="strip-item">
            <div style="width:36px;height:36px;border-radius:10px;background:#7c3aed;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="mini-label">Temps Écoulé</p>
                <p style="font-size:16px;font-weight:900;margin:0;color:{{ $isExpired ? '#dc2626' : '#0f172a' }};">{{ $progress }}%</p>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;">
        <div style="display:flex;flex-direction:column;gap:24px;">
            
            {{-- SUIVI DU CYCLE --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:10px;background:#fdf2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">Progression du Cycle</p>
                    </div>
                    @if($expiration)
                        <span style="font-size:11px;font-weight:800;color:#64748b;">Fin prévue : {{ $expiration->format('d/m/Y') }}</span>
                    @endif
                </div>

                <div style="position:relative;padding:10px 0;">
                    <div style="height:12px;background:#f1f5f9;border-radius:99px;overflow:hidden;margin-bottom:12px;">
                        <div style="height:100%;background:{{ $isExpired ? '#dc2626' : '#3b82f6' }};width:{{ $progress }}%;border-radius:99px;transition:width 1s;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <p style="font-size:12px;font-weight:700;color:#475569;">Début : {{ \Carbon\Carbon::parse($student->registration_date)->format('d/m/Y') }}</p>
                        @if($isExpired)
                            <p style="font-size:11px;font-weight:900;color:#dc2626;text-transform:uppercase;animation:pulse 2s infinite;">Temps dépassé — Promotion requise</p>
                        @else
                            <p style="font-size:12px;font-weight:700;color:#16a34a;">Cycle sain</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ACADÉMIQUE --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:#eff6ff;color:#1e40af;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <p style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">Cursus en cours</p>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:15px;">
                    <div class="mini-cell">
                        <p class="mini-label">Cours</p>
                        <p style="font-size:14px;font-weight:900;margin:0;color:#1e40af;">{{ optional($student->tuitionFee)->label ?? 'Non défini' }}</p>
                    </div>
                    <div class="mini-cell">
                        <p class="mini-label">Niveau</p>
                        <p style="font-size:14px;font-weight:900;margin:0;">{{ optional($student->level)->name }}</p>
                    </div>
                    <div class="mini-cell">
                        <p class="mini-label">Professeur</p>
                        <p style="font-size:14px;font-weight:900;margin:0;color:#475569;">{{ optional($student->group->teacher)->name ?? '—' }}</p>
                    </div>
                </div>
                @if($student->last_transfer_reason)
                    <div style="margin-top:15px;padding:12px;background:#fffbeb;border-radius:10px;border:1px solid #fef3c7;">
                        <p class="mini-label" style="color:#b45309;">Motif du dernier transfert</p>
                        <p style="font-size:12px;font-weight:600;color:#92400e;margin:0;">{{ $student->last_transfer_reason }}</p>
                    </div>
                @endif
            </div>

            {{-- FINANCES --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:10px;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">Situation Financière Cycle</p>
                    </div>
                    <a href="{{ route('students.payments.show', $student) }}" style="font-size:11px;font-weight:800;color:#1e40af;text-decoration:none;display:flex;align-items:center;gap:4px;">
                        Historique complet <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div style="padding:20px;border-radius:18px;background:{{ $paidReg ? '#f0fdf4' : '#fef2f2' }};border:1.5px solid {{ $paidReg ? '#bbf7d0' : '#fecaca' }};">
                        <p class="mini-label" style="color:{{ $paidReg ? '#16a34a' : '#dc2626' }}">Frais d'inscription</p>
                        <p style="font-size:22px;font-weight:900;color:#0f172a;margin:0;">{{ number_format($regRequired, 0, ',', ' ') }} <span style="font-size:10px;font-weight:600;">F</span></p>
                        <div style="display:flex;align-items:center;gap:5px;margin-top:8px;">
                            <div style="width:6px;height:6px;border-radius:50%;background:{{ $paidReg ? '#16a34a' : '#dc2626' }};"></div>
                            <span style="font-size:10px;font-weight:900;color:{{ $paidReg ? '#16a34a' : '#dc2626' }};text-transform:uppercase;">{{ $paidReg ? 'Confirmée' : 'Impayée' }}</span>
                        </div>
                    </div>
                    <div style="padding:20px;border-radius:18px;background:#f8fafc;border:1.5px solid #f1f5f9;">
                        <p class="mini-label">Pension Restante</p>
                        <p style="font-size:22px;font-weight:900;color:{{ $balance > 0 ? '#b45309' : '#16a34a' }};margin:0;">{{ number_format($balance, 0, ',', ' ') }} <span style="font-size:10px;font-weight:600;">F</span></p>
                        <p style="font-size:10px;font-weight:700;color:#94a3b8;margin:8px 0 0;">Total Cursus : {{ number_format(optional($student->tuitionFee)->total_amount ?? 0, 0, ',', ' ') }} F</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:24px;">
            <div class="card" style="background:#0f172a;color:white;padding:24px;position:relative;">
                <div style="position:absolute;top:0;right:0;width:100px;height:100px;background:radial-gradient(circle,rgba(59,130,246,0.15),transparent 70%);"></div>
                <p class="mini-label" style="color:rgba(255,255,255,0.4)">Contact & Support</p>
                <div style="margin-top:20px;display:flex;flex-direction:column;gap:18px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <p style="font-size:14px;font-weight:800;letter-spacing:0.02em;">{{ $student->phone ?? '—' }}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:white;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <p style="font-size:13px;font-weight:700;word-break:break-all;color:rgba(255,255,255,0.8);">{{ $student->email ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="card" style="padding:24px;">
                <p class="mini-label">Résumé Global</p>
                <div style="margin-top:15px;display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;">Total Historique</span>
                        <span style="font-size:13px;font-weight:900;color:#0f172a;">{{ number_format($student->totalPaid(), 0, ',', ' ') }} F</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;">Status</span>
                        <span style="font-size:11px;font-weight:900;color:{{ $student->status == 'actif' ? '#16a34a' : '#94a3b8' }};text-transform:uppercase;">{{ $student->status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>