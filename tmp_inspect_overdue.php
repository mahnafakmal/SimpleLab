<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

$loans = Peminjaman::all();
echo "TOTAL LOANS: " . $loans->count() . "\n";
foreach ($loans as $loan) {
    echo sprintf(
        "id=%s user_id=%s status=%s due=%s started=%s returned=%s overdue_notified_at=%s\n",
        $loan->id,
        $loan->user_id,
        $loan->status,
        $loan->due_date?->format('Y-m-d H:i:s') ?: 'NULL',
        $loan->started_at?->format('Y-m-d H:i:s') ?: 'NULL',
        $loan->returned_at?->format('Y-m-d H:i:s') ?: 'NULL',
        $loan->overdue_notified_at?->format('Y-m-d H:i:s') ?: 'NULL'
    );
}

$overdue = Peminjaman::where('status', 'active')->whereNotNull('due_date')->where('due_date', '<', now())->get();
echo "OVERDUE ACTIVE LOANS: " . $overdue->count() . "\n";
foreach ($overdue as $loan) {
    echo sprintf(
        "overdue id=%s user_id=%s due=%s notified=%s\n",
        $loan->id,
        $loan->user_id,
        $loan->due_date?->format('Y-m-d H:i:s') ?: 'NULL',
        $loan->overdue_notified_at?->format('Y-m-d H:i:s') ?: 'NULL'
    );
}
