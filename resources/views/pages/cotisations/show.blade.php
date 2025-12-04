@extends('admin.layouts.master')
@section('content')
    <header class="page-header page-header-dark pb-10"
        style="background: linear-gradient(90deg, rgb(86, 146, 113) 0%, rgb(67, 189, 91) 50%, rgb(97, 243, 67) 100%);">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Fiche du paiement N°: {{ $finds->code }}
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                            <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                            <div class="form-control ps-0 pointer">
                                {{ Carbon\Carbon::now()->format('d-m-Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-8">
                <!-- Tabbed dashboard card example-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="sbp-preview-content">
                            <div class="row">
                                <table class="table table-bordere table-striped" style="width: 100%;">
                                    <tr>
                                        <th>Membre</th>
                                        <td>{{ $finds->User->nom }} {{ $finds->User->prenom }}</td>
                                    </tr>
                                    <tr>
                                        <th>Code paiement</th>
                                        <td>{{ $finds->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date paiement</th>
                                        <td>{{ $finds->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Période</th>
                                        <td>{{ $finds->Annee->annee }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mode reglement</th>
                                        <td>{{ $finds->mode }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <th>Montant</th>
                                        <td>{{ number_format($finds->montant, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card mb-4">
                    <div class="card-header">Plus d'actions</div>
                    <div class="list-group list-group-flush small">
                        {{-- @if (Auth::user()->role !== 'Recouvrement') --}}
                            <a class="list-group-item list-group-item-action" href="{{ url('impression/print_cotisation/' . $finds->id) }}">
                                <i class="fas fa-print fa-fw text-success me-2"></i>
                                Imprimer le paiement
                            </a>
                        {{-- @endif --}}
                        {{-- @if (Auth::user()->role == 'Privilege' || Auth::user()->role == 'Secretaire') --}}
                            <a class="list-group-item list-group-item-action" href="">
                                <i class="fas fa-edit fa-fw text-warning me-2"></i>
                                Modifier le paiement
                            </a>
                            <a class="list-group-item list-group-item-action" href="" onclick="return confirm('Voulez vous vraiment supprimer cet element ?')">
                                <i class="fas fa-trash fa-fw text-danger me-2"></i>
                                Supprimer le paiement
                            </a>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
