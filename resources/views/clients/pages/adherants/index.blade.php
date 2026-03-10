@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-users me-2"></i>Gestion des Adhérants</p>
                <p class="hero-sub">Liste et gestion des adhérants enregistrés</p>
            </div>
            <a href="{{ route('gestion_adherants.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus"></i> Nouvel adhérant
            </a>
        </div>
    </div>

    @include('clients.pages.configs.menu_config')

    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-users"></i></div>
            <div>
                <p class="tch-title">Liste des adhérants</p>
                <p class="tch-sub mb-0">Adhérants enregistrés dans le système</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>#</th><th>Code</th><th>Nom adhérant</th><th>Contact</th><th>Email</th><th>Date création</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adherants as $adherant)
                        <tr>
                            <td style="color:#94a3b8;font-size:.75rem;">{{ $adherant->id }}</td>
                            <td><span class="status-badge blue">{{ $adherant->code ?: '—' }}</span></td>
                            <td style="font-weight:600;">{{ $adherant->nom_adherant }}</td>
                            <td>{{ $adherant->contact_adherant ?: '—' }}</td>
                            <td>{{ $adherant->email_adherant ?: '—' }}</td>
                            <td style="font-size:.78rem;color:#64748b;">{{ optional($adherant->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('gestion_adherants.edit', $adherant->id) }}" class="btn-warning-custom">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="{{ route('gestion_adherants.destroy', $adherant->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cet adhérant ?')">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
