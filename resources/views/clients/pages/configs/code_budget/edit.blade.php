@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-code me-2"></i>Modifier un Code Budgétaire</p>
                <p class="hero-sub">Modification de : <strong>{{ $findCodeBudget->code }} — {{ $findCodeBudget->intitule }}</strong></p>
            </div>
            <a href="{{ route('gestion_code_budgets.index') }}" class="hero-badge">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-edit"></i></div>
                    <p class="fch-title">Modifier — {{ $findCodeBudget->code }}</p>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('gestion_code_budgets.update', $findCodeBudget->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" value="{{ $findCodeBudget->code }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="rubrique_id" class="form-label">Rubrique <span class="text-danger">*</span></label>
                            <select class="form-select" id="rubrique_id" name="rubrique_id" required>
                                <option value="">Sélectionnez une rubrique</option>
                                @foreach ($rubriques as $rubrique)
                                    <option value="{{ $rubrique->id }}" {{ $findCodeBudget->rubrique_id == $rubrique->id ? 'selected' : '' }}>{{ $rubrique->intitule }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="intitule" class="form-label">Intitulé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intitule" name="intitule" value="{{ $findCodeBudget->intitule }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant</label>
                            <input type="number" class="form-control" id="montant" name="montant" value="{{ $findCodeBudget->montant }}">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $findCodeBudget->description }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                            <a href="{{ route('gestion_code_budgets.index') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="col-lg-8">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-code"></i></div>
                    <div>
                        <p class="tch-title">Liste des codes budgétaires</p>
                        <p class="tch-sub mb-0">{{ count($codeBudgets) }} code(s)</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Rubrique</th>
                                <th>Intitulé</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($codeBudgets as $codeBudget)
                                <tr class="{{ $codeBudget->id === $findCodeBudget->id ? 'table-active' : '' }}">
                                    <td><span class="status-badge blue">{{ $codeBudget->code }}</span></td>
                                    <td>{{ $codeBudget->rubrique->intitule }}</td>
                                    <td style="font-weight:600;">{{ $codeBudget->intitule }}</td>
                                    <td>{{ number_format($codeBudget->montant, 0, ',', ' ') }}</td>
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
