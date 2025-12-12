@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">GESTION DES RUBRIQUES</h4>
        <div class="card">
            <div class="card-body">
                {{-- Include the menu configuration partial --}}
                @include('clients.pages.configs.menu_config')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">Modification de la rubrique "{{ $findRubrique->code }}"</h4>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="data-table table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom de la Rubrique</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rubriques as $rubrique)
                                    <tr>
                                        <td>{{ $rubrique->code }}</td>
                                        <td>{{ $rubrique->intitule }}</td>
                                        <td>{{ $rubrique->description }}</td>
                                        <td>
                                            <a href="{{ route('gestion_rubriques.edit', $rubrique->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i data-feather="edit"></i>&thinsp;&thinsp; Modifier
                                            </a>
                                            <form action="{{ route('gestion_rubriques.destroy', $rubrique->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rubrique ?')"><i
                                                        data-feather="trash-2"></i>&thinsp;&thinsp; Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- More rows can be added here --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('gestion_rubriques.update', $findRubrique->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    placeholder="Entrez le code" value="{{ $findRubrique->code }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de la Rubrique</label>
                                <input type="text" class="form-control" id="nom" name="intitule"
                                    placeholder="Entrez le nom" value="{{ $findRubrique->intitule }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description">{{ $findRubrique->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="edit"></i> &thinsp;&thinsp; Modifier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
