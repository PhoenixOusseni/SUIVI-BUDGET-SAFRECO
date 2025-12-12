@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <div class="container mt-3 text-center">
            <h1 class="mb-2 page-title">GESTION DES FOURNISSEURS</h1>
            <p>
                Bienvenue dans la section de gestion des fournisseurs. Ici, vous pouvez gérer les informations relatives à
                vos fournisseurs, suivre leurs performances et assurer une collaboration efficace.
            </p>
            <!-- Ajoutez ici le contenu spécifique au suivi de la situation financière -->
        </div>

        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">Formulaire de saisi des Fournisseurs</h5>
                        <form method="POST" action="{{ route('engagement.fournisseurs.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="code" class="small">Code</label>
                                        <input type="text" class="form-control" id="code"
                                            placeholder="Code fournisseur" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nom_fournisseur" class="small">Nom fournisseur</label>
                                        <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="contact_fournisseur" class="small">Contact fournisseur</label>
                                    <input type="text" class="form-control" id="contact_fournisseur" name="contact_fournisseur">
                                </div>
                                <div class="col-md-6">
                                    <label for="email_fournisseur" class="small">Email fournisseur</label>
                                    <input type="email" class="form-control" id="email_fournisseur" name="email_fournisseur">
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i>&nbsp; Enregistrer le Fournisseur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">Liste des Fournisseurs</h5>
                        <table class="table table-striped" id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom fournisseur</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fournisseurs as $fournisseur)
                                    <tr>
                                        <td>{{ $fournisseur->code }}</td>
                                        <td>{{ $fournisseur->nom_fournisseur }}</td>
                                        <td>{{ $fournisseur->contact_fournisseur }}</td>
                                        <td>{{ $fournisseur->email_fournisseur }}</td>
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
