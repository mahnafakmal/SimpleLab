<div id="scan" class="tab-content">
    <div class="card form-card">
        <h3>Scan Kartu RFID User</h3>
        <form action="/rfid/authenticate" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="card_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Kartu RFID" autocomplete="off" required>
                <select name="action" class="input-custom" required>
                    <option value="akses">Akses Masuk Lab</option>
                    <option value="peminjaman">Persiapan Peminjaman</option>
                </select>
            </div>
            <button class="btn-primary" type="submit">Autentikasi</button>
        </form>
    </div>

    <div class="card form-card">
        <h3>Scan Tag RFID Aset</h3>
        <form action="/rfid/track" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="tag_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID Aset" autocomplete="off" required>
                <select name="lokasi" class="input-custom" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>
            <button class="btn-primary" type="submit">Update Lokasi</button>
        </form>
    </div>

    <div class="note-box">
        <p>Untuk scan RFID: klik salah satu input dan pindai tag/kartu. Jika pembaca mengirimkan Enter, formulir akan otomatis dikirim.</p>
    </div>
</div>
