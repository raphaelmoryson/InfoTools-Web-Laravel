<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de la liste des factures
     */
    /** @test */
    public function test_can_create_invoice(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'total' => 500.00,
            'status' => 'paid',
            'reference' => 'INV-' . time(),      // Donnée requise par ta DB
            'invoiced_at' => now()->format('Y-m-d'), // La date manquante
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/invoices-api', $data);

        // Si ça échoue encore, décommente la ligne suivante pour voir l'erreur exacte :
        // $response->dump();

        $response->assertStatus(201);
        $this->assertDatabaseHas('invoices', [
            'customer_id' => $customer->id,
            'total' => 500
        ]);
    }
}