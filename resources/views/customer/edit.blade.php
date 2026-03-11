@extends('layouts.app')
@section('title', 'Modifier un client')
@section('header', true) 


@section('content')
<div class="container-fluid py-3">

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

    <form method="POST" action="{{ route('customers.update', $customer) }}" class="card shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row g-3">
                {{-- Identité --}}
                <div class="col-md-4">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="first_name" class="form-control"
                           value="{{ old('first_name', $customer->first_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nom</label>
                    <input type="text" name="last_name" class="form-control"
                           value="{{ old('last_name', $customer->last_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nom complet (optionnel)</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $customer->name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Société</label>
                    <input type="text" name="company_name" class="form-control"
                           value="{{ old('company_name', $customer->company_name) }}">
                </div>

                {{-- Contact --}}
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $customer->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $customer->phone) }}">
                </div>

                {{-- Adresse --}}
                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control"
                           value="{{ old('address', $customer->address) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ville</label>
                    <input type="text" name="city" class="form-control"
                           value="{{ old('city', $customer->city) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Code postal</label>
                    <input type="text" name="postal_code" class="form-control"
                           value="{{ old('postal_code', $customer->postal_code) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" class="form-control"
                           value="{{ old('country', $customer->country ?? 'France') }}">
                </div>

                {{-- CRM --}}
                @php $statuses = ['prospect','actif','inactif','perdu']; @endphp
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(old('status', $customer->status) === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dernier contact</label>
                    <input type="date" name="last_contact_at" class="form-control"
                           value="{{ old('last_contact_at', optional($customer->last_contact_at)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prochain RDV</label>
                    <input type="date" name="next_meeting_at" class="form-control"
                           value="{{ old('next_meeting_at', optional($customer->next_meeting_at)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Total dépensé (€)</label>
                    <input type="number" step="0.01" min="0" name="total_spent" class="form-control"
                           value="{{ old('total_spent', $customer->total_spent) }}">
                </div>

                {{-- Assignation commercial --}}
                <div class="col-md-4">
                    <label class="form-label">Commercial (User ID)</label>
                    <input type="number" name="user_id" class="form-control"
                           value="{{ old('user_id', $customer->user_id) }}">
                </div>
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle me-1"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
