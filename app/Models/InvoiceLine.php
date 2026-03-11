<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'qty',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'qty'         => 'integer',
        'unit_price'  => 'decimal:2',
        'line_total'  => 'decimal:2',
    ];

    /**
     * Facture parente.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Produit lié à la ligne.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calcule automatiquement le total de ligne si non défini.
     */
    protected static function booted(): void
    {
        static::saving(function (InvoiceLine $line) {
            $line->line_total = $line->qty * $line->unit_price;
        });
    }
}
