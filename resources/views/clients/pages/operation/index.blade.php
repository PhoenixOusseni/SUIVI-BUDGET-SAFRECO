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
                <h4 class="mb-3 text-success">Ajouter une nouvelle opération</h4>
                <div class="row mb-4">
                    <form action="{{ route('gestion_operations.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date" class="small">Date operation</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        placeholder="Entrez la date de l'opération">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year" class="small">Année</label>
                                    <select name="year" id="year" class="form-select">
                                        @php
                                            $current = old('year', date('Y'));
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ligneBudget" class="small">Ligne budgetaire</label>
                                    <select name="ligne_budget_id" id="ligneBudget" class="form-select">
                                        <option value="">-- Sélectionnez une ligne budgétaire --</option>
                                        @foreach ($ligneBudgets as $ligneBudget)
                                            <option value="{{ $ligneBudget->id }}">{{ $ligneBudget->code }} -
                                                {{ $ligneBudget->intitule }}</option>
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
                                                {{ (int) old('adherant_id') === (int) $adherant->id ? 'selected' : '' }}>
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
                                                {{ (int) old('fournisseur_id') === (int) $fournisseur->id ? 'selected' : '' }}>
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
                                        placeholder="Entrez le libellé de l'opération">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reference" class="small">Référence</label>
                                    <input type="text" class="form-control" id="reference" name="reference"
                                        placeholder="Entrez la référence de l'opération">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="small">Montant</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="Entrez le montant de l'opération">
                                </div>
                            </div>
                        </div>
                        <div class="text-start">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>&nbsp;&nbsp; Enregistrer l'opération
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
                                <th>Tiers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operations as $operation)
                                <tr>
                                    <td>{{ $operation->id }}</td>
                                    <td>{{ $operation->date ? \Carbon\Carbon::parse($operation->date)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>{{ $operation->ligneBudget->code ?? '' }}</td>
                                    <td>{{ $operation->ligneBudget->intitule ?? '' }}</td>
                                    <td>{{ $operation->libelle }}</td>
                                    <td>{{ $operation->amount }}</td>
                                    <td>
                                        @if ($operation->adherant)
                                            Adhérant: {{ $operation->adherant->nom_adherant }}
                                        @elseif ($operation->fournisseur)
                                            Fournisseur: {{ $operation->fournisseur->nom_fournisseur }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Actions: Edit, Delete, View -->
                                        <a href="{{ route('gestion_operations.edit', $operation->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('gestion_operations.destroy', $operation->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rubrique ?')"><i
                                                    data-feather="trash-2"></i>
                                            </button>
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
