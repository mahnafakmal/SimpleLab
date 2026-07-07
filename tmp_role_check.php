<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

foreach (User::select('id', 'name', 'email', 'role')->get() as $user) {
    echo sprintf("ID: %d | %s | %s | [%s]\n", $user->id, $user->name, $user->email, $user->role);
}
