@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-tasks me-2"></i>Gestion des Tâches Projet</p>
                <p class="hero-sub">Suivez l'avancement de vos tâches et projets</p>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-5">
            <div class="form-card h-100">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-plus"></i></div>
                    <p class="fch-title">Nouvelle tâche</p>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('gestion_taches.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Code de la tâche">
                        </div>
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="libelle" name="libelle" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="date_debut" class="form-label">Date de Début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut">
                            </div>
                            <div class="col-6">
                                <label for="date_echeance" class="form-label">Date d'Échéance</label>
                                <input type="date" class="form-control" id="date_echeance" name="date_echeance">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="taux" class="form-label">Taux (%)</label>
                                <input type="number" class="form-control" id="taux" name="taux" min="0" max="100" placeholder="0">
                            </div>
                            <div class="col-6">
                                <label for="file" class="form-label">Fichier</label>
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description détaillée"></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="col-lg-7">
            <div class="table-card h-100">
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
                                @php
                                    $t = (int)($tache->taux ?? 0);
                                    $cls = $t >= 100 ? 'green' : ($t >= 70 ? 'blue' : ($t >= 40 ? 'orange' : 'red'));
                                @endphp
                                <tr>
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
