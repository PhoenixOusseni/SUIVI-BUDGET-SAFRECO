@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES COLLABORATEURS</h4>
        <div class="card">
            <div class="card-body">
                {{-- Menu des collaborateurs --}}
                @include('clients.pages.collaborateurs.menu_collaborateur')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de gestion des collaborateurs!</h4>
                            <p>Dans cette section, vous pouvez ajouter, modifier ou supprimer des collaborateurs pour
                                organiser vos données de manière efficace</p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
