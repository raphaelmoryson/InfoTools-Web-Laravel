@extends('layouts.app')
@section('title', 'Ajouter un client')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-short me-1"></i>Retour
        </a>
    </div>

    {{-- Alertes validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.store') }}" class="card shadow-sm border-0">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                {{-- Identité --}}
                <div class="col-md-4">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nom</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nom complet (optionnel)</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex. Jean Dupont">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Société</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                </div>

                {{-- Contact --}}
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                {{-- Adresse --}}
                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ville</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Code postal</label>
                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'France') }}">
                </div>

                {{-- CRM --}}
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        @foreach(($statuses ?? ['prospect','actif','inactif','perdu']) as $s)
                            <option value="{{ $s }}" @selected(old('status','prospect') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dernier contact</label>
                    <input type="date" name="last_contact_at" class="form-control" value="{{ old('last_contact_at') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prochain RDV</label>
                    <input type="date" name="next_meeting_at" class="form-control" value="{{ old('next_meeting_at') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total dépensé (€)</label>
                    <input type="number" step="0.01" min="0" name="total_spent" class="form-control" value="{{ old('total_spent', 0) }}">
                </div>

                {{-- Assignation commercial (par défaut : user connecté) --}}
                @auth
                <input type="hidden" name="user_id" value="{{ old('user_id', auth()->id()) }}">
                @endauth
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle me-1"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
