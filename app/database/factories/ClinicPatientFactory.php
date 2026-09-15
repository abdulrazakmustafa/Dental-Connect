<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ClinicPatient> */
class ClinicPatientFactory extends Factory
{
    protected $model = ClinicPatient::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'user_id' => User::factory(),
            'patient_number' => 'PT-'.$this->faker->unique()->numerify('######'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->numerify('+2557########'),
            'status' => 'active',
        ];
    }
}
