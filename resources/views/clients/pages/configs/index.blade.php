@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-cogs me-2"></i>Configuration de l'application</p>
                <p class="hero-sub">Gérez les paramètres et informations de votre organisation</p>
            </div>
        </div>
    </div>

    @include('clients.pages.configs.menu_config')

    <div class="row g-3">

        {{-- Aperçu société --}}
        <div class="col-md-5">
            <div class="table-card h-100">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-building"></i></div>
                    <div>
                        <p class="tch-title">Aperçu de la société</p>
                        <p class="tch-sub mb-0">Informations actuelles</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" class="img-fluid rounded-circle shadow"
                            width="120" alt="Logo société">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-building text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Nom de la société</small>
                                <strong>Ma Société</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Adresse</small>
                                <strong>123 Rue Principale, Ville, Pays</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-phone text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Téléphone</small>
                                <strong>+1234567890</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-envelope text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <strong>contact@masociete.com</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulaire modification --}}
        <div class="col-md-7">
            <div class="table-card h-100">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-edit"></i></div>
                    <div>
                        <p class="tch-title">Modifier les informations</p>
                        <p class="tch-sub mb-0">Mettez à jour les données de votre société</p>
                    </div>
                </div>
                <div class="p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Nom de la société</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" value="Ma Société">
                        </div>
                        <div class="mb-3">
                            <label for="companyAddress" class="form-label">Adresse de la société</label>
                            <input type="text" class="form-control" id="companyAddress" name="companyAddress" value="123 Rue Principale, Ville, Pays">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="companyPhone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="companyPhone" name="companyPhone" value="+1234567890">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="companyEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="companyEmail" name="companyEmail" value="contact@masociete.com">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="companyLogo" class="form-label">Logo de la société</label>
                            <input type="file" class="form-control" id="companyLogo" name="companyLogo">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
