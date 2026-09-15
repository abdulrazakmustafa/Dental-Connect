<?php

namespace Tests\Feature\Patient;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewAndRescheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_patient_can_review_their_own_completed_appointment(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $patient->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_COMPLETED,
        ]);

        $this->actingAs($patient)->post(route('patient.reviews.store', $appointment), [
            'rating' => 5,
            'comment' => 'Great visit.',
        ])->assertRedirect(route('patient.appointments.show', $appointment));

        $this->assertDatabaseHas('reviews', ['appointment_id' => $appointment->id, 'rating' => 5]);
    }

    public function test_patient_cannot_review_someone_elses_appointment(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('patient');
        $intruder = User::factory()->create();
        $intruder->assignRole('patient');

        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $owner->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_COMPLETED,
        ]);

        $this->actingAs($intruder)
            ->post(route('patient.reviews.store', $appointment), ['rating' => 1])
            ->assertForbidden();

        $this->assertDatabaseMissing('reviews', ['appointment_id' => $appointment->id]);
    }

    public function test_patient_cannot_review_an_appointment_that_is_not_completed(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $patient->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_REQUESTED,
        ]);

        $this->actingAs($patient)
            ->post(route('patient.reviews.store', $appointment), ['rating' => 5])
            ->assertNotFound();
    }

    public function test_patient_can_reschedule_their_own_requested_appointment(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $patient->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_CONFIRMED,
            'preferred_date' => now()->addDays(2)->toDateString(),
        ]);

        $newDate = now()->addDays(5)->toDateString();

        $this->actingAs($patient)
            ->patch(route('patient.appointments.reschedule.update', $appointment), ['preferred_date' => $newDate, 'preferred_time' => '11:30'])
            ->assertRedirect(route('patient.appointments.show', $appointment));

        $appointment->refresh();
        $this->assertSame($newDate, $appointment->preferred_date->toDateString());
        $this->assertSame(Appointment::STATUS_REQUESTED, $appointment->status);
    }

    public function test_patient_can_cancel_their_own_appointment(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $patient->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_REQUESTED,
        ]);

        $this->actingAs($patient)
            ->patch(route('patient.appointments.cancel', $appointment))
            ->assertRedirect(route('patient.appointments.index'));

        $this->assertSame(Appointment::STATUS_CANCELLED, $appointment->fresh()->status);
    }

    public function test_patient_cannot_cancel_another_patients_appointment(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('patient');
        $intruder = User::factory()->create();
        $intruder->assignRole('patient');

        $clinic = Clinic::factory()->create();
        $clinicPatient = ClinicPatient::factory()->create(['clinic_id' => $clinic->id, 'user_id' => $owner->id]);
        $appointment = Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'clinic_patient_id' => $clinicPatient->id,
            'status' => Appointment::STATUS_REQUESTED,
        ]);

        $this->actingAs($intruder)
            ->patch(route('patient.appointments.cancel', $appointment))
            ->assertForbidden();
    }
}
