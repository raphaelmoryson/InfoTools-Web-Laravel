<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste la récupération des achats d'un client via l'API.
     */
    public function test_it_returns_customer_purchases_successfully(): void
    {
        // 1. Création des données
        // On s'assure que l'utilisateur a le rôle requis par ton middleware
        $user = User::factory()->create([
            'is_commercial' => true, // <-- C'est LA bonne colonne !
        ]);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['name' => 'Produit Test']);

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'reference' => 'REF-123',
            'invoiced_at' => now(),
            'total' => 100
        ]);

        InvoiceLine::create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'qty' => 1,
            'unit_price' => 100,
            'line_total' => 100
        ]);

        // 2. APPEL : On simule l'authentification du commercial
        $response = $this->actingAs($user)
            ->getJson("/api/clients-api/{$customer->id}");

        // 3. Vérifications
        // On vérifie le code 200 et que le JSON contient les infos du client
        $response->assertStatus(200)
            ->assertJson([
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ]);
    }

    /**
     * Teste la sécurité : un invité ne peut pas accéder aux données.
     */
    public function test_guest_cannot_access_purchases(): void
    {
        $customer = Customer::factory()->create();

        // Appel sans 'actingAs'
        $response = $this->getJson("/api/clients-api/{$customer->id}");

        // On attend une 401 si sanctum est en place, ou 302 si c'est une redirection web
        // Pour une API, le 401 est la norme.
        $response->assertStatus(401);
    }
}