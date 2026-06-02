<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\JadwalLab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@simplelab.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        // Seed some lab equipment (Alat)
        Barang::create([
            'name' => 'Arduino Uno R3',
            'kategori' => 'Mikrokontroler',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        Barang::create([
            'name' => 'Raspberry Pi 4 Model B (4GB)',
            'kategori' => 'Single Board Computer',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        Barang::create([
            'name' => 'ESP32 NodeMCU Wi-Fi',
            'kategori' => 'IoT Module',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        Barang::create([
            'name' => 'Oscilloscope Digital 100MHz',
            'kategori' => 'Alat Ukur',
            'kondisi' => 'Baik',
            'status' => 'borrowed',
        ]);

        Barang::create([
            'name' => 'Sensor Ultrasonik HC-SR04',
            'kategori' => 'Sensor',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        Barang::create([
            'name' => 'Soldering Station Adjustable',
            'kategori' => 'Peralatan Kerja',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        // Seed lab schedules (Jadwal Lab)
        JadwalLab::create([
            'hari' => 'Senin',
            'mata_kuliah' => 'Praktikum IoT Dasar',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30',
            'dosen' => 'Dr. Ir. Budi Santoso, M.T.',
            'kelas' => 'IK-3A',
        ]);

        JadwalLab::create([
            'hari' => 'Selasa',
            'mata_kuliah' => 'Sistem Tertanam (Embedded)',
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:30',
            'dosen' => 'Ahmad Fauzi, M.Kom.',
            'kelas' => 'IK-3B',
        ]);

        JadwalLab::create([
            'hari' => 'Rabu',
            'mata_kuliah' => 'Proyek Penelitian Mandiri IoT',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:30',
            'dosen' => 'Rina Wijayanti, Ph.D.',
            'kelas' => 'Penelitian',
        ]);

        JadwalLab::create([
            'hari' => 'Kamis',
            'mata_kuliah' => 'Jaringan Sensor Nirkabel',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30',
            'dosen' => 'Dr. Ir. Budi Santoso, M.T.',
            'kelas' => 'IK-4A',
        ]);

        JadwalLab::create([
            'hari' => 'Jumat',
            'mata_kuliah' => 'Workshop Robotika & IoT',
            'jam_mulai' => '14:00',
            'jam_selesai' => '16:00',
            'dosen' => 'Haryanto, M.T.',
            'kelas' => 'Lab Member',
        ]);
    }
}
