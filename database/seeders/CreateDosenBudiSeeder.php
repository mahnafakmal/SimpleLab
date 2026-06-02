<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateDosenBudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'DosenBudi@gmail.com';
        $passwordPlain = 'DosenBudi123';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Dosen Budi',
                'password' => Hash::make($passwordPlain),
                'role' => 'dosen',
            ]
        );

        // Optionally log to storage for admin reference
        try {
            $path = storage_path('app/dosen-credentials.txt');
            $content = "Email: $email\nPassword: $passwordPlain\n";
            file_put_contents($path, $content, FILE_APPEND);
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
