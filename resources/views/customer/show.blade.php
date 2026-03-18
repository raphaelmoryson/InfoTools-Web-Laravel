@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- En-tête avec bouton retour --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Clients</a></li>
                        <li class="breadcrumb-item active">Fiche client</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-person-bounding-box me-2"></i>{{ $customer->full_name }}
                </h1>
            </div>
            <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">Informations du profil</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-1 text-primary">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h4 class="fw-bold mt-2">{{ $customer->full_name }}</h4>
                            <span class="badge {{ $customer->status === 'actif' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center px-0">
                                <i class="bi bi-building text-muted me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Société</small>
                                    <strong>{{ $customer->company_name ?? 'Non renseigné' }}</strong>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center px-0">
                                <i class="bi bi-envelope text-muted me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <a href="mailto:{{ $customer->email }}"
                                        class="text-decoration-none">{{ $customer->email ?? '—' }}</a>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center px-0">
                                <i class="bi bi-telephone text-muted me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Téléphone</small>
                                    <strong>{{ $customer->phone ?? '—' }}</strong>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center px-0">
                                <i class="bi bi-briefcase text-muted me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Commercial assigné</small>
                                    <strong>{{ $customer->user->name ?? 'Aucun' }}</strong>
                                </div>
                            </li>
                        </ul>

                        <div class="d-grid mt-4">
                            <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Modifier le profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOC PRODUITS COMMANDÉS --}}
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="bi bi-cart-check me-2"></i>Historique des produits commandés
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Facture</th>
                                        <th>Produit</th>
                                        <th class="text-center">Qté</th>
                                        <th class="text-end">Prix Unitaire</th>
                                        <th class="text-end pe-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->invoices as $invoice)
                                        @foreach($invoice->lines as $line)
                                            <tr>
                                                <td class="ps-3 small text-muted">
                                                    <span class="badge bg-light text-dark border">#{{ $invoice->reference }}</span>
                                                    <div class="x-small">{{ $invoice->invoiced_at->format('d/m/Y') }}</div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('products.show', $line->product_id) }}"
                                                        class="text-decoration-none fw-bold">
                                                        {{ $line->product->name }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill bg-info text-dark">x{{ $line->qty }}</span>
                                                </td>
                                                <td class="text-end">{{ number_format($line->unit_price, 2, ',', ' ') }} €</td>
                                                <td class="text-end fw-bold pe-3 text-primary">
                                                    {{ number_format($line->line_total, 2, ',', ' ') }} €
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                                                Aucun produit acheté pour le moment.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection