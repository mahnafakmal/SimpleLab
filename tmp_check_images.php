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
    $img = $b->image;
    $public = public_path($img);
    $candidate = public_path('images/barangs/' . basename($img));
    $storage = storage_path('app/public/' . basename($img));
    echo "ID: {$b->id}\n";
    echo "  DB value: {$img}\n";
    echo "  public_path: {$public} -> " . (file_exists($public) ? 'exists' : 'missing') . "" . (file_exists($public) ? (" (" . filesize($public) . " bytes)") : "") . "\n";
    echo "  candidate: {$candidate} -> " . (file_exists($candidate) ? 'exists' : 'missing') . "" . (file_exists($candidate) ? (" (" . filesize($candidate) . " bytes)") : "") . "\n";
    echo "  storage: {$storage} -> " . (file_exists($storage) ? 'exists' : 'missing') . "" . (file_exists($storage) ? (" (" . filesize($storage) . " bytes)") : "") . "\n";
}
