<?php

namespace Database\Factories;

use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Dentist> */
class DentistFactory extends Factory
{
    protected $model = Dentist::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'full_name' => 'Dr. '.fake()->lastName(),
            'status' => 'active',
        ];
    }
}
