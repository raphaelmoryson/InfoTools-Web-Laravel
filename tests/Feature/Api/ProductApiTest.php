<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_list_products(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/products-api');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function test_can_create_product(): void
    {
        $user = User::factory()->create();

        $data = [
            'name'  => 'Clavier Gaming',
            'price' => 89.99,
            // Optionnels selon ton contrôleur mais présents en DB
            'description' => 'Un super clavier RGB',
            'stock' => 10
        ];

        $response = $this->actingAs($user)->postJson('/api/products-api', $data);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'Clavier Gaming');

        $this->assertDatabaseHas('products', ['name' => 'Clavier Gaming']);
    }

    /** @test */
    public function test_can_update_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Ancien Nom']);

        $response = $this->actingAs($user)->putJson("/api/products-api/{$product->id}", [
            'name' => 'Nouveau Nom',
            'price' => 45.00
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nouveau Nom'
        ]);
    }

    /** @test */
    public function test_can_delete_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/products-api/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}