<?php

namespace App\Modules\Patient\Policies;

use App\Models\User;
use App\Modules\Patient\Models\ClinicPatient;

/**
 * Tenant isolation boundary (PRD §14.2, §66): Clinic A must never view or
 * manage a ClinicPatient row that belongs to Clinic B, even if it is the
 * same human. Ownership is re-verified server-side on every check — the
 * clinic_id on the model is compared against the actor's own clinic(s),
 * never trusted from client input alone.
 */
class ClinicPatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('patients.view');
    }

    public function view(User $user, ClinicPatient $clinicPatient): bool
    {
        if ($user->id === $clinicPatient->user_id) {
            return true; // the patient may view their own enrollment
        }

        return $user->can('patients.view') && $this->belongsToActorsClinic($user, $clinicPatient);
    }

    public function update(User $user, ClinicPatient $clinicPatient): bool
    {
        return $user->can('patients.manage') && $this->belongsToActorsClinic($user, $clinicPatient);
    }

    private function belongsToActorsClinic(User $user, ClinicPatient $clinicPatient): bool
    {
        return $user->ownedClinics()->whereKey($clinicPatient->clinic_id)->exists()
            || $user->clinicStaffMemberships()->where('clinic_id', $clinicPatient->clinic_id)->exists();
    }
}
