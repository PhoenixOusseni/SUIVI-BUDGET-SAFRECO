@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-exchange-alt me-2"></i>Gestion des Opérations Budgétaires</p>
                <p class="hero-sub">Saisie et suivi de toutes les opérations budgétaires</p>
            </div>
        </div>
    </div>

    {{-- Formulaire d'ajout --}}
    <div class="form-card mb-4">
        <div class="form-card-header">
            <div class="fch-icon"><i class="fas fa-plus"></i></div>
            <p class="fch-title">Nouvelle opération</p>
        </div>
        <div class="form-card-body">
            <form action="{{ route('gestion_operations.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label for="date" class="form-label">Date opération</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Année</label>
                        <select name="year" id="year" class="form-select">
                            @php $current = old('year', date('Y')); @endphp
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ (int) $current === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mois" class="form-label">Mois de l'opération</label>
                        <select name="mois" id="mois" class="form-select">
                            <option value="">-- Sélectionnez un mois --</option>
                            <option value="01">Janvier</option>
                            <option value="02">Février</option>
                            <option value="03">Mars</option>
                            <option value="04">Avril</option>
                            <option value="05">Mai</option>
                            <option value="06">Juin</option>
                            <option value="07">Juillet</option>
                            <option value="08">Août</option>
                            <option value="09">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="ligneBudget" class="form-label">Ligne budgétaire</label>
                        <select name="ligne_budget_id" id="ligneBudget" class="form-select">
                            <option value="">-- Sélectionnez une ligne budgétaire --</option>
                            @foreach ($ligneBudgets as $ligneBudget)
                                <option value="{{ $ligneBudget->id }}">{{ $ligneBudget->code }} - {{ $ligneBudget->intitule }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label d-block">Tiers</label>
                        @php $selectedTierType = old('tier_type'); @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tier-type-radio" type="radio" name="tier_type" id="tierTypeAdherant" value="adherant" {{ $selectedTierType === 'adherant' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tierTypeAdherant">Adhérant</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tier-type-radio" type="radio" name="tier_type" id="tierTypeFournisseur" value="fournisseur" {{ $selectedTierType === 'fournisseur' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tierTypeFournisseur">Fournisseur</label>
                        </div>
                    </div>
                    <div class="col-md-4" id="adherantSelectWrapper" style="display: none;">
                        <label for="adherant_id" class="form-label">Adhérant</label>
                        <select name="adherant_id" id="adherant_id" class="form-select">
                            <option value="">-- Sélectionnez un adhérant --</option>
                            @foreach ($adherants as $adherant)
                                <option value="{{ $adherant->id }}" {{ (int) old('adherant_id') === (int) $adherant->id ? 'selected' : '' }}>{{ $adherant->nom_adherant }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" id="fournisseurSelectWrapper" style="display: none;">
                        <label for="fournisseur_id" class="form-label">Fournisseur</label>
                        <select name="fournisseur_id" id="fournisseur_id" class="form-select">
                            <option value="">-- Sélectionnez un fournisseur --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ (int) old('fournisseur_id') === (int) $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom_fournisseur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="libelle" class="form-label">Libellé</label>
                        <input type="text" class="form-control" id="libelle" name="libelle" placeholder="Libellé de l'opération">
                    </div>
                    <div class="col-md-4">
                        <label for="reference" class="form-label">Référence</label>
                        <input type="text" class="form-control" id="reference" name="reference" placeholder="Référence de l'opération">
                    </div>
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Montant</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Montant de l'opération">
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer l'opération</button>
            </form>
        </div>
    </div>

    {{-- Liste des opérations --}}
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
                        <th>Ligne budget</th>
                        <th>Libellé</th>
                        <th>Montant</th>
                        <th>Tiers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                        <tr>
                            <td>{{ $operation->id }}</td>
                            <td>{{ $operation->date ? \Carbon\Carbon::parse($operation->date)->format('d/m/Y') : '—' }}</td>
                            <td><span class="status-badge blue">{{ $operation->ligneBudget->code ?? '—' }}</span></td>
                            <td>{{ $operation->ligneBudget->intitule ?? '—' }}</td>
                            <td style="font-weight:600;">{{ $operation->libelle }}</td>
                            <td>{{ number_format($operation->amount, 0, ',', ' ') }}</td>
                            <td>
                                @if ($operation->adherant)
                                    <span class="status-badge teal">Adhérant: {{ $operation->adherant->nom_adherant }}</span>
                                @elseif ($operation->fournisseur)
                                    <span class="status-badge orange">Fournisseur: {{ $operation->fournisseur->nom_fournisseur }}</span>
                                @else
                                    <span class="status-badge gray">—</span>
                                @endif
                            </td>
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
