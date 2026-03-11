@extends('layouts.app')
@section('title','Détail de la facture')

@section('content')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="bi bi-receipt me-2"></i>Facture {{ $invoice->reference }}</h1>
    <div class="btn-group">
      <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline-secondary">
        <i class="bi bi-pencil me-2"></i>Éditer
      </a>
      <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Supprimer cette facture ?')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger"><i class="bi bi-trash me-2"></i>Supprimer</button>
      </form>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-3">
    <div class="card-body row g-3">
      <div class="col-md-4">
        <div class="text-muted small">Client</div>
        <div class="fw-semibold">{{ $invoice->customer->name ?? '—' }}</div>
      </div>
      <div class="col-md-4">
        <div class="text-muted small">Date</div>
        <div class="fw-semibold">{{ $invoice->invoiced_at?->format('d/m/Y') }}</div>
      </div>
      <div class="col-md-4 text-md-end">
        <div class="text-muted small">Total</div>
        <div class="h4 mb-0">{{ number_format($invoice->total, 2, ',', ' ') }} €</div>
      </div>
      <div class="col-12">
        <div class="text-muted small">Référence</div>
        <div class="fw-semibold">{{ $invoice->reference }}</div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
      Lignes de la facture
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Produit</th>
            <th class="text-end">Qté</th>
            <th class="text-end">PU (€)</th>
            <th class="text-end">Total ligne (€)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($invoice->lines as $line)
            <tr>
              <td>{{ $line->product->name ?? '—' }}</td>
              <td class="text-end">{{ $line->qty }}</td>
              <td class="text-end">{{ number_format($line->unit_price, 2, ',', ' ') }}</td>
              <td class="text-end">{{ number_format($line->line_total, 2, ',', ' ') }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucune ligne.</td></tr>
          @endforelse
        </tbody>
        @if($invoice->lines->count())
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">TOTAL</th>
            <th class="text-end">{{ number_format($invoice->total, 2, ',', ' ') }} €</th>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

  <a href="{{ route('invoices.index') }}" class="btn btn-link mt-3">
    <i class="bi bi-arrow-left-short"></i>Retour à la liste
  </a>
</div>
@endsection
