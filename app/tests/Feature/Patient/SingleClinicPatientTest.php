<?php

namespace Tests\Feature\Patient;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleClinicPatientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    private function enrolledPatient(): array
    {
        $clinic = Clinic::factory()->create(['verification_status' => Clinic::STATUS_APPROVED, 'is_active' => true]);
        $user = User::factory()->create();
        $user->assignRole('patient');
        ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $user->id]);

        return [$user, $clinic];
    }

    public function test_enrolled_patient_is_sent_to_support_instead_of_the_clinic_directory(): void
    {
        [$user, $clinic] = $this->enrolledPatient();

        $this->actingAs($user)->get(route('clinics.index'))->assertRedirect(route('patient.support'));
        $this->actingAs($user)->get(route('clinics.show', $clinic))->assertRedirect(route('patient.support'));
    }

    public function test_enrolled_patient_cannot_enroll_with_another_clinic(): void
    {
        [$user] = $this->enrolledPatient();
        $other = Clinic::factory()->create(['verification_status' => Clinic::STATUS_APPROVED, 'is_active' => true]);

        $this->actingAs($user)->get(route('patient.clinics.enroll.form', $other))->assertRedirect(route('patient.support'));
        $this->actingAs($user)->post(route('patient.clinics.enroll', $other), ['first_name' => 'A', 'last_name' => 'B'])
            ->assertRedirect(route('patient.support'));

        $this->assertDatabaseMissing('clinic_patients', ['clinic_id' => $other->id, 'user_id' => $user->id]);
    }

    public function test_support_page_shows_the_patients_own_clinic(): void
    {
        [$user, $clinic] = $this->enrolledPatient();

        $this->actingAs($user)->get(route('patient.support'))->assertOk()->assertSee($clinic->name)->assertSee('About the app');
    }
}
