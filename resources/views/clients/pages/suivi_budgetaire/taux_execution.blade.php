@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="container mt-2">
            <h2 class="mb-4 page-title">Suivi du Taux d’Exécution du Budget</h2>
            <p class="text-center">Bienvenue dans la section de suivi du taux d’exécution du budget. Ici, vous pouvez suivre
                et gérer votre budget
                efficacement.</p>
            <!-- Ajoutez ici le contenu spécifique au suivi du taux d’exécution du budget -->
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3 class="mb-0 text-danger">Taux d'exécution — Année {{ $year }}</h3>
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0 text-danger"></h3>
            <div>
                <a href="{{ route('tresorerie.export_excel') }}?year={{ $year }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Exporter vers Excel
                </a>
                <a href="{{ route('tresorerie.print_situation_financiere') }}?year={{ $year }}" target="_blank"
                    class="btn btn-success">
                    <i class="fas fa-print"></i>&nbsp;&nbsp;Imprimer
                </a>
            </div>
        </div>

        @php
            // Formatting helpers
            $fmtAmount = fn($v) => $v !== null && $v != 0.0 ? number_format((float) $v, 0, ',', ' ') : '';
            $fmtPercent = fn($v) => $v === null ? '' : number_format($v * 100, 1) . '%';

            // color by rate: >=95% green, 85-94.99% yellow, <85% red. null = grey
            $rateClass = function ($rate) {
                if ($rate === null) {
                    return 'bg-light text-muted';
                }
                $r = $rate * 100;
                if ($r >= 95) {
                    return 'bg-success text-success';
                }
                if ($r >= 85 && $r < 95) {
                    return 'bg-warning text-warning';
                }
                if ($r < 85) {
                    return 'bg-danger text-danger';
                }
            };
        @endphp

        {{--  Rest of the content remains unchanged     --}}
        @php
            // Formatting helpers
            $fmtAmount = fn($v) => $v !== null && $v != 0.0 ? number_format((float) $v, 0, ',', ' ') : '';
            // Calculer le taux restant (100% - taux d'exécution)
