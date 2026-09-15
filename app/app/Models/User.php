<?php

namespace App\Models;

use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicStaff;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Shared\Concerns\HasPublicUlid;
use App\Modules\Supplier\Models\Supplier;
use App\Modules\Supplier\Models\SupplierStaff;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasPublicUlid, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /** Clinic-owned patient enrollments — one row per clinic this user has joined. */
    public function clinicPatients(): HasMany
    {
        return $this->hasMany(ClinicPatient::class);
    }

    /** Clinics this user owns as clinic_owner. */
    public function ownedClinics(): HasMany
    {
        return $this->hasMany(Clinic::class, 'owner_user_id');
    }

    /** Suppliers this user owns as supplier_owner. */
    public function ownedSuppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'owner_user_id');
    }

    /** Explicit clinic staff assignments (clinic_admin/clinic_staff scope). */
    public function clinicStaffMemberships(): HasMany
    {
        return $this->hasMany(ClinicStaff::class);
    }

    /** Explicit supplier staff assignments (supplier_admin/supplier_staff scope). */
    public function supplierStaffMemberships(): HasMany
    {
        return $this->hasMany(SupplierStaff::class);
    }
}
