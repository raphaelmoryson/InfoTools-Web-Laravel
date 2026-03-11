<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(),
            'invoiced_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'reference'   => strtoupper(Str::random(3)) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'total'       => 0, // sera recalculé après les lignes
        ];
    }

    /**
     * Crée aussi des lignes associées automatiquement.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Invoice $invoice) {
            // crée entre 2 et 5 lignes aléatoires
            $lines = \App\Models\InvoiceLine::factory(rand(2, 5))->create([
                'invoice_id' => $invoice->id,
            ]);

            // met à jour le total de la facture
            $invoice->update(['total' => $lines->sum('line_total')]);
        });
    }
}
