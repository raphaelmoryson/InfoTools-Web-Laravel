<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $invoice->getTable(),
            'row_id'     => $invoice->getKey(),
            'action'     => 'INSERT',
            'changed'    => json_encode($invoice->getAttributes()),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function updated(Invoice $invoice): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $invoice->getTable(),
            'row_id'     => $invoice->getKey(),
            'action'     => 'UPDATE',
            'changed'    => json_encode([
                'before' => $invoice->getOriginal(),
                'after'  => $invoice->getChanges(),
            ]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function deleted(Invoice $invoice): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $invoice->getTable(),
            'row_id'     => $invoice->getKey(),
            'action'     => 'DELETE',
            'changed'    => json_encode(['before' => $invoice->getOriginal()]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}