@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid px-3 pb-4">

        <div class="page-hero">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <p class="hero-title"><i class="fas fa-tags me-2"></i>Modifier une Rubrique</p>
                    <p class="hero-sub">Modification de : <strong>{{ $findRubrique->intitule }}</strong></p>
                </div>
                <a href="{{ route('gestion_rubriques.index') }}" class="hero-badge text-decoration-none text-white">
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
                        <p class="fch-title">Modifier — {{ $findRubrique->code }}</p>
                    </div>
                    <div class="form-card-body">
                        <form action="{{ route('gestion_rubriques.update', $findRubrique->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ $findRubrique->code }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="intitule" class="form-label">Nom de la rubrique <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="intitule" name="intitule"
                                    value="{{ $findRubrique->intitule }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ $findRubrique->description }}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à
                                    jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Liste --}}
            <div class="col-lg-8">
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
                                    <tr class="{{ $rubrique->id === $findRubrique->id ? 'table-active' : '' }}">
                                        <td><span class="status-badge blue">{{ $rubrique->code }}</span></td>
                                        <td style="font-weight:600;">{{ $rubrique->intitule }}</td>
                                        <td>{{ $rubrique->description }}</td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('gestion_rubriques.edit', $rubrique->id) }}"
                                                    class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('gestion_rubriques.destroy', $rubrique->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-danger-custom"
                                                        onclick="return confirm('Supprimer cette rubrique ?')"><i
                                                            class="fas fa-trash-alt"></i></button>
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
