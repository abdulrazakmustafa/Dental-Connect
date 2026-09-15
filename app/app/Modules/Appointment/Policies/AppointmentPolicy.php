<?php

namespace App\Modules\Appointment\Policies;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        if ($appointment->clinicPatient->user_id === $user->id) {
            return true; // the requesting patient
        }

        return $user->can('appointments.view') && $this->belongsToActorsClinic($user, $appointment);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        // Patients may cancel their own pending request; clinic staff manage the rest.
        if ($appointment->clinicPatient->user_id === $user->id) {
            return true;
        }

        return $user->can('appointments.manage') && $this->belongsToActorsClinic($user, $appointment);
    }

    private function belongsToActorsClinic(User $user, Appointment $appointment): bool
    {
        return $user->ownedClinics()->whereKey($appointment->clinic_id)->exists()
            || $user->clinicStaffMemberships()->where('clinic_id', $appointment->clinic_id)->exists();
    }
}
