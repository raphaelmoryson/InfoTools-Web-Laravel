<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceApiController extends Controller
{
    public function index()
    {
        return Invoice::with('customer')
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string',
            'invoiced_at' => 'required|date', // Ajout de la validation
            'reference' => 'required|string|unique:invoices,reference', // Ajout si requis
        ]);

        return response()->json(
            Invoice::create($data),
            201
        );
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load('customer');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->all());

        return response()->json($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json(null, 204);
    }
}
