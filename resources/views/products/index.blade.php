@extends('layouts.app')
@section('title', 'Produits')

@section('content')
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h1 class="h3 mb-2 mb-sm-0"><i class="bi bi-box-seam me-2"></i>Produits</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouveau produit
        </a>
    </div>

    {{-- Barre de recherche --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un produit...">
                    </div>
                </div>
                <div class="col-md-4 d-grid">
                    <button class="btn btn-outline-secondary"><i class="bi bi-sliders me-2"></i>Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Message flash --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tableau produits --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ number_format($p->price, 2, ',', ' ') }} €</td>
                            <td>{{ $p->stock }}</td>
                            <td class="text-muted small">{{ Str::limit($p->description, 60) }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $p) }}" class="btn btn-light"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('products.edit', $p) }}" class="btn btn-light"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('products.destroy', $p) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun produit trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="card-footer bg-white py-2">
                <div class="d-flex justify-content-center">
                    {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
