@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            <i class="bi bi-person-plus me-2"></i>Ajouter un client
        </h1>
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                
                @include('customer._form')

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Enregistrer le client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection