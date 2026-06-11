<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Barang;

function norm($s){
    $s = mb_strtolower($s);
    $s = preg_replace('/[^a-z0-9]+/u','', $s);
    return $s;
}

$barangs = Barang::all();
$cleared = 0;
foreach ($barangs as $b) {
    if (!$b->image) continue;
    $fn = $b->image;
    $nn = norm($b->name);
    $fnn = norm(pathinfo($fn, PATHINFO_FILENAME));
    if ($nn === $fnn || strpos($fnn, $nn) !== false || strpos($nn, $fnn) !== false) {
        echo "Keep {$b->image} for {$b->name}\n";
        continue;
    }
    echo "Clearing image for Barang #{$b->id} ({$b->name}) — current: {$b->image}\n";
    $b->image = null; $b->save(); $cleared++;
}

echo "Done. Cleared: $cleared\n";
