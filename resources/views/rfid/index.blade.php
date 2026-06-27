@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-rfid"></i> Manajemen RFID
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('rfid.register-user') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Daftarkan Pengguna
            </a>
            <a href="{{ route('rfid.register-equipment') }}" class="btn btn-info">
                <i class="bi bi-plus-circle"></i> Daftarkan Barang
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-5">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total RFID Terdaftar</h6>
                    <h3 class="mb-0">{{ $rfidCards->total() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-5">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pengguna Terdaftar</h6>
                    <h3 class="mb-0">{{ $registeredUsers ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-5">
                <div class="card-body">
                    <h6 class="card-title text-muted">Barang Terdaftar</h6>
                    <h3 class="mb-0">{{ $registeredEquipment ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-5">
                <div class="card-body">
                    <h6 class="card-title text-muted">RFID Aktif</h6>
                    <h3 class="mb-0">{{ $rfidCards->where('is_active', true)->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar RFID -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Kartu & Tag RFID</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>UID RFID</th>
                            <th>Tipe</th>
                            <th>Pemegang/Barang</th>
                            <th>Detail Terkait</th>
                            <th>Status</th>
                            <th>Terdaftar Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rfidCards as $rfid)
                            <tr>
                                <td>
                                    <code>{{ substr($rfid->uid, 0, 8) }}...</code>
                                </td>
                                <td>
                                    @if($rfid->tag_type === 'user_card')
                                        <span class="badge bg-success">
                                            <i class="bi bi-person-badge"></i> Pengguna
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-box"></i> Barang
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($rfid->tag_type === 'user_card')
                                        <strong>{{ $rfid->card_holder_name ?? $rfid->user->name ?? '-' }}</strong>
                                    @else
                                        <strong>{{ $rfid->barang->nama_barang ?? '-' }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($rfid->tag_type === 'user_card' && $rfid->user)
                                        <small>{{ $rfid->user->email }}</small>
                                    @elseif($rfid->tag_type === 'equipment_tag' && $rfid->barang)
                                        <small>Kode: {{ $rfid->barang->kode_barang }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($rfid->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $rfid->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    @if($rfid->is_active)
                                        <form method="POST" action="{{ route('rfid.deactivate', $rfid->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" title="Nonaktifkan">
                                                <i class="bi bi-pause"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('rfid.activate', $rfid->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Aktifkan">
                                                <i class="bi bi-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('rfid.destroy', $rfid->id) }}" style="display:inline;" onsubmit="return confirm('Hapus RFID ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Belum ada RFID terdaftar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $rfidCards->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
