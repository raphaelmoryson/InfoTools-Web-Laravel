<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'        => ucfirst($this->faker->words(2, true)), // ex: "Chaise ergonomique"
            'description' => $this->faker->optional()->sentence(10),
            'price'       => $this->faker->randomFloat(2, 5, 500), // entre 5€ et 500€
            'stock'       => $this->faker->numberBetween(0, 100),
        ];
    }
}
