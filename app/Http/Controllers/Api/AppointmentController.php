<?php
// app/Http/Controllers/Api/AppointmentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $q = Appointment::query()
            ->with(['customer'])
            ->where('start_at', '>=', now());

        return $q->orderBy('start_at')->limit(50)->get();
    }

    public function store(StoreAppointmentRequest $r)
    {
        $user = $r->user();
        $data = $r->validated();
        $data['commercial_id'] = $user->id; // un commercial ne crée que pour lui-même

        $apt = Appointment::create($data);

        AuditLog::create([
            'user_id' => $user->id,
            'table_name' => 'appointments',
            'row_id' => $apt->id,
            'action' => 'INSERT',
            'changed' => json_encode($apt->toArray()),
            'ip' => $r->ip(),
        ]);

        return response()->json($apt, 201);
    }

    public function update(StoreAppointmentRequest $r, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $before = $appointment->getOriginal();

        $appointment->update($r->validated());

        AuditLog::create([
            'user_id' => $r->user()->id,
            'table_name' => 'appointments',
            'row_id' => $appointment->id,
            'action' => 'UPDATE',
            'changed' => json_encode(['before'=>$before,'after'=>$appointment->getChanges()]),
            'ip' => $r->ip(),
        ]);

        return $appointment;
    }

    public function destroy(Request $r, Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $before = $appointment->toArray();
        $appointment->delete();

        AuditLog::create([
            'user_id' => $r->user()->id,
            'table_name' => 'appointments',
            'row_id' => null,
            'action' => 'DELETE',
            'changed' => json_encode(['before'=>$before]),
            'ip' => $r->ip(),
        ]);

        return response()->noContent();
    }
}
