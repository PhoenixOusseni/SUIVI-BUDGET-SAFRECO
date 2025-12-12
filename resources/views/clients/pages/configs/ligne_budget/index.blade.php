@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES LIGNES BUDGETAIRES</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.configs.menu_config')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de gestion des lignes budgetaires!</h4>
                            <p>Dans cette section, vous pouvez ajouter, modifier ou supprimer des lignes budgetaires pour
                                organiser vos données budgétaires de manière efficace</p>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">Liste des Lignes Budgetaires</h5>
                        <table id="datatablesSimple" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Code Budgetaire</th>
                                    <th>Intitule</th>
                                    <th>Montant</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ligneBudgets as $ligneBudget)
                                    <tr>
                                        <td>{{ $ligneBudget->code }}</td>
                                        <td>{{ $ligneBudget->codeBudget->intitule }}</td>
                                        <td>{{ $ligneBudget->intitule }}</td>
                                        <td>{{ number_format($ligneBudget->montant, 0, ',', ' ') }}</td>
                                        <td>{{ $ligneBudget->description }}</td>
                                        <td>
                                            <a href="{{ route('gestion_ligne_budgets.edit', $ligneBudget->id) }}"
                                                class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_ligne_budgets.destroy', $ligneBudget->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ligne budgetaire ?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Ajout de Ligne Budgetaire</h5>
                        <form action="{{ route('gestion_ligne_budgets.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="small">Code</label>
                                <input type="text" class="form-control" id="code"
                                    placeholder="Le code se genère automatiquement" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="code_budget_id" class="small">Code budgetaire</label>
                                <select class="form-select" id="code_budget_id" name="code_budget_id">
                                    <option value="" selected disabled>-- Sélectionnez un code budgetaire --</option>
                                    @foreach ($codeBudgets as $codeBudget)
                                        <option value="{{ $codeBudget->id }}">{{ $codeBudget->code }} - {{ $codeBudget->intitule }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="intitule" class="small">Intitule</label>
                                <input type="text" class="form-control" id="intitule" name="intitule"
                                    placeholder="Entrez l'intitule">
                            </div>
                            <div class="mb-3">
                                <label for="montant" class="small">Montant</label>
                                <input type="number" step="0.01" class="form-control" id="montant" name="montant"
                                    placeholder="Entrez le montant">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="small">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description"></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> &thinsp;&thinsp; Enregistrer
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                    <i data-feather="x-circle"></i> &thinsp;&thinsp; Fermer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
