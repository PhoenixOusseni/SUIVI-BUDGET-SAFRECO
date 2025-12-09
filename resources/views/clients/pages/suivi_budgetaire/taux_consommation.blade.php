@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="container mt-2">
            <h2 class="mb-4 page-title">Suivi du Taux de Consommation des Subventions</h2>
            <p class="text-center">Bienvenue dans la section de suivi du taux de consommation des subventions. Ici, vous pouvez suivre
                et gérer votre budget efficacement.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3 class="mb-0 text-danger">Taux de consommation — Année {{ $year }}</h3>
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

        @php
            // Formatting helpers
            $fmtAmount = fn($v) => $v !== null && $v != 0.0 ? number_format((float) $v, 0, ',', ' ') : '';

            // Calculer le taux restant (100% - taux de consommation) - pour le 2ème tableau
            $fmtPercent = fn($v) => $v === null ? '' : number_format((1 - $v) * 100, 1) . '%';

            // Calculer le taux de consommation global (taux consommé) - pour le 3ème tableau
            $fmtPercentGlobal = fn($v) => $v === null ? '' : number_format($v * 100, 1) . '%';

            // color by rate: 90-100% green, 80-89% yellow, <80% ou >110% red. null = grey
            $rateClass = function ($rate) {
                if ($rate === null) {
                    return 'bg-light text-muted';
                }
                $r = $rate * 100;
                // Vert : 90% - 100%
                if ($r >= 90 && $r <= 100) {
                    return 'bg-success text-success';
                }
                // Jaune : 80% - 89%
                if ($r >= 80 && $r < 90) {
                    return 'bg-warning text-warning';
                }
                // Rouge : < 80% ou > 110%
                if ($r < 80 || $r > 110) {
                    return 'bg-danger text-danger';
                }
                // Orange pour 101% - 110% (zone intermédiaire avant le rouge)
                return 'bg-warning text-warning';
            };

            // Calculer les taux de consommation pour chaque ligne
            $rowsWithTaux = [];
            foreach ($rows as $row) {
                $taux = [];
                $totalMontant = 0;
                $totalCons = 0;

                for ($m = 1; $m <= 12; $m++) {
                    $montant = (float) ($row['preMonths'][$m] ?? 0.0);
                    $cons = (float) ($row['consMonths'][$m] ?? 0.0);

                    $taux[$m] = $montant != 0.0 ? $cons / $montant : null;
                    $totalMontant += $montant;
                    $totalCons += $cons;
                }

                $rowsWithTaux[] = array_merge($row, [
                    'taux' => $taux,
                    'totalMontant' => $totalMontant,
                    'totalCons' => $totalCons
                ]);
            }
        @endphp

        {{-- Premier tableau : Montant et Consommation --}}
        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Etat du suivi de taux de consommation des subventions</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:2500px;">
                        <colgroup>
                            <col />
                            <col style="width:420px" />
                            @for ($i = 1; $i <= 12; $i++)
                                <col />
                            @endfor
                            <col />
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2">Code</th>
                                <th rowspan="2" class="libelle-col">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th colspan="3" style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000;">{{ $m }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($monthsLabels as $m)
                                    <th class="small">Montant</th>
                                    <th class="small">Consommation</th>
                                    <th class="small" style="border-right: 1px solid #000;">Écart</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rowsWithTaux as $row)
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col" style="min-width:300px;">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        @php
                                            $montant = $row['preMonths'][$mo] ?? 0;
                                            $cons = $row['consMonths'][$mo] ?? 0;
                                            $ecart = $montant - $cons;
                                        @endphp
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($montant, 0, ',', ' ') }}</td>
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($cons, 0, ',', ' ') }}</td>
                                        <td class="text-end" style="min-width:100px; border-right: 1px solid #000;">
                                            <strong>{{ number_format($ecart, 0, ',', ' ') }}</strong>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Deuxième tableau : Variations et Taux de consommation --}}
        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Etat du suivi des variations</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:2500px;">
                        <colgroup>
                            <col />
                            <col style="width:420px" />
                            @for ($i = 1; $i <= 12; $i++)
                                <col />
                            @endfor
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2">Code</th>
                                <th rowspan="2" class="libelle-col">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th colspan="2" style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000;">{{ $m }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($monthsLabels as $m)
                                    <th class="small">Variation</th>
                                    <th class="small" style="border-right: 1px solid #000;">Taux</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rowsWithTaux as $row)
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col" style="min-width:300px;">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        @php
                                            $montant = $row['preMonths'][$mo] ?? 0;
                                            $cons = $row['consMonths'][$mo] ?? 0;
                                            $variation = $montant - $cons;
                                            $r = $row['taux'][$mo];
                                        @endphp
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($variation, 0, ',', ' ') }}
                                        </td>
                                        <td class="text-center" style="border-right: 1px solid #000;">
                                            {{ $fmtPercent($r) }}
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Troisième tableau : Synthèse du suivi des taux de consommation --}}
        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Synthèse du suivi des taux de consommation</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:1500px;">
                        <colgroup>
                            <col />
                            <col style="width:420px" />
                            @for ($i = 1; $i <= 12; $i++)
                                <col />
                            @endfor
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th style="width:100px">Code</th>
                                <th class="libelle-col" style="min-width: 300px">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000; min-width: 130px">{{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rowsWithTaux as $row)
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        @php $r = $row['taux'][$mo]; @endphp
                                        <td class="text-center {{ $rateClass($r) }}">{{ $fmtPercentGlobal($r) }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Échelle d'analyse --}}
        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Échelle d'analyse du taux de consommation</h3>
                <table class="table table-bordered table-sm table-exec mt-4" style="max-width:1000px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Bonne exécution</th>
                            <th>Vigilance</th>
                            <th>Risque de perte de financement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>90% - 100%</td>
                            <td class="bg-success"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>80% - 89%</td>
                            <td></td>
                            <td class="bg-warning"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&lt; 80% ou &gt; 110%</td>
                            <td></td>
                            <td></td>
                            <td class="bg-danger"></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Légende --}}
                <div class="mt-4">
                    <h5 class="mb-3 mt-5">Légende des taux de consommation :</h5>
                    <ul class="list-inline">
                        <li class="list-inline-item me-4">
                            <span class="badge bg-success text-white">90% - 100%</span> : Bonne exécution
                        </li>
                        <li class="list-inline-item me-4">
                            <span class="badge bg-warning text-dark">80% - 89%</span> : Vigilance
                        </li>
                        <li class="list-inline-item me-4">
                            <span class="badge bg-danger text-white">&lt; 80% ou &gt; 110%</span> : Risque de perte de financement
                        </li>
                        <li class="list-inline-item me-4">
                            <span class="badge bg-light text-muted">N/A</span> : Données non disponibles
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
