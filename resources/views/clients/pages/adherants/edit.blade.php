@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">MODIFIER UN ADHÉRANT</h4>
        <div class="card">
            <div class="card-body">
                @include('clients.pages.configs.menu_config')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gestion_adherants.update', $adherant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ old('code', $adherant->code) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="nom_adherant" class="form-label">Nom adhérant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom_adherant" name="nom_adherant"
                                value="{{ old('nom_adherant', $adherant->nom_adherant) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_adherant" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact_adherant" name="contact_adherant"
                                value="{{ old('contact_adherant', $adherant->contact_adherant) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email_adherant" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_adherant" name="email_adherant"
                                value="{{ old('email_adherant', $adherant->email_adherant) }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i>&thinsp;&thinsp; Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
