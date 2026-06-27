@extends('layouts.app-enhanced')

@section('title', 'Semua Alat - SimpleLab')

@section('css')
<style>
    .items-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .items-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead {
        background-color: #003366;
        color: white;
    }

    .items-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .items-table tbody tr:hover {
        background-color: rgba(0, 51, 102, 0.02);
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-available {
        background: #d4edda;
        color: #155724;
    }

    .status-borrowed {
        background: #fff3cd;
        color: #856404;
    }

    .status-damaged {
        background: #f8d7da;
        color: #721c24;
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

    .no-items {
        text-align: center;
        padding: 3rem;
        color: #999;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="bi bi-boxes"></i> Semua Alat Laboratorium</h2>
    <p>Total aset: <strong>{{ $totalAssets ?? 0 }}</strong> — Daftar lengkap seluruh peralatan laboratorium.</p>
</div>

@if($items->isEmpty())
<div class="no-items">
    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
    <p>Belum ada data alat di dalam sistem.</p>
</div>
@else
<div class="items-table">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $it)
                <tr>
                    <td>
                        @if($it->image)
                            <img src="/{{ $it->image }}" alt="{{ $it->name }}" style="width:56px;height:40px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:56px;height:40px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-image" style="color: #ccc;"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $it->name }}</strong></td>
                    <td>{{ $it->kategori ?? '-' }}</td>
                    <td>{{ $it->kondisi ?? 'Baik' }}</td>
                    <td>
                        @php
                            $statusClass = match($it->status ?? 'available') {
                                'available' => 'status-available',
                                'borrowed' => 'status-borrowed',
                                'damaged' => 'status-damaged',
                                default => 'status-available'
                            };
                            $statusText = match($it->status ?? 'available') {
                                'available' => '✓ Tersedia',
                                'borrowed' => '↻ Dipinjam',
                                'damaged' => '⚠ Rusak',
                                default => 'Tersedia'
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
