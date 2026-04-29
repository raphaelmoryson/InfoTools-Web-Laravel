@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="h4 mb-3">
            <i class="bi bi-pencil-square me-2"></i>Modifier le rendez-vous
        </h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Corrige les champs suivants :</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    {{-- Client --}}
                    <div class="col-md-6">
                        <label class="form-label">Client</label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" @selected(old('customer_id', $appointment->customer_id) == $id)>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Commercial --}}
                    <div class="col-md-6">
                        <label class="form-label">Commercial</label>
                        <select name="commercial_id" class="form-select @error('commercial_id') is-invalid @enderror"
                            required>
                            <option value="">— Sélectionner —</option>
                            @foreach($commercials as $id => $name)
                                <option value="{{ $id }}" @selected(old('commercial_id', $appointment->user_id) == $id)>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('commercial_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Début --}}
                    <div class="col-md-6">
                        <label class="form-label">Début</label>
                        <input type="datetime-local" name="start_at"
                            value="{{ old('start_at', $appointment->start_at ? $appointment->start_at->format('Y-m-d\TH:i') : '') }}"
                            class="form-control @error('start_at') is-invalid @enderror" required>
                        @error('start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fin --}}
                    <div class="col-md-6">
                        <label class="form-label">Fin (optionnel)</label>
                        <input type="datetime-local" name="end_at"
                            value="{{ old('end_at', $appointment->end_at ? $appointment->end_at->format('Y-m-d\TH:i') : '') }}"
                            class="form-control @error('end_at') is-invalid @enderror">
                        @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Objet --}}
                    <div class="col-12">
                        <label class="form-label">Objet</label>
                        <input type="text" name="subject" maxlength="150"
                            value="{{ old('subject', $appointment->subject) }}"
                            class="form-control @error('subject') is-invalid @enderror" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="5"
                            class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="col-12 d-flex justify-content-between mt-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-light">
                                <i class="bi bi-eye me-2"></i>Voir
                            </a>
                            <button class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection