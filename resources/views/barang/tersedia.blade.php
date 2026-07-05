@extends('layouts.app-enhanced')

@section('title', 'Alat Tersedia - SIMPLELAB')

@section('css')
<style>
    .equipment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .equipment-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .equipment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .equipment-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #003366 0%, #004d99 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        overflow: hidden;
    }

    .equipment-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .equipment-info {
        padding: 1.5rem;
    }

    .equipment-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #003366;
        margin-bottom: 0.5rem;
    }

    .equipment-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.75rem;
    }

    .equipment-meta-item {
        display: flex;
        flex-direction: column;
    }

    .equipment-meta-label {
        font-weight: 600;
        color: #333;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .status-badge {
        display: inline-block;
        background: #d4edda;
        color: #155724;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        width: 100%;
        margin-top: 0.75rem;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #003366;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .no-equipment {
        text-align: center;
        padding: 3rem;
        color: #999;
        background: white;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h2 {
        color: #003366;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #666;
        margin: 0;
    }

    @media (max-width: 768px) {
        .equipment-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="bi bi-box"></i> Alat Tersedia</h2>
    <p>Menampilkan semua barang yang saat ini berstatus tersedia di laboratorium.</p>
</div>

<!-- Statistics -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-number">{{ $totalAssets ?? 0 }}</div>
        <div class="stat-label">Total Alat Lab</div>
    </div>
    <div class="stat-box">
        <div class="stat-number" style="color: #28a745;">{{ $availableItems->count() ?? 0 }}</div>
        <div class="stat-label">Alat Tersedia</div>
    </div>
</div>

<!-- Equipment List -->
@if($availableItems->isEmpty())
<div class="no-equipment">
    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
    <p>Tidak ada barang yang tersedia saat ini.</p>
    <p style="font-size: 0.9rem; margin: 0;">Silakan cek lagi nanti atau hubungi admin laboratorium.</p>
</div>
@else
<div class="equipment-grid">
    @foreach($availableItems as $item)
    <div class="equipment-card">
        <div class="equipment-image">
            @php
                $imagePath = 'images/barangs/'.$item->image;
                $imageExists = $item->image && file_exists(public_path($imagePath));
            @endphp
            @if($imageExists)
                <img src="{{ asset($imagePath) }}" alt="{{ $item->name }}">
            @else
                <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
            @endif
        </div>
        <div class="equipment-info">
            <div class="equipment-name">{{ $item->name }}</div>
            <div class="equipment-meta">
                <div class="equipment-meta-item">
                    <span class="equipment-meta-label">Kategori</span>
                    <span>{{ $item->kategori ?? '-' }}</span>
                </div>
                <div class="equipment-meta-item">
                    <span class="equipment-meta-label">Kondisi</span>
                    <span>{{ $item->kondisi ?? 'Baik' }}</span>
                </div>
            </div>
            <div class="status-badge">
                <i class="bi bi-check-circle"></i> Tersedia
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
