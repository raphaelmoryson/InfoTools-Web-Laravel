@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <h1 class="h4 mb-3">
        <i class="bi bi-eye me-2"></i>Rendez-vous
    </h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body row g-3">
            {{-- Client --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Client</label>
                <div>{{ $appointment->customer->name ?? '-' }}</div>
            </div>

            {{-- Commercial --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Commercial</label>
                <div>{{ $appointment->commercial->name ?? '-' }}</div>
            </div>

            {{-- Début --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Début</label>
                <div>{{ $appointment->start_at?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>

            {{-- Fin --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Fin</label>
                <div>{{ $appointment->end_at?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>

            {{-- Objet --}}
            <div class="col-12">
                <label class="form-label fw-bold">Objet</label>
                <div>{{ $appointment->subject }}</div>
            </div>

            {{-- Notes --}}
            <div class="col-12">
                <label class="form-label fw-bold">Notes</label>
                <div style="white-space: pre-wrap;">{{ $appointment->notes ?? '-' }}</div>
            </div>

            {{-- Boutons --}}
            <div class="col-12 d-flex justify-content-between mt-2">
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
