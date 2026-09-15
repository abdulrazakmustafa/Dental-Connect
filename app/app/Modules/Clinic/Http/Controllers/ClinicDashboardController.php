<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = $request->user()->ownedClinics()->first()
            ?? $request->user()->clinicStaffMemberships()->with('clinic')->first()?->clinic;

        abort_if(! $clinic, 404, 'No clinic is associated with this account yet.');

        $today = today();

        return view('clinic.dashboard', [
            'clinic' => $clinic,
            'todaysAppointments' => Appointment::where('clinic_id', $clinic->id)->whereDate('preferred_date', $today)->count(),
            'pendingRequests' => Appointment::where('clinic_id', $clinic->id)->where('status', Appointment::STATUS_REQUESTED)->count(),
            'totalPatients' => $clinic->patients()->count(),
        ]);
    }
}
