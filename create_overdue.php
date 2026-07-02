<?php
$loader = require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = new Illuminate\Foundation\Application(getcwd());
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'test@example.com')->first();
$barang = \App\Models\Barang::skip(1)->first();
$tag = \App\Models\TagRfid::where('barang_id', $barang->id)->first();

if (!$tag) {
    $tag = \App\Models\TagRfid::create([
        'uid' => 'DEMO-OVD-' . uniqid(),
        'barang_id' => $barang->id
    ]);
}

\App\Models\Peminjaman::create([
    'user_id' => $user->id,
    'barang_id' => $barang->id,
    'tag_rfid_id' => $tag->id,
    'started_at' => \Carbon\Carbon::now()->subDays(7),
    'due_date' => \Carbon\Carbon::now()->subDays(3),
    'status' => 'active'
]);

echo 'Overdue peminjaman dibuat!' . PHP_EOL;

