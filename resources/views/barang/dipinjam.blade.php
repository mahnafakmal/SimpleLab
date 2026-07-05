@extends('layouts.app-enhanced')

@section('title', 'Alat Dipinjam - SIMPLELAB')

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
        background: #fff3cd;
        color: #856404;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
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
    <h2><i class="bi bi-clipboard-check"></i> Alat yang Sedang Dipinjam</h2>
    <p>Total aset: <strong>{{ $totalAssets ?? 0 }}</strong> — Daftar alat yang saat ini berstatus dipinjam oleh pengguna.</p>
</div>

@if($borrowedItems->isEmpty())
<div class="no-items">
    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
    <p>Tidak ada alat yang sedang dipinjam saat ini.</p>
</div>
@else
<div class="items-table">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th style="width: 150px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowedItems as $it)
                <tr>
                    <td><strong>{{ $it->name }}</strong></td>
                    <td>{{ $it->kategori ?? '-' }}</td>
                    <td>{{ $it->kondisi ?? 'Baik' }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge">↻ Dipinjam</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
