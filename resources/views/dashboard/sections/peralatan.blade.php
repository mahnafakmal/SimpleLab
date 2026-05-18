<div id="peralatan" class="tab-content">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="label">TOTAL ALAT</span>
            <span class="value">{{ $totalAssets ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="label">TERSEDIA</span>
            <span class="value" style="color: #22c55e;">{{ $available ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="label">DIPINJAM</span>
            <span class="value" style="color: #3b82f6;">{{ $borrowed ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="label">TAG RFID TERDAFTAR</span>
            <span class="value" style="color: #f59e0b;">{{ $tags->count() ?? 0 }}</span>
        </div>
    </div>

    <div class="search-container">
        <div class="search-input-wrapper">
            <i data-lucide="search"></i>
            <input type="text" class="search-input" placeholder="Cari nama, kode, kategori...">
        </div>
    </div>

    <form action="/barang/register" method="POST" class="card form-card">
        @csrf
        <h3>Registrasi Barang dan Tag RFID</h3>
        <div class="input-group">
            <input type="text" name="name" class="input-custom" placeholder="Nama Barang" required>
            <input type="text" name="kategori" class="input-custom" placeholder="Kategori" required>
            <input type="text" name="rfid_uid" class="input-custom" placeholder="UID Tag RFID" required>
        </div>
        <button class="btn-primary" type="submit">Daftarkan Barang</button>
    </form>

    <div class="list-card">
        <h3>Daftar Aset RFID</h3>
        @if($tags->isEmpty())
            <div class="empty-state">
                <i data-lucide="package" size="48"></i>
                <p>Belum ada tag RFID terdaftar.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>RFID UID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td>{{ $tag->barang->name ?? 'Tidak Ditemukan' }}</td>
                                <td>{{ $tag->uid }}</td>
                                <td>{{ ucfirst($tag->barang->status ?? 'unknown') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
