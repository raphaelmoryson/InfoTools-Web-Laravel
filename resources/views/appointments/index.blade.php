@extends('layouts.app')
@section('title', 'Rendez-vous')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h1 class="h3 mb-2 mb-sm-0"><i class="bi bi-calendar3 me-2"></i>Rendez-vous</h1>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouveau rendez-vous
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}" class="row g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Sujet ou client...">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" name="from" value="{{ $dateFrom?->format('Y-m-d') }}" class="form-control" placeholder="Du">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" value="{{ $dateTo?->format('Y-m-d') }}" class="form-control" placeholder="Au">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-secondary"><i class="bi bi-sliders me-2"></i>Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Client</th>
                        <th>Objet</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->start_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $a->end_at ? \Carbon\Carbon::parse($a->end_at)->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $a->customer->name ?? '—' }}</td>
                            <td>{{ $a->subject }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('appointments.show', $a) }}" class="btn btn-light" title="Voir"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('appointments.edit', $a) }}" class="btn btn-light" title="Éditer"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('appointments.destroy', $a) }}" method="POST" onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light" title="Supprimer"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucun rendez-vous.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="card-footer bg-white">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
