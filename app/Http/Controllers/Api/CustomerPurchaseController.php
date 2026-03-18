<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerPurchaseController extends Controller
{
    public function show(Customer $customer)
    {
        // On récupère les factures paginées avec leurs lignes et produits
        $invoices = $customer->invoices()
            ->with('lines.product')
            ->orderByDesc('invoiced_at')
            ->paginate(10);

        // On transforme les données pour l'affichage
        return [
            'customer' => $customer->only(['id', 'name', 'email', 'phone']),
            'invoices' => $invoices->getCollection()->map(function($inv) {
                return [
                    'id'        => $inv->id,
                    'reference' => $inv->formatted_reference, // Utilise ton accesseur
                    'date'      => $inv->invoiced_at->format('d/m/Y'),
                    'total'     => $inv->total,
                    'lines'     => $inv->lines->map(fn($l) => [
                        'product' => $l->product->name ?? 'Produit inconnu',
                        'qty'     => $l->qty,
                        'unit'    => $l->unit_price,
                        'total'   => $l->line_total,
                    ])
                ];
            }),
            // On renvoie les infos de pagination pour la vue
            'pagination' => [
                'current_page' => $invoices->currentPage(),
                'last_page'    => $invoices->lastPage(),
            ]
        ];
    }
}