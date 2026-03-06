@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-user-edit me-2"></i>Modifier un Adhérant</p>
                <p class="hero-sub">Modification de : <strong>{{ $adherant->nom_adherant }}</strong></p>
            </div>
            <a href="{{ route('gestion_adherants.index') }}" class="hero-badge">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="error-banner"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="form-card" style="max-width:720px;">
        <div class="form-card-header">
            <div class="fch-icon"><i class="fas fa-user-edit"></i></div>
            <p class="fch-title">Informations de l'adhérant</p>
        </div>
        <div class="form-card-body">
            <form action="{{ route('gestion_adherants.update', $adherant->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $adherant->code) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="nom_adherant" class="form-label">Nom adhérant <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom_adherant" name="nom_adherant" value="{{ old('nom_adherant', $adherant->nom_adherant) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_adherant" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="contact_adherant" name="contact_adherant" value="{{ old('contact_adherant', $adherant->contact_adherant) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email_adherant" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_adherant" name="email_adherant" value="{{ old('email_adherant', $adherant->email_adherant) }}">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                    <a href="{{ route('gestion_adherants.index') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
