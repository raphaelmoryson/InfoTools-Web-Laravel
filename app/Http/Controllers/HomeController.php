<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Customer;

class HomeController extends Controller
{
    // Appliquer le middleware auth à toutes les méthodes
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today       = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $kpis = [
            'rdv_today'    => Appointment::whereDate('start_at', $today)->count(),
            'rdv_week'     => Appointment::whereBetween('start_at', [$startOfWeek, $endOfWeek])->count(),
            'clients_new'  => Customer::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'revenue_week' => \App\Models\Invoice::whereBetween('invoiced_at', [$startOfWeek, $endOfWeek])->sum('total'),
        ];

        $upcomingAppointments = Appointment::with('customer')
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(10)
            ->get()
            ->map(function ($a) {
                return (object) [
                    'start_at'       => $a->start_at,
                    'subject'        => $a->subject,
                    'client_name'    => optional($a->customer)->name ?? '—',
                    'client_company' => optional($a->customer)->company ?? null,
                    'location'       => optional($a->customer)->address ?? null,
                    'status'         => 'prévu',
                ];
            });

        $recentPurchases = [];
        $topProducts     = [];
        $auditLogs       = [];

        return view('welcome', compact(
            'kpis',
            'upcomingAppointments',
            'recentPurchases',
            'topProducts',
            'auditLogs'
        ));
    }
}
