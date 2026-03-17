@extends('layouts.app')

@section('page_title', 'Supprimer un centre')
@section('page_kicker', 'Pilotage')
@section('page_subtitle', 'Confirmation requise avant d archiver un centre.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Retour aux centres</a>
@endsection

@include('partials.soft-form-styles')

@section('content')
<div class="page-shell stack-grid theme-page theme-sky">
    <section class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Confirmation de suppression</div>
                <div class="td-muted">Pour archiver ce centre, saisissez son nom exact.</div>
            </div>
        </div>
        <div class="table-wrap">
            <form method="POST" action="{{ route('centers.archive', $center) }}" class="sf-form" style="padding: 20px;" data-loading="true" data-loading-text="Archivage du centre en cours...">
                @csrf
                <input type="hidden" name="action" value="archive">
                <div class="field" style="max-width: 420px;">
                    <label for="confirm_name">Nom du centre</label>
                    <input id="confirm_name" name="confirm_name" type="text" placeholder="{{ $center->name }}" required>
                    @error('confirm_name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="toolbar-actions" style="margin-top: 16px;">
                    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Annuler</a>
                    <button class="btn btn-danger" type="submit">Archiver le centre</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

