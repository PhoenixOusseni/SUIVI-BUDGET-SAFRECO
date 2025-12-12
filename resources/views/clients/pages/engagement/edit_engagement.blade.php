@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <div class="container mt-3 text-center">
            <h1 class="mb-2 page-title">GESTION DES ENGAGEMENTS</h1>
            <p>
                Bienvenue dans la section de gestion des engagements. Ici, vous pouvez gérer les engagements financiers,
                suivre les dépôts et assurer un suivi efficace des pièces jointes.
            </p>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success mb-3">Modification de l'engagement {{ $engagementFind->code }}</h5>
                        <form method="POST" action="{{ route('engagement.update_engagement', $engagementFind->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="code" class="small">Code</label>
                                <input type="text" class="form-control" id="code" value="{{ $engagementFind->code }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="date_depot" class="small">Date de dépôt <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_depot" name="date_depot" value="{{ $engagementFind->date_depot }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="montant" class="small">Montant <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant de l'engagement" value="{{ $engagementFind->montant }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fournisseur_id" class="small">Fournisseur <span class="text-danger">*</span></label>
                                <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" {{ $engagementFind->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom_fournisseur }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="j_1" class="small">30J</label>
                                <input type="text" class="form-control" id="j_1" name="j_1" placeholder="30J" value="{{ $engagementFind->j_1 }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="j_2" class="small">45J</label>
                                <input type="text" class="form-control" id="j_2" name="j_2" placeholder="45J" value="{{ $engagementFind->j_2 }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="j_3" class="small">+45J</label>
                                <input type="text" class="form-control" id="j_3" name="j_3" placeholder="+45J" value="{{ $engagementFind->j_3 }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="piece_joint" class="small">Pièce jointe</label>
                            <input type="file" class="form-control" id="piece_joint" name="piece_joint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, JPG, PNG</small>
                        </div>
                    </div>
                    <div class="mb-3 text-start">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-edit"></i>&nbsp; Modifier l'engagement
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
