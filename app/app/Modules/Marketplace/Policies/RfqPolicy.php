<?php

namespace App\Modules\Marketplace\Policies;

use App\Models\User;
use App\Modules\Marketplace\Models\Rfq;

class RfqPolicy
{
    public function view(User $user, Rfq $rfq): bool
    {
        return $this->belongsToActorsClinic($user, $rfq) || $this->belongsToActorsSupplier($user, $rfq);
    }

    public function respond(User $user, Rfq $rfq): bool
    {
        return $user->can('rfqs.manage') && $this->belongsToActorsSupplier($user, $rfq);
    }

    private function belongsToActorsClinic(User $user, Rfq $rfq): bool
    {
        return $user->ownedClinics()->whereKey($rfq->clinic_id)->exists()
            || $user->clinicStaffMemberships()->where('clinic_id', $rfq->clinic_id)->exists();
    }

    private function belongsToActorsSupplier(User $user, Rfq $rfq): bool
    {
        return $user->ownedSuppliers()->whereKey($rfq->supplier_id)->exists()
            || $user->supplierStaffMemberships()->where('supplier_id', $rfq->supplier_id)->exists();
    }
}
