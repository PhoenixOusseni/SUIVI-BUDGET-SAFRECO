@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-clipboard-check me-2"></i>Suivi des Audits Traités</p>
                <p class="hero-sub">Suivi et gestion de l'avancement des tâches d'audit</p>
            </div>
            <form class="d-flex gap-2 align-items-center" method="GET" action="#">
                <select name="year" class="form-select" style="min-width:130px;">
                    @for ($y = date('Y') - 3; $y <= date('Y') + 3; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button class="btn-primary-custom"><i class="fas fa-filter"></i> Filtrer</button>
            </form>
        </div>
    </div>

    {{-- Table Tâches --}}
    <div class="table-card mb-4">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-tasks"></i></div>
            <div>
                <p class="tch-title">Liste des tâches — {{ $year }}</p>
                <p class="tch-sub mb-0">{{ count($taches) }} tâche(s)</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Tâche</th>
                        <th>Taux Exécution</th>
                        <th>Pièce jointe</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($taches as $tache)
                        <tr>
                            <td><span class="status-badge blue">{{ $tache->code }}</span></td>
                            <td style="font-size:.78rem;">{{ $tache->date_debut ? \Carbon\Carbon::parse($tache->date_debut)->format('d/m/Y') : '—' }}</td>
                            <td style="font-size:.78rem;">{{ $tache->date_fin ? \Carbon\Carbon::parse($tache->date_fin)->format('d/m/Y') : '—' }}</td>
                            <td style="font-weight:600;">{{ $tache->libelle }}</td>
                            <td style="min-width:160px;">
                                @php
                                    $t = (int)($tache->taux ?? 0);
                                    $barCls = $t == 100 ? 'bg-success' : ($t >= 80 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress" style="height:22px; border-radius:8px;">
                                    <div class="progress-bar {{ $barCls }}" role="progressbar" style="width:{{ $t }}%;" aria-valuenow="{{ $t }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $t }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($tache->file)
                                    <a href="{{ asset('storage/' . $tache->file) }}" target="_blank" class="btn-info-custom"><i class="fas fa-paperclip"></i></a>
                                @else
                                    <span class="status-badge gray">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucune tâche disponible</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Table États d'exécution --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <p class="tch-title">États d'exécution</p>
                <p class="tch-sub mb-0">Analyse de conformité par tâche</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Tâche</th>
                        <th>Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taches as $tache)
                        @php
                            $t = (int)($tache->taux ?? 0);
                            if ($t == 100) { $obs = 'Conformité parfaite'; $cls = 'green'; }
                            elseif ($t >= 80) { $obs = 'Progrès partiel'; $cls = 'orange'; }
                            else { $obs = 'Risque élevé de non-conformité'; $cls = 'red'; }
                        @endphp
                        <tr>
                            <td><span class="status-badge blue">{{ $tache->code }}</span></td>
                            <td style="font-weight:600;">{{ $tache->libelle }}</td>
                            <td><span class="status-badge {{ $cls }}">{{ $obs }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
