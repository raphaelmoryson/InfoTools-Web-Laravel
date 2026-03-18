<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'status',
        'last_contact_at',
        'next_meeting_at',
        'total_spent',
        'user_id'
    ];

    protected $casts = [
        'last_contact_at' => 'date',
        'next_meeting_at' => 'date',
        'total_spent' => 'decimal:2',
    ];


    // Dans app/Models/Customer.php
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    // Relation vers le commercial (User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // Accesseur pour {{ $customer->full_name }}
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        return $this->name ?? $this->company_name ?? 'Client inconnu';
    }
}