<!-- resources/views/dashboard/sections/admin_peminjaman.blade.php -->
@php
    // Ensure only admin can view (controller already checks)
@endphp
<div class="panel-card">
    <div class="panel-header">
        <div class="panel-title">
            <i data-lucide="clipboard-list"></i>
            <h3>Admin - Peminjaman Alat</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert success-alert">
            <i data-lucide="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert error-alert">
            <i data-lucide="alert-triangle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Barang</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allLoans ?? [] as $loan)
                    <tr>
                        <td>{{ $loan->user->name ?? '-' }}</td>
                        <td>{{ $loan->barang->name ?? '-' }}</td>
                        <td>{{ $loan->started_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $loan->status == 'active' ? 'info' : ($loan->status == 'returned' ? 'success' : 'danger') }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.loan.status', $loan->id) }}" method="POST" style="display:flex;gap:0.5rem;align-items:center;">
                                @csrf
                                <select name="status" class="form-control-custom" required>
                                    <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="returned" {{ $loan->status == 'returned' ? 'selected' : '' }}>Returned</option>
                                    <option value="cancelled" {{ $loan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn-primary" style="padding:0.4rem 0.8rem;font-size:0.75rem;">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;">Tidak ada peminjaman alat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel-card">
    <div class="panel-header">
        <div class="panel-title">
            <i data-lucide="calendar"></i>
            <h3>Admin - Booking Ruangan</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allRooms ?? [] as $room)
                    <tr>
                        <td>{{ $room->user->name ?? '-' }}</td>
                        <td>{{ $room->nama_ruangan }}</td>
                        <td>{{ \Carbon\Carbon::parse($room->tanggal)->format('d M Y') }}</td>
                        <td>{{ $room->jam_mulai }}</td>
                        <td>{{ $room->jam_selesai }}</td>
                        <td>{{ $room->keperluan }}</td>
                        <td>
                            <span class="badge badge-{{ $room->status == 'approved' ? 'success' : ($room->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.room.status', $room->id) }}" method="POST" style="display:flex;gap:0.5rem;align-items:center;">
                                @csrf
                                <select name="status" class="form-control-custom" required>
                                    <option value="pending" {{ $room->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $room->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $room->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ $room->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn-primary" style="padding:0.4rem 0.8rem;font-size:0.75rem;">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;">Tidak ada booking ruangan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
