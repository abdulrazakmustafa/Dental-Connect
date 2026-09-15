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
        $tab = $request->query('tab', 'upcoming');

        $appointments = Appointment::query()
            ->whereHas('clinicPatient', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($tab === 'upcoming', fn ($q) => $q->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED]))
            ->when($tab === 'completed', fn ($q) => $q->where('status', Appointment::STATUS_COMPLETED))
            ->when($tab === 'cancelled', fn ($q) => $q->whereIn('status', [Appointment::STATUS_CANCELLED, Appointment::STATUS_DECLINED, Appointment::STATUS_NO_SHOW]))
            ->with(['clinic:id,public_id,name,slug'])
            ->orderByDesc('preferred_date')
            ->paginate(10)
            ->withQueryString();

        return view('patient.appointments.index', ['appointments' => $appointments, 'tab' => $tab]);
    }

    public function patientShow(Request $request, Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        return view('patient.appointments.show', ['appointment' => $appointment->load(['clinic', 'dentist', 'service', 'statusHistory'])]);
    }

    public function confirmation(Request $request, Appointment $appointment): View
    {
        $this->authorize('view', $appointment);

        return view('patient.appointments.confirmation', ['appointment' => $appointment->load(['clinic', 'service'])]);
    }

    public function bookForm(Request $request, Clinic $clinic): View
    {
        abort_unless($clinic->isVerified() && $clinic->is_active, 404);

        ClinicPatient::where('clinic_id', $clinic->id)->where('user_id', $request->user()->id)->firstOrFail();

        $clinic->load(['services' => fn ($q) => $q->orderBy('name'), 'dentists' => fn ($q) => $q->where('status', 'active')->with('specialties')]);

        return view('patient.appointments.book', ['clinic' => $clinic]);
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

        $this->assertNoDentistConflict($data);

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

        return redirect()->route('patient.appointments.confirmation', $appointment);
    }

    public function rescheduleForm(Request $request, Appointment $appointment): View
    {
        $this->authorize('update', $appointment);

        $appointment->load(['clinic.dentists' => fn ($q) => $q->where('status', 'active')]);

        return view('patient.appointments.reschedule', ['appointment' => $appointment]);
    }

    public function reschedule(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);
        abort_unless(in_array($appointment->status, [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED], true), 422);

        $data = $request->validate([
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'date_format:H:i'],
        ]);

        $this->assertNoDentistConflict($data + ['dentist_id' => $appointment->dentist_id]);

        $appointment->update([
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'] ?? null,
            'status' => Appointment::STATUS_REQUESTED, // clinic re-confirms the new time
        ]);

        return redirect()->route('patient.appointments.show', $appointment)->with('status', 'Your appointment was rescheduled and sent to the clinic for confirmation.');
    }

    public function cancel(Request $request, Appointment $appointment, AppointmentStatusService $service): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $service->transition($appointment, Appointment::STATUS_CANCELLED, $request->user());

        return redirect()->route('patient.appointments.index')->with('status', 'Appointment cancelled.');
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

    private function assertNoDentistConflict(array $data): void
    {
        if (empty($data['dentist_id']) || empty($data['preferred_time'])) {
            return;
        }

        $conflict = Appointment::where('dentist_id', $data['dentist_id'])
            ->where('preferred_date', $data['preferred_date'])
            ->where('preferred_time', $data['preferred_time'])
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages(['preferred_time' => 'This dentist already has a confirmed appointment at that time.']);
        }
    }
}
