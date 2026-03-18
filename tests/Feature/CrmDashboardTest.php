<?php

use App\Models\User;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Très important pour repartir d'une base propre à chaque test
uses(RefreshDatabase::class);

it('refuse l’accès aux invités', function () {
    $response = $this->getJson('/api/clients-api');
    $response->assertStatus(401);
});

it('refuse l’accès aux utilisateurs non commerciaux', function () {
    $user = User::factory()->create([
        'is_commercial' => false, // Doit être bloqué par le middleware
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/clients-api');

    $response->assertStatus(403);
});

it('autorise un commercial à voir la liste des clients', function () {
    $commercial = User::factory()->create([
        'is_commercial' => true,
    ]);

    // On crée les clients APRÈS le refresh database
    Customer::factory()->count(3)->create();

    Sanctum::actingAs($commercial);

    $response = $this->getJson('/api/clients-api');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('autorise un commercial à voir un client précis', function () {
    $commercial = User::factory()->create([
        'is_commercial' => true,
    ]);

    $customer = Customer::factory()->create();

    Sanctum::actingAs($commercial);

    // On s'assure de pointer sur l'ID qu'on vient de créer
    $response = $this->getJson("/api/clients-api/{$customer->id}");
    
    $response->assertStatus(200)
        ->assertJson([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
        ]);
});