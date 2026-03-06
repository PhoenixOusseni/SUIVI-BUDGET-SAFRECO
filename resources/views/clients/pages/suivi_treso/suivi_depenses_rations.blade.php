@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-receipt me-2"></i>Suivi des Dépenses Rationnelles — {{ $year }}</p>
                <p class="hero-sub">Analyse des dépenses et ratios par ligne budgétaire</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <form class="d-flex gap-2 align-items-center" method="GET" action="#">
                    <select name="year" id="yearSelect" class="form-select" style="width:auto;" onchange="this.form.submit();">
                        @for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
                <a href="{{ route('tresorerie.export_excel') }}?year={{ $year }}" class="btn-export"><i class="fas fa-file-excel"></i> Exporter</a>
                <a href="{{ route('tresorerie.print_ration') }}?year={{ $year }}" target="_blank" class="btn-print"><i class="fas fa-print"></i> Imprimer</a>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-receipt"></i></div>
            <div>
                <p class="tch-title">Suivi des dépenses rationnelles</p>
                <p class="tch-sub mb-0">Montants et ratios par mois</p>
            </div>
        </div>
        <div class="table-responsive">
            @php
                $getMontantPrevision = function ($ligne, $month) {
                    if ($ligne->previsions && $ligne->previsions->count() > 0) {
                        $prevision = $ligne->previsions->first();
                        $monthData = $prevision->months->where('month', $month)->first();
                        return $monthData ? (float) $monthData->amount : 0;
                    }
                    return 0;
                };
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
            <table class="table table-bordered table-sm table-exec mb-0" style="min-width:2500px;">
                <thead class="text-center align-middle">
                    <tr>
                        <th colspan="2" style="background:var(--surface);"></th>
                        @foreach (range(1, 12) as $month)
                            <th colspan="2" style="background:rgba(243,144,83,.71);">{{ $monthsLabels[$month] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        @foreach (range(1, 12) as $month)
                            <th>Montant</th>
                            <th>Ratio</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedByCodeBudget as $codeBudgetId => $groupData)
                        @php $codeBudget = $groupData['codeBudget']; $lignes = $groupData['lignes']; @endphp
                        @foreach ($lignes as $ligne)
                            <tr>
                                <td><strong>{{ $ligne->code }}</strong></td>
                                <td style="min-width:250px;"><em>{{ $ligne->intitule }}</em></td>
                                @foreach (range(1, 12) as $month)
                                    @php $montant = $getMontantPrevision($ligne, $month); @endphp
                                    <td class="text-end" style="min-width:120px;">{{ number_format($montant, 0, ',', ' ') }}</td>
                                    <td class="text-center">
                                        {{ $totaux[$month] > 0 ? number_format(($montant / $totaux[$month]) * 100, 2, ',', ' ') . '%' : '0%' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr style="background:rgba(0,61,130,.07);">
                            <td><strong>{{ $codeBudget->code }}</strong></td>
                            <td><strong><em>Total {{ $codeBudget->intitule }}</em></strong></td>
                            @foreach (range(1, 12) as $month)
                                @php
                                    $totalCB = 0;
                                    foreach ($lignes as $lg) { $totalCB += $getMontantPrevision($lg, $month); }
                                @endphp
                                <td class="text-end"><strong>{{ number_format($totalCB, 0, ',', ' ') }}</strong></td>
                                <td class="text-center">
                                    <strong>{{ $totaux[$month] > 0 ? number_format(($totalCB / $totaux[$month]) * 100, 2, ',', ' ') . '%' : '0%' }}</strong>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr style="background:rgba(243,144,83,.25);">
                        <td colspan="2"><strong><em>DÉCAISSEMENTS TOTAUX</em></strong></td>
                        @foreach (range(1, 12) as $month)
                            <td class="text-end"><strong>{{ number_format($totaux[$month], 0, ',', ' ') }}</strong></td>
                            <td class="text-center"><strong>100%</strong></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
