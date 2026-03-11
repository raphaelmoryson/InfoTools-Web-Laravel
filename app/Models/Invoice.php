<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoiced_at',
        'reference',
        'total',
    ];

    protected $casts = [
        'invoiced_at' => 'date',
        'total'       => 'decimal:2',
    ];

    /**
     * Client lié à la facture.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Lignes associées à cette facture.
     */
    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /**
     * Recalcule le total en fonction des lignes.
     */
    public function recalculateTotal(): void
    {
        $total = $this->lines()->sum('line_total');
        $this->update(['total' => $total]);
    }

    /**
     * Format affichage (ex: INV-0012)
     */
    public function getFormattedReferenceAttribute(): string
    {
        return strtoupper($this->reference);
    }
}
