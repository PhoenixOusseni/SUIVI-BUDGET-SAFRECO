@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <div class="container mt-3 text-center">
            <h1 class="mb-2 page-title">GESTION DES TÂCHES</h1>
            <p>Cette section vous permet de gérer les tâches associées à vos projets. Vous pouvez ajouter, modifier ou
                supprimer des tâches selon vos besoins.</p>
            <!-- Ajoutez ici le contenu spécifique au suivi de la situation financière -->
        </div>

        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">Modification de la Tâche {{ $tacheFind->code }}</h5>
                        <form method="POST" action="{{ route('gestion_taches.update', $tacheFind->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="code" class="small">Code</label>
                                <input type="text" class="form-control" id="code" value="{{ $tacheFind->code }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="libelle" class="small">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $tacheFind->libelle }}" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_debut" class="small">Date de Début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ $tacheFind->date_debut }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_echeance" class="small">Date d'Échéance</label>
                                    <input type="date" class="form-control" id="date_echeance" name="date_echeance" value="{{ $tacheFind->date_echeance }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="taux" class="small">Taux (%)</label>
                                    <input type="number" class="form-control" id="taux" name="taux" min="0" max="100" value="{{ $tacheFind->taux }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="file" class="small">Fichier</label>
                                    <input type="file" class="form-control" id="file" name="file">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="small">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ $tacheFind->description }}</textarea>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-edit"></i>&nbsp; Modifier la Tâche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">Liste des Tâches</h5>
                        <table class="table table-striped" id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Date debut</th>
                                    <th>Libellé</th>
                                    <th>Taux (%)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taches as $tache)
                                    <tr>
                                        <td>{{ $tache->code }}</td>
                                        <td>{{ $tache->date_debut ? \Carbon\Carbon::parse($tache->date_debut)->format('d/m/Y') : '' }}</td>
                                        <td>{{ $tache->libelle }}</td>
                                        <td>{{ $tache->taux }}</td>
                                        <td>
                                            <a href="{{ route('gestion_taches.edit', $tache->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('gestion_taches.destroy', $tache->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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
