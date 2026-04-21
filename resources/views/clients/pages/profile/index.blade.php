@extends('clients.layouts.master')

@section('content')
    @php $user = auth()->user(); @endphp

    <div class="container-fluid px-3 pb-4">
        <div class="page-hero">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <p class="hero-title"><i class="fas fa-user-circle me-2"></i>Mon Profil</p>
                    <p class="hero-sub">Consultez et modifiez vos informations personnelles</p>
                </div>
                <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="error-banner mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3" style="max-width:900px;">
            {{-- Avatar + Nom --}}
            <div class="col-12">
                <div class="form-card">
                    <div class="form-card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width:64px; height:64px; background:linear-gradient(135deg, var(--blue), #c47800); color:#0f172a; font-size:1.6rem;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-size:1.2rem; font-weight:700; color:var(--blue);">{{ $user->nom }}
                                    {{ $user->prenom }}</div>
                                <div class="text-muted" style="font-size:.9rem;">{{ $user->email }}</div>
                                @if ($user->email_verified_at)
                                    <span class="status-badge green mt-1">Vérifié</span>
                                @else
                                    <span class="status-badge gray mt-1">Non vérifié</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informations détaillées --}}
            <div class="col-12">
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-info-circle"></i></div>
                        <p class="fch-title">Informations détaillées</p>
                    </div>
                    <div class="form-card-body">
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
                                        <span class="status-badge green">Vérifié</span>
                                    @else
                                        <span class="status-badge gray">Non vérifié</span>
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

    {{-- Modal Modifier --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 32px rgba(240,168,0,.2);">
                <div class="modal-header modal-header-blue">
                    <h5 class="modal-title text-white"><i class="fas fa-edit me-2 text-white"></i>Modifier mon profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" id="nom" name="nom" class="form-control"
                                    value="{{ old('nom', $user->nom) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" id="prenom" name="prenom" class="form-control"
                                    value="{{ old('prenom', $user->prenom) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" id="telephone" name="telephone" class="form-control"
                                    value="{{ old('telephone', $user->telephone) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Laisser vide pour ne pas modifier">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 px-4 pb-4">
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i>
                            Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                modal.show();
            });
        </script>
    @endif
@endsection
