<?php

namespace App\Modules\Patient\Actions;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Notification\Services\NotificationService;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Creates the clinic-owned patient enrollment (PRD §13/§36, non-negotiable).
 * The same `users` login identity may enroll with many clinics; each
 * enrollment is a fully independent ClinicPatient row, isolated per clinic.
 */
class EnrollPatientWithClinicAction
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function execute(User $user, Clinic $clinic, array $data): ClinicPatient
    {
        $clinicPatient = DB::transaction(function () use ($user, $clinic, $data) {
            $existing = ClinicPatient::where('clinic_id', $clinic->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            return ClinicPatient::create([
                'clinic_id' => $clinic->id,
                'user_id' => $user->id,
                'patient_number' => $this->nextPatientNumber($clinic),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'phone' => $data['phone'] ?? $user->phone,
                'email' => $user->email,
                'status' => 'active',
            ]);
        });

        if ($clinicPatient->wasRecentlyCreated) {
            $this->notifications->notify(
                $user,
                'enrollment.active',
                'Dental Connect',
                'Your clinic enrollment is active.',
                $clinicPatient,
            );
        }

        return $clinicPatient;
    }

    private function nextPatientNumber(Clinic $clinic): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $clinic->slug ?: $clinic->name), 0, 3)) ?: 'DC';

        $sequence = ClinicPatient::where('clinic_id', $clinic->id)->lockForUpdate()->count() + 1;

        $number = $prefix.'-'.str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);

        if (ClinicPatient::where('clinic_id', $clinic->id)->where('patient_number', $number)->exists()) {
            throw ValidationException::withMessages(['patient_number' => 'Could not allocate a patient number, please retry.']);
        }

        return $number;
    }
}
