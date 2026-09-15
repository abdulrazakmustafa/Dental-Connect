<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Supplier> */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        $name = fake()->company().' Dental Supplies';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'email' => fake()->unique()->companyEmail(),
            'owner_user_id' => User::factory(),
            'verification_status' => 'approved',
            'verified_at' => now(),
            'is_active' => true,
        ];
    }
}
