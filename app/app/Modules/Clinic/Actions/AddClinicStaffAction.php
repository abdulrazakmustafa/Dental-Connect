<?php

namespace App\Modules\Clinic\Actions;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Adds a staff member to a clinic (PRD §38 "Staff"). If no Dental Connect
 * account exists for the given email yet, one is created with a generated
 * temporary password — there is no email/SMS invite channel built yet
 * (Notification module adapters are still TODO), so the owner is shown the
 * temporary password once to relay to the new staff member directly.
 */
class AddClinicStaffAction
{
    /** @return array{user: User, temporaryPassword: ?string} */
    public function execute(Clinic $clinic, array $data): array
    {
        return DB::transaction(function () use ($clinic, $data) {
            $user = User::where('email', $data['email'])->first();
            $temporaryPassword = null;

            if ($user && ($user->ownedClinics()->exists() || $user->hasAnyRole(['supplier_owner', 'supplier_admin', 'supplier_staff', 'admin', 'super_admin']))) {
                throw ValidationException::withMessages(['email' => 'This email already belongs to an account that cannot be added as clinic staff.']);
            }

            if (! $user) {
                $temporaryPassword = Str::password(12);

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($temporaryPassword),
                    'status' => 'active',
                ]);
            }

            if (ClinicStaff::where('clinic_id', $clinic->id)->where('user_id', $user->id)->exists()) {
                throw ValidationException::withMessages(['email' => 'This person is already staff at this clinic.']);
            }

            ClinicStaff::create([
                'clinic_id' => $clinic->id,
                'user_id' => $user->id,
                'title' => $data['title'] ?? null,
                'is_active' => true,
            ]);

            $user->syncRoles([$data['role']]);

            return ['user' => $user, 'temporaryPassword' => $temporaryPassword];
        });
    }
}
