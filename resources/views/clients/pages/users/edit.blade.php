@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid px-3 pb-4">
        <div class="page-hero">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <p class="hero-title"><i class="fas fa-user-edit me-2"></i>Modifier un Utilisateur</p>
                    <p class="hero-sub">Modification de : <strong>{{ $user->nom }} {{ $user->prenom }}</strong></p>
                </div>
                <a href="{{ route('gestion_users.index') }}" class="hero-badge text-decoration-none text-white">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>

        @include('clients.pages.configs.menu_config')

        @if ($errors->any())
            <div class="error-banner">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card" style="max-width:800px;">
            <div class="form-card-header">
                <div class="fch-icon"><i class="fas fa-user-edit"></i></div>
                <p class="fch-title">Informations du compte</p>
            </div>
            <div class="form-card-body">
                <form action="{{ route('gestion_users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="{{ old('nom', $user->nom) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="{{ old('prenom', $user->prenom) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                value="{{ old('telephone', $user->telephone) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    Utilisateur</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Laisser vide pour ne pas modifier">
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                        <a href="{{ route('gestion_users.index') }}" class="btn-secondary-custom"><i
                                class="fas fa-times"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
