@extends('layouts.app-enhanced')

@section('title', 'Jadwal & Peminjaman Lab - SimpleLab')

@section('css')
<style>
    .section-header {
        border-bottom: 3px solid var(--unimus-secondary);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .table th {
        font-weight: 600;
        background-color: var(--unimus-primary) !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    
    <div class="d-flex justify-content-between align-items-center section-header mb-4">
        <h2 class="h3 font-bold text-primary m-0">🗓️ Jadwal & Peminjaman Laboratorium</h2>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-secondary text-white font-semibold shadow-sm">
            <i class="bi bi-plus-lg"></i> Ajukan Peminjaman Lab
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0 h6">
                @if(Auth::user()->role === 'admin')
                    <i class="bi bi-list-stars"></i> Semua Pengajuan Peminjaman Laboratorium
                @else
                    <i class="bi bi-history"></i> Riwayat Pengajuan Peminjaman Anda
                @endif
            </h5>
        </div>
        
        <div class="card-body p-0">
            @if($peminjaman->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-calendar-x display-4 text-secondary mb-3 d-block"></i>
                    <p class="mb-0">Belum ada riwayat pengajuan peminjaman laboratorium.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Laboratorium</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-4 py-3">Pemohon</th>
                                @endif
                                <th class="px-4 py-3">Keperluan</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3">Catatan Admin / Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman as $item)
                                <tr>
                                    <td class="px-4 py-3 fw-semibold text-primary">
                                        <i class="bi bi-building-gear me-2"></i>{{ $item->nama_lab }}
                                    </td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-4 py-3">
                                            <div class="fw-bold">{{ $item->user?->name }}</div>
                                            <small class="text-muted">
                                                @if($item->user?->role === 'user')
                                                    Mahasiswa
                                                @elseif($item->user?->role === 'dosen')
                                                    Dosen
                                                @else
                                                    {{ ucfirst($item->user?->role) }}
                                                @endif
                                            </small>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 text-secondary">{{ $item->keperluan }}</td>
                                    <td class="px-4 py-3">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        @elseif($item->status == 'disetujui')
                                            <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(Auth::user()->role === 'admin' && $item->status == 'pending')
                                            <!-- Admin Actions for pending requests -->
                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="{{ route('peminjaman.approve', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm px-3" onclick="return confirm('Setujui peminjaman ini?')">
                                                        <i class="bi bi-check-lg"></i> Setujui
                                                    </button>
                                                </form>
                                                
                                                <button class="btn btn-danger btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#rejectForm-{{ $item->id }}">
                                                    <i class="bi bi-x-lg"></i> Tolak
                                                </button>
                                            </div>

                                            <!-- Collapsible Reject Form -->
                                            <div class="collapse mt-2" id="rejectForm-{{ $item->id }}">
                                                <form action="{{ route('peminjaman.reject', $item->id) }}" method="POST" class="bg-light p-3 rounded border">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold">Alasan Penolakan:</label>
                                                        <input type="text" name="catatan_admin" class="form-control form-control-sm" placeholder="Masukkan alasan..." required>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <button type="button" class="btn btn-secondary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#rejectForm-{{ $item->id }}">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Kirim</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @elseif($item->status == 'pending')
                                            <!-- User Action for pending requests -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan peminjaman lab ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Batalkan
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-secondary small italic">{{ $item->catatan_admin ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection