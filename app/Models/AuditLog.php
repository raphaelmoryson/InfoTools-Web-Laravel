<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    // On n'utilise pas les timestamps Laravel classiques
    public $timestamps = false;

    // Table associée
    protected $table = 'audit_logs';

    // Champs assignables en masse
    protected $fillable = [
        'user_id',
        'table_name',
        'row_id',
        'action',
        'changed',
        'ip',
        'created_at',
    ];

    // Cast pour JSON
    protected $casts = [
        'changed' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * L'utilisateur lié à l'action (nullable)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
