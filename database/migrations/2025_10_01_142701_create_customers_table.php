<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $t) {
            $t->id();

            // Informations de base
            $t->string('first_name')->nullable();
            $t->string('last_name')->nullable();
            $t->string('name')->nullable(); // nom complet, utile pour compatibilité rapide
            $t->string('company_name')->nullable();

            // Coordonnées
            $t->string('email')->unique()->nullable();
            $t->string('phone')->nullable();
            $t->string('address')->nullable();
            $t->string('city')->nullable();
            $t->string('postal_code', 10)->nullable();
            $t->string('country')->default('France');

            // CRM
            $t->enum('status', ['prospect', 'actif', 'inactif', 'perdu'])->default('prospect');
            $t->date('last_contact_at')->nullable();
            $t->date('next_meeting_at')->nullable();
            $t->decimal('total_spent', 12, 2)->default(0);

            // Assignation au commercial (user du CRM)
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Audit
            $t->timestamps();
            $t->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
