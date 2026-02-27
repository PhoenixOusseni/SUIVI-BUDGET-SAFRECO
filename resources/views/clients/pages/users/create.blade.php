@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">NOUVEL UTILISATEUR</h4>
        <div class="card">
            <div class="card-body">
                @include('clients.pages.configs.menu_config')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gestion_users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="{{ old('nom') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="{{ old('prenom') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                value="{{ old('telephone') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Role<span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Sélectionnez un rôle</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Confirmation mot de passe
                                <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i>&thinsp;&thinsp; Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
