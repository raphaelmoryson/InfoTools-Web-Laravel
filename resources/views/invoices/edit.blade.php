@extends('layouts.app')
@section('title', 'Éditer la facture')
@php
    $existingLines = $invoice->lines->map(function ($l) {
        return [
            'id' => $l->id,
            'product_id' => $l->product_id,
            'qty' => $l->qty,
            'unit_price' => $l->unit_price,
        ];
    });
@endphp
@section('content')
    <div class="container-fluid mt-4">
        <h1 class="h4 mb-3"><i class="bi bi-pencil-square me-2"></i>Modifier la facture</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Corrige les erreurs suivantes :</div>
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoice-form">
                    @csrf @method('PUT')

                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Client</label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror"
                                required>
                                <option value="">— Sélectionner —</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}" @selected(old('customer_id', $invoice->customer_id) == $id)>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="invoiced_at"
                                value="{{ old('invoiced_at', $invoice->invoiced_at?->format('Y-m-d')) }}"
                                class="form-control @error('invoiced_at') is-invalid @enderror" required>
                            @error('invoiced_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" value="{{ old('reference', $invoice->reference) }}"
                                class="form-control @error('reference') is-invalid @enderror" required>
                            @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Lignes --}}
                    <div class="table-responsive">
                        <table class="table align-middle" id="lines-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:38%">Produit</th>
                                    <th style="width:12%">Qté</th>
                                    <th style="width:20%">PU (€)</th>
                                    <th style="width:20%">Total ligne (€)</th>
                                    <th class="text-end" style="width:10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="lines-body">
                                {{-- rempli par JS (server → seed initial) --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-line">
                                            <i class="bi bi-plus-circle me-1"></i>Ajouter une ligne
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-2">
                        <div class="text-end">
                            <div class="text-muted">Total facture</div>
                            <div class="h4 mb-0"><span id="invoice-total">0,00</span> €</div>
                            <input type="hidden" name="total" id="total-input"
                                value="{{ number_format($invoice->total, 2, '.', '') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-light"><i
                                    class="bi bi-eye me-2"></i>Voir</a>
                            <button class="btn btn-primary"><i class="bi bi-check2 me-2"></i>Mettre à jour</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price]));
                const existing = @json($existingLines);

                const tbody = document.getElementById('lines-body');
                const addBtn = document.getElementById('add-line');
                const totalEl = document.getElementById('invoice-total');
                const totalInput = document.getElementById('total-input');

                function numberFr(x) { return (Math.round(x * 100) / 100).toFixed(2).replace('.', ','); }
                function parseVal(inp) { const v = parseFloat((inp.value || '0').toString().replace(',', '.')); return isNaN(v) ? 0 : v; }

                function recalc() {
                    let total = 0;
                    tbody.querySelectorAll('tr').forEach(tr => {
                        const qty = parseVal(tr.querySelector('.line-qty'));
                        const pu = parseVal(tr.querySelector('.line-unit'));
                        const lt = qty * pu;
                        tr.querySelector('.line-total').value = lt.toFixed(2);
                        tr.querySelector('.line-total-display').textContent = numberFr(lt);
                        total += lt;
                    });
                    totalEl.textContent = numberFr(total);
                    totalInput.value = (Math.round(total * 100) / 100).toFixed(2);
                }

                function productOptionsHTML(selectedId = null) {
                    return `<option value="">— Sélectionner —</option>` + products.map(p =>
                        `<option value="${p.id}" data-price="${p.price}" ${selectedId == p.id ? 'selected' : ''}>${p.name}</option>`
                    ).join('');
                }

                function addLine(initial = { product_id: '', qty: 1, unit_price: '' }, lineKey = null) {
                    const idx = lineKey ?? (Date.now() + Math.floor(Math.random() * 1000));
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
              <td>
                <select name="lines[${idx}][product_id]" class="form-select line-product" required>
                  ${productOptionsHTML(initial.product_id)}
                </select>
              </td>
              <td>
                <input type="number" min="1" step="1" name="lines[${idx}][qty]" value="${initial.qty}" class="form-control line-qty">
              </td>
              <td>
                <input type="number" min="0" step="0.01" name="lines[${idx}][unit_price]" value="${initial.unit_price}" class="form-control line-unit">
              </td>
              <td>
                <div class="input-group">
                  <input type="hidden" name="lines[${idx}][line_total]" class="line-total" value="0">
                  <span class="form-control bg-light line-total-display text-end">0,00</span>
                  <span class="input-group-text">€</span>
                </div>
              </td>
              <td class="text-end">
                <button type="button" class="btn btn-light btn-sm remove-line" title="Supprimer"><i class="bi bi-x-lg"></i></button>
              </td>
            `;
                    tbody.appendChild(tr);

                    const productSelect = tr.querySelector('.line-product');
                    productSelect.addEventListener('change', () => {
                        const pu = productSelect.selectedOptions[0]?.dataset?.price ?? '';
                        const unitInput = tr.querySelector('.line-unit');
                        if (!unitInput.value) unitInput.value = pu || '';
                        recalc();
                    });

                    tr.addEventListener('input', e => {
                        if (e.target.classList.contains('line-qty') || e.target.classList.contains('line-unit')) recalc();
                    });

                    tr.querySelector('.remove-line').addEventListener('click', () => { tr.remove(); recalc(); });

                    recalc();
                }

                addBtn.addEventListener('click', () => addLine());

                // Seed existantes
                if (existing.length) {
                    existing.forEach((l) => addLine({
                        product_id: l.product_id, qty: l.qty, unit_price: l.unit_price
                    }, l.id));
                } else {
                    addLine();
                }
            })();
        </script>
    @endpush
@endsection