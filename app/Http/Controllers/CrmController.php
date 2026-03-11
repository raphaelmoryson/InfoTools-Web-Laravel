<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Auth;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $appointments = Appointment::where('user_id', $userId)
                                   ->where('date', '>=', now())
                                   ->orderBy('date', 'asc')
                                   ->get();

        return view('crm.dashboard', compact('appointments'));
    }
}
