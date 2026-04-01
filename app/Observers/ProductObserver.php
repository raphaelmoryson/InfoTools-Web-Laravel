<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function created(Product $product): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $product->getTable(),
            'row_id'     => $product->getKey(),
            'action'     => 'INSERT',
            'changed'    => json_encode($product->getAttributes()),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function updated(Product $product): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $product->getTable(),
            'row_id'     => $product->getKey(),
            'action'     => 'UPDATE',
            'changed'    => json_encode([
                'before' => $product->getOriginal(),
                'after'  => $product->getChanges(),
            ]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function deleted(Product $product): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'table_name' => $product->getTable(),
            'row_id'     => $product->getKey(),
            'action'     => 'DELETE',
            'changed'    => json_encode(['before' => $product->getOriginal()]),
            'ip'         => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}