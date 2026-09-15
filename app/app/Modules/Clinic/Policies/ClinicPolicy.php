<?php

namespace App\Modules\Clinic\Policies;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;

class ClinicPolicy
{
    public function view(?User $user, Clinic $clinic): bool
    {
        if ($clinic->isVerified() && $clinic->is_active) {
            return true; // public verified directory listing
        }

        return $user !== null && $this->belongsToClinic($user, $clinic);
    }

    public function update(User $user, Clinic $clinic): bool
    {
        return $user->can('clinic.profile.manage') && $this->belongsToClinic($user, $clinic);
    }

    public function manageStaff(User $user, Clinic $clinic): bool
    {
        return $user->can('clinic.staff.manage') && $clinic->owner_user_id === $user->id;
    }

    public function verify(User $user, Clinic $clinic): bool
    {
        return $user->can('clinic.verify');
    }

    private function belongsToClinic(User $user, Clinic $clinic): bool
    {
        return $clinic->owner_user_id === $user->id
            || $user->clinicStaffMemberships()->where('clinic_id', $clinic->id)->exists();
    }
}
