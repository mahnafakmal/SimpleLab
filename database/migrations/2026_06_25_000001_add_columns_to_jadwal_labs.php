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
        Schema::table('jadwal_labs', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal_labs', 'ruangan')) {
                $table->string('ruangan')->nullable()->after('kelas');
            }
            if (!Schema::hasColumn('jadwal_labs', 'kapasitas')) {
                $table->integer('kapasitas')->nullable()->after('ruangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_labs', function (Blueprint $table) {
            $table->dropColumn(['ruangan', 'kapasitas']);
        });
    }
};
