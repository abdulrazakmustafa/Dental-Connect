<?php

namespace App\Modules\Appointment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Appointment\Services\AppointmentStatusService;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function patientIndex(Request $request): View
    {
        $appointments = Appointment::query()
            ->whereHas('clinicPatient', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with(['clinic:id,public_id,name,slug', 'dentist:id,full_name'])
            ->orderByDesc('preferred_date')
            ->paginate(10);

        return view('patient.appointments.index', ['appointments' => $appointments]);
    }

    public function patientShow(Request $request, Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        return view('patient.appointments.show', ['appointment' => $appointment->load(['clinic', 'dentist', 'statusHistory'])]);
    }

    public function store(Request $request, Clinic $clinic): RedirectResponse
    {
        abort_unless($clinic->isVerified() && $clinic->is_active, 404);

        $clinicPatient = ClinicPatient::where('clinic_id', $clinic->id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $data = $request->validate([
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'date_format:H:i'],
            'service_id' => ['nullable', 'exists:services,id'],
            'dentist_id' => ['nullable', 'exists:dentists,id'],
            'patient_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! empty($data['dentist_id']) && ! empty($data['preferred_time'])) {
            $conflict = Appointment::where('dentist_id', $data['dentist_id'])
                ->where('preferred_date', $data['preferred_date'])
                ->where('preferred_time', $data['preferred_time'])
                ->whereIn('status', [Appointment::STATUS_CONFIRMED])
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages(['preferred_time' => 'This dentist already has a confirmed appointment at that time.']);
            }
        }

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'dentist_id' => $data['dentist_id'] ?? null,
            'service_id' => $data['service_id'] ?? null,
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'] ?? null,
            'patient_note' => $data['patient_note'] ?? null,
            'status' => Appointment::STATUS_REQUESTED,
        ]);

        return redirect()->route('patient.appointments.show', $appointment)->with('status', 'Appointment request sent to the clinic.');
    }

    public function clinicIndex(Request $request): View
    {
        $clinicIds = $request->user()->ownedClinics()->pluck('id')
            ->merge($request->user()->clinicStaffMemberships()->pluck('clinic_id'));

        $appointments = Appointment::query()
            ->whereIn('clinic_id', $clinicIds)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->with(['clinicPatient:id,first_name,last_name,patient_number', 'dentist:id,full_name'])
            ->orderBy('preferred_date')
            ->paginate(15);

        return view('clinic.appointments.index', ['appointments' => $appointments]);
    }

    public function updateStatus(Request $request, Appointment $appointment, AppointmentStatusService $service): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $data = $request->validate([
            'status' => ['required', 'in:confirmed,reschedule_proposed,declined,cancelled,completed,no_show'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $service->transition($appointment, $data['status'], $request->user(), $data['note'] ?? null);

        return back()->with('status', 'Appointment updated.');
    }
}
