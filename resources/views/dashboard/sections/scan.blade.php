<div id="scan" class="tab-content">
    <div class="card form-card">
        <h3>Registrasi Barang dengan Scan RFID</h3>
        <form action="/barang/register" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="name" class="input-custom" placeholder="Nama Barang" required>
                <input type="text" name="kategori" class="input-custom" placeholder="Kategori" required>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" name="rfid_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID" autocomplete="off" required>
                    <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=rfid_uid]'), 'Klik field UID lalu pindai tag RFID.')">Scan</button>
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
