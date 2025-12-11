@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid mt-4">

        <div class="container mt-3 text-center">
            <h1 class="mb-2 page-title">Suivi des Dépenses Rationnelles</h1>
            <p>Bienvenue dans la section de suivi des dépenses rationnelles. Ici, vous pouvez suivre et gérer vos dépenses
                efficacement.</p>
            <!-- Ajoutez ici le contenu spécifique au suivi des dépenses rationnelles -->
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3 class="mb-0 text-danger">Suivi des Dépenses Rationnelles — Année {{ $year }}</h3>
            <form method="GET" class="form-inline">
                <label for="yearSelect" class="mr-2">Année :</label>
                <select name="year" id="yearSelect" class="form-select mr-2" onchange="this.form.submit();">
                    @for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0 text-danger">Suivi des Dépenses Rationnelles</h3>
                    <div>
                        <a href="{{ route('tresorerie.export_excel') }}?year={{ $year }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Exporter vers Excel
                        </a>
                        <a href="{{ route('tresorerie.print_ration') }}?year={{ $year }}" target="_blank" class="btn btn-success">
                            <i class="fas fa-print"></i>&nbsp;&nbsp;Imprimer
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center" style="background-color: #fff9e6;"></th>
                                @foreach (range(1, 12) as $month)
                                    <th colspan="2" class="text-center" style="background-color: #e8b8a0;">
                                        <strong>{{ $monthsLabels[$month] }}</strong>
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                <th style="background-color: #fff9e6;"><strong>Code</strong></th>
                                <th style="background-color: #fff9e6;"><strong>Libellé</strong></th>
                                @foreach (range(1, 12) as $month)
                                    <th class="text-center" style="background-color: #fff9e6;"><strong>Montant</strong></th>
                                    <th class="text-center" style="background-color: #fff9e6;"><strong>Ratio</strong></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Helper function pour obtenir le montant prévisionnel pour une ligne et un mois
                                $getMontantPrevision = function ($ligne, $month) {
                                    if ($ligne->previsions && $ligne->previsions->count() > 0) {
                                        $prevision = $ligne->previsions->first();
                                        $monthData = $prevision->months->where('month', $month)->first();
                                        return $monthData ? (float) $monthData->amount : 0;
                                    }
                                    return 0;
                                };

                                // Calcul des totaux par mois
                                $totaux = [];
                                foreach (range(1, 12) as $month) {
                                    $totaux[$month] = 0;
                                    foreach ($groupedByCodeBudget as $groupData) {
                                        foreach ($groupData['lignes'] as $ligne) {
                                            $totaux[$month] += $getMontantPrevision($ligne, $month);
                                        }
                                    }
                                }
                            @endphp

                            @foreach ($groupedByCodeBudget as $codeBudgetId => $groupData)
                                @php
                                    $codeBudget = $groupData['codeBudget'];
                                    $lignes = $groupData['lignes'];
                                @endphp

                                <!-- Lignes du code budgétaire -->
                                @foreach ($lignes as $index => $ligne)
                                    <tr>
                                        <td><strong>{{ $ligne->code }}</strong></td>
                                        <td style="min-width:250px;"><em>{{ $ligne->intitule }}</em></td>
                                        @foreach (range(1, 12) as $month)
                                            @php $montant = $getMontantPrevision($ligne, $month); @endphp
                                            <td class="text-right" style="min-width:150px;">{{ number_format($montant, 0, ',', ' ') }}</td>
                                            <td class="text-center">
                                                @if ($totaux[$month] > 0)
                                                    {{ number_format(($montant / $totaux[$month]) * 100, 2, ',', ' ') }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <!-- Total par code budgétaire -->
                                <tr style="background-color: #d3d3d3;">
                                    <td><strong>{{ $codeBudget->code }}</strong></td>
                                    <td><strong><em>Total {{ $codeBudget->intitule }}</em></strong></td>
                                    @foreach (range(1, 12) as $month)
                                        @php
                                            $totalCodeBudget = 0;
                                            foreach ($lignes as $ligne) {
                                                $totalCodeBudget += $getMontantPrevision($ligne, $month);
                                            }
                                        @endphp
                                        <td class="text-right">
                                            <strong>{{ number_format($totalCodeBudget, 0, ',', ' ') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong>
                                                @if ($totaux[$month] > 0)
                                                    {{ number_format(($totalCodeBudget / $totaux[$month]) * 100, 2, ',', ' ') }}%
                                                @else
                                                    0%
                                                @endif
                                            </strong>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <!-- Total général -->
                            <tr style="background-color: #e8b8a0;">
                                <td colspan="2"><strong><em>DÉCAISSEMENTS TOTAUX</em></strong></td>
                                @foreach (range(1, 12) as $month)
                                    <td class="text-right">
                                        <strong>{{ number_format($totaux[$month], 2, ',', ' ') }}</strong>
                                    </td>
                                    <td class="text-center"><strong>100%</strong></td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
