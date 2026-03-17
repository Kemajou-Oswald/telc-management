@extends('layouts.app')

@section('page_title', 'Nouvelle session')
@section('page_kicker', 'Pilotage')
@section('page_subtitle')
    Renseignez le centre, les dates et les informations de la session.
@endsection

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('sessions.index') }}">Retour aux sessions</a>
@endsection

@include('partials.soft-form-styles')

@section('content')
<div class="sf-page" style="grid-template-columns:minmax(0,1fr);">
    <section class="sf-card sf-card--main">
        <div class="sf-hero">
            <span class="sf-hero__eyebrow">Nouvelle session</span>
            <h2 class="sf-hero__title">Cr&eacute;er une session</h2>
            <p class="sf-hero__text">Saisissez l'essentiel pour ouvrir une nouvelle session d'examen.</p>
            <div class="sf-hero__meta">
                <span class="sf-pill">{{ $centerOptions->count() }} centre{{ $centerOptions->count() > 1 ? 's' : '' }} disponible{{ $centerOptions->count() > 1 ? 's' : '' }}</span>
            </div>
        </div>

        @php
            $selectedCenterIds = old('center_ids', $selectedCenterId ? [$selectedCenterId] : []);
            $initialCities = $centerOptions->whereIn('id', $selectedCenterIds)->pluck('city')->filter()->unique()->values();
            $oldCity = old('city');
            if ($oldCity && ! $initialCities->contains($oldCity)) {
                $initialCities = $initialCities->prepend($oldCity)->unique();
            }
        @endphp

        <form method="POST" action="{{ route('sessions.store') }}" class="sf-form" data-loading="true" data-loading-text="Creation en cours...">
            @csrf

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Informations</div>
                        <div class="sf-section__text">Titre, centre, niveau, tarif et statut initial.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field field--full">
                        <label for="title">Titre</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="TELC B1 Juin 2026" required>
                        @error('title')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field--full">
                        <label for="center_ids">Centres</label>
                        <select id="center_ids" name="center_ids[]" multiple required style="min-height:90px;">
                            @foreach ($centerOptions as $center)
                                <option value="{{ $center->id }}" data-city="{{ $center->city }}" {{ in_array($center->id, old('center_ids', $selectedCenterId ? [$selectedCenterId] : [])) ? 'selected' : '' }}>
                                    {{ $center->name }}{{ $center->city ? ' - ' . $center->city : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="sf-help">Maintenez Ctrl (ou Cmd) pour s&eacute;lectionner plusieurs centres.</small>
                        @error('center_ids')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="city">Ville de la session</label>
                        <select id="city" name="city" required>
                            @if ($initialCities->isEmpty())
                                <option value="">Selectionner d'abord les centres</option>
                            @else
                                @foreach ($initialCities as $city)
                                    <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="sf-help">La ville est proposee depuis les centres selectionnes.</small>
                        @error('city')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="level">Niveau</label>
                        <select id="level" name="level" required>
                            @foreach (\App\Models\ExamSession::LEVELS as $levelOption)
                                <option value="{{ $levelOption }}" {{ old('level', 'B1') === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                            @endforeach
                        </select>
                        @error('level')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="price">Prix (FCFA)</label>
                        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="50000" required>
                        @error('price')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="status">Statut</label>
                        <select id="status" name="status" required>
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="open" {{ old('status') === 'open' ? 'selected' : '' }}>Ouverte</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Ferm&eacute;e</option>
                        </select>
                        @error('status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Dates</div>
                        <div class="sf-section__text">Indiquez l'ouverture, la cl&ocirc;ture et, si elle est connue, la date d'examen.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="opens_at">D&eacute;but des inscriptions</label>
                        <input id="opens_at" type="date" name="opens_at" value="{{ old('opens_at') }}" required>
                        @error('opens_at')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="closes_at">Fin des inscriptions</label>
                        <input id="closes_at" type="date" name="closes_at" value="{{ old('closes_at') }}">
                        @error('closes_at')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field--full">
                        <label for="exam_date">Date d'examen</label>
                        <input id="exam_date" type="date" name="exam_date" value="{{ old('exam_date') }}">
                        @error('exam_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Notes</div>
                        <div class="sf-section__text">Ajoutez les consignes utiles au suivi de la session.</div>
                    </div>
                </div>
                <div class="field">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="5" placeholder="Notes internes.">{{ old('notes') }}</textarea>
                    @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </section>

            <div class="sf-actions">
                <div></div>
                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('sessions.index') }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Cr&eacute;er la session</button>
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const centerSelect = document.getElementById('center_ids');
        const citySelect = document.getElementById('city');
        if (!centerSelect || !citySelect) return;

        const buildCityOptions = () => {
            const selectedCities = Array.from(centerSelect.selectedOptions)
                .map((option) => option.dataset.city)
                .filter((city) => city && city.trim().length > 0);
            const uniqueCities = [...new Set(selectedCities)];
            const previousValue = citySelect.value;

            citySelect.innerHTML = '';

            if (uniqueCities.length === 0) {
                const option = new Option('Selectionner d\'abord les centres', '');
                citySelect.appendChild(option);
                return;
            }

            uniqueCities.forEach((city) => {
                const option = new Option(city, city);
                if (city === previousValue) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });

            if (!citySelect.value && uniqueCities.length > 0) {
                citySelect.selectedIndex = 0;
            }
        };

        buildCityOptions();
        centerSelect.addEventListener('change', buildCityOptions);
    });
</script>
@endsection
