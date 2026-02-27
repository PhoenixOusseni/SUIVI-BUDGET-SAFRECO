@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="text-center mb-3">
            <h3 class="mb-2 page-title text-center">GESTION DES OPERATIONS BUDGETAIRES</h3>
            <p class="text-center">Cette section vous permet de visualiser et de gérer toutes les opérations
                budgétaires enregistrées dans le système.</p>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <h4 class="mb-3 text-success">Modification de l'opération "{{ $operationFind->id }}"</h4>
                <div class="row mb-4">
                    <form action="{{ route('gestion_operations.update', $operationFind->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date" class="small">Date operation</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        placeholder="Entrez la date de l'opération" value="{{ old('date', $operationFind->date) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year" class="small">Année</label>
                                    <select name="year" id="year" class="form-select">
                                        @php
                                            $current = old('year', $operationFind->year);
                                        @endphp
                                        @foreach ($years as $y)
                                            <option value="{{ $y }}"
                                                {{ (int) $current === (int) $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mois" class="small">Mois de l'opération</label>
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ligneBudget" class="small">Ligne budgetaire</label>
                                    <select name="ligne_budget_id" id="ligneBudget" class="form-select">
                                        <option value="">-- Sélectionnez une ligne budgétaire --</option>
                                        @foreach ($ligneBudgets as $ligne)
                                            <option value="{{ $ligne->id }}"
                                                {{ (int) old('ligne_budget_id', $operationFind->ligne_budget_id) === (int) $ligne->id ? 'selected' : '' }}>
                                                {{ $ligne->code }} - {{ $ligne->intitule }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small d-block">Tiers</label>
                                    @php
                                        $selectedTierType = old('tier_type');
                                        if (!$selectedTierType) {
                                            $selectedTierType = $operationFind->adherant_id ? 'adherant' : ($operationFind->fournisseur_id ? 'fournisseur' : null);
                                        }
                                    @endphp
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tier-type-radio" type="radio" name="tier_type"
                                            id="tierTypeAdherant" value="adherant"
                                            {{ $selectedTierType === 'adherant' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tierTypeAdherant">Adherant</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tier-type-radio" type="radio" name="tier_type"
                                            id="tierTypeFournisseur" value="fournisseur"
                                            {{ $selectedTierType === 'fournisseur' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tierTypeFournisseur">Fournisseur</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="adherantSelectWrapper" style="display: none;">
                                <div class="form-group">
                                    <label for="adherant_id" class="small">Adherant</label>
                                    <select name="adherant_id" id="adherant_id" class="form-select">
                                        <option value="">-- Sélectionnez un adherant --</option>
                                        @foreach ($adherants as $adherant)
                                            <option value="{{ $adherant->id }}"
                                                {{ (int) old('adherant_id', $operationFind->adherant_id) === (int) $adherant->id ? 'selected' : '' }}>
                                                {{ $adherant->nom_adherant }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="fournisseurSelectWrapper" style="display: none;">
                                <div class="form-group">
                                    <label for="fournisseur_id" class="small">Fournisseur</label>
                                    <select name="fournisseur_id" id="fournisseur_id" class="form-select">
                                        <option value="">-- Sélectionnez un fournisseur --</option>
                                        @foreach ($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}"
                                                {{ (int) old('fournisseur_id', $operationFind->fournisseur_id) === (int) $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->nom_fournisseur }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="libelle" class="small">Libellé</label>
                                    <input type="text" class="form-control" id="libelle" name="libelle"
                                        placeholder="Entrez le libellé de l'opération" value="{{ old('libelle', $operationFind->libelle) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reference" class="small">Référence</label>
                                    <input type="text" class="form-control" id="reference" name="reference"
                                        placeholder="Entrez la référence de l'opération" value="{{ old('reference', $operationFind->reference) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="small">Montant</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="Entrez le montant de l'opération" value="{{ old('amount', $operationFind->amount) }}">
                                </div>
                            </div>
                        </div>
                        <div class="text-start">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-edit"></i>&nbsp;&nbsp; Modifier l'opération
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <h4 class="mb-3 text-success">Liste des opérations</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Code budget</th>
                                <th>Ligne budget</th>
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operations as $operation)
                                <tr>
                                    <td>{{ $operation->id }}</td>
                                    <td>{{ $operation->date ? \Carbon\Carbon::parse($operation->date)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $operation->ligneBudget->code ?? '' }}</td>
                                    <td>{{ $operation->ligneBudget->intitule ?? '' }}</td>
                                    <td>{{ $operation->libelle }}</td>
                                    <td>{{ $operation->amount }}</td>
                                    <td class="text-center">
                                        <!-- Actions: Edit, Delete, View -->
                                        <a href="{{ route('gestion_operations.edit', $operation->id) }}" class="btn btn-sm btn-warning">Éditer</a>
                                        <form action="{{ route('gestion_operations.destroy', $operation->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rubrique ?')"><i
                                                    data-feather="trash-2"></i>&thinsp;&thinsp; Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    return;
                }

                if (selected === 'fournisseur') {
                    fournisseurWrapper.style.display = '';
                    adherantWrapper.style.display = 'none';
                    adherantSelect.value = '';
                    return;
                }

                adherantWrapper.style.display = 'none';
                fournisseurWrapper.style.display = 'none';
                adherantSelect.value = '';
                fournisseurSelect.value = '';
            }

            radios.forEach(function(radio) {
                radio.addEventListener('change', toggleTierSelect);
            });

            toggleTierSelect();
        });
    </script>
@endsection
