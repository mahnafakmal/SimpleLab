<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kategori')->nullable();
            $table->string('kondisi')->default('Baik');
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('tag_rfids', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            // allow null barang_id for user cards; remove cascade so deletion is null-on-delete
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->nullOnDelete();
            // optional relation to a user (for user cards)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tag_type')->default('equipment_tag');
            $table->string('card_holder_name')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('rfid_card_id')->nullable()->constrained('rfid_cards')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('rfid_cards', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->foreignId('tag_rfid_id')->constrained('tag_rfids')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('log_akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rfid_card_id')->nullable()->constrained('rfid_cards')->nullOnDelete();
            $table->string('action');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('riwayat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->text('detail');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_logs');
        Schema::dropIfExists('log_akses');
        Schema::dropIfExists('peminjamans');
        Schema::dropIfExists('rfid_cards');
        Schema::dropIfExists('tag_rfids');
        Schema::dropIfExists('barangs');
    }
};
