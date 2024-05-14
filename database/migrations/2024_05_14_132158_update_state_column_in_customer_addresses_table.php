<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade')->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropForeign(['state_id']); // Isso remove a restrição de chave estrangeira
            $table->dropColumn('state_id'); // Isso remove a coluna
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('state')->after('city');
        });
    }
};
