<?php

namespace Database\Factories;

use App\Modules\Marketplace\Models\Product;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'supplier_id' => Supplier::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => fake()->randomFloat(2, 1000, 500000),
            'status' => 'active',
            'moderation_status' => 'approved',
            'is_available' => true,
        ];
    }
}
