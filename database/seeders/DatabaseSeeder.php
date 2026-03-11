<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name' => 'test',
            'email' => 'test@infotools.local',
            'password' => Hash::make('test'), // mot de passe du compte
            'created_at' => now(),
            'updated_at' => now(),
            'is_commercial' => true,
        ]);
    }
}
