<?php
// app/Observers/AuditObserver.php (ex. générique pour Customer)
namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'row_id' => $model->getKey(),
            'action' => 'INSERT',
            'changed' => json_encode($model->getAttributes()),
            'ip' => request()?->ip(),
        ]);
    }
    public function updated(Model $model): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'row_id' => $model->getKey(),
            'action' => 'UPDATE',
            'changed' => json_encode(['before'=>$model->getOriginal(), 'after'=>$model->getChanges()]),
            'ip' => request()?->ip(),
        ]);
    }
    public function deleted(Model $model): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'table_name' => $model->getTable(),
            'row_id' => null,
            'action' => 'DELETE',
            'changed' => json_encode(['before'=>$model->getOriginal()]),
            'ip' => request()?->ip(),
        ]);
    }
}
