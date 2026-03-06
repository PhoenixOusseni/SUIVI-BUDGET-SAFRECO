@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-handshake me-2"></i>Modifier un Engagement</p>
                <p class="hero-sub">Modification de : <strong>{{ $engagementFind->code }}</strong></p>
            </div>
            <a href="{{ route('engagement.gestion_engagements') }}" class="hero-badge">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="form-card" style="max-width:800px;">
        <div class="form-card-header">
            <div class="fch-icon"><i class="fas fa-edit"></i></div>
            <p class="fch-title">Modifier — {{ $engagementFind->code }}</p>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('engagement.update_engagement', $engagementFind->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" value="{{ $engagementFind->code }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="date_depot" class="form-label">Date de dépôt <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_depot" name="date_depot" value="{{ $engagementFind->date_depot }}" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="montant" name="montant" value="{{ $engagementFind->montant }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fournisseur_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                        <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ $engagementFind->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom_fournisseur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">30J</label>
                        <input type="text" class="form-control" name="j_1" value="{{ $engagementFind->j_1 }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">45J</label>
                        <input type="text" class="form-control" name="j_2" value="{{ $engagementFind->j_2 }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">+45J</label>
                        <input type="text" class="form-control" name="j_3" value="{{ $engagementFind->j_3 }}" disabled>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="piece_joint" class="form-label">Pièce jointe</label>
                    <input type="file" class="form-control" id="piece_joint" name="piece_joint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Formats : PDF, DOC, DOCX, JPG, PNG</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Mettre à jour</button>
                    <a href="{{ route('engagement.gestion_engagements') }}" class="btn-secondary-custom"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
