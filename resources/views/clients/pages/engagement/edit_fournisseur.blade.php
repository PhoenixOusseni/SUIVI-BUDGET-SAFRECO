@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-industry me-2"></i>Modifier un Fournisseur</p>
                <p class="hero-sub">Modification de : <strong>{{ $fournisseurFind->nom_fournisseur }}</strong></p>
            </div>
            <a href="{{ route('engagement.gestion_fournisseurs') }}" class="hero-badge">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Formulaire --}}
        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="fch-icon"><i class="fas fa-edit"></i></div>
                    <p class="fch-title">Modifier — {{ $fournisseurFind->code }}</p>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('engagement.update_fournisseur', $fournisseurFind->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ $fournisseurFind->code }}">
                        </div>
                        <div class="mb-3">
                            <label for="nom_fournisseur" class="form-label">Nom fournisseur <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur" value="{{ $fournisseurFind->nom_fournisseur }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_fournisseur" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact_fournisseur" name="contact_fournisseur" value="{{ $fournisseurFind->contact_fournisseur }}">
                        </div>
                        <div class="mb-4">
                            <label for="email_fournisseur" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_fournisseur" name="email_fournisseur" value="{{ $fournisseurFind->email_fournisseur }}">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                            <a href="{{ route('engagement.gestion_fournisseurs') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="col-lg-8">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="tch-icon"><i class="fas fa-industry"></i></div>
                    <div>
                        <p class="tch-title">Liste des fournisseurs</p>
                        <p class="tch-sub mb-0">{{ count($fournisseurs) }} fournisseur(s)</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom fournisseur</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fournisseurs as $fournisseur)
                                <tr class="{{ $fournisseur->id === $fournisseurFind->id ? 'table-active' : '' }}">
                                    <td><span class="status-badge blue">{{ $fournisseur->code }}</span></td>
                                    <td style="font-weight:600;">{{ $fournisseur->nom_fournisseur }}</td>
                                    <td>{{ $fournisseur->contact_fournisseur }}</td>
                                    <td>{{ $fournisseur->email_fournisseur }}</td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('engagement.edit_fournisseur', $fournisseur->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('engagement.delete_fournisseur', $fournisseur->id) }}" method="POST" style="display:inline-block;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer ce fournisseur ?');"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
