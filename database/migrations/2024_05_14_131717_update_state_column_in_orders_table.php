<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Primeiro, remova a coluna 'state' se já não estiver sendo usada como FK
            $table->dropColumn('state');
        });

        // Agora, adicione a coluna 'state_id' como uma FK
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade')->after('city');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['state_id']); // Isso remove a restrição de chave estrangeira
            $table->dropColumn('state_id'); // Isso remove a coluna
        });

        // Reverter para o formato anterior com coluna 'state' como string
        Schema::table('orders', function (Blueprint $table) {
            $table->string('state')->after('city');
        });
    }
};