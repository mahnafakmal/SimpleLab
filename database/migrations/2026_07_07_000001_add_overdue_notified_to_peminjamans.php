<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (! Schema::hasColumn('peminjamans', 'overdue_notified_at')) {
                $table->timestamp('overdue_notified_at')->nullable()->after('due_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'overdue_notified_at')) {
                $table->dropColumn('overdue_notified_at');
            }
        });
    }
};
