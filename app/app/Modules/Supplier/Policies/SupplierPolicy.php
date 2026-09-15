<?php

namespace App\Modules\Supplier\Policies;

use App\Models\User;
use App\Modules\Supplier\Models\Supplier;

class SupplierPolicy
{
    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('supplier.profile.manage') && $this->belongsToSupplier($user, $supplier);
    }

    public function verify(User $user, Supplier $supplier): bool
    {
        return $user->can('supplier.verify');
    }

    private function belongsToSupplier(User $user, Supplier $supplier): bool
    {
        return $supplier->owner_user_id === $user->id
            || $user->supplierStaffMemberships()->where('supplier_id', $supplier->id)->exists();
    }
}
