@extends('clients.layouts.master')

@section('content')
    <div class="container-fluid px-3 pb-4">

        <div class="page-hero">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <p class="hero-title"><i class="fas fa-industry me-2"></i>Gestion des Fournisseurs</p>
                    <p class="hero-sub">Gérez les informations et performances de vos fournisseurs</p>
                </div>
            </div>
        </div>

        @include('clients.pages.configs.menu_config')

        <div class="row g-3">
            {{-- Formulaire --}}
            <div class="col-lg-4">
                <div class="form-card h-100">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-plus"></i></div>
                        <p class="fch-title">Nouveau fournisseur</p>
                    </div>
                    <div class="form-card-body">
                        <form method="POST" action="{{ route('engagement.fournisseurs.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    placeholder="Code fournisseur">
                            </div>
                            <div class="mb-3">
                                <label for="nom_fournisseur" class="form-label">Nom fournisseur <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_fournisseur" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact_fournisseur"
                                    name="contact_fournisseur">
                            </div>
                            <div class="mb-4">
                                <label for="email_fournisseur" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_fournisseur" name="email_fournisseur">
                            </div>
                            <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i>
                                Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="col-lg-8">
                <div class="table-card h-100">
                    <div class="table-card-header">
                        <div class="tch-icon"><i class="fas fa-industry"></i></div>
                        <div>
                            <p class="tch-title">Liste des fournisseurs</p>
                            <p class="tch-sub mb-0">{{ count($fournisseurs) }} fournisseur(s)</p>
                        </div>
                    </div>
                    <div class="table-responsive p-3">
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
                                    <tr>
                                        <td><span class="status-badge blue">{{ $fournisseur->code }}</span></td>
                                        <td style="font-weight:600;">{{ $fournisseur->nom_fournisseur }}</td>
                                        <td>{{ $fournisseur->contact_fournisseur }}</td>
                                        <td>{{ $fournisseur->email_fournisseur }}</td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('engagement.edit_fournisseur', $fournisseur->id) }}"
                                                    class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                                <form
                                                    action="{{ route('engagement.delete_fournisseur', $fournisseur->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-danger-custom"
                                                        onclick="return confirm('Supprimer ce fournisseur ?');"><i
                                                            class="fas fa-trash-alt"></i></button>
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
