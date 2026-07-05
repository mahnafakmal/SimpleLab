@extends('layouts.app-enhanced')

@section('title', 'Formulir Pengajuan Peminjaman Lab - SimpleLab')

@section('css')
<style>
    .form-container {
        max-width: 600px;
        margin: 2rem auto;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card form-container shadow-sm">
        
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0 h6">
                <i class="bi bi-pencil-square"></i> Formulir Pengajuan Peminjaman Lab
            </h5>
        </div>

        <form action="{{ route('peminjaman.store') }}" method="POST" class="card-body p-4">
            @csrf

            <div class="mb-3">
                <label for="nama_lab" class="form-label fw-bold small text-secondary">Pilih Laboratorium</label>
                <select name="nama_lab" id="nama_lab" class="form-select" required>
                    <option value="">-- Pilih Lab --</option>
                    <option value="Laboratorium Komputer">Laboratorium Komputer</option>
                    <option value="Laboratorium IoT & Elektronika">Laboratorium IoT & Elektronika</option>
                    <option value="Laboratorium Jaringan (GNS3)">Laboratorium Jaringan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="keperluan" class="form-label fw-bold small text-secondary">Keperluan / Nama Kegiatan</label>
                <input type="text" name="keperluan" id="keperluan" placeholder="Contoh: Praktikum Mandiri IoT / Penelitian Mandiri" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_pinjam" class="form-label fw-bold small text-secondary">Tanggal Peminjaman</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="jam_mulai" class="form-label fw-bold small text-secondary">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="jam_selesai" class="form-label fw-bold small text-secondary">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('peminjaman.index') }}" class="btn btn-light border px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endsection