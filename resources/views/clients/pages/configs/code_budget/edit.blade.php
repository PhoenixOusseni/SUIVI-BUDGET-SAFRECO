@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES CODES BUDGETAIRES</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.configs.menu_config')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Modification de code budgetaire "{{ $findCodeBudget->code }}"</h4>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">Liste des Codes Budgetaires</h5>
                        <table id="datatablesSimple" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Rubrique</th>
                                    <th>Intitule</th>
                                    <th>Montant</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($codeBudgets as $codeBudget)
                                    <tr>
                                        <td>{{ $codeBudget->code }}</td>
                                        <td>{{ $codeBudget->rubrique->intitule }}</td>
                                        <td>{{ $codeBudget->intitule }}</td>
                                        <td>{{ number_format($codeBudget->montant, 0, ',', ' ') }}</td>
                                        <td>{{ $codeBudget->description }}</td>
                                        <td>
                                            <a href="{{ route('gestion_code_budgets.edit', $codeBudget->id) }}"
                                                class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_code_budgets.destroy', $codeBudget->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce code budgetaire ?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Ajout de Code Budgetaire</h5>
                        <form action="{{ route('gestion_code_budgets.update', $findCodeBudget->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="code" class="small">Code</label>
                                <input type="text" class="form-control" id="code" value="{{ $findCodeBudget->code }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="nom" class="small">Rubrique</label>
                                <select class="form-select" id="rubrique_id" name="rubrique_id">
                                    <option value="">Sélectionnez une rubrique</option>
                                    @foreach ($rubriques as $rubrique)
                                        <option value="{{ $rubrique->id }}"
                                            {{ $findCodeBudget->rubrique_id == $rubrique->id ? 'selected' : '' }}>
                                            {{ $rubrique->intitule }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="intitule" class="small">Intitule</label>
                                <input type="text" class="form-control" id="intitule" name="intitule" value="{{ $findCodeBudget->intitule }}">
                            </div>
                            <div class="mb-3">
                                <label for="montant" class="small">Montant</label>
                                <input type="number" class="form-control" id="montant" name="montant" value="{{ $findCodeBudget->montant }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="small">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description">{{ $findCodeBudget->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="edit"></i> &thinsp;&thinsp; Modifier
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
