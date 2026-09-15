<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PRD §103/§104 (non-negotiable): cross-tenant access is a security bug.
 * Clinic A must never read or write Clinic B's patients/appointments;
 * Supplier A must never update Supplier B's product.
 */
class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinic_owner_cannot_view_another_clinics_patient(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $ownerA = User::factory()->create();
        $ownerA->assignRole('clinic_owner');
        $clinicA = Clinic::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $ownerB->assignRole('clinic_owner');
        $clinicB = Clinic::factory()->create(['owner_user_id' => $ownerB->id]);

        $patientOfB = ClinicPatient::factory()->create(['clinic_id' => $clinicB->id]);

        $this->actingAs($ownerA)
            ->get(route('clinic.patients.show', $patientOfB))
            ->assertForbidden();
    }

    public function test_clinic_owner_cannot_update_another_clinics_appointment(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $ownerA = User::factory()->create();
        $ownerA->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $ownerB->assignRole('clinic_owner');
        $clinicB = Clinic::factory()->create(['owner_user_id' => $ownerB->id]);
        $patientOfB = ClinicPatient::factory()->create(['clinic_id' => $clinicB->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinicB->id,
            'clinic_patient_id' => $patientOfB->id,
            'preferred_date' => now()->addDay(),
        ]);

        $this->actingAs($ownerA)
            ->patch(route('clinic.appointments.status', $appointment), ['status' => 'confirmed'])
            ->assertForbidden();
    }

    public function test_clinic_patients_index_only_lists_own_clinics_patients(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $ownerA = User::factory()->create();
        $ownerA->assignRole('clinic_owner');
        $clinicA = Clinic::factory()->create(['owner_user_id' => $ownerA->id]);
        $ownPatient = ClinicPatient::factory()->create(['clinic_id' => $clinicA->id, 'first_name' => 'Amina']);

        $ownerB = User::factory()->create();
        $clinicB = Clinic::factory()->create(['owner_user_id' => $ownerB->id]);
        ClinicPatient::factory()->create(['clinic_id' => $clinicB->id, 'first_name' => 'Beatrice']);

        $response = $this->actingAs($ownerA)->get(route('clinic.patients.index'));

        $response->assertOk();
        $response->assertSee('Amina');
        $response->assertDontSee('Beatrice');
    }

    public function test_supplier_owner_cannot_update_another_suppliers_product(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $ownerA = User::factory()->create();
        $ownerA->assignRole('supplier_owner');
        Supplier::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $ownerB->assignRole('supplier_owner');
        $supplierB = Supplier::factory()->create(['owner_user_id' => $ownerB->id]);
        $productOfB = Product::factory()->create(['supplier_id' => $supplierB->id]);

        $this->assertFalse($ownerA->can('update', $productOfB));
        $this->assertTrue($ownerB->can('update', $productOfB));
    }

    public function test_patient_can_view_own_enrollment_but_not_another_clinics(): void
    {
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $clinic = Clinic::factory()->create();
        $ownRecord = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $patient->id]);
        $someoneElsesRecord = ClinicPatient::factory()->create(['clinic_id' => $clinic->id]);

        $this->assertTrue($patient->can('view', $ownRecord));
        $this->assertFalse($patient->can('view', $someoneElsesRecord));
    }
}
