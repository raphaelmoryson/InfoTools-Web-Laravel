<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    public function index()
    {
        return Appointment::with(['customer', 'commercial'])
            ->orderBy('start_at')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'commercial_id' => 'required|exists:users,id',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'subject' => 'required|string|max:150',
            'notes' => 'nullable|string',
        ]);

        return response()->json(
            Appointment::create($data),
            201
        );
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return $appointment->load(['customer', 'commercial']);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->json(null, 204);
    }
}
