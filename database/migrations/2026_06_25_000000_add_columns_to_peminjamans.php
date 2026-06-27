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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->after('ended_at');
            $table->timestamp('due_date')->nullable()->after('returned_at');
        });

        // Add new columns to tag_rfids if not exists
        if (!Schema::hasColumn('tag_rfids', 'rfid_card_id')) {
            Schema::table('tag_rfids', function (Blueprint $table) {
                $table->foreignId('rfid_card_id')->nullable()->constrained('rfid_cards')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['returned_at', 'due_date']);
        });
    }
};
