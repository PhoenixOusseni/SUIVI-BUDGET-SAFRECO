@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES UTILISATEURS</h4>
        <div class="card">
            <div class="card-body">
                @include('clients.pages.configs.menu_config')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Gestion des utilisateurs</h4>
                            <p>Ajoutez, modifiez ou supprimez les comptes utilisateurs de l'application.</p>
                            <hr>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12 text-end">
                        <a href="{{ route('gestion_users.create') }}" class="btn btn-primary">
                            <i data-feather="plus-circle"></i>&thinsp;&thinsp; Nouvel utilisateur
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Role</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telephone ?: '-' }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('gestion_users.edit', $user->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>&thinsp;&thinsp; Modifier
                                        </a>
                                        <form action="{{ route('gestion_users.destroy', $user->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                <i data-feather="trash-2"></i>&thinsp;&thinsp; Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
