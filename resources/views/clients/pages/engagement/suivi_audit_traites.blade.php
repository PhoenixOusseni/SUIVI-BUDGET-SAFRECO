@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid mt-4">

        <div class="container mt-2">
            <h2 class="mb-4 page-title">Suivi des audits traités</h2>
            <p class="text-center">Bienvenue dans la section de suivi des audits traités. Ici, vous pouvez suivre
                et gérer votre budget efficacement.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3 class="mb-0 text-danger">Suivi des audits — Année {{ $year }}</h3>
            <form class="d-flex" method="GET" action="#">
                <select name="year" class="form-select me-2" style="min-width: 250px">
                    @for ($y = date('Y') - 3; $y <= date('Y') + 3; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button class="btn btn-outline-primary">
                    <i class="fas fa-filter"></i>&nbsp;Filtrer
                </button>
            </form>
        </div>

        <!-- Premier Tableau: Liste des Tâches -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="mb-3 text-success"><i class="fas fa-tasks"></i>&nbsp; Liste des Tâches</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Date debut</th>
                                <th>Date fin</th>
                                <th>Tache</th>
                                <th>Taux Exécution</th>
                                <th>PIECE JOINT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taches as $tache)
                                <tr>
                                    <td>{{ $tache->code }}</td>
                                    <td>{{ $tache->date_debut ? \Carbon\Carbon::parse($tache->date_debut)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $tache->date_fin ? \Carbon\Carbon::parse($tache->date_fin)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $tache->libelle }}</td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar
                                                @if($tache->taux == 100) bg-success
                                                @elseif($tache->taux >= 80 && $tache->taux < 100) bg-warning
                                                @else bg-danger
                                                @endif"
                                                role="progressbar"
                                                style="width: {{ $tache->taux }}%;"
                                                aria-valuenow="{{ $tache->taux }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $tache->taux }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($tache->file)
                                            <a href="{{ asset('storage/' . $tache->file) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-paperclip"></i> Voir
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="border: 1px solid #000; padding: 10px; text-align: center;">Aucune tâche disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Deuxième Tableau: États d'Exécution -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3 text-success"><i class="fas fa-chart-line"></i> États d'Exécution</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Tâches</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taches as $tache)
                                <tr>
                                    <td>{{ $tache->code }}</td>
                                    <td>{{ $tache->libelle }}</td>
                                    <td>
                                        @if($tache->taux == 100)
                                            Conformité parfaite
                                        @elseif($tache->taux >= 80)
                                            Progrès partiel
                                        @else
                                            Risque élevé de non-conformité
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
@endsection
