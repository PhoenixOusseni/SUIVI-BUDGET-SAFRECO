@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-tasks me-2"></i>Modifier une Tâche</p>
                <p class="hero-sub">Modification de : <strong>{{ $tacheFind->libelle }}</strong></p>
            </div>
            <a href="{{ route('gestion_taches.index') }}" class="hero-badge">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-5">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-edit"></i></div>
                    <p class="fch-title">Modifier — {{ $tacheFind->code }}</p>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('gestion_taches.update', $tacheFind->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ $tacheFind->code }}">
                        </div>
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $tacheFind->libelle }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="date_debut" class="form-label">Date de Début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ $tacheFind->date_debut }}">
                            </div>
                            <div class="col-6">
                                <label for="date_echeance" class="form-label">Date d'Échéance</label>
                                <input type="date" class="form-control" id="date_echeance" name="date_echeance" value="{{ $tacheFind->date_echeance }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="taux" class="form-label">Taux (%)</label>
                                <input type="number" class="form-control" id="taux" name="taux" min="0" max="100" value="{{ $tacheFind->taux }}">
                            </div>
                            <div class="col-6">
                                <label for="file" class="form-label">Fichier</label>
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $tacheFind->description }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                            <a href="{{ route('gestion_taches.index') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="col-lg-7">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-tasks"></i></div>
                    <div>
                        <p class="tch-title">Liste des tâches</p>
                        <p class="tch-sub mb-0">{{ count($taches) }} tâche(s)</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr><th>Code</th><th>Date début</th><th>Libellé</th><th>Taux</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($taches as $tache)
                                @php $t = (int)($tache->taux ?? 0); $cls = $t >= 100 ? 'green' : ($t >= 70 ? 'blue' : ($t >= 40 ? 'orange' : 'red')); @endphp
                                <tr class="{{ $tache->id === $tacheFind->id ? 'table-active' : '' }}">
                                    <td><span class="status-badge blue">{{ $tache->code }}</span></td>
                                    <td style="font-size:.78rem;">{{ $tache->date_debut ? \Carbon\Carbon::parse($tache->date_debut)->format('d/m/Y') : '—' }}</td>
                                    <td style="font-weight:600;">{{ $tache->libelle }}</td>
                                    <td><span class="status-badge {{ $cls }}">{{ $t }}%</span></td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('gestion_taches.edit', $tache->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('gestion_taches.destroy', $tache->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cette tâche ?')"><i class="fas fa-trash-alt"></i></button>
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
