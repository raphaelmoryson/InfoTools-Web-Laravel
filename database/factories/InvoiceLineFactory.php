<?php

namespace Database\Factories;

use App\Models\InvoiceLine;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLineFactory extends Factory
{
    protected $model = InvoiceLine::class;

    public function definition(): array
    {
        $qty        = $this->faker->numberBetween(1, 5);
        $unit_price = $this->faker->randomFloat(2, 10, 500);
        $line_total = $qty * $unit_price;

        return [
            'invoice_id' => null, // défini depuis InvoiceFactory ou manuellement
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
            'qty'        => $qty,
            'unit_price' => $unit_price,
            'line_total' => $line_total,
        ];
    }
}
