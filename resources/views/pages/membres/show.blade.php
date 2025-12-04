@extends('admin.layouts.master')

@section('content')
    <!-- Main page content-->
    <div class="container-xl">
        <div class="container my-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center text-success mb-4">
                    Profil de l'utilisateur
                    <span>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </span>
                </h3>
                <!-- 👤 SECTION 1 : État Civil -->
                <div class="mb-4">
                    <h5 class="text-success border-bottom pb-2 mb-3">👤 État Civil</h5>
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <img src="{{ asset('assets/img/avatar.png') }}" class="img-fluid rounded-circle shadow"
                                width="150" alt="Avatar">
                            <h5 class="mt-3">{{ $finds->prenom }} {{ $finds->nom }}</h5>
                            <span class="badge bg-success">{{ $finds->Role->libelle }}</span>
                            <h5 class="mt-3">Code : {{ $finds->code }}</h5>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Nom de jeune fille :</strong>
                                    {{ $finds->nom_jeune_fille }}
                                </div>
                                <div class="col-md-6"><strong>Situation matrimoniale :</strong>
                                    {{ $finds->situation_matrimoniale }}</div>
                                <div class="col-md-6"><strong>Date de naissance :</strong>
                                    {{ $finds->date_naiss }}</div>
                                <div class="col-md-6"><strong>Lieu de naissance :</strong> {{ $finds->lieu_naiss }}
                                </div>
                                <div class="col-md-6"><strong>Nationalité :</strong> {{ $finds->nationalite }}</div>
                                <div class="col-md-6"><strong>Section :</strong> {{ $finds->Section->libelle }}</div>
                            </div>
                            <hr>
                            <h5 class="text-success border-bottom pb-2 mb-3 mt-5">📞 Coordonnées géographique</h5>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Région ordinale :</strong>
                                    {{ $finds->RegionOrdinal->libelle ?? 'Non spécifié' }}
                                </div>
                                <div class="col-md-6"><strong>Régions :</strong>
                                    {{ $finds->Region->libelle ?? 'Non spécifié' }}
                                </div>
                                <div class="col-md-6"><strong>Privince :</strong>
                                    {{ $finds->Province->libelle ?? 'Non spécifié' }}
                                </div>
                                <div class="col-md-6"><strong>Commune/Ville :</strong>
                                    {{ $finds->Commune->libelle ?? 'Non spécifié' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 📞 SECTION 2 : Coordonnées -->
                <div class="mb-4">
                    <h5 class="text-success border-bottom pb-2 mb-3">📞 Coordonnées</h5>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Email :</strong> {{ $finds->email }}</div>
                        <div class="col-md-6"><strong>Téléphone :</strong> {{ $finds->telephone }}</div>
                        <div class="col-md-6"><strong>Adresse permanente :</strong> {{ $finds->adresse }}</div>
                        <div class="col-md-6"><strong>Domicile :</strong> {{ $finds->domicile }}</div>
                        <div class="col-md-6"><strong>N° Matricule :</strong> {{ $finds->matricule }}</div>
                        <div class="col-md-6"><strong>Lieu d'exercice :</strong> {{ $finds->lieu_exercice }}</div>
                    </div>
                </div>

                <!-- 🎓 SECTION 3 : Diplômes -->
                <div class="mb-4">
                    <h5 class="text-success border-bottom pb-2 mb-3">🎓 Diplômes</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ asset('storage') . '/' . $finds->diplome }}" target="_blank"
                                class="btn btn-outline-success">
                                <img src="{{ asset('assets/img/téléchargement.png') }}" style="width: 10%" alt="file">
                            </a>
                            <p class="text-danger">Diplome du doctotat</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ asset('storage') . '/' . $finds->file }}" target="_blank"
                                class="btn btn-outline-success">
                                <img src="{{ asset('assets/img/téléchargement.png') }}" style="width: 10%" alt="file">
                            </a>
                            <p class="text-danger">Fichiers joints</p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Date d'obtention :</strong>
                            {{ $finds->date_diplome }}
                        </div>
                        <div class="col-md-6"><strong>Institution :</strong> {{ $finds->inst_delivre }}</div>
                        <div class="col-md-6"><strong>Lieu de délivrance :</strong> {{ $finds->lieu_delivrance }}</div>
                    </div>
                </div>

                <!-- 🎓 SECTION 3 : Autre Diplômes et fonction -->
                <div class="mb-4">
                    <h5 class="text-success border-bottom pb-2 mb-3">🎓 Autres diplome et Fonction</h5>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="text-center">Autres diplomes</h4>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date d'obtention</th>
                                        <th>Nature diplôme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($autres_diplomes as $item)
                                        <tr>
                                            <td>{{ $item->date_diplome }}</td>
                                            <td>{{ $item->nature }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-danger"><em>Aucun autre diplôme
                                                    enregistré</em></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-center">Fonctions</h4>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Libelle fonction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($fonctions as $item)
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->libelle }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-danger"><em>Aucun fonction
                                                    enregistré</em></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2 mt-4">
                    @if ($finds->statut == 'En cours' && (Auth()->user()->role_id == 2 || Auth()->user()->role_id == 3))
                        <button class="btn btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#activationBackdrop">Activer le compte</button>
                    @endif
                    @if (Auth()->user()->role_id == 3 || Auth()->user()->role_id == 4)
                        @if ($finds->statut == 'Actif')
                            <a href="#!" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#cotisationBackdrop">
                                💰 Faire une cotisations
                            </a>
                        @endif
                    @endif
                    @if (Auth()->user()->role_id == 3)
                        <a href="" class="btn btn-danger">❌ Désactiver le compte</a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Modal activation -->
    <div class="modal fade" id="activationBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Activation du compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" action="{{ url('membre_activation/' . $finds->id) }}">
                            @csrf
                            <div class="col-lg-12 col-md-12 text-center">
                                <img src="{{ asset('assets/img/avertissement.jpg') }}" alt="warning" width="15%">
                            </div>
                            <div class="col-lg-12 col-md-12 text-center">
                                <h5 class="text-danger">Voulez-vous vraiment activer le compte de
                                    <span class="text-dark">{{ $finds->prenom }} {{ $finds->nom }}</span> ?
                                </h5>
                            </div>
                            <hr>
                            <div class="row">
                                <input type="text" name="montant_cotisation" hidden>
                                <div class="col-lg-12 col-md-12">
                                    <div class="mb-3">
                                        <label>Categorie du membre<span class="text-danger">*</span></label>
                                        <select class="form-select" name="responsabilite_id" required>
                                            <option disabled>Selectionner ici...</option>
                                            @foreach (App\Models\Responsabilite::all() as $item)
                                                <option value="{{ $item->id }}">{{ $item->libelle }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-12 col-md-12">
                                    <div class="mb-3">
                                        <label>Montant du membre<span class="text-danger">*</span></label>
                                        <input type="text" name="montant_cotisation" id="montant_cotisation" class="form-control" required>
                                    </div>
                                </div> -->
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">Activer</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal cotisation -->
    <div class="modal fade" id="cotisationBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Faire une cotisation de {{ $finds->nom }}
                        {{ $finds->prenom }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="sbp-preview-content">
                                        <form method="POST" action="{{ route('gestion_cotisations.store') }}">
                                            @csrf
                                            <div class="row">
                                                <input type="text" name="user_id" value="{{ $finds->id }}" hidden>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Nom <span class="text-danger">*</span></label>
                                                        <input class="form-control" value="{{ $finds->nom }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Prénom <span class="text-danger">*</span></label>
                                                        <input class="form-control" value="{{ $finds->prenom }}" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Période<span class="text-danger">*</span></label>
                                                        <select class="form-select" name="annee_id" required>
                                                            @foreach (App\Models\Annee::where('statut', '=', 'Activé')->get() as $item)
                                                                <option value="{{ $item->id }}">{{ $item->annee }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Somme<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="montant_cotisation"
                                                            value="{{ $finds->montant_cotisation }}" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Date</label>
                                                        <input class="form-control" name="date" type="date" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Mode du paiement <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="mode" required>
                                                            <option value="">Selectionner ici...</option>
                                                            <option value="Administration">Espèce</option>
                                                            <option value="Recouvrement">Cheque</option>
                                                            <option value="Controle">Virement</option>
                                                            <option value="Autre">Mobile Money</option>
                                                            <option value="Autre">Autre</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label>Observation</label>
                                                        <textarea class="form-control" name="desc" rows="5" placeholder="Entrez vos observations ici..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
