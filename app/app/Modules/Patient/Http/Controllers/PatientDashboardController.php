<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Notification\Models\PatientNotification;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $clinicPatients = $user->clinicPatients()->with('clinic:id,public_id,name,slug,logo_path')->orderByDesc('id')->get();
        $activeClinicPatient = $this->resolveActiveClinicPatient($request, $clinicPatients);

        $nextAppointment = Appointment::whereHas('clinicPatient', fn ($q) => $q->where('user_id', $user->id))
            ->when($activeClinicPatient, fn ($q) => $q->where('clinic_patient_id', $activeClinicPatient->id))
            ->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED])
            ->where('preferred_date', '>=', today())
            ->orderBy('preferred_date')
            ->with(['clinic:id,public_id,name,slug', 'dentist:id,full_name', 'service:id,name'])
            ->first();

        $recentActivity = PatientNotification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('patient.dashboard', [
            'clinicPatients' => $clinicPatients,
            'activeClinicPatient' => $activeClinicPatient,
            'nextAppointment' => $nextAppointment,
            'recentActivity' => $recentActivity,
        ]);
    }

    public function switchClinic(Request $request): \Illuminate\View\View
    {
        $clinicPatients = $request->user()->clinicPatients()->with('clinic:id,name')->get();

        return view('patient.dashboard-switch', ['clinicPatients' => $clinicPatients]);
    }

    public function selectClinic(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate(['clinic_patient_id' => ['required', 'exists:clinic_patients,id']]);

        $owns = $request->user()->clinicPatients()->whereKey($data['clinic_patient_id'])->exists();
        abort_unless($owns, 403);

        $request->session()->put('active_clinic_patient_id', (int) $data['clinic_patient_id']);

        return redirect()->route('patient.dashboard');
    }

    private function resolveActiveClinicPatient(Request $request, $clinicPatients): ?ClinicPatient
    {
        $activeId = $request->session()->get('active_clinic_patient_id');

        if ($activeId && $match = $clinicPatients->firstWhere('id', $activeId)) {
            return $match;
        }

        return $clinicPatients->first();
    }
}
