@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-list-alt me-2"></i>Gestion des Lignes Budgétaires</p>
                <p class="hero-sub">Ajoutez et gérez les lignes budgétaires de votre organisation</p>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-4">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-plus"></i></div>
                    <p class="fch-title">Nouvelle ligne budgétaire</p>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('gestion_ligne_budgets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" placeholder="Généré automatiquement" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="code_budget_id" class="form-label">Code budgétaire <span class="text-danger">*</span></label>
                            <select class="form-select" id="code_budget_id" name="code_budget_id" required>
                                <option value="" selected disabled>-- Sélectionnez un code budgétaire --</option>
                                @foreach ($codeBudgets as $codeBudget)
                                    <option value="{{ $codeBudget->id }}">{{ $codeBudget->code }} - {{ $codeBudget->intitule }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="intitule" class="form-label">Intitulé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Entrez l'intitulé" required>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant</label>
                            <input type="number" step="0.01" class="form-control" id="montant" name="montant" placeholder="Entrez le montant">
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
                    <div class="tch-icon"><i class="fas fa-list-alt"></i></div>
                    <div>
                        <p class="tch-title">Liste des lignes budgétaires</p>
                        <p class="tch-sub mb-0">{{ count($ligneBudgets) }} ligne(s)</p>
                    </div>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Code Budgétaire</th>
                                <th>Intitulé</th>
                                <th>Montant</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ligneBudgets as $ligneBudget)
                                <tr>
                                    <td><span class="status-badge blue">{{ $ligneBudget->code }}</span></td>
                                    <td>{{ $ligneBudget->codeBudget->intitule }}</td>
                                    <td style="font-weight:600;">{{ $ligneBudget->intitule }}</td>
                                    <td>{{ number_format($ligneBudget->montant, 0, ',', ' ') }}</td>
                                    <td>{{ $ligneBudget->description }}</td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('gestion_ligne_budgets.edit', $ligneBudget->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_ligne_budgets.destroy', $ligneBudget->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cette ligne budgétaire ?')"><i class="fas fa-trash-alt"></i></button>
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
