<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * RBAC baseline per PRD §31. Roles and permissions are deliberately separate:
 * a role is a named bundle of permissions, but every protected action ALSO
 * re-checks contextual scope (clinic_id / supplier_id ownership) in policies —
 * see ARCHITECTURE.md "RBAC". Holding a permission never implies cross-tenant
 * access on its own.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'patients.view', 'patients.manage',
            'appointments.view', 'appointments.manage',
            'clinic.verify',
            'supplier.verify',
            'marketplace.access',
            'products.create', 'products.manage', 'products.moderate',
            'rfqs.view', 'rfqs.manage',
            'reviews.moderate',
            'complaints.manage',
            'clinic.profile.manage',
            'clinic.staff.manage',
            'supplier.profile.manage',
            'supplier.staff.manage',
            'roles.manage',
            'users.manage',
            'audit.view',
            'settings.manage',
            'analytics.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'patient' => [],
            'clinic_owner' => [
                'patients.view', 'patients.manage', 'appointments.view', 'appointments.manage',
                'marketplace.access', 'rfqs.view', 'rfqs.manage', 'clinic.profile.manage',
                'clinic.staff.manage', 'analytics.view',
            ],
            'clinic_admin' => [
                'patients.view', 'patients.manage', 'appointments.view', 'appointments.manage',
                'marketplace.access', 'rfqs.view', 'rfqs.manage', 'clinic.profile.manage', 'analytics.view',
            ],
            'clinic_staff' => [
                'patients.view', 'appointments.view', 'appointments.manage',
            ],
            'supplier_owner' => [
                'marketplace.access', 'products.create', 'products.manage', 'rfqs.view', 'rfqs.manage',
                'supplier.profile.manage', 'supplier.staff.manage', 'analytics.view',
            ],
            'supplier_admin' => [
                'marketplace.access', 'products.create', 'products.manage', 'rfqs.view', 'rfqs.manage',
                'supplier.profile.manage', 'analytics.view',
            ],
            'supplier_staff' => [
                'marketplace.access', 'products.manage', 'rfqs.view',
            ],
            'admin' => [
                'patients.view', 'clinic.verify', 'supplier.verify', 'marketplace.access',
                'products.moderate', 'reviews.moderate', 'complaints.manage', 'audit.view',
                'analytics.view',
            ],
            'super_admin' => $permissions, // full access; still subject to policy checks
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
