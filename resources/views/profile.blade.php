@extends('layouts.app-enhanced')

@section('title', 'Profil Mahasiswa - SIMPLELAB')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; background: linear-gradient(135deg, #003366, #004d99); color: white; font-size: 2rem; font-weight: 700;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Profil Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <div class="form-control bg-light">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <div class="form-control bg-light">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">NIM</label>
                            <div class="form-control bg-light">{{ $user->nim ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Program Studi</label>
                            <div class="form-control bg-light">{{ $user->prodi ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Semester</label>
                            <div class="form-control bg-light">{{ $user->semester ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Role</label>
                            <div class="form-control bg-light">{{ ucfirst($user->role) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Peminjaman Aktif</h5>
                </div>
                <div class="card-body">
                    @if($activeLoans->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($activeLoans as $loan)
                                <li class="list-group-item px-0">
                                    <strong>{{ $loan->barang->name ?? 'Alat' }}</strong>
                                    <div class="text-muted small">Status: {{ ucfirst($loan->status) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Tidak ada peminjaman aktif saat ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Riwayat Peminjaman</h5>
                </div>
                <div class="card-body">
                    @if($peminjamanSaya->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($peminjamanSaya as $loan)
                                <li class="list-group-item px-0">
                                    <strong>{{ $loan->barang->name ?? 'Alat' }}</strong>
                                    <div class="text-muted small">{{ $loan->created_at->format('d M Y H:i') }} · {{ ucfirst($loan->status) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Belum ada riwayat peminjaman.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
