<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui peuvent être remplis en masse.
     */
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
        'user_id',
    ];

    /**
     * Les attributs convertis automatiquement.
     */
    protected $casts = [
        'last_contact_at' => 'date',
        'next_meeting_at' => 'date',
        'total_spent' => 'decimal:2',
    ];

    /**
     * Relation : le commercial assigné (utilisateur).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : les rendez-vous du client.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relation : les achats / commandes du client.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Scope : filtrer les clients d’un commercial spécifique.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accesseur : nom complet du client.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        return $this->name ?? $this->company_name ?? '—';
    }
}
