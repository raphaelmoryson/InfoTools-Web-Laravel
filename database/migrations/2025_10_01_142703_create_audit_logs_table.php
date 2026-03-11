<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/xxxx_xx_xx_create_audit_logs_table.php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('table_name', 128);
            $t->unsignedBigInteger('row_id')->nullable();
            $t->enum('action', ['INSERT', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT'])->index();
            $t->json('changed')->nullable();   // diff avant/après ou payload
            $t->string('ip', 45)->nullable();
            $t->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
