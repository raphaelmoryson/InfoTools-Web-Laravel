@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            <i class="bi bi-pencil-square me-2"></i>Modifier : {{ $customer->full_name }}
        </h1>
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                @include('customer._form')

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection