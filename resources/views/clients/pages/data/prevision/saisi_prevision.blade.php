@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">SAISI DES PREVISIONS</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.data.menu_data')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="small">Veuillez importer votre fichier de prévisions, veuillez sélectionner un
                                fichier ci-dessous (.xlsx, .xls)</h4>
                            <div class="row">
                                <form action="{{ route('previsions.import') }}" method="POST" enctype="multipart/form-data" class="d-flex">
                                    @csrf
                                    <div class="col-md-4">
                                        <input type="file" name="prevision_file" id="prevision_file" class="form-control"
                                            accept=".xlsx,.xls" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-warning" type="submit">
                                            <i class="fas fa-upload"></i>&nbsp;&nbsp; Importer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        {{-- Additional content can be added here --}}
                        <form action="{{ route('gestion_previsions.store') }}" method="POST">
                            @csrf
                            {{-- Ligne Budget and Date/Year Selection --}}
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="ligne_budget_id" class="form-label">Ligne budgétaire</label>
                                    <select name="ligne_budget_id" id="ligne_budget_id" class="form-select">
                                        <option value="">Sélectionner un élément</option>
                                        @foreach ($lignesBudgets as $ligne)
                                            <option value="{{ $ligne->id }}"
                                                {{ (int) old('ligne_budget_id') === $ligne->id ? 'selected' : '' }}>
                                                {{ $ligne->code }}{{ isset($ligne->intitule) ? ' - ' . $ligne->intitule : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date de la Prévision</label>
                                    <input type="date" id="date" name="date" value="{{ old('date') }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="year" class="form-label">Année Prévisionnel</label>
                                    <select name="year" id="year" class="form-select">
                                        @php
                                            $current = old('year', date('Y'));
                                        @endphp
                                        @foreach ($years as $y)
                                            <option value="{{ $y }}"
                                                {{ (int) $current === (int) $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Months grid --}}
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
                                $oldMonths = old('months', []);
                            @endphp

                            <div class="card mb-4">
                                <div class="card-body bg-light">
                                    <div class="row">
                                        @foreach ($months as $num => $label)
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">{{ $label }}</label>
                                                <input type="number" step="0.01" min="0"
                                                    name="months[{{ $num }}]" class="form-control"
                                                    value="{{ array_key_exists($num, (array) $oldMonths) ? $oldMonths[$num] : '' }}"
                                                    placeholder="Saisir le montant">
                                            </div>
                                            @if ($num % 4 === 0 && $num !== 12)
                                    </div>
                                    <div class="row">
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Notes (optional) --}}
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (optionnel)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Enregistrer la Prévision
                                </button>
                                <a href="#" class="btn btn-secondary ms-2">Retour à la
                                    liste</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
