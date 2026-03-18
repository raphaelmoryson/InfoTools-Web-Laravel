<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['customer_id', 'user_id', 'start_at', 'end_at', 'subject', 'notes'];
    use HasFactory;
    // AJOUTE CE BLOC ICI 👇
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function commercial()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un rendez-vous appartient à un client
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}