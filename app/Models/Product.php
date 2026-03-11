<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Lignes de factures associées à ce produit.
     */
    public function invoiceLines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /**
     * Formate le prix pour l’affichage (ex: 49,90 €)
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }

    /**
     * Réduit le stock après une vente.
     */
    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }

    /**
     * Réapprovisionne le stock.
     */
    public function increaseStock(int $qty): void
    {
        $this->increment('stock', $qty);
    }
}
