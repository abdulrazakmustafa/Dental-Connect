<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $nextAppointment = Appointment::whereHas('clinicPatient', fn ($q) => $q->where('user_id', $user->id))
            ->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED])
            ->where('preferred_date', '>=', today())
            ->orderBy('preferred_date')
            ->with('clinic:id,public_id,name,slug')
            ->first();

        return view('patient.dashboard', [
            'clinicPatients' => $user->clinicPatients()->with('clinic:id,public_id,name,slug,logo_path')->get(),
            'nextAppointment' => $nextAppointment,
        ]);
    }
}
