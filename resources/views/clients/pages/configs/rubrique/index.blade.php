@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-tags me-2"></i>Gestion des Rubriques</p>
                <p class="hero-sub">Organisez vos données budgétaires par rubriques</p>
            </div>
            <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addRubriqueModal">
                <i class="fas fa-plus"></i> Nouvelle rubrique
            </button>
        </div>
    </div>

    {{-- Modal Ajout --}}
    <div class="modal fade" id="addRubriqueModal" tabindex="-1" aria-labelledby="addRubriqueModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 32px rgba(0,61,130,.15);">
                <div class="modal-header modal-header-blue">
                    <h5 class="modal-title text-white" id="addRubriqueModalLabel"><i class="fas fa-plus me-2" class="text-white"></i>Nouvelle rubrique</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('gestion_rubriques.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Entrez le code">
                        </div>
                        <div class="mb-3">
                            <label for="intitule" class="form-label">Nom de la rubrique <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Entrez le nom" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description"></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-tags"></i></div>
            <div>
                <p class="tch-title">Liste des rubriques</p>
                <p class="tch-sub mb-0">{{ count($rubriques) }} rubrique(s)</p>
            </div>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom de la rubrique</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rubriques as $rubrique)
                        <tr>
                            <td><span class="status-badge blue">{{ $rubrique->code }}</span></td>
                            <td style="font-weight:600;">{{ $rubrique->intitule }}</td>
                            <td>{{ $rubrique->description }}</td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('gestion_rubriques.edit', $rubrique->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('gestion_rubriques.destroy', $rubrique->id) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cette rubrique ?')"><i class="fas fa-trash-alt"></i></button>
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
@endsection
