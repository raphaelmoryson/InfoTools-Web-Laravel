<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ─── Test 1 : IDOR ────────────────────────────────────────────────
    public function test_commercial_cannot_access_appointment_of_another_commercial(): void
    {
        $commercialA = User::factory()->create(['is_commercial' => true]);
        $commercialB = User::factory()->create(['is_commercial' => true]);
        $customer    = Customer::factory()->create();

        $appointment = Appointment::factory()->create([
            'user_id'     => $commercialB->id,
            'customer_id' => $customer->id,
        ]);

        $tokenA = $commercialA->createToken('test')->plainTextToken;

        $this->withToken($tokenA)
             ->getJson("/api/appointments-api/{$appointment->id}")
             ->assertStatus(403);
    }

    // ─── Test 2 : Validation / XSS ────────────────────────────────────
    public function test_store_appointment_rejects_xss_payload(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson('/api/appointments-api', [
                 'customer_id' => 999,
                 'user_id'     => 999,
                 'start_at'    => 'not-a-date',
                 'subject'     => '<script>alert(1)</script>',
             ])
             ->assertStatus(422);
    }

    // ─── Test 3 : Throttling ──────────────────────────────────────────
    public function test_login_is_throttled_after_five_attempts(): void
    {
        $payload = ['email' => 'x@x.com', 'password' => 'wrong'];

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', $payload);
        }

        $this->postJson('/api/login', $payload)
             ->assertStatus(429);
    }
}