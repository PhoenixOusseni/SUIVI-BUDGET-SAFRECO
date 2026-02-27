@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h4 class="page-title">NOUVEL ADHÉRANT</h4>
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12 mb-3">
                    <a href="{{ route('gestion_adherants.index') }}" class="btn btn-secondary"> <i
                            data-feather="list"></i>&thinsp;&thinsp; Liste des adhérants</a>
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gestion_adherants.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ old('code') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="nom_adherant" class="form-label">Nom adhérant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom_adherant" name="nom_adherant"
                                value="{{ old('nom_adherant') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_adherant" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact_adherant" name="contact_adherant"
                                value="{{ old('contact_adherant') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email_adherant" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_adherant" name="email_adherant"
                                value="{{ old('email_adherant') }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i>&thinsp;&thinsp; Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
