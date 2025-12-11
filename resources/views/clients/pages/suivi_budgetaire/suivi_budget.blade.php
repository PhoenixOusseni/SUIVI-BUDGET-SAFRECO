@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3 class="mb-0 page-title text-center">Etat de suivi budgetaire — Année {{ $year }}</h3>
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
                <a href="" class="btn btn-success">
                    <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Exporter vers Excel
                </a>
                <a href="{{ route('budget.print', ['year' => $year]) }}" target="_blank"
                    class="btn btn-success">
                    <i class="fas fa-print"></i>&nbsp;&nbsp;Imprimer
                </a>
            </div>
        </div>

        <div class="row">
            {{-- ENCAISSEMENTS --}}
            <div class="col-12 mb-5">

                @if (empty($encaissementsByRubrique))
                    <div class="alert alert-secondary">Aucune prévision d'encaissement pour {{ $year }}.</div>
                @else
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="text-danger text-center mb-3">Suivi prévisions des recettes</h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-exec table-bordered mb-0" style="min-width:1750px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code</th>
                                            <th style="width:250px">Libellés</th>
                                            @foreach ($months as $m)
                                                <th class="text-end" style="width:110px">{{ $m }}</th>
                                            @endforeach
                                            <th class="text-center" style="width:150px; background: rgb(241, 180, 110)">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encaissementsByRubrique as $rubId => $group)
                                            @foreach ($group['items'] as $row)
                                                <tr>
                                                    <td class="align-middle"><strong>{{ $row['ligne_code'] }}</strong></td>
                                                    <td class="align-middle">{{ $row['ligne_label'] }}</td>
                                                    @foreach ($row['months'] as $amt)
                                                        <td class="text-end">
                                                            {{ $amt ? number_format($amt, 0, ',', ' ') : '' }}
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center"
                                                        style="width:150px; background: rgb(241, 180, 110)">
                                                        <strong>{{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- DECAISSEMENTS --}}
            <div class="col-12 mb-5">

                @if (empty($decaissementsByRubrique))
                    <div class="alert alert-secondary">Aucune prévision de décaissement pour {{ $year }}.</div>
                @else
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="text-danger text-center mb-3">Suivi prévisions des dépenses</h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-exec table-bordered mb-0" style="min-width:1750px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code</th>
                                            <th style="width:250px">Libellés</th>
                                            @foreach ($months as $m)
                                                <th class="text-end" style="width:110px">{{ $m }}</th>
                                            @endforeach
                                            <th class="text-center" style="width:150px; background: rgb(241, 180, 110)">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($decaissementsByRubrique as $rubId => $group)
                                            @foreach ($group['items'] as $row)
                                                <tr>
                                                    <td class="align-middle"><strong>{{ $row['ligne_code'] }}</strong></td>
                                                    <td class="align-middle">{{ $row['ligne_label'] }}</td>
                                                    @foreach ($row['months'] as $amt)
                                                        <td class="text-end">
                                                            {{ $amt ? number_format($amt, 0, ',', ' ') : '' }}
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center"
                                                        style="width:150px; background: rgb(241, 180, 110)">
                                                        <strong>{{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
