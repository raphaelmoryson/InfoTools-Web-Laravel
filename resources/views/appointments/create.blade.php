@extends('layouts.app')
@section('title', 'Nouveau rendez-vous')

@section('content')
    <div class="container-fluid mt-4">
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

        <div class="card">
            <div class="card-body">
                <form action="{{ route('appointments.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Client</label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" @selected(old('customer_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                  
                    <div class="col-md-6">
                        <label class="form-label">Début</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
                            class="form-control @error('start_at') is-invalid @enderror" required>
                        @error('start_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fin (optionnel)</label>
                        <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
                            class="form-control @error('end_at') is-invalid @enderror">
                        @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Objet</label>
                        <input type="text" name="subject" maxlength="150" value="{{ old('subject') }}"
                            class="form-control @error('subject') is-invalid @enderror" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="5"
                            class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-between mt-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-light">Réinitialiser</button>
                            <button class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Créer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection