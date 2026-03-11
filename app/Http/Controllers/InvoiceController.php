<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    /**
     * Liste + recherche + filtres de date.
     */
    public function index(Request $request)
    {
        $q    = $request->string('q')->toString();
        $from = $request->date('from');
        $to   = $request->date('to');

        $invoices = Invoice::query()
            ->with(['customer'])
            ->when($q, function ($query) use ($q) {
                $query->where('reference', 'like', "%{$q}%")
                      ->orWhereHas('customer', fn($qq) => $qq->where('name', 'like', "%{$q}%"));
            })
            ->when($from, fn($query) => $query->whereDate('invoiced_at', '>=', $from))
            ->when($to,   fn($query) => $query->whereDate('invoiced_at', '<=', $to))
            ->orderByDesc('invoiced_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->onEachSide(1)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'q', 'from', 'to'));
    }

    /**
     * Formulaire création.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products  = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * Enregistrer une facture + lignes.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'invoiced_at' => ['required', 'date'],
            'reference'   => ['required', 'string', 'max:255', 'unique:invoices,reference'],
            'total'       => ['nullable', 'numeric', 'min:0'], // ignoré côté serveur

            'lines'                       => ['required', 'array', 'min:1'],
            'lines.*.product_id'          => ['required', 'integer', 'exists:products,id'],
            'lines.*.qty'                 => ['required', 'integer', 'min:1'],
            'lines.*.unit_price'          => ['required', 'numeric', 'min:0'],
            'lines.*.line_total'          => ['nullable', 'numeric', 'min:0'], // recalculé serveur
        ]);

        $invoice = DB::transaction(function () use ($data) {
            // Crée la facture
            $invoice = Invoice::create([
                'customer_id' => $data['customer_id'],
                'invoiced_at' => $data['invoiced_at'],
                'reference'   => $data['reference'],
                'total'       => 0,
            ]);

            // Crée les lignes & calcule le total serveur
            $total = 0;
            foreach ($data['lines'] as $line) {
                $qty  = (int) $line['qty'];
                $unit = (float) $line['unit_price'];
                $lt   = round($qty * $unit, 2);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $line['product_id'],
                    'qty'        => $qty,
                    'unit_price' => $unit,
                    'line_total' => $lt,
                ]);

                $total += $lt;
            }

            $invoice->update(['total' => round($total, 2)]);

            return $invoice;
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Détail.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'lines.product']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Formulaire édition.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['lines.product']);
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products  = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Mettre à jour facture + synchroniser lignes.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'invoiced_at' => ['required', 'date'],
            'reference'   => [
                'required', 'string', 'max:255',
                Rule::unique('invoices', 'reference')->ignore($invoice->id),
            ],
            'total'       => ['nullable', 'numeric', 'min:0'],

            'lines'                       => ['required', 'array', 'min:1'],
            'lines.*.product_id'          => ['required', 'integer', 'exists:products,id'],
            'lines.*.qty'                 => ['required', 'integer', 'min:1'],
            'lines.*.unit_price'          => ['required', 'numeric', 'min:0'],
            'lines.*.line_total'          => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $invoice) {
            // Maj de l'entête
            $invoice->update([
                'customer_id' => $data['customer_id'],
                'invoiced_at' => $data['invoiced_at'],
                'reference'   => $data['reference'],
            ]);

            // Synchronisation des lignes
            $existingIds = $invoice->lines()->pluck('id')->all();
            $keepIds     = [];

            $total = 0;

            foreach ($data['lines'] as $key => $line) {
                $qty  = (int) $line['qty'];
                $unit = (float) $line['unit_price'];
                $lt   = round($qty * $unit, 2);

                // Si la clé du tableau correspond à un ID existant → update
                if (is_numeric($key) && in_array((int)$key, $existingIds, true)) {
                    $lineModel = InvoiceLine::find((int)$key);
                    if ($lineModel && $lineModel->invoice_id === $invoice->id) {
                        $lineModel->update([
                            'product_id' => $line['product_id'],
                            'qty'        => $qty,
                            'unit_price' => $unit,
                            'line_total' => $lt,
                        ]);
                        $keepIds[] = $lineModel->id;
                    }
                } else {
                    // Sinon → create
                    $lineModel = InvoiceLine::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $line['product_id'],
                        'qty'        => $qty,
                        'unit_price' => $unit,
                        'line_total' => $lt,
                    ]);
                    $keepIds[] = $lineModel->id;
                }

                $total += $lt;
            }

            // Supprime les lignes non conservées
            $toDelete = array_diff($existingIds, $keepIds);
            if (!empty($toDelete)) {
                InvoiceLine::whereIn('id', $toDelete)->delete();
            }

            // Recalcule total
            $invoice->update(['total' => round($total, 2)]);
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour.');
    }

    /**
     * Suppression.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}
