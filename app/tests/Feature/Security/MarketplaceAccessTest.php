<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PRD §11/§45/§46/§125 (non-negotiable): the marketplace is private to
 * clinics, suppliers and authorized admin. Guests are redirected to login;
 * patients — and any account without marketplace.access — get a 403.
 * There are deliberately no /patient/marketplace routes.
 */
class MarketplaceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('marketplace.home'))->assertRedirect(route('login'));
    }

    public function test_patient_receives_forbidden(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $this->actingAs($patient)->get(route('marketplace.home'))->assertForbidden();
    }

    public function test_verified_clinic_owner_is_allowed(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->get(route('marketplace.home'))->assertOk();
    }

    public function test_verified_supplier_owner_is_allowed(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $owner = User::factory()->create();
        $owner->assignRole('supplier_owner');
        Supplier::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->get(route('marketplace.home'))->assertOk();
    }

    public function test_admin_is_allowed(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get(route('marketplace.home'))->assertOk();
    }
}
