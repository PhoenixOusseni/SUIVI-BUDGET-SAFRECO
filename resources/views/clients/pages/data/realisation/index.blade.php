@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="text-center mb-3">
            <h4 class="page-title">LISTE DES REALISATIONS</h4>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Include the menu data partial --}}
                @include('clients.pages.data.menu_data')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="col-md-6">
                        {{-- Filters --}}
                        <form method="GET" action="{{ route('gestion_realisations.index') }}" class="row g-2 mb-4">
                            <div class="col-auto">
                                <select name="year" class="form-select">
                                    @php
                                        $currentYear = request('year', date('Y'));
                                        $start = date('Y') - 3;
                                        $end = date('Y') + 3;
                                    @endphp
                                    @for ($y = $start; $y <= $end; $y++)
                                        <option value="{{ $y }}"
                                            {{ (int) $currentYear === $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="ligne_budget_id" class="form-select">
                                    <option value="">Toutes les lignes budgétaires</option>
                                    @foreach ($lignesBudgets as $cb)
                                        <option value="{{ $cb->id }}"
                                            {{ request('ligne_budget_id') == $cb->id ? 'selected' : '' }}>
                                            {{ $cb->code }}{{ isset($cb->intitule) ? ' - ' . $cb->intitule : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-outline-secondary">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2">
                        {{-- Export Button --}}
                        <a href="#" class="btn btn-success">
                            <i class="fas fa-file-export"></i>&nbsp;&nbsp; Exporter
                        </a>
                    </div>
                </div>
                {{-- Realisations Table --}}
                @php
                    $months = [
                        1 => 'Janvier',
                        2 => 'Février',
                        3 => 'Mars',
                        4 => 'Avril',
                        5 => 'Mai',
                        6 => 'Juin',
                        7 => 'Juillet',
                        8 => 'Août',
                        9 => 'Septembre',
                        10 => 'Octobre',
                        11 => 'Novembre',
                        12 => 'Décembre',
                    ];

                    // Initialise totaux colonne
                    $colTotals = array_fill(1, 12, 0.0);
                    $grandTotal = 0.0;
                @endphp

                <div class="table-responsive" style="max-height:70vh; overflow:auto;">
                    <table class="table table-bordered table-hover table-striped table-sm table-exec align-middle mb-0" style="min-width:1600px;">
                        <thead class="table-light" style="position:sticky; top:0; z-index:5;">
                            <tr>
                                <th style="width:140px">CODE</th>
                                <th style="min-width:260px">Libellés</th>
                                @foreach ($months as $m)
                                    <th class="text-end" style="width:120px">{{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($groupedRealisations as $ligneId => $realis)
                                @php
                                    // Prenons la première réalisation (normalement une réalisation par ligne / année)
                                    $realisation = $realis->first();

                                    // Récupérer info de la ligne budgétaire (si existante)
                                    $ligne = $lignesMap->get($ligneId);

                                    // Construire tableau months 1..12 initialisé à 0
                                    $rowMonths = array_fill(1, 12, 0.0);

                                    // Remplir à partir des RealisationMonth
                                    if ($realisation && $realisation->relationLoaded('months')) {
                                        foreach ($realisation->months as $pm) {
                                            $rowMonths[(int) $pm->month] = (float) $pm->amount;
                                        }
                                    } elseif ($realisation) {
                                        foreach ($realisation->months()->get() as $pm) {
                                            $rowMonths[(int) $pm->month] = (float) $pm->amount;
                                        }
                                    }
                                    
                                    // Calcul total ligne
                                    $rowTotal = array_sum($rowMonths);

                                    // Accumulation totaux colonnes
                                    foreach ($rowMonths as $k => $v) {
                                        $colTotals[$k] += $v;
                                    }
                                    $grandTotal += $rowTotal;
                                @endphp

                                <tr>
                                    <td class="align-middle"><strong>{{ $ligne ? $ligne->code : '—' }}</strong></td>
                                    <td class="align-middle">
                                        {{ $ligne ? $ligne->intitule ?? ($ligne->name ?? '') : $realisation->description ?? '' }}
                                    </td>

                                    @foreach ($rowMonths as $amt)
                                        <td class="text-end">{{ $amt ? number_format($amt, 0, ',', ' ') : '' }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 2 + count($months) + 1 }}">Aucune réalisation enregistrée pour l'année
                                        {{ $year }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
