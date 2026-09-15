<?php

namespace Database\Factories;

use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Models\Rfq;
use App\Modules\Supplier\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Rfq> */
class RfqFactory extends Factory
{
    protected $model = Rfq::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'supplier_id' => Supplier::factory(),
            'requested_by' => User::factory(),
            'quantity' => fake()->numberBetween(1, 50),
            'message' => fake()->sentence(),
            'status' => 'open',
        ];
    }
}