$fmtPercent = fn($v) => $v === null ? '' : number_format((1 - $v) * 100, 1) . '%';

            // color by rate: >=95% green, 85-94.99% yellow, <85% red. null = grey
            $ratesClass = function ($rate) {
                if ($rate === null) {
                    return $rate;
                }
                $var = $rate * 100;
                if ($var >= 95) {
                    return $var;
                }
                if ($var >= 85 && $var < 95) {
                    return $var;
                }
                if ($var < 85) {
                    return $var;
                }
            };
        @endphp

        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Etat du suivi de taux d'execution</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:2500px;">
                        <!-- reserve explicitement une largeur pour la colonne Libellés -->
                        <colgroup>
                            <col /> <!-- colonne 1 : placeholder (si tu as une colonne code avant libellé) -->
                            <col style="width:420px" /> <!-- colonne Libellés -->
                            @for ($i = 1; $i <= 12; $i++)
                                <col /> <!-- colonnes mois (laissent le navigateur distribuer la largeur restante) -->
                            @endfor
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2">Code</th>
                                <th rowspan="2" class="libelle-col">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th colspan="3"
                                        style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000;">
                                        {{ $m }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($monthsLabels as $m)
                                    <th class="small">Prévision</th>
                                    <th class="small">Réalisation</th>
                                    <th class="small" style="border-right: 1px solid #000;">Écart</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $row)
                                {{-- ligne d'en-tête pour code + libellé --}}
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col" style="min-width:300px;">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    {{-- on affiche prévision/réalisation/écart par mois (ou 3 colonnes groupées) --}}
                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($row['preMonths'][$mo] ?? ' ', 0, ',', ' ') }}</td>
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($row['realMonths'][$mo] ?? ' ', 0, ',', ' ') }}</td>
                                        <td class="text-end" style="min-width:100px; border-right: 1px solid #000;">
                                            <strong>{{ number_format($row['ecarts'][$mo] ?? ' ', 0, ',', ' ') }}</strong>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Etat du suivi des variations</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:2500px;">
                        <!-- reserve explicitement une largeur pour la colonne Libellés -->
                        <colgroup>
                            <col /> <!-- colonne 1 : placeholder (si tu as une colonne code avant libellé) -->
                            <col style="width:420px" /> <!-- colonne Libellés -->
                            @for ($i = 1; $i <= 12; $i++)
                                <col /> <!-- colonnes mois (laissent le navigateur distribuer la largeur restante) -->
                            @endfor
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2">Code</th>
                                <th rowspan="2" class="libelle-col">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th colspan="2"
                                        style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000;">
                                        {{ $m }}</th>
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
                            @foreach ($rows as $row)
                                {{-- ligne d'en-tête pour code + libellé --}}
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col" style="min-width:300px;">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    {{-- on affiche prévision/réalisation/écart par mois (ou 3 colonnes groupées) --}}
                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        <td class="text-end" style="min-width:100px;">
                                            {{ number_format($row['ecarts'][$mo] ?? ' ', 0, ',', ' ') }}</td>
                                        @php $var = $row['taux'][$mo]; @endphp
                                        <td class="text-center {{ $ratesClass($var) }}"
                                            style="border-right: 1px solid #000;">{{ $fmtPercent($var) }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Synthese du suivi des variations</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-exec" style="min-width:1500px;">
                        <!-- reserve explicitement une largeur pour la colonne Libellés -->
                        <colgroup>
                            <col /> <!-- colonne 1 : placeholder (si tu as une colonne code avant libellé) -->
                            <col style="width:420px" /> <!-- colonne Libellés -->
                            @for ($i = 1; $i <= 12; $i++)
                                <col /> <!-- colonnes mois (laissent le navigateur distribuer la largeur restante) -->
                            @endfor
                        </colgroup>

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th style="width:100px">Code</th>
                                <th class="libelle-col" style="min-width: 300px">Libellés</th>
                                @foreach ($monthsLabels as $m)
                                    <th colspan="3"
                                        style="background: rgba(243, 144, 83, 0.71); border-right: 1px solid #000; min-width: 130px">
                                        {{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $row)
                                {{-- ligne d'en-tête pour code + libellé --}}
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $row['code'] }}</strong></td>
                                    <td class="libelle-col">
                                        <strong>{{ $row['libelle'] }}</strong>
                                    </td>

                                    @for ($mo = 1; $mo <= 12; $mo++)
                                        @php $r = $row['taux'][$mo]; @endphp
                                        <td colspan="3" class="text-center {{ $rateClass($r) }}">{{ $fmtPercent($r) }}
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <h3 class="mb-3 text-success">Echelle d'analyse du taux d'exécution</h3>
                <table class="table table-bordered table-sm table-exec mt-4" style="max-width:1000px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Budget bien utilisé</th>
                            <th>À surveiller</th>
                            <th>Analyse urgente nécessaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>≥ 95%</td>
                            <td class="bg-success"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>85% - 94.99%</td>
                            <td></td>
                            <td class="bg-warning"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&lt; 85%</td>
                            <td></td>
                            <td></td>
                            <td class="bg-danger"></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Légende / Echelle --}}
                <div class="mt-4">
                    <h5 class="mb-3 mt-5">Légende des taux d'exécution :</h5>
                    <ul class="list-inline">
                        <li class="list-inline-item me-4">
                            <span class="badge bg-success text-white">≥ 95%</span> : Excellente exécution
                        </li>
                        <li class="list-inline-item me-4">
                            <span class="badge bg-warning text-dark">85% - 94.99%</span> : Exécution moyenne
                        </li>
                        <li class="list-inline-item me-4">
                            <span class="badge bg-danger text-white">&lt; 85%</span> : Faible exécution
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
