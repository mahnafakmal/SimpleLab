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
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="text" name="rfid_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID" autocomplete="off" required>
                <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=rfid_uid]'), 'Klik field UID lalu pindai tag RFID.')">Scan</button>
            </div>
        </div>
        <button class="btn-primary" type="submit">Daftarkan Barang</button>
        <p class="hint-text">Klik field UID dan pindai tag RFID untuk melengkapi secara cepat.</p>
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

    <div class="list-card" style="margin-top:1rem;">
        <h3>Barang Tersedia</h3>
        @if(isset($availableItems) && $availableItems->isEmpty())
            <div class="empty-state">
                <i data-lucide="box" size="48"></i>
                <p>Tidak ada barang tersedia saat ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->kategori ?? '-' }}</td>
                                <td>{{ $item->kondisi ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="list-card" style="margin-top:1rem;">
        <h3>Ringkasan Jumlah Per Barang</h3>
        @if(isset($itemsSummary) && $itemsSummary->isEmpty())
            <div class="empty-state">
                <i data-lucide="box" size="48"></i>
                <p>Tidak ada data barang.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Total</th>
                            <th>Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemsSummary as $summary)
                            @php
                                $avail = $availableSummary->firstWhere('name', $summary->name);
                            @endphp
                            <tr>
                                <td>{{ $summary->name }}</td>
                                <td>{{ $summary->total_count }}</td>
                                <td>{{ $avail->available_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
