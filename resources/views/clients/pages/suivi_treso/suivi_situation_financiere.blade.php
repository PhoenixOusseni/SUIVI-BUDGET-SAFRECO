@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-wallet me-2"></i>Suivi de la Situation Financière — {{ $year }}</p>
                <p class="hero-sub">Analysez votre situation financière et anticipez les risques</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <form class="d-flex gap-2 align-items-center" method="GET" action="#">
                    <select name="year" class="form-select" style="width:auto;">
                        @for ($y = date('Y') - 3; $y <= date('Y') + 3; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-filter"></i> Filtrer</button>
                </form>
                <a href="{{ route('tresorerie.export_excel') }}?year={{ $year }}" class="btn-export"><i class="fas fa-file-excel"></i> Exporter</a>
                <a href="{{ route('tresorerie.print_situation_financiere') }}?year={{ $year }}" target="_blank" class="btn-print"><i class="fas fa-print"></i> Imprimer</a>
            </div>
        </div>
    </div>

    @php
        $fmtAmount = fn($v) => $v !== null && $v != 0.0 ? number_format((float) $v, 0, ',', ' ') : '';
        $rateClass = function ($rate) {
            if ($rate === null) return 'bg-light text-muted';
            $r = $rate * 100;
            if ($r >= 90 && $r <= 100) return 'bg-success text-success';
            if ($r >= 80 && $r < 90) return 'bg-warning text-warning';
            return 'bg-danger text-danger';
        };
        $rowsWithTaux = [];
        foreach ($rows as $row) {
            $taux = []; $totalMontant = 0; $totalCons = 0;
            for ($m = 1; $m <= 12; $m++) {
                $montant = (float) ($row['preMonths'][$m] ?? 0.0);
                $cons = (float) ($row['consMonths'][$m] ?? 0.0);
                $taux[$m] = $montant != 0.0 ? $cons / $montant : null;
                $totalMontant += $montant;
                $totalCons += $cons;
            }
            $rowsWithTaux[] = array_merge($row, ['taux' => $taux, 'totalMontant' => $totalMontant, 'totalCons' => $totalCons]);
        }
    @endphp

    {{-- Tableau 1 : Prévision / Réalisation / Écart --}}
    <div class="table-card mb-4">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-table"></i></div>
            <div>
                <p class="tch-title">État du suivi de la situation financière</p>
                <p class="tch-sub mb-0">Prévision / Réalisation / Écart par mois</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-exec mb-0" style="min-width:2500px;">
                <thead class="text-center align-middle">
                    <tr>
                        <th rowspan="2">Code</th>
                        <th rowspan="2" style="width:420px;">Libellés</th>
                        @foreach ($monthsLabels as $m)
                            <th colspan="3" style="background:rgba(243,144,83,.71); border-right:1px solid #ccc;">{{ $m }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($monthsLabels as $m)
                            <th class="small">Prévision</th>
                            <th class="small">Réalisation</th>
                            <th class="small" style="border-right:1px solid #ccc;">Écart</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rowsWithTaux as $row)
                        <tr>
                            <td class="text-center"><strong>{{ $row['code'] }}</strong></td>
                            <td><strong>{{ $row['libelle'] }}</strong></td>
                            @for ($mo = 1; $mo <= 12; $mo++)
                                @php
                                    $montant = $row['preMonths'][$mo] ?? 0;
                                    $cons = $row['consMonths'][$mo] ?? 0;
                                    $ecart = $montant - $cons;
                                @endphp
                                <td class="text-end">{{ number_format($montant, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($cons, 0, ',', ' ') }}</td>
                                <td class="text-end" style="border-right:1px solid #ccc;"><strong>{{ number_format($ecart, 0, ',', ' ') }}</strong></td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tableau 2 : Synthèse --}}
    <div class="table-card mb-4">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-chart-area"></i></div>
            <div>
                <p class="tch-title">Synthèse du suivi de la situation financière</p>
                <p class="tch-sub mb-0">Analyse par couleur</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-exec mb-0" style="min-width:1500px;">
                <thead class="text-center align-middle">
                    <tr>
                        <th style="width:100px;">Code</th>
                        <th style="min-width:300px;">Libellés</th>
                        @foreach ($monthsLabels as $m)
                            <th style="background:rgba(243,144,83,.71); min-width:130px;">{{ $m }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rowsWithTaux as $row)
                        <tr>
                            <td class="text-center"><strong>{{ $row['code'] }}</strong></td>
                            <td><strong>{{ $row['libelle'] }}</strong></td>
                            @for ($mo = 1; $mo <= 12; $mo++)
                                @php
                                    $montant = $row['preMonths'][$mo] ?? 0;
                                    $cons = $row['consMonths'][$mo] ?? 0;
                                    $ecartActuel = $montant - $cons;
                                    $cumul3Mois = 0; $cumul2Mois = 0;
                                    for ($i = 1; $i <= 3; $i++) {
                                        $nextMonth = $mo + $i;
                                        if ($nextMonth <= 12) {
                                            $ecartNext = ($row['preMonths'][$nextMonth] ?? 0) - ($row['consMonths'][$nextMonth] ?? 0);
                                            $cumul3Mois += $ecartNext;
                                            if ($i <= 2) $cumul2Mois += $ecartNext;
                                        }
                                    }
                                    if ($ecartActuel > $cumul3Mois) { $colorClass = 'bg-success text-success'; }
                                    elseif ($ecartActuel >= $cumul2Mois && $ecartActuel <= $cumul3Mois) { $colorClass = 'bg-warning text-warning'; }
                                    elseif ($ecartActuel < $cumul2Mois) { $colorClass = 'bg-danger text-danger'; }
                                    else { $colorClass = ''; }
                                @endphp
                                <td class="text-center {{ $colorClass }}">{{ number_format($ecartActuel, 0, ',', ' ') }}</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Légende --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-info-circle"></i></div>
            <p class="tch-title mb-0">Échelle d'analyse</p>
        </div>
        <div class="p-4">
            <div class="d-flex gap-3 flex-wrap">
                <span class="status-badge green">≥ 3 mois : Situation saine</span>
                <span class="status-badge orange">2-3 mois : Zone d'alerte</span>
                <span class="status-badge red">&lt; 2 mois : Situation critique</span>
                <span class="status-badge gray">N/A : Données non disponibles</span>
            </div>
        </div>
    </div>

</div>
@endsection
