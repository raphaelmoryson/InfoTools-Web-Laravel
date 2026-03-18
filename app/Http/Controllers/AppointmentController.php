<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    /**
     * Liste + recherche + filtre date.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $dateFrom = $request->date('from');
        $dateTo = $request->date('to');

        $appointments = Appointment::query()
            ->with(['customer', 'commercial'])
            ->when($q, function ($query) use ($q) {
                $query->where('subject', 'like', "%{$q}%")
                    ->orWhereHas('customer', fn($qq) => $qq->where('name', 'like', "%{$q}%"));
            })
            ->when($dateFrom, fn($query) => $query->where('start_at', '>=', $dateFrom))
            ->when($dateTo, fn($query) => $query->where('start_at', '<=', $dateTo))
            ->orderBy('start_at', 'desc')
            ->simplePaginate(15)
            ->withQueryString();

        return view('appointments.index', compact('appointments', 'q', 'dateFrom', 'dateTo'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $commercials = User::orderBy('name')->pluck('name', 'id');

        return view('appointments.create', compact('customers', 'commercials'));
    }

    /**
     * Enregistrement.
     */
    public function store(StoreAppointmentRequest $request)
    {
        // 1. On récupère les données validées par le FormRequest
        $data = $request->validated();

        // 2. 🔒 On force l'injection de l'utilisateur connecté côté serveur
        $data['user_id'] = auth()->id();

        // 3. On crée le rendez-vous
        $appointment = Appointment::create($data);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Rendez-vous créé avec succès.');
    }

    /**
     * Détail.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'commercial']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Appointment $appointment)
    {
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $commercials = User::orderBy('name')->pluck('name', 'id');

        return view('appointments.edit', compact('appointment', 'customers', 'commercials'));
    }

    /**
     * Mise à jour.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()
            ->route('appointments.index', $appointment)
            ->with('success', 'Rendez-vous mis à jour.');
    }

    /**
     * Suppression.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Rendez-vous supprimé.');
    }
}
