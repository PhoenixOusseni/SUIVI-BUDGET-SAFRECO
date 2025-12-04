@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">CONFIGURATION DE L'APPLICATION</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.configs.menu_config')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Bienvenue dans la section de configuration de l'application!</h4>
                            <p>Dans cette section, vous pouvez gérer les paramètres essentiels de l'application pour
                                personnaliser son fonctionnement selon vos besoins. Utilisez les options disponibles
                                pour mettre à jour les informations de la société, gérer les rubriques, les codes
                                budgétaires, les lignes budgétaires, ainsi que les utilisateurs et leurs rôles et
                                permissions.</p>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <strong>Info de la société:</strong> Gérez les informations de votre entreprise, telles que
                            le nom, l'adresse, le logo, et d'autres détails pertinents.
                        </p>
                        <div class="mb-4">
                            <h5 class="text-success border-bottom pb-2 mb-3">👤 Info de la société</h5>
                            <div class="row">
                                <div class="col-md-12 text-center mb-4">
                                    <img src="{{ asset('images/logo.png') }}" class="img-fluid rounded-circle shadow"
                                        width="150" alt="Avatar">
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Nom de la société :</strong>
                                            Ma Société</div>
                                        <div class="col-md-6"><strong>Adresse :</strong>
                                            123 Rue Principale, Ville, Pays
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Téléphone :</strong>
                                            +1234567890
                                        </div>
                                        <div class="col-md-6"><strong>Email :</strong>
                                            contact@masociete.com
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="companyName" class="small">Nom de la société</label>
                                <input type="text" class="form-control" id="companyName" value="Ma Société">
                            </div>
                            <div class="mb-3">
                                <label for="companyAddress" class="small">Adresse de la société</label>
                                <input type="text" class="form-control" id="companyAddress"
                                    value="123 Rue Principale, Ville, Pays">
                            </div>
                            <div class="mb-3">
                                <label for="companyPhone" class="small">Téléphone</label>
                                <input type="text" class="form-control" id="companyPhone" value="+1234567890">
                            </div>
                            <div class="mb-3">
                                <label for="companyEmail" class="small">Email</label>
                                <input type="email" class="form-control" id="companyEmail" value="contact@masociete.com">
                            </div>
                            <div class="mb-3">
                                <label for="companyLogo" class="small">Logo de la société</label>
                                <input type="file" class="form-control" id="companyLogo">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i>&thinsp;&thinsp; Enregistrer les modifications
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
