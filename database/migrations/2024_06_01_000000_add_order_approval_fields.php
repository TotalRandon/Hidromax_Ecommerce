<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('approval_status')->default('awaiting')->after('status');
            $table->foreignId('approval_decided_by')->nullable()->after('approval_status')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('approval_decided_at')->nullable()->after('approval_decided_by');
        });

        // Completed/cancelled historical orders are not sent back into the approval queue.
        DB::table('orders')
            ->whereIn('status', ['shipped', 'delivered', 'cancelled'])
            ->update(['approval_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['approval_decided_by']);
            $table->dropColumn(['approval_status', 'approval_decided_by', 'approval_decided_at']);
        });
    }
};
