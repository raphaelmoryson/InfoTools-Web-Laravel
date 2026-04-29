<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. CRÉATION DES UTILISATEURS (Profils du projet) ---
        
        // Le Responsable (Accès total) 
        User::factory()->create([
            'name' => 'Jean Manager',
            'email' => 'manager@infotools.fr',
            'password' => Hash::make('password'),
            'is_commercial' => false, // Profil manager
        ]);

        // Les Commerciaux (Accès restreint à leurs propres données) [cite: 114]
        $commercialsData = [
            ['name' => 'Alice Commercial', 'email' => 'alice@infotools.fr'],
            ['name' => 'Bob Commercial', 'email' => 'bob@infotools.fr'],
            ['name' => 'Charlie Commercial', 'email' => 'charlie@infotools.fr'],
        ];

        foreach ($commercialsData as $data) {
            User::factory()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'), // Mot de passe identique pour les tests
                'is_commercial' => true,
            ]);
        }

        $this->command->info('Utilisateurs créés !');

        // --- 2. CRÉATION DU CATALOGUE PRODUITS ---
        Product::factory(20)->create();
        $this->command->info('Catalogue produits créé (20 articles)[cite: 68].');

        // --- 3. CRÉATION DES CLIENTS, RDV ET FACTURES ---
        // On récupère les IDs des commerciaux pour leur assigner des clients/RDV
        $commercialIds = User::where('is_commercial', true)->pluck('id')->toArray();

        Customer::factory(30)->create()->each(function ($customer) use ($commercialIds) {
            // On assigne chaque client à un commercial aléatoire pour tester les droits d'accès [cite: 114]
            $assignedCommercialId = $commercialIds[array_rand($commercialIds)];
            
            // Mise à jour du client avec son commercial (si ton modèle Customer a un user_id)
            $customer->update(['user_id' => $assignedCommercialId]);

            // Création de 1 à 4 rendez-vous [cite: 67]
            Appointment::factory(rand(1, 4))->create([
                'customer_id' => $customer->id,
                'user_id' => $assignedCommercialId, // Le RDV appartient au commercial
            ]);

            // Création de 1 à 3 factures [cite: 68]
            Invoice::factory(rand(1, 3))->create([
                'customer_id' => $customer->id,
            ]);
        });

        $this->command->info('Données générées avec répartition par commercial.');

        // --- RÉSUMÉ DES IDENTIFIANTS ---
        $this->command->info('-------------------------------------------');
        $this->command->info('IDENTIFIANTS DE TEST (MDP: password)');
        $this->command->info('Manager: manager@infotools.fr');
        $this->command->info('Commercial 1: alice@infotools.fr');
        $this->command->info('Commercial 2: bob@infotools.fr');
        $this->command->info('Commercial 3: charlie@infotools.fr');
        $this->command->info('-------------------------------------------');
    }
}