@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-code me-2"></i>Gestion des Codes Budgétaires</p>
                <p class="hero-sub">Ajoutez et gérez les codes budgétaires de votre organisation</p>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-4">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-plus"></i></div>
                    <p class="fch-title">Nouveau code budgétaire</p>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('gestion_code_budgets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" placeholder="Généré automatiquement" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="rubrique_id" class="form-label">Rubrique <span class="text-danger">*</span></label>
                            <select class="form-select" id="rubrique_id" name="rubrique_id" required>
                                <option value="" selected disabled>-- Sélectionnez une rubrique --</option>
                                @foreach ($rubriques as $rubrique)
                                    <option value="{{ $rubrique->id }}">{{ $rubrique->code }} - {{ $rubrique->intitule }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="intitule" class="form-label">Intitulé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Entrez l'intitulé" required>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant</label>
                            <input type="number" class="form-control" id="montant" name="montant" placeholder="Entrez le montant">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description"></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="col-lg-8">
            <div class="table-card h-100">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-code"></i></div>
                    <div>
                        <p class="tch-title">Liste des codes budgétaires</p>
                        <p class="tch-sub mb-0">{{ count($codeBudgets) }} code(s)</p>
                    </div>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Rubrique</th>
                                <th>Intitulé</th>
                                <th>Montant</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($codeBudgets as $codeBudget)
                                <tr>
                                    <td><span class="status-badge blue">{{ $codeBudget->code }}</span></td>
                                    <td>{{ $codeBudget->rubrique->intitule }}</td>
                                    <td style="font-weight:600;">{{ $codeBudget->intitule }}</td>
                                    <td>{{ number_format($codeBudget->montant, 0, ',', ' ') }}</td>
                                    <td>{{ $codeBudget->description }}</td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('gestion_code_budgets.edit', $codeBudget->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_code_budgets.destroy', $codeBudget->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer ce code budgétaire ?')"><i class="fas fa-trash-alt"></i></button>
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
@endsection
