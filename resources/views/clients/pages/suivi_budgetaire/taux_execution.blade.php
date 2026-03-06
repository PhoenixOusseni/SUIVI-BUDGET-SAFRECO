@extends('clients.layouts.master')

@section('content')

<div class="container-fluid px-3 pb-4">

    {{-- ── Page hero ─────────────────────────────────── --}}
    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-chart-line me-2"></i>Suivi du Taux d'Exécution Budgétaire</p>
                <p class="hero-sub">Analyse mensuelle des prévisions, réalisations et écarts — Année <strong>{{ $year }}</strong></p>
            </div>
            <span class="hero-badge"><i class="fas fa-calendar-alt"></i> {{ $year }}</span>
        </div>
    </div>

    {{-- ── Toolbar : filtre + actions ───────────────── --}}
    <div class="toolbar-card">
        {{-- Filtre année --}}
        <span class="toolbar-label"><i class="fas fa-sliders-h me-1"></i>Filtrer</span>
        <form class="d-flex align-items-center gap-2 flex-grow-1" method="GET" action="#">
            <select name="year" class="form-select">
                @for ($y = date('Y') - 3; $y <= date('Y') + 3; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-filter"></i> Appliquer
            </button>
        </form>

        {{-- Actions --}}
        <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
            <a href="{{ route('tresorerie.export_excel') }}?year={{ $year }}" class="btn-export">
                <i class="fas fa-file-excel"></i> Exporter Excel
            </a>
            <a href="{{ route('tresorerie.print_situation_financiere') }}?year={{ $year }}" target="_blank" class="btn-print">
                <i class="fas fa-print"></i> Imprimer
            </a>
        </div>
    </div>

    @php
        $fmtAmount  = fn($v) => ($v !== null && $v != 0.0) ? number_format((float) $v, 0, ',', ' ') : '';
        $fmtPercent = fn($v) => $v === null ? '' : number_format($v * 100, 1) . '%';
        $fmtEcart   = function($v) {
            if ($v === null || $v == 0) return '';
            return ($v > 0 ? '+' : '') . number_format((float) $v, 0, ',', ' ');
        };
        $ecartClass = fn($v) => ($v === null || $v == 0) ? '' : ($v >= 0 ? 'ecart-pos' : 'ecart-neg');
    @endphp

    {{-- ── Table card ──────────────────────────────────── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-table"></i></div>
            <div>
                <p class="tch-title">État du suivi de taux d'exécution</p>
                <p class="tch-sub mb-0">Prévision · Réalisation · Écart — par mois</p>
            </div>
        </div>

        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-sm table-exec mb-0" style="min-width:2600px;">
                <colgroup>
                    <col style="width:80px" />
                    <col style="width:380px" />
                    @for ($i = 1; $i <= 12; $i++)
                        <col /><col /><col />
                    @endfor
                </colgroup>

                <thead class="text-center align-middle">
                    <tr>
                        <th rowspan="2">Code</th>
                        <th rowspan="2" style="text-align:left; padding-left:.75rem;">Libellés</th>
                        @foreach ($monthsLabels as $m)
                            <th colspan="3" class="month-head">{{ $m }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($monthsLabels as $m)
                            <th>Prévision</th>
                            <th>Réalisation</th>
                            <th style="border-right: 2px solid #cbd5e1;">Écart</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td class="col-code">{{ $row['code'] }}</td>
                            <td class="col-libelle" style="padding-left:.75rem;">{{ $row['libelle'] }}</td>

                            @for ($mo = 1; $mo <= 12; $mo++)
                                @php
                                    $prev  = $row['preMonths'][$mo]  ?? null;
                                    $real  = $row['realMonths'][$mo] ?? null;
                                    $ecar  = $row['ecarts'][$mo]     ?? null;
                                @endphp
                                <td class="text-end prev">{{ $fmtAmount($prev) }}</td>
                                <td class="text-end real">{{ $fmtAmount($real) }}</td>
                                <td class="text-end border-month {{ $ecartClass($ecar) }}">
                                    <strong>{{ $fmtEcart($ecar) }}</strong>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
