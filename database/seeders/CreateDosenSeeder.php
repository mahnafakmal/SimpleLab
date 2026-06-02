<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update dosen account to ensure role is 'dosen'
        $email = 'Budikompani@gmail.com';
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Budi',
                'password' => Hash::make('Budi23456'),
                'role' => 'dosen',
            ]
        );
    }
}
