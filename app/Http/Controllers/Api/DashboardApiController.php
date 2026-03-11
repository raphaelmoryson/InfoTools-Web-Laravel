<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Invoice;

class DashboardApiController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'customers' => Customer::count(),
            'appointments_today' => Appointment::whereDate('start_at', today())->count(),
            'revenue_month' => Invoice::whereMonth('created_at', now()->month)->sum('total'),
        ]);
    }
}
