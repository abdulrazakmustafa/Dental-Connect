<?php

namespace App\Modules\Clinic\Policies;

use App\Models\User;
use App\Modules\Clinic\Models\Dentist;

class DentistPolicy
{
    public function update(User $user, Dentist $dentist): bool
    {
        return $user->can('clinic.profile.manage') && $this->belongsToActorsClinic($user, $dentist);
    }

    private function belongsToActorsClinic(User $user, Dentist $dentist): bool
    {
        return $user->ownedClinics()->whereKey($dentist->clinic_id)->exists()
            || $user->clinicStaffMemberships()->where('clinic_id', $dentist->clinic_id)->exists();
    }
}
