<?php

namespace App\Modules\Marketplace\Policies;

use App\Models\User;
use App\Modules\Marketplace\Models\Product;

/**
 * Tenant isolation for supplier product ownership (PRD §66, §103):
 * Supplier A must never update Supplier B's product.
 */
class ProductPolicy
{
    public function update(User $user, Product $product): bool
    {
        return $user->can('products.manage') && $this->belongsToSupplier($user, $product);
    }

    public function moderate(User $user, Product $product): bool
    {
        return $user->can('products.moderate');
    }

    private function belongsToSupplier(User $user, Product $product): bool
    {
        return $product->supplier->owner_user_id === $user->id
            || $user->supplierStaffMemberships()->where('supplier_id', $product->supplier_id)->exists();
    }
}
