@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-user-cog me-2"></i>Gestion des Utilisateurs</p>
                <p class="hero-sub">Gérez les comptes et accès des utilisateurs de l'application</p>
            </div>
            <a href="{{ route('gestion_users.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus"></i> Nouvel utilisateur
            </a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-user-cog"></i></div>
            <div>
                <p class="tch-title">Liste des utilisateurs</p>
                <p class="tch-sub mb-0">Comptes enregistrés dans le système</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-std mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Date création</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td style="color:#94a3b8;font-size:.75rem;">{{ $user->id }}</td>
                            <td style="font-weight:600;">{{ $user->nom }}</td>
                            <td>{{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone ?: '—' }}</td>
                            <td>
                                <span class="status-badge {{ $user->role === 'admin' ? 'orange' : 'blue' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td style="font-size:.78rem;color:#64748b;">{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('gestion_users.edit', $user->id) }}" class="btn-warning-custom">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="{{ route('gestion_users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cet utilisateur ?')">
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
