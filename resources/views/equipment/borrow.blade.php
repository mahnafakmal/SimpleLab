@extends('layouts.app-enhanced')

@section('title', 'Peminjaman Barang - SimpleLab')

@section('css')
<style>
    .borrow-container {
        max-width: 1200px;
    }

    .equipment-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .equipment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .equipment-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #003366 0%, #004d99 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
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
        font-size: 1.2rem;
        font-weight: 700;
        color: #003366;
        margin-bottom: 0.5rem;
    }

    .equipment-category {
        display: inline-block;
        background: #f0f0f0;
        color: #666;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .equipment-details {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .equipment-details strong {
        color: #333;
    }

    .equipment-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e0e0e0;
    }

    .btn-borrow {
        flex: 1;
        background: #003366;
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.3s ease;
    }

    .btn-borrow:hover {
        background: #004d99;
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

    .active-loans {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .loan-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 0.75rem;
        border-left: 4px solid #003366;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .loan-info {
        flex: 1;
    }

    .loan-equipment {
        font-weight: 600;
        color: #003366;
        margin-bottom: 0.25rem;
    }

    .loan-date {
        font-size: 0.85rem;
        color: #666;
    }

    .no-equipment {
        text-align: center;
        padding: 2rem;
        color: #999;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-header h3 {
        margin: 0;
        color: #003366;
        font-size: 1.3rem;
    }

    @media (max-width: 768px) {
        .equipment-card {
            margin-bottom: 1rem;
        }

        .borrow-container {
            padding: 0 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="borrow-container">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <h2 style="color: #003366; margin-bottom: 0.5rem;">Peminjaman Barang Inventaris</h2>
        <p style="color: #666; margin: 0;">Pilih barang yang ingin Anda pinjam dari laboratorium</p>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-number">{{ $barangs->count() }}</div>
            <div class="stat-label">Alat Tersedia</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $activeLoans->count() }}</div>
            <div class="stat-label">Peminjaman Aktif Anda</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $barangs->count() + $activeLoans->count() }}</div>
            <div class="stat-label">Total Alat Lab</div>
        </div>
    </div>

    <!-- Active Loans Alert -->
    @if($activeLoans->count() > 0)
    <div class="active-loans">
        <div class="section-header">
            <i class="bi bi-info-circle" style="color: #003366; font-size: 1.3rem;"></i>
            <h3>Peminjaman Aktif Anda</h3>
        </div>
        @foreach($activeLoans as $loan)
        <div class="loan-item">
            <div class="loan-info">
                <div class="loan-equipment">{{ $loan->barang->name }}</div>
                <div class="loan-date">Dipinjam: {{ $loan->started_at ? $loan->started_at->format('d/m/Y H:i') : '-' }}</div>
                @if($loan->due_date)
                <div class="loan-date">Tenggat: {{ $loan->due_date->format('d/m/Y H:i') }}</div>
                @endif
            </div>
            <a href="{{ route('equipment.return') }}" class="btn btn-sm btn-warning" style="white-space: nowrap;">
                <i class="bi bi-arrow-counterclockwise"></i> Kembalikan
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Available Equipment Grid -->
    <div class="section-header">
        <i class="bi bi-box-seam" style="color: #003366; font-size: 1.3rem;"></i>
        <h3>Alat Tersedia untuk Dipinjam</h3>
    </div>

    @if($barangs->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($barangs as $barang)
        <div class="equipment-card">
            <div class="equipment-image">
                @if($barang->image)
                    <img src="/{{ $barang->image }}" alt="{{ $barang->name }}">
                @else
                    <i class="bi bi-box"></i>
                @endif
            </div>
            <div class="equipment-info">
                <div class="equipment-name">{{ $barang->name }}</div>
                @if($barang->kategori)
                <div class="equipment-category">{{ $barang->kategori }}</div>
                @endif
                <div class="equipment-details">
                    <strong>Kondisi:</strong> {{ $barang->kondisi ?? 'Baik' }}<br>
                    <strong>Status:</strong> 
                    <span style="color: #28a745; font-weight: 600;">✓ Tersedia</span>
                </div>
                <form action="{{ route('web.peminjaman.alat') }}" method="POST" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                    <div class="equipment-actions">
                        <button type="submit" class="btn-borrow">
                            <i class="bi bi-plus-circle"></i> Pinjam
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-equipment">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
        <p>Saat ini tidak ada alat yang tersedia untuk dipinjam.</p>
        <p style="font-size: 0.9rem; margin: 0;">Silakan coba lagi nanti atau hubungi admin laboratorium.</p>
    </div>
    @endif
</div>

@endsection

@section('js')
<script>
    // Optional: Add confirmation dialog before borrowing
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin meminjam alat ini?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
