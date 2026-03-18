<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_appointments(): void
    {
        $user = User::factory()->create();
        Appointment::factory()->count(3)->create();

        $this->actingAs($user)
            ->getJson('/api/appointments-api')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_appointment(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'commercial_id' => $user->id,
            'start_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'subject' => 'Réunion importante',
        ];

        $this->actingAs($user)
            ->postJson('/api/appointments-api', $data)
            ->assertStatus(201)
            ->assertJsonPath('subject', 'Réunion importante');
    }
}