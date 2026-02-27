@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES ADHÉRANTS</h4>
        <div class="card">
            <div class="card-body">
                
                <div class="row mb-3">
                    <div class="col-sm-12 text-end">
                        <a href="{{ route('gestion_adherants.create') }}" class="btn btn-primary">
                            <i data-feather="plus-circle"></i>&thinsp;&thinsp; Nouvel adhérant
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Nom adhérant</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adherants as $adherant)
                                <tr>
                                    <td>{{ $adherant->id }}</td>
                                    <td>{{ $adherant->code ?: '-' }}</td>
                                    <td>{{ $adherant->nom_adherant }}</td>
                                    <td>{{ $adherant->contact_adherant ?: '-' }}</td>
                                    <td>{{ $adherant->email_adherant ?: '-' }}</td>
                                    <td>{{ optional($adherant->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('gestion_adherants.edit', $adherant->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>&thinsp;&thinsp; Modifier
                                        </a>
                                        <form action="{{ route('gestion_adherants.destroy', $adherant->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet adhérant ?')">
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
