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
        <div class="stat-card" data-api="{{ route('api.admin.rfid.tags') }}" style="cursor:pointer;" title="Lihat daftar RFID terdaftar">
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

    <div class="card form-card">
        <h3>Daftar Peralatan</h3>
        <p class="hint-text">Semua pendaftaran barang sekarang dilakukan di tab <strong>Scan RFID</strong>. Silakan buka tab Scan untuk mendaftarkan aset baru dengan tag RFID.</p>
    </div>

    <div class="list-card">
        <h3>Daftar Aset RFID</h3>
        @if($tags->isEmpty())
            <div class="empty-state">
                <i data-lucide="package" size="48"></i>
                <p><a href="{{ route('rfid.register-equipment') }}" style="text-decoration:none;color:inherit;">Belum ada tag RFID terdaftar. Klik untuk mendaftarkan tag baru.</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Barang</th>
                            <th>RFID UID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td>
                                    @if(isset($tag->barang) && $tag->barang->image)
                                        <img src="/{{ $tag->barang->image }}" alt="{{ $tag->barang->name }}" style="width:56px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:56px;height:40px;background:#f3f4f6;border-radius:6px;display:inline-block;"></div>
                                    @endif
                                </td>
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
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableItems as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="/{{ $item->image }}" alt="{{ $item->name }}" style="width:56px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:56px;height:40px;background:#f3f4f6;border-radius:6px;display:inline-block;"></div>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->kategori ?? '-' }}</td>
                                <td>{{ $item->kondisi ?? '-' }}</td>
                                <td style="display:flex;gap:8px;align-items:center;">
                                    <a href="/barang/{{ $item->id }}/edit" class="btn-link">Edit</a>
                                    <form action="/barang/{{ $item->id }}/delete" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini? Aksi tidak dapat dibatalkan.');" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-danger" style="background:transparent;border:none;color:#ef4444;cursor:pointer;padding:4px 6px;">Hapus</button>
                                    </form>
                                </td>
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

<script>
    (function() {
        const rfidInput = document.getElementById('rfid_uid_peralatan');
        if (!rfidInput) return;
        const liveError = document.getElementById('rfid-live-error');
        const liveOk = document.getElementById('rfid-live-ok');
        const submitBtn = document.getElementById('submit-peralatan');
        const form = rfidInput.closest('form');
        let rfidCheckTimer;
        let lastValidUid = '';

        function setButtonState(enabled) {
            submitBtn.disabled = !enabled;
        }

        rfidInput.addEventListener('focus', function() {
            this.value = '';
            liveError.style.display = 'none';
            liveOk.style.display = 'none';
            lastValidUid = '';
            setButtonState(false);
        });

        rfidInput.addEventListener('input', function() {
            const uid = this.value.trim();
            clearTimeout(rfidCheckTimer);
            liveError.style.display = 'none';
            liveOk.style.display = 'none';
            setButtonState(false);

            if (!uid) {
                lastValidUid = '';
                return;
            }

            if (uid === lastValidUid) {
                setButtonState(true);
                return;
            }

            rfidCheckTimer = setTimeout(async () => {
                try {
                    const res = await fetch('/api/rfid/validate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name=\"_token\"]').value,
                        },
                        body: JSON.stringify({ uid: uid }),
                    });
                    const data = await res.json();

                    if (!data.valid) {
                        liveError.textContent = '❌ Kode RFID belum terdaftar di database! Hubungi admin terlebih dahulu.';
                        liveError.style.display = 'block';
                        liveOk.style.display = 'none';
                        lastValidUid = '';
                        setButtonState(false);
                    } else if (data.is_assigned) {
                        liveError.textContent = '⚠️ RFID ini sudah terpasang pada barang lain! Lepaskan tag dari barang sebelumnya terlebih dahulu.';
                        liveError.style.display = 'block';
                        liveOk.style.display = 'none';
                        lastValidUid = '';
                        setButtonState(false);
                    } else if (!data.is_active) {
                        liveError.textContent = '⚠️ RFID ini sudah dinonaktifkan! Hubungi admin.';
                        liveError.style.display = 'block';
                        liveOk.style.display = 'none';
                        lastValidUid = '';
                        setButtonState(false);
                    } else {
                        liveOk.textContent = '✓ Kode RFID terdaftar dan tersedia untuk didaftarkan';
                        liveOk.style.display = 'block';
                        liveError.style.display = 'none';
                        lastValidUid = uid;
                        setButtonState(true);
                    }
                } catch (err) {
                    console.error('RFID check failed:', err);
                    liveError.textContent = 'Gagal cek RFID. Coba lagi.';
                    liveError.style.display = 'block';
                    lastValidUid = '';
                    setButtonState(false);
                }
            }, 300);
        });

        form.addEventListener('submit', function(e) {
            const uid = rfidInput.value.trim();
            if (!uid || uid !== lastValidUid) {
                e.preventDefault();
                if (!uid) {
                    liveError.textContent = '❌ Kode RFID belum terdaftar di database! Hubungi admin terlebih dahulu.';
                    liveError.style.display = 'block';
                }
                setButtonState(false);
            }
        });

        setButtonState(false);
    })();
</script>
