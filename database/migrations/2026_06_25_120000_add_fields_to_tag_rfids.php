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
        Schema::table('tag_rfids', function (Blueprint $table) {
            if (! Schema::hasColumn('tag_rfids', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('tag_rfids', 'tag_type')) {
                $table->string('tag_type')->default('equipment_tag');
            }

            if (! Schema::hasColumn('tag_rfids', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable();
            }

            if (! Schema::hasColumn('tag_rfids', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (! Schema::hasColumn('tag_rfids', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (! Schema::hasColumn('tag_rfids', 'rfid_card_id')) {
                $table->foreignId('rfid_card_id')->nullable()->constrained('rfid_cards')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tag_rfids', function (Blueprint $table) {
            if (Schema::hasColumn('tag_rfids', 'rfid_card_id')) {
                $table->dropConstrainedForeignId('rfid_card_id');
            }
            if (Schema::hasColumn('tag_rfids', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('tag_rfids', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('tag_rfids', 'card_holder_name')) {
                $table->dropColumn('card_holder_name');
            }
            if (Schema::hasColumn('tag_rfids', 'tag_type')) {
                $table->dropColumn('tag_type');
            }
            if (Schema::hasColumn('tag_rfids', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
