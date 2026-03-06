@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-exchange-alt me-2"></i>Modifier une Opération Budgétaire</p>
                <p class="hero-sub">Modification de l'opération #{{ $operationFind->id }}</p>
            </div>
            <a href="{{ route('gestion_operations.index') }}" class="hero-badge text-white">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-5">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-edit"></i></div>
                    <p class="fch-title">Modifier — Opération #{{ $operationFind->id }}</p>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('gestion_operations.update', $operationFind->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="date" class="form-label">Date opération</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $operationFind->date) }}">
                            </div>
                            <div class="col-6">
                                <label for="year" class="form-label">Année</label>
                                <select name="year" id="year" class="form-select">
                                    @php $current = old('year', $operationFind->year); @endphp
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" {{ (int) $current === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mois" class="form-label">Mois de l'opération</label>
                            <select name="mois" id="mois" class="form-select">
                                <option value="">-- Sélectionnez un mois --</option>
                                <option value="01" {{ old('mois', $operationFind->mois) == '01' ? 'selected' : '' }}>Janvier</option>
                                <option value="02" {{ old('mois', $operationFind->mois) == '02' ? 'selected' : '' }}>Février</option>
                                <option value="03" {{ old('mois', $operationFind->mois) == '03' ? 'selected' : '' }}>Mars</option>
                                <option value="04" {{ old('mois', $operationFind->mois) == '04' ? 'selected' : '' }}>Avril</option>
                                <option value="05" {{ old('mois', $operationFind->mois) == '05' ? 'selected' : '' }}>Mai</option>
                                <option value="06" {{ old('mois', $operationFind->mois) == '06' ? 'selected' : '' }}>Juin</option>
                                <option value="07" {{ old('mois', $operationFind->mois) == '07' ? 'selected' : '' }}>Juillet</option>
                                <option value="08" {{ old('mois', $operationFind->mois) == '08' ? 'selected' : '' }}>Août</option>
                                <option value="09" {{ old('mois', $operationFind->mois) == '09' ? 'selected' : '' }}>Septembre</option>
                                <option value="10" {{ old('mois', $operationFind->mois) == '10' ? 'selected' : '' }}>Octobre</option>
                                <option value="11" {{ old('mois', $operationFind->mois) == '11' ? 'selected' : '' }}>Novembre</option>
                                <option value="12" {{ old('mois', $operationFind->mois) == '12' ? 'selected' : '' }}>Décembre</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ligneBudget" class="form-label">Ligne budgétaire</label>
                            <select name="ligne_budget_id" id="ligneBudget" class="form-select">
                                <option value="">-- Sélectionnez une ligne budgétaire --</option>
                                @foreach ($ligneBudgets as $ligne)
                                    <option value="{{ $ligne->id }}" {{ (int) old('ligne_budget_id', $operationFind->ligne_budget_id) === (int) $ligne->id ? 'selected' : '' }}>
                                        {{ $ligne->code }} - {{ $ligne->intitule }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Tiers</label>
                            @php
                                $selectedTierType = old('tier_type');
                                if (!$selectedTierType) {
                                    $selectedTierType = $operationFind->adherant_id ? 'adherant' : ($operationFind->fournisseur_id ? 'fournisseur' : null);
                                }
                            @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tier-type-radio" type="radio" name="tier_type" id="tierTypeAdherant" value="adherant" {{ $selectedTierType === 'adherant' ? 'checked' : '' }}>
                                <label class="form-check-label" for="tierTypeAdherant">Adhérant</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input tier-type-radio" type="radio" name="tier_type" id="tierTypeFournisseur" value="fournisseur" {{ $selectedTierType === 'fournisseur' ? 'checked' : '' }}>
                                <label class="form-check-label" for="tierTypeFournisseur">Fournisseur</label>
                            </div>
                        </div>

                        <div id="adherantSelectWrapper" style="display: none;" class="mb-3">
                            <label for="adherant_id" class="form-label">Adhérant</label>
                            <select name="adherant_id" id="adherant_id" class="form-select">
                                <option value="">-- Sélectionnez un adhérant --</option>
                                @foreach ($adherants as $adherant)
                                    <option value="{{ $adherant->id }}" {{ (int) old('adherant_id', $operationFind->adherant_id) === (int) $adherant->id ? 'selected' : '' }}>{{ $adherant->nom_adherant }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="fournisseurSelectWrapper" style="display: none;" class="mb-3">
                            <label for="fournisseur_id" class="form-label">Fournisseur</label>
                            <select name="fournisseur_id" id="fournisseur_id" class="form-select">
                                <option value="">-- Sélectionnez un fournisseur --</option>
                                @foreach ($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" {{ (int) old('fournisseur_id', $operationFind->fournisseur_id) === (int) $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom_fournisseur }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" value="{{ old('libelle', $operationFind->libelle) }}">
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference', $operationFind->reference) }}">
                        </div>
                        <div class="mb-4">
                            <label for="amount" class="form-label">Montant</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $operationFind->amount) }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                            <a href="{{ route('gestion_operations.index') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="col-lg-7">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-exchange-alt"></i></div>
                    <div>
                        <p class="tch-title">Liste des opérations</p>
                        <p class="tch-sub mb-0">{{ count($operations) }} opération(s)</p>
                    </div>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Code budget</th>
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operations as $operation)
                                <tr class="{{ $operation->id === $operationFind->id ? 'table-active' : '' }}">
                                    <td>{{ $operation->id }}</td>
                                    <td style="font-size:.78rem;">{{ $operation->date ? \Carbon\Carbon::parse($operation->date)->format('d/m/Y') : '—' }}</td>
                                    <td><span class="status-badge blue">{{ $operation->ligneBudget->code ?? '—' }}</span></td>
                                    <td style="font-weight:600;">{{ $operation->libelle }}</td>
                                    <td>{{ number_format($operation->amount, 0, ',', ' ') }}</td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('gestion_operations.edit', $operation->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_operations.destroy', $operation->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cette opération ?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.tier-type-radio');
        const adherantWrapper = document.getElementById('adherantSelectWrapper');
        const fournisseurWrapper = document.getElementById('fournisseurSelectWrapper');
        const adherantSelect = document.getElementById('adherant_id');
        const fournisseurSelect = document.getElementById('fournisseur_id');

        function toggleTierSelect() {
            const selected = document.querySelector('.tier-type-radio:checked')?.value;
            if (selected === 'adherant') {
                adherantWrapper.style.display = '';
                fournisseurWrapper.style.display = 'none';
                fournisseurSelect.value = '';
            } else if (selected === 'fournisseur') {
                fournisseurWrapper.style.display = '';
                adherantWrapper.style.display = 'none';
                adherantSelect.value = '';
            } else {
                adherantWrapper.style.display = 'none';
                fournisseurWrapper.style.display = 'none';
                adherantSelect.value = '';
                fournisseurSelect.value = '';
            }
        }

        radios.forEach(function (radio) {
            radio.addEventListener('change', toggleTierSelect);
        });

        toggleTierSelect();
    });
</script>
@endsection
