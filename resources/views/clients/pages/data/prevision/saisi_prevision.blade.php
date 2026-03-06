@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-chart-bar me-2"></i>Saisie des Prévisions</p>
                <p class="hero-sub">Enregistrez les montants prévisionnels par mois</p>
            </div>
            <a href="{{ route('gestion_previsions.index') }}" class="hero-badge">
                <i class="fas fa-list"></i> Liste des prévisions
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div class="fch-icon"><i class="fas fa-plus"></i></div>
            <p class="fch-title">Nouvelle prévision</p>
        </div>
        <div class="form-card-body">
            <form action="{{ route('gestion_previsions.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="ligne_budget_id" class="form-label">Ligne budgétaire <span class="text-danger">*</span></label>
                        <select name="ligne_budget_id" id="ligne_budget_id" class="form-select" required>
                            <option value="">Sélectionner un élément</option>
                            @foreach ($lignesBudgets as $ligne)
                                <option value="{{ $ligne->id }}" {{ (int) old('ligne_budget_id') === $ligne->id ? 'selected' : '' }}>
                                    {{ $ligne->code }}{{ isset($ligne->intitule) ? ' - ' . $ligne->intitule : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date de la prévision</label>
                        <input type="date" id="date" name="date" value="{{ old('date') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Année prévisionnelle</label>
                        <select name="year" id="year" class="form-select">
                            @php $current = old('year', date('Y')); @endphp
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ (int) $current === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $months = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',
                               7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
                    $oldMonths = old('months', []);
                @endphp

                <div class="p-3 mb-4" style="background:var(--surface); border-radius:10px; border:1px solid var(--border);">
                    <div class="row g-3">
                        @foreach ($months as $num => $label)
                            <div class="col-md-3">
                                <label class="form-label">{{ $label }}</label>
                                <input type="number" step="0.01" min="0" name="months[{{ $num }}]" class="form-control"
                                    value="{{ array_key_exists($num, (array) $oldMonths) ? $oldMonths[$num] : '' }}"
                                    placeholder="Montant">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">Notes (optionnel)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer la prévision</button>
                    <a href="{{ route('gestion_previsions.index') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
