<?php

use App\Models\User;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Customer::factory()->count(3)->create();
});

it('refuse l’accès aux invités', function () {
    $response = $this->getJson('/api/clients-api');

    $response->assertStatus(401);
});

it('refuse l’accès aux utilisateurs non commerciaux', function () {
    $user = User::factory()->create([
        'is_commercial' => false,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/clients-api');

    $response->assertStatus(403);
});

it('autorise un commercial à voir la liste des clients', function () {
    $commercial = User::factory()->create([
        'is_commercial' => true,
    ]);

    Sanctum::actingAs($commercial);

    $response = $this->getJson('/api/clients-api');

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                // adapte si ton modèle Customer a d'autres champs
            ],
        ]);
});

it('autorise un commercial à voir un client précis', function () {
    $commercial = User::factory()->create([
        'is_commercial' => true,
    ]);

    Sanctum::actingAs($commercial);

    $customer = Customer::first();

    $response = $this->getJson("/api/clients-api/{$customer->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
        ]);
});
