@extends('layouts.app')
@section('title', 'Clients')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h1 class="h3 mb-0">
            <i class="bi bi-people me-2"></i>Clients
        </h1>
        <a href="{{ route('customer.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Ajouter un client
        </a>
    </div>

    {{-- Barre de recherche --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.index') }}" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Rechercher un client, une société, un email...">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-outline-secondary w-100 w-md-auto">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau des clients --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Société</th>
                            <th>Statut</th>
                            <th>Commercial</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->full_name }}</td>
                                <td>{{ $customer->email ?? '—' }}</td>
                                <td>{{ $customer->phone ?? '—' }}</td>
                                <td>{{ $customer->company_name ?? '—' }}</td>
                                <td>
                                    <span class="badge @class([
                                        'text-bg-secondary' => $customer->status === 'prospect',
                                        'text-bg-success' => $customer->status === 'actif',
                                        'text-bg-warning' => $customer->status === 'inactif',
                                        'text-bg-danger' => $customer->status === 'perdu',
                                    ])">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>{{ $customer->user->name ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-light"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('customer.index', $customer->id) }}" class="btn btn-light"><i class="bi bi-pencil"></i></a>

                                        {{-- Bouton Delete déclencheur modal --}}
                                        <button type="button" class="btn btn-light text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->full_name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-emoji-frown me-1"></i>Aucun client trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($customers->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

{{-- Modal de confirmation de suppression --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Souhaitez-vous vraiment supprimer le client <strong id="customerName"></strong> ?</p>
                    <p class="text-muted small mb-0">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script Bootstrap pour injecter le bon client dans la modale --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        const form = deleteModal.querySelector('#deleteForm');
        const nameHolder = deleteModal.querySelector('#customerName');

        form.action = `/clients/${id}`;
        nameHolder.textContent = name || 'ce client';
    });
});
</script>
@endsection
