@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-handshake me-2"></i>Gestion des Engagements</p>
                <p class="hero-sub">Gérez les engagements financiers et suivez les pièces jointes</p>
            </div>
            <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addEngagementModal">
                <i class="fas fa-plus"></i> Nouvel engagement
            </button>
        </div>
    </div>

    {{-- Modal Ajout --}}
    <div class="modal fade" id="addEngagementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 32px rgba(0,61,130,.15);">
                <div class="modal-header modal-header-blue">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Ajouter un engagement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('engagement.gestion_engagements.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" placeholder="Auto-généré" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="date_depot" class="form-label">Date de dépôt <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_depot" name="date_depot" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant de l'engagement" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fournisseur_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                                <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom_fournisseur }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">30J</label>
                                <input type="text" class="form-control" name="j_1" placeholder="30J" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">45J</label>
                                <input type="text" class="form-control" name="j_2" placeholder="45J" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">+45J</label>
                                <input type="text" class="form-control" name="j_3" placeholder="+45J" disabled>
                            </div>
                        </div>
                        <div>
                            <label for="piece_joint" class="form-label">Pièce jointe</label>
                            <input type="file" class="form-control" id="piece_joint" name="piece_joint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats : PDF, DOC, DOCX, JPG, PNG</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 px-4 pb-4">
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save"></i> Enregistrer</button>
                        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table Engagements --}}
    <div class="table-card mb-4">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-handshake"></i></div>
            <div>
                <p class="tch-title">Liste des engagements</p>
                <p class="tch-sub mb-0">{{ count($engagements) }} engagement(s)</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Fournisseur</th>
                        <th>Date Dépôt</th>
                        <th>Montant</th>
                        <th>30J</th><th>45J</th><th>+45J</th>
                        <th>Pièce jointe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($engagements as $engagement)
                        <tr>
                            <td><span class="status-badge blue">{{ $engagement->code }}</span></td>
                            <td style="font-weight:600;">{{ $engagement->fournisseur ? $engagement->fournisseur->nom_fournisseur : '—' }}</td>
                            <td>{{ $engagement->date_depot ? \Carbon\Carbon::parse($engagement->date_depot)->format('d/m/Y') : '—' }}</td>
                            <td>{{ number_format($engagement->montant, 0, ',', ' ') }}</td>
                            <td>{{ $engagement->j_1 }}</td>
                            <td>{{ $engagement->j_2 }}</td>
                            <td>{{ $engagement->j_3 }}</td>
                            <td>
                                @if($engagement->piece_joint)
                                    <a href="{{ asset('storage/' . $engagement->piece_joint) }}" target="_blank" class="btn-info-custom"><i class="fas fa-file-download"></i></a>
                                @else
                                    <span class="status-badge gray">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('engagement.edit_engagement', $engagement->id) }}" class="btn-warning-custom"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('engagement.delete_engagement', $engagement->id) }}" style="display:inline-block;" onsubmit="return confirm('Supprimer cet engagement ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Fournisseurs / Observations --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-industry"></i></div>
            <div>
                <p class="tch-title">Fournisseurs — Observations</p>
                <p class="tch-sub mb-0">Analyse par fournisseur</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0">
                <thead>
                    <tr><th>Fournisseur / Tiers</th><th>Observation</th></tr>
                </thead>
                <tbody>
                    @foreach ($fournisseurs as $fournisseur)
                        @php
                            $dernierEngagement = $engagements->where('fournisseur_id', $fournisseur->id)->sortByDesc('date_depot')->first();
                            if ($dernierEngagement) {
                                $joursEcoules = \Carbon\Carbon::parse($dernierEngagement->date_depot)->diffInDays(\Carbon\Carbon::now());
                                if ($joursEcoules <= 30) { $obs = 'Gestion fluide'; $obsCls = 'green'; }
                                elseif ($joursEcoules <= 45) { $obs = 'Ralentissement'; $obsCls = 'orange'; }
                                else { $obs = 'Risque de rupture de service'; $obsCls = 'red'; }
                            }
                        @endphp
                        @if(!empty($dernierEngagement))
                            <tr>
                                <td style="font-weight:600;">{{ strtoupper($fournisseur->nom_fournisseur) }}</td>
                                <td><span class="status-badge {{ $obsCls }}">{{ $obs }}</span></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', function() { alert('{{ session('success') }}'); });</script>
@endif
@endsection
