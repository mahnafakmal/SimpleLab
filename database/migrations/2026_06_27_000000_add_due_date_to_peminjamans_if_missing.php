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
        if (! Schema::hasColumn('peminjamans', 'returned_at')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->timestamp('returned_at')->nullable()->after('ended_at');
            });
        }

        if (! Schema::hasColumn('peminjamans', 'due_date')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->timestamp('due_date')->nullable()->after('returned_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('peminjamans', 'due_date')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }

        if (Schema::hasColumn('peminjamans', 'returned_at')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn('returned_at');
            });
        }
    }
};
