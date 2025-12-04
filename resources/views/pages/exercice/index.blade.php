@extends('admin.layouts.master')

@section('content')
    <header class="page-header page-header-dark pb-10"
        style="background: linear-gradient(90deg, rgb(86, 146, 113) 0%, rgb(67, 189, 91) 50%, rgb(97, 243, 67) 100%);">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Exercices de cotisation
                        </h1>
                    </div>
                </div>
                <div class="row align-items-center justify-content-between mt-4">
                    <div class="col-auto">
                        <nav class="nav">
                            <a class="btn btn-warning" href="#"data-bs-toggle="modal"
                                data-bs-target="#formUserBackdrop">Ajouter execice</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <!-- Tabbed dashboard card example-->
                <div class="card mb-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Année budgetaire</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collection as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->annee }}</td>
                                        <td>{{ $item->statut }}</td>
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#formUserBackdrop{{ $item->id }}"
                                                class="text-decoration-none text-success">
                                                <i class="me-2" data-feather="edit"></i>
                                            </a>
                                        </td>
                                        <!-- Modal for managing user account -->
                                        @include('admin.pages.membres.manage-modal', ['item' => $item])
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="formUserBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Ajouter exercice de cotisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('gestion_exercice.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12">
                                                <div class="mb-3">
                                                    <label>Statut<span class="text-danger">*</span></label>
                                                    <select class="form-select" name="statut" required>
                                                        <option>Selectionner ici...</option>
                                                        <option value="Activé">Activé</option>
                                                        <option value="Desactivé">Desactivé</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="mb-3">
                                                    <label>Année budgetaire<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="annee" required>
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
@endsection
