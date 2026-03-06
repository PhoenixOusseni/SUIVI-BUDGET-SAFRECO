@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-balance-scale me-2"></i>État de Suivi Budgétaire — {{ $year }}</p>
                <p class="hero-sub">Suivi des prévisions de recettes et de dépenses</p>
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
                <a href="{{ route('budget.print', ['year' => $year]) }}" target="_blank" class="btn-print"><i class="fas fa-print"></i> Imprimer</a>
            </div>
        </div>
    </div>

    {{-- ENCAISSEMENTS --}}
    @if (empty($encaissementsByRubrique))
        <div class="info-banner mb-4">Aucune prévision d'encaissement pour {{ $year }}.</div>
    @else
        <div class="table-card mb-4">
            <div class="table-card-header">
                <div class="tch-icon"><i class="fas fa-arrow-down"></i></div>
                <div>
                    <p class="tch-title">Suivi prévisions des recettes</p>
                    <p class="tch-sub mb-0">Encaissements — {{ $year }}</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-exec table-bordered mb-0" style="min-width:1750px;">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th style="width:250px;">Libellés</th>
                            @foreach ($months as $m)
                                <th class="text-end" style="width:110px;">{{ $m }}</th>
                            @endforeach
                            <th class="text-center" style="width:150px; background:rgba(241,180,110,.5);">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encaissementsByRubrique as $rubId => $group)
                            @foreach ($group['items'] as $row)
                                <tr>
                                    <td><strong>{{ $row['ligne_code'] }}</strong></td>
                                    <td>{{ $row['ligne_label'] }}</td>
                                    @foreach ($row['months'] as $amt)
                                        <td class="text-end">{{ $amt ? number_format($amt, 0, ',', ' ') : '' }}</td>
                                    @endforeach
                                    <td class="text-center" style="background:rgba(241,180,110,.25);">
                                        <strong>{{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- DECAISSEMENTS --}}
    @if (empty($decaissementsByRubrique))
        <div class="info-banner mb-4">Aucune prévision de décaissement pour {{ $year }}.</div>
    @else
        <div class="table-card mb-4">
            <div class="table-card-header">
                <div class="tch-icon"><i class="fas fa-arrow-up"></i></div>
                <div>
                    <p class="tch-title">Suivi prévisions des dépenses</p>
                    <p class="tch-sub mb-0">Décaissements — {{ $year }}</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-exec table-bordered mb-0" style="min-width:1750px;">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th style="width:250px;">Libellés</th>
                            @foreach ($months as $m)
                                <th class="text-end" style="width:110px;">{{ $m }}</th>
                            @endforeach
                            <th class="text-center" style="width:150px; background:rgba(241,180,110,.5);">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($decaissementsByRubrique as $rubId => $group)
                            @foreach ($group['items'] as $row)
                                <tr>
                                    <td><strong>{{ $row['ligne_code'] }}</strong></td>
                                    <td>{{ $row['ligne_label'] }}</td>
                                    @foreach ($row['months'] as $amt)
                                        <td class="text-end">{{ $amt ? number_format($amt, 0, ',', ' ') : '' }}</td>
                                    @endforeach
                                    <td class="text-center" style="background:rgba(241,180,110,.25);">
                                        <strong>{{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
