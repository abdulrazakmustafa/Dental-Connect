<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Clinic> */
class ClinicFactory extends Factory
{
    protected $model = Clinic::class;

    public function definition(): array
    {
        $name = fake()->company().' Dental Clinic';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'description' => fake()->sentence(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('+2557########'),
            'owner_user_id' => User::factory(),
            'verification_status' => Clinic::STATUS_APPROVED,
            'verified_at' => now(),
            'is_active' => true,
            'profile_completion_percent' => 100,
        ];
    }
}
