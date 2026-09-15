<?php

namespace Tests\Feature\Patient;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PRD §26 "Patient appointment" golden path, and §13 clinic-scoped enrollment. */
class EnrollmentAndAppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_patient_can_enroll_with_a_verified_clinic_and_request_an_appointment(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();

        $this->actingAs($patient)->post(route('patient.clinics.enroll', $clinic), [
            'first_name' => 'Grace',
            'last_name' => 'Mwakasege',
        ])->assertRedirect(route('patient.appointments.book', $clinic));

        $this->assertDatabaseHas('clinic_patients', [
            'clinic_id' => $clinic->id,
            'user_id' => $patient->id,
        ]);

        $this->actingAs($patient)->post(route('patient.appointments.store', $clinic), [
            'preferred_date' => now()->addDays(2)->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'clinic_id' => $clinic->id,
            'status' => Appointment::STATUS_REQUESTED,
        ]);
    }

    public function test_enrolling_twice_with_the_same_clinic_does_not_create_duplicate_records(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();

        $payload = ['first_name' => 'Grace', 'last_name' => 'Mwakasege'];

        $this->actingAs($patient)->post(route('patient.clinics.enroll', $clinic), $payload);
        $this->actingAs($patient)->post(route('patient.clinics.enroll', $clinic), $payload);

        $this->assertSame(1, ClinicPatient::where('clinic_id', $clinic->id)->where('user_id', $patient->id)->count());
    }

    public function test_patient_enrolling_with_two_clinics_gets_two_independent_records(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinicA = Clinic::factory()->create();
        $clinicB = Clinic::factory()->create();

        $payload = ['first_name' => 'Grace', 'last_name' => 'Mwakasege'];
        $this->actingAs($patient)->post(route('patient.clinics.enroll', $clinicA), $payload);
        $this->actingAs($patient)->post(route('patient.clinics.enroll', $clinicB), $payload);

        $recordA = ClinicPatient::where('clinic_id', $clinicA->id)->where('user_id', $patient->id)->first();
        $recordB = ClinicPatient::where('clinic_id', $clinicB->id)->where('user_id', $patient->id)->first();

        $this->assertNotNull($recordA);
        $this->assertNotNull($recordB);
        $this->assertNotSame($recordA->patient_number, $recordB->patient_number);
    }
}
