@extends('clients.layouts.master')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    <div class="container py-4">
        <h2 class="page-title">MON PROFIL</h2>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 64px; height: 64px; background-color: #eef4fb; color: #003d82;">
                                <i data-feather="user"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $user->nom }} {{ $user->prenom }}</h4>
                                <div class="text-muted">{{ $user->email }}</div>
                            </div>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i data-feather="edit-2"></i> &thinsp; Modifier mon profil
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Informations détaillées</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Nom</label>
                                <div>{{ $user->nom }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Prénom</label>
                                <div>{{ $user->prenom }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Email</label>
                                <div>{{ $user->email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Téléphone</label>
                                <div>{{ $user->telephone ?: 'Non renseigné' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Statut email</label>
                                <div>
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success">Vérifié</span>
                                    @else
                                        <span class="badge bg-secondary">Non vérifié</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1">Membre depuis</label>
                                <div>{{ optional($user->created_at)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Modifier le profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" id="prenom" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Laisser vide pour ne pas modifier">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalElement = document.getElementById('editProfileModal');
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endif
@endsection
