<?php
// app/Http/Controllers/Api/CustomerPurchaseController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerPurchaseController extends Controller
{
    public function show(Customer $customer)
    {
        // Si tu veux restreindre par ownership commercial, crée une CustomerPolicy similaire
        $customer->load(['invoices' => function($q) {
            $q->with('lines.product')->orderByDesc('invoiced_at');
        }]);

        return [
            'customer' => $customer->only(['id','name','email','phone']),
            'invoices' => $customer->invoices->map(function($inv){
                return [
                    'id' => $inv->id,
                    'reference' => $inv->reference,
                    'date' => $inv->invoiced_at->format('Y-m-d'),
                    'total' => $inv->total,
                    'lines' => $inv->lines->map(fn($l)=>[
                        'product' => $l->product->name,
                        'qty'     => $l->qty,
                        'unit'    => $l->unit_price,
                        'total'   => $l->line_total,
                    ])
                ];
            })
        ];
    }
}
