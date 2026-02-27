@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES RÉALISATIONS</h4>
        <div class="card">
            <div class="card-body">

                <div class="col-sm-12 mb-3">
                    <a href="{{ route('data.realisation.saisi_realisation') }}" class="btn btn-secondary"><i
                            data-feather="plus"></i>&thinsp;&thinsp; Saisi des réalisations
                    </a>
                    <a href="{{ route('gestion_realisations.index') }}" class="btn btn-secondary"><i
                            data-feather="align-right"></i>&thinsp;&thinsp; Liste des réalisations
                    </a>
                    <a href="#" class="btn btn-light"><i data-feather="align-right"></i>&thinsp;&thinsp;
                        Parametres</a>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de gestion des réalisations!</h4>
                            <p>Dans cette section, vous pouvez ajouter, modifier ou supprimer des réalisations pour
                                organiser vos données budgétaires de manière efficace</p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
