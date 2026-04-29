<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // --- LOGIQUE DE FILTRAGE ---
        // Si c'est un commercial, on filtre par son ID. 
        // Si c'est un manager (is_commercial = false), on ne filtre pas (null).
        $commercialId = $user->is_commercial ? $user->id : null;

        $kpis = [
            'rdv_today' => Appointment::query()
                ->when($commercialId, fn($q) => $q->where('user_id', $commercialId))
                ->whereDate('start_at', $today)
                ->count(),

            'rdv_week' => Appointment::query()
                ->when($commercialId, fn($q) => $q->where('user_id', $commercialId))
                ->whereBetween('start_at', [$startOfWeek, $endOfWeek])
                ->count(),

            'clients_new' => Customer::query()
                ->when($commercialId, fn($q) => $q->where('user_id', $commercialId)) // Si tes clients sont liés à un commercial
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),

            'revenue_week' => Invoice::query()
                ->when($commercialId, function($q) use ($commercialId) {
                    // On ne somme que les factures des clients appartenant au commercial
                    $q->whereHas('customer', fn($sub) => $sub->where('user_id', $commercialId));
                })
                ->whereBetween('invoiced_at', [$startOfWeek, $endOfWeek])
                ->sum('total'),
        ];

        // --- LISTE DES RDV (CALENDRIER) ---
        $upcomingAppointments = Appointment::with(['customer', 'commercial'])
            ->when($commercialId, fn($q) => $q->where('user_id', $commercialId))
            ->where('start_at', '>=', $today->copy()->subDays(30)) // On prend un peu de passé pour le calendrier
            ->orderBy('start_at')
            ->get();

        return view('welcome', compact('kpis', 'upcomingAppointments'));
    }
}