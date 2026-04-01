<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $appointment->getTable(),
            'row_id'     => $appointment->getKey(),
            'action'     => 'INSERT',
            'changed'    => json_encode($appointment->getAttributes()),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function updated(Appointment $appointment): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $appointment->getTable(),
            'row_id'     => $appointment->getKey(),
            'action'     => 'UPDATE',
            'changed'    => json_encode([
                'before' => $appointment->getOriginal(),
                'after'  => $appointment->getChanges(),
            ]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function deleted(Appointment $appointment): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $appointment->getTable(),
            'row_id'     => $appointment->getKey(),
            'action'     => 'DELETE',
            'changed'    => json_encode(['before' => $appointment->getOriginal()]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}