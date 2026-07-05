<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_labs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (bisa mahasiswa atau dosen)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Detail lab dan keperluan
            $table->string('nama_lab'); // Contoh: Lab Komputer, Lab IoT
            $table->string('keperluan'); // Contoh: Praktikum, Penelitian Mandiri
            
            // Waktu peminjaman
            $table->date('tanggal_pinjam');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            
            // Status persetujuan dari admin/kalab
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable(); // Alasan jika ditolak
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_labs');
    }
};