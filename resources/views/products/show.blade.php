@extends('layouts.app')
@section('title', 'Détail du produit')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4"><i class="bi bi-box-seam me-2"></i>{{ $product->name }}</h1>
        <div class="btn-group">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-2"></i>Éditer
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger">
                    <i class="bi bi-trash me-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Nom</div>
                <div class="fw-semibold">{{ $product->name }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Prix</div>
                <div class="fw-semibold">{{ number_format($product->price, 2, ',', ' ') }} €</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Stock</div>
                <div class="fw-semibold">{{ $product->stock }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Description</div>
                <div>{{ $product->description ?: '—' }}</div>
            </div>
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-link mt-3">
        <i class="bi bi-arrow-left-short"></i>Retour à la liste
    </a>
</div>
@endsection
