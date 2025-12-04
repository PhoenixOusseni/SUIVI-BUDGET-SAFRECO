@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES PREVISIONS & REALISATIONS</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.data.menu_data')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de gestion des prévisions et réalisations!</h4>
                            <p>Dans cette section, vous pouvez ajouter, modifier ou supprimer des prévisions et réalisations pour
                                organiser vos données budgétaires de manière efficace</p>
                            <hr>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
