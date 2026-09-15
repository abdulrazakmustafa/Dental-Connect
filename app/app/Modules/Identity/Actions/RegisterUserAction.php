<?php

namespace App\Modules\Identity\Actions;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Registration is a single critical transaction: identity + role assignment
 * (+ draft clinic/supplier record where applicable) commit together, before
 * any queued welcome-notification side effect (PRD §25/§13/§26).
 */
class RegisterUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            match ($data['role']) {
                'patient' => $user->assignRole('patient'),
                'clinic' => $this->createClinicOwner($user, $data),
                'supplier' => $this->createSupplierOwner($user, $data),
            };

            return $user;
        });
    }

    private function createClinicOwner(User $user, array $data): void
    {
        $user->assignRole('clinic_owner');

        Clinic::create([
            'name' => $data['organization_name'],
            'slug' => Str::slug($data['organization_name']).'-'.Str::lower(Str::random(5)),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'owner_user_id' => $user->id,
            'verification_status' => Clinic::STATUS_DRAFT,
            'is_active' => false,
        ]);
    }

    private function createSupplierOwner(User $user, array $data): void
    {
        $user->assignRole('supplier_owner');

        Supplier::create([
            'name' => $data['organization_name'],
            'slug' => Str::slug($data['organization_name']).'-'.Str::lower(Str::random(5)),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'owner_user_id' => $user->id,
            'verification_status' => 'draft',
            'is_active' => false,
        ]);
    }
}
