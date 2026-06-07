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
<!-- Booking ruangan panel removed -->
