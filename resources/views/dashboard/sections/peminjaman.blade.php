<div id="peminjaman" class="tab-content">
    <form action="/peminjaman/borrow" method="POST" class="card form-card">
        @csrf
        <h3>Proses Peminjaman Aset</h3>
        <div class="input-group">
            <input type="text" name="card_uid" class="input-custom" placeholder="UID Kartu RFID User" required>
            <input type="text" name="tag_uid" class="input-custom" placeholder="UID Tag RFID Barang" required>
        </div>
        <button class="btn-primary" type="submit">Pinjam Aset</button>
    </form>

    <div class="list-card">
        <h3>Peminjaman Terbaru</h3>
        @if($recentLoans->isEmpty())
            <div class="empty-state">
                <i data-lucide="clipboard-list" size="48"></i>
                <p>Belum ada peminjaman</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Barang</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                            <tr>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->barang->name ?? '-' }}</td>
                                <td>{{ $loan->started_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ ucfirst($loan->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
