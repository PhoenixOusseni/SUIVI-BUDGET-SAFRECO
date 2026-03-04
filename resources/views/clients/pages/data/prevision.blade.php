@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES PREVISIONS</h4>
        <div class="card">
            <div class="card-body">

                <div class="col-sm-12 mb-3">
                    <a href="{{ route('data.prevision.saisi_prevision') }}" class="btn btn-warning btn-sm"> <i
                            data-feather="plus"></i>&thinsp;&thinsp; Saisi des prévisions
                    </a>
                    <a href="{{ route('gestion_previsions.index') }}" class="btn btn-warning btn-sm"> <i
                            data-feather="align-right"></i>&thinsp;&thinsp; Liste des prévisions
                    </a>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de gestion des prévisions!</h4>
                            <p>Dans cette section, vous pouvez ajouter, modifier ou supprimer des prévisions pour
                                organiser vos données budgétaires de manière efficace</p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
