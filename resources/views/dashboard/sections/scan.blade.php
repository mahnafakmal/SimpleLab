<div id="scan" class="tab-content">
    <div class="card form-card">
        <h3>Registrasi Barang dengan Scan RFID</h3>
        <form action="/barang/register" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="name" class="input-custom" placeholder="Nama Barang" required>
                <input type="text" name="kategori" class="input-custom" placeholder="Kategori" required>
                <input type="text" name="rfid_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID" autocomplete="off" required>
            </div>
            <button class="btn-primary" type="submit">Daftarkan Barang</button>
        </form>
    </div>

    <div class="card form-card">
        <h3>Scan Peminjaman Aset</h3>
        <form action="/peminjaman/borrow" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="card_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Kartu RFID User" autocomplete="off" required>
                <input type="text" name="tag_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID Barang" autocomplete="off" required>
            </div>
            <button class="btn-primary" type="submit">Pinjam Aset</button>
        </form>
    </div>

    <div class="card form-card">
        <h3>Scan Masuk/Keluar Aset</h3>
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
        <p>Untuk scan RFID: pilih field UID, pindai tag/kartu, dan jika pembaca mengirimkan Enter, formulir akan otomatis dikirim.</p>
    </div>
</div>
