<div id="scan" class="tab-content">
    <div class="card form-card">
        <h3>Registrasi Barang dengan Scan RFID</h3>
        <form action="/barang/register" method="POST" id="form-scan-register">
            @csrf
            <div class="input-group">
                <input type="text" name="name" class="input-custom" placeholder="Nama Barang" required>
                <input type="text" name="kategori" class="input-custom" placeholder="Kategori" required>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" name="rfid_uid" id="rfid_uid_scan" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID" autocomplete="off" required>
                    <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(document.getElementById('rfid_uid_scan'), 'Klik field UID lalu pindai tag RFID.')">Scan</button>
                </div>
                <div id="rfid-live-error-scan" style="color:#ef4444;font-size:0.85rem;display:none;"></div>
                <div id="rfid-live-ok-scan" style="color:#22c55e;font-size:0.85rem;display:none;"></div>
            </div>
            <button class="btn-primary" type="submit" id="submit-scan">Daftarkan Barang</button>
            <p class="hint-text">Gunakan tombol Scan untuk memfokuskan input UID sebelum membaca tag.</p>
        </form>
    </div>
            </div>
            <button class="btn-primary" type="submit">Daftarkan Barang</button>
            <p class="hint-text">Gunakan tombol Scan untuk memfokuskan input UID sebelum membaca tag.</p>
        </form>
    </div>

    <div class="card form-card">
        <h3>Scan Peminjaman Aset</h3>
        <form action="/peminjaman/borrow" method="POST">
            @csrf
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
            <p class="hint-text">Gunakan tombol Scan untuk memfokuskan field dan membaca UID dengan cepat.</p>
        </form>
    </div>

    <div class="card form-card">
        <h3>Scan Masuk/Keluar Aset</h3>
        <form action="/rfid/track" method="POST">
            @csrf
            <div class="input-group" style="display:flex;gap:8px;align-items:center;">
                <input type="text" name="tag_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID Aset" autocomplete="off" required>
                <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=tag_uid]'), 'Klik field Tag lalu pindai tag RFID.')">Scan</button>
                <select name="lokasi" class="input-custom" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>
            <button class="btn-primary" type="submit">Update Lokasi</button>
            <p class="hint-text">Gunakan tombol Scan untuk langsung memfokuskan field UID dan membaca tag.</p>
        </form>
    </div>

    <div class="note-box">
        <p>Untuk scan RFID: pilih field UID, pindai tag/kartu, dan jika pembaca mengirimkan Enter, formulir akan otomatis dikirim.</p>
    </div>
</div>

<script>
    (function() {
        const rfidInput = document.getElementById('rfid_uid_scan');
        if (!rfidInput) return;
        const liveError = document.getElementById('rfid-live-error-scan');
        const liveOk = document.getElementById('rfid-live-ok-scan');
        const submitBtn = document.getElementById('submit-scan');
        const form = document.getElementById('form-scan-register');
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
