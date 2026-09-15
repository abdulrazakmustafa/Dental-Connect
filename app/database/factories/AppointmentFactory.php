<?php

namespace Database\Factories;

use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Appointment> */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'clinic_patient_id' => ClinicPatient::factory(),
            'preferred_date' => fake()->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'status' => Appointment::STATUS_REQUESTED,
        ];
    }
}
