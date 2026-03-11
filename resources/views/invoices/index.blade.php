@extends('layouts.app')
@section('title', 'Factures')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h1 class="h3 mb-2 mb-sm-0"><i class="bi bi-receipt me-2"></i>Factures</h1>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle facture
            </a>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index') }}" class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                placeholder="Référence ou client...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="Du">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="Au">
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

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Client</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($inv->invoiced_at)->format('d/m/Y') }}</td>
                                <td class="fw-semibold">{{ $inv->reference }}</td>
                                <td>{{ $inv->customer->name ?? '—' }}</td>
                                <td class="text-end">{{ number_format($inv->total, 2, ',', ' ') }} €</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-light" title="Voir"><i
                                                class="bi bi-eye"></i></a>
                                        <a href="{{ route('invoices.edit', $inv) }}" class="btn btn-light" title="Éditer"><i
                                                class="bi bi-pencil"></i></a>
                                        <form action="{{ route('invoices.destroy', $inv) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cette facture ?')" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-light" title="Supprimer"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucune facture.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="card-footer bg-white py-2">
                    <div class="d-flex justify-content-center">
                        {{ $invoices->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('styles')
        <style>
            .pagination .page-link {
                padding: .25rem .5rem;
                font-size: .875rem
            }

            .pagination {
                gap: .25rem
            }
        </style>
    @endpush
@endsection