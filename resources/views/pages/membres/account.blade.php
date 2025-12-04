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
                            GESTION DES COMPTES DES MEMBRES
                        </h1>
                        <div class="page-header-subtitle">Liste des membres actifs</div>
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
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Region ordinale</th>
                                    <th>Role</th>
                                    {{-- <th>Documents</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collection as $item)
                                    <tr>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->nom }}</td>
                                        <td>{{ $item->prenom }}</td>
                                        <td>{{ $item->RegionOrdinal->libelle ?? 'Non defini' }}</td>
                                        <td>{{ $item->Role->libelle ?? 'Non defini' }}</td>
                                        {{-- <td class="text-center">
                                            <a href="{{ asset('storage') . '/' . $item->file }}" target="_blank">
                                                <i class="me-2 text-warning" data-feather="file"></i>
                                            </a>
                                        </td> --}}
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
@endsection
