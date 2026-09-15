<?php

namespace Tests\Feature\Clinic;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PRD §37-39/§103-104: clinic-side management, tenant isolation on dentists/staff/hours/pricing. */
class ClinicManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->seed(\Database\Seeders\CatalogueSeeder::class);
    }

    public function test_clinic_owner_can_add_a_dentist(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->post(route('clinic.dentists.store'), [
            'full_name' => 'Dr. Amina Juma',
            'status' => 'active',
        ])->assertRedirect(route('clinic.dentists.index'));

        $this->assertDatabaseHas('dentists', ['full_name' => 'Dr. Amina Juma']);
    }

    public function test_clinic_owner_cannot_edit_another_clinics_dentist(): void
    {
        $ownerA = User::factory()->create();
        $ownerA->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $clinicB = Clinic::factory()->create(['owner_user_id' => $ownerB->id]);
        $dentistOfB = Dentist::factory()->create(['clinic_id' => $clinicB->id]);

        $this->actingAs($ownerA)
            ->put(route('clinic.dentists.update', $dentistOfB), ['full_name' => 'Hijacked', 'status' => 'active'])
            ->assertForbidden();
    }

    public function test_clinic_owner_can_set_service_pricing_only_for_own_services(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $owner->id]);
        $service = Service::first();
        $clinic->services()->attach($service->id);

        $unrelatedService = Service::skip(1)->first();

        $this->actingAs($owner)->put(route('clinic.services.update'), [
            'prices' => [$service->id => '75000', $unrelatedService->id => '99999'],
        ])->assertRedirect(route('clinic.services.index'));

        $this->assertDatabaseHas('clinic_services', ['clinic_id' => $clinic->id, 'service_id' => $service->id, 'price' => 75000]);
        $this->assertDatabaseMissing('clinic_services', ['clinic_id' => $clinic->id, 'service_id' => $unrelatedService->id]);
    }

    public function test_clinic_owner_can_set_working_hours_and_add_blackout_date(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->put(route('clinic.availability.update'), [
            'days' => [1 => ['opens_at' => '08:00', 'closes_at' => '17:00']],
        ])->assertRedirect(route('clinic.availability.index'));

        $this->assertDatabaseHas('clinic_hours', ['clinic_id' => $clinic->id, 'day_of_week' => 1, 'is_closed' => 0]);

        $this->actingAs($owner)->post(route('clinic.availability.blackout.store'), [
            'date' => now()->addWeek()->toDateString(),
            'reason' => 'Public holiday',
        ])->assertRedirect(route('clinic.availability.index'));

        $this->assertDatabaseHas('clinic_blackout_dates', ['clinic_id' => $clinic->id, 'reason' => 'Public holiday']);
    }

    public function test_clinic_owner_can_add_staff_and_new_staff_can_log_in(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->post(route('clinic.staff.store'), [
            'name' => 'Receptionist Jane',
            'email' => 'jane@example.com',
            'role' => 'clinic_staff',
        ])->assertRedirect(route('clinic.staff.index'));

        $this->assertDatabaseHas('clinic_staff', ['clinic_id' => $clinic->id]);
        $newUser = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('clinic_staff'));
    }

    public function test_clinic_owner_cannot_remove_another_clinics_staff(): void
    {
        $ownerA = User::factory()->create();
        $ownerA->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $clinicB = Clinic::factory()->create(['owner_user_id' => $ownerB->id]);
        $staffUser = User::factory()->create();
        $staffMember = \App\Modules\Clinic\Models\ClinicStaff::create(['clinic_id' => $clinicB->id, 'user_id' => $staffUser->id, 'is_active' => true]);

        $this->actingAs($ownerA)
            ->delete(route('clinic.staff.destroy', $staffMember))
            ->assertForbidden();
    }

    public function test_approved_clinic_editing_profile_does_not_reset_verification_status(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $owner->id, 'verification_status' => Clinic::STATUS_APPROVED]);

        $this->actingAs($owner)->post(route('clinic.onboarding.store'), [
            'address_line' => '123 New Street',
            'region' => 'Dar es Salaam',
            'city' => 'Dar es Salaam',
        ])->assertRedirect(route('clinic.dashboard'));

        $this->assertSame(Clinic::STATUS_APPROVED, $clinic->fresh()->verification_status);
    }
}
