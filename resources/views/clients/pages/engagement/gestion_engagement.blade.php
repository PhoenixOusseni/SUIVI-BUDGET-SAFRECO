@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <div class="container mt-3 text-center">
            <h1 class="mb-2 page-title">GESTION DES ENGAGEMENTS</h1>
            <p>
                Bienvenue dans la section de gestion des engagements. Ici, vous pouvez gérer les engagements financiers,
                suivre les dépôts et assurer un suivi efficace des pièces jointes.
            </p>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title text-success mb-0">Liste des Engagements</h5>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEngagementModal">
                                <i class="fas fa-plus"></i>&nbsp; Ajouter un engagement
                            </button>
                        </div>
                        <table class="table table-striped" id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Fournisseur</th>
                                    <th>Date Depot</th>
                                    <th>Montant</th>
                                    <th>30J</th>
                                    <th>45J</th>
                                    <th>+45J</th>
                                    <th>Fournisseur</th>
                                    <th>Pièce jointe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($engagements as $engagement)
                                    <tr>
                                        <td>{{ $engagement->code }}</td>
                                        <td>{{ $engagement->fournisseur ? $engagement->fournisseur->nom_fournisseur : 'N/A' }}</td>
                                        <td>{{ $engagement->date_depot ? \Carbon\Carbon::parse($engagement->date_depot)->format('d/m/Y') : '' }}</td>
                                        <td>{{ number_format($engagement->montant, 0, ',', ' ') }}</td>
                                        <td>{{ $engagement->j_1 }}</td>
                                        <td>{{ $engagement->j_2 }}</td>
                                        <td>{{ $engagement->j_3 }}</td>
                                        <td>{{ $engagement->fournisseur ? $engagement->fournisseur->nom_fournisseur : 'N/A' }}</td>
                                        <td>
                                            @if($engagement->piece_joint)
                                                <a href="{{ asset('storage/' . $engagement->piece_joint) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-file-download"></i>&nbsp; Voir
                                                </a>
                                            @else
                                                <span class="text-muted">Aucun</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des Fournisseurs/Tiers avec Observations -->
        <div class="row mb-5">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success mb-3">Fournisseurs/tiers - Observation</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%">Fournisseurs/tiers</th>
                                    <th style="width: 50%">Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fournisseurs as $fournisseur)
                                    @php
                                        // Récupérer le dernier engagement pour ce fournisseur
                                        $dernierEngagement = $engagements->where('fournisseur_id', $fournisseur->id)->sortByDesc('date_depot')->first();

                                        // Calculer l'observation basée sur les jours
                                        $observation = '';
                                        if ($dernierEngagement) {
                                            $dateDepot = \Carbon\Carbon::parse($dernierEngagement->date_depot);
                                            $joursEcoules = $dateDepot->diffInDays(\Carbon\Carbon::now());

                                            if ($joursEcoules <= 30) {
                                                $observation = 'Gestion fluide';
                                            } elseif ($joursEcoules <= 45) {
                                                $observation = 'Ralentissement';
                                            } else {
                                                $observation = 'Risque de rupture de service';
                                            }
                                        }
                                    @endphp
                                    @if($dernierEngagement)
                                        <tr>
                                            <td>{{ strtoupper($fournisseur->nom_fournisseur) }}</td>
                                            <td>{{ $observation }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout d'engagement -->
    <div class="modal fade" id="addEngagementModal" tabindex="-1" aria-labelledby="addEngagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addEngagementModalLabel">
                        <i class="fas fa-plus-circle"></i>&nbsp; Ajouter un engagement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('engagement.gestion_engagements.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="code" class="small">Code</label>
                                <input type="text" class="form-control" id="code" placeholder="Code auto-g�n�r�" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="date_depot" class="small">Date de dépôt <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_depot" name="date_depot" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="montant" class="small">Montant <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant de l'engagement" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fournisseur_id" class="small">Fournisseur <span class="text-danger">*</span></label>
                                <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom_fournisseur }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="j_1" class="small">30J</label>
                                <input type="text" class="form-control" id="j_1" name="j_1" placeholder="30J">
                            </div>
                            <div class="col-md-4">
                                <label for="j_2" class="small">45J</label>
                                <input type="text" class="form-control" id="j_2" name="j_2" placeholder="45J">
                            </div>
                            <div class="col-md-4">
                                <label for="j_3" class="small">+45J</label>
                                <input type="text" class="form-control" id="j_3" name="j_3" placeholder="+45J">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="piece_joint" class="small">Pièce jointe</label>
                            <input type="file" class="form-control" id="piece_joint" name="piece_joint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, JPG, PNG</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>&nbsp; Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>&nbsp; Enregistrer l'engagement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('{{ session('success') }}');
            });
        </script>
    @endif
@endsection
