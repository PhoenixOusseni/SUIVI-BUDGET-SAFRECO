@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">MODIFIER UN UTILISATEUR</h4>
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

                <form action="{{ route('gestion_users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
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
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Laisser vide pour ne pas modifier">
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i>&thinsp;&thinsp; Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
