<div id="peminjaman" class="tab-content">
    <form action="/peminjaman/borrow" method="POST" class="card form-card">
        @csrf
        <h3>Proses Peminjaman Aset</h3>
        <div class="input-group" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <div style="display:flex;gap:6px;align-items:center;">
                <input type="text" name="card_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Kartu RFID User" autocomplete="off" required>
                <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=card_uid]'), 'Klik field Kartu lalu pindai kartu RFID.')">Scan</button>
            </div>
            <div style="display:flex;gap:6px;align-items:center;">
                <input type="text" name="tag_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID Barang" autocomplete="off" required>
                <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=tag_uid]'), 'Klik field Tag lalu pindai tag RFID.')">Scan</button>
            </div>
        </div>
        <button class="btn-primary" type="submit">Pinjam Aset</button>
        <p class="hint-text">Klik field UID lalu pindai kartu/tag RFID untuk memproses pinjaman.</p>
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
                            @php
                                $displayStarted = '-';
                                if(!empty($loan->started_at)) {
                                    try {
                                        if($loan->started_at instanceof \Illuminate\Support\Carbon) {
                                            $displayStarted = $loan->started_at->format('d M Y H:i');
                                        } else {
                                            $displayStarted = \Illuminate\Support\Carbon::parse($loan->started_at)->format('d M Y H:i');
                                        }
                                    } catch (\Exception $e) {
                                        $displayStarted = '-';
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->barang->name ?? '-' }}</td>
                                <td>{{ $displayStarted }}</td>
                                <td>{{ ucfirst($loan->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
