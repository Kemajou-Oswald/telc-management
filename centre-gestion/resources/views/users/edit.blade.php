<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Modifier un membre
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            {{ $user->name }} · {{ $user->role }}
        </p>
    </x-slot>

    <style>
        .form-wrap { max-width:680px; }
        .form-card {
            background:white; border:1px solid rgba(15,14,61,0.07);
            border-radius:20px; box-shadow:0 2px 16px rgba(15,14,61,0.05); overflow:hidden;
        }
        .form-header {
            padding:20px 26px 18px; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; gap:12px;
        }
        .form-body { padding:26px; }
        .form-section { margin-bottom:26px; }
        .form-section-label {
            font-size:9.5px; font-weight:900; color:rgba(29,27,132,0.4);
            text-transform:uppercase; letter-spacing:0.18em;
            margin-bottom:14px; display:flex; align-items:center; gap:8px;
        }
        .form-section-label::after { content:''; flex:1; height:1px; background:rgba(29,27,132,0.07); }
        .erp-label {
            display:block; font-size:10.5px; font-weight:800;
            color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;
        }
        .erp-input {
            width:100%; border:1.5px solid rgba(15,14,61,0.1); border-radius:11px;
            font-size:13px; padding:10px 13px; font-family:inherit;
            color:#0f172a; background:rgba(15,14,61,0.02); outline:none; transition:all 0.18s ease;
            box-sizing:border-box;
        }
        .erp-input::placeholder { color:#94a3b8; }
        .erp-input:focus { border-color:#1D1B84; background:white; box-shadow:0 0 0 3px rgba(29,27,132,0.08); }
        .erp-input-error { border-color:#E31E24 !important; background:rgba(227,30,36,0.02) !important; }
        .erp-select {
            width:100%; border:1.5px solid rgba(15,14,61,0.1); border-radius:11px;
            font-size:13px; padding:10px 13px; font-family:inherit;
            color:#0f172a; background:white; outline:none; transition:all 0.18s; cursor:pointer; box-sizing:border-box;
        }
        .erp-select:focus { border-color:#1D1B84; box-shadow:0 0 0 3px rgba(29,27,132,0.08); }
        .field-error { margin-top:5px; font-size:11.5px; font-weight:700; color:#E31E24; display:flex; align-items:center; gap:5px; }
        .input-icon-wrap { position:relative; }
        .input-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; }
        .input-icon + .erp-input { padding-left:36px; }
        .error-banner {
            margin-bottom:20px; background:rgba(227,30,36,0.05);
            border:1px solid rgba(227,30,36,0.2); border-radius:12px;
            padding:12px 16px; font-size:12.5px; font-weight:700; color:#E31E24;
            display:flex; align-items:center; gap:9px;
        }
        .btn-primary {
            display:inline-flex; align-items:center; gap:8px;
            padding:11px 22px;
            background:linear-gradient(135deg,#1D1B84 0%,#2d2bb0 100%);
            color:white; font-size:13px; font-weight:800; font-family:inherit;
            border:none; border-radius:12px; cursor:pointer;
            transition:all 0.2s; box-shadow:0 3px 16px rgba(29,27,132,0.3); white-space:nowrap;
        }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 22px rgba(29,27,132,0.38); }
        .btn-cancel {
            display:inline-flex; align-items:center; gap:7px;
            padding:11px 20px; font-size:13px; font-weight:700; font-family:inherit;
            color:#475569; background:white; border:1.5px solid #e2e8f0;
            border-radius:12px; text-decoration:none; transition:all 0.15s;
        }
        .btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        @media(max-width:640px){ .grid-2 { grid-template-columns:1fr; } }

        /* Carte profil en haut */
        .profile-preview {
            background:linear-gradient(135deg,rgba(29,27,132,0.04) 0%,rgba(227,30,36,0.03) 100%);
            border:1px solid rgba(29,27,132,0.08); border-radius:16px;
            padding:18px 20px; display:flex; align-items:center; gap:14px;
            margin-bottom:26px;
        }

        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .a1{animation:fadeUp 0.4s 0.05s cubic-bezier(0.22,1,0.36,1) both}
        .a2{animation:fadeUp 0.4s 0.14s cubic-bezier(0.22,1,0.36,1) both}
    </style>

    <div class="form-wrap" style="margin-top:4px;">

        {{-- Fil d'ariane --}}
        <div class="a1" style="display:flex; align-items:center; gap:8px; margin-bottom:18px; font-size:12px; font-weight:600; color:#94a3b8;">
            <a href="{{ route('users.index') }}" style="color:#1D1B84; text-decoration:none; font-weight:700; transition:color 0.15s;"
               onmouseenter="this.style.color='#E31E24'" onmouseleave="this.style.color='#1D1B84'">
                Personnel
            </a>
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span>Modifier</span>
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span style="color:#0f172a; font-weight:800;">{{ $user->name }}</span>
        </div>

        <div class="form-card a2">
            <div class="form-header">
                <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,#E31E24,#1D1B84); display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:900; color:white; flex-shrink:0; box-shadow:0 4px 14px rgba(29,27,132,0.25);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p style="font-size:15px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">{{ $user->name }}</p>
                    <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">Modification du compte · créé le {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="form-body">

                @if($errors->any())
                    <div class="error-banner">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Veuillez corriger les erreurs ci-dessous.
                    </div>
                @endif

                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    {{-- SECTION : Identité --}}
                    <div class="form-section">
                        <p class="form-section-label">Identité</p>
                        <div style="display:flex; flex-direction:column; gap:14px;">

                            <div>
                                <label class="erp-label" for="name">Nom complet</label>
                                <div class="input-icon-wrap">
                                    <svg class="input-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <input type="text" id="name" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="erp-input @error('name') erp-input-error @enderror"
                                           placeholder="Nom complet" required>
                                </div>
                                @error('name')
                                    <p class="field-error">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="erp-label" for="email">Adresse email</label>
                                <div class="input-icon-wrap">
                                    <svg class="input-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="erp-input @error('email') erp-input-error @enderror"
                                           placeholder="email@cfpltara.com" required>
                                </div>
                                @error('email')
                                    <p class="field-error">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION : Affectation --}}
                    <div class="form-section">
                        <p class="form-section-label">Affectation</p>
                        <div class="grid-2">
                            <div>
                                <label class="erp-label" for="role">Rôle</label>
                                <select id="role" name="role"
                                        class="erp-select @error('role') erp-input-error @enderror" required>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="field-error">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="erp-label" for="centre_id">Centre</label>
                                <select id="centre_id" name="centre_id"
                                        class="erp-select @error('centre_id') erp-input-error @enderror">
                                    <option value="">(Aucun centre)</option>
                                    @foreach($centres as $centre)
                                        <option value="{{ $centre->id }}" {{ old('centre_id', $user->centre_id) == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->name }} — {{ $centre->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('centre_id')
                                    <p class="field-error">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:6px; border-top:1px solid #f1f5f9; margin-top:4px; flex-wrap:wrap; gap:12px;">
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:0; display:flex; align-items:center; gap:5px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Le mot de passe n'est pas modifié ici
                        </p>
                        <div style="display:flex; gap:12px;">
                            <a href="{{ route('users.index') }}" class="btn-cancel">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
