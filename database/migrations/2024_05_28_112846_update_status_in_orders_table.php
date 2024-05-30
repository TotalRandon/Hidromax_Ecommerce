<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altere a coluna 'status' usando uma declaração SQL direta
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte a coluna 'status' para o estado original
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'shipped', 'delivered') DEFAULT 'pending'");
    }
};

