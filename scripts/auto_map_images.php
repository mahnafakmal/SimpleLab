<?php
// Run with: php scripts/auto_map_images.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Barang;

$paths = [
    public_path('images/barangs'),
    public_path('images'),
];
$files = [];
foreach ($paths as $p) {
    if (is_dir($p)) {
        foreach (scandir($p) as $f) {
            if ($f === '.' || $f === '..') continue;
            $files[$f] = $p . DIRECTORY_SEPARATOR . $f;
        }
    }
}

function norm($s){
    $s = mb_strtolower($s);
    $s = preg_replace('/[^a-z0-9]+/u','', $s);
    return $s;
}

$barangs = Barang::all();
$updated = 0;
foreach ($barangs as $b) {
    $name = $b->name;
    $found = null;
    $nn = norm($name);
    foreach ($files as $fn => $full) {
        $fnn = norm(pathinfo($fn, PATHINFO_FILENAME));
        if ($fnn === $nn || strpos($fnn, $nn) !== false || strpos($nn, $fnn) !== false) {
            $found = $fn; break;
        }
    }
    if ($found) {
        if ($b->image !== $found) {
            echo "Assigning $found to Barang #{$b->id} ({$b->name})\n";
            $b->image = $found; $b->save(); $updated++;
        } else {
            echo "OK Barang #{$b->id} already has $found\n";
        }
    } else {
        echo "No match for Barang #{$b->id} ({$b->name})\n";
    }
}

echo "Done. Updated: $updated\n";
