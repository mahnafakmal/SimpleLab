<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Barang;

$items = Barang::whereNotNull('image')->take(50)->get();
if ($items->isEmpty()) {
    echo "No items with image found.\n";
    exit;
}
foreach ($items as $b) {
    echo "ID: {$b->id} -> {$b->image}\n";
}
