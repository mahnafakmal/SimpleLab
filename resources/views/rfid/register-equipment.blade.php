@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">
                <i class="bi bi-box"></i> Daftarkan Tag RFID Barang
            </h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('rfid.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('rfid.store-equipment') }}" class="needs-validation">
                        @csrf

                        <!-- Equipment Selection -->
                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                            <select name="barang_id" id="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-kode="{{ $barang->kode_barang }}">
                                        {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- RFID UID -->
                        <div class="mb-3">
                            <label for="rfid_uid" class="form-label">UID Tag RFID <span class="text-danger">*</span></label>
                            <input type="text" name="rfid_uid" id="rfid_uid" class="form-control @error('rfid_uid') is-invalid @enderror" 
                                   placeholder="Tap tag RFID di sini atau masukkan UID" autofocus required>
                            <div id="rfid-live-error" class="invalid-feedback d-block" style="display:none;"></div>
                            <div id="rfid-live-ok" class="valid-feedback d-block" style="display:none;"></div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Letakkan tag RFID di depan reader untuk scan otomatis
                            </small>
                            @error('rfid_uid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Equipment Info Display -->
                        <div class="mb-3" id="equipment-info" style="display: none;">
                            <div class="alert alert-info">
                                <small>
                                    <strong>Barang Dipilih:</strong> <span id="selected-equipment"></span><br>
                                    <strong>Kode:</strong> <span id="equipment-code"></span>
                                </small>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Catatan tambahan tentang tag ini"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Daftarkan Tag RFID
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb"></i> Petunjuk Pendaftaran Tag RFID Barang
                    </h6>
                    <ul class="mb-0 small">
                        <li>Pilih barang yang akan diberi tag RFID</li>
                        <li>Letakkan tag RFID baru di depan reader</li>
                        <li>UID akan terbaca otomatis atau masukkan manual</li>
                        <li>Pastikan tag RFID telah direkatkan dengan baik di barang</li>
                        <li>Klik "Daftarkan Tag RFID" untuk menyimpan</li>
                        <li>Setelah terdaftar, tag dapat digunakan untuk scanning</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value;
    const rfidInput = document.getElementById('rfid_uid');
    const liveError = document.getElementById('rfid-live-error');
    const liveOk = document.getElementById('rfid-live-ok');
    const submitBtn = document.querySelector('button[type="submit"]');
    const form = document.querySelector('form');
    let rfidCheckTimer;
    let lastValidUid = '';

    function setButtonState(enabled) {
        submitBtn.disabled = !enabled;
    }

    rfidInput.addEventListener('focus', function() {
        this.value = '';
        liveError.style.display = 'none';
        liveOk.style.display = 'none';
        this.classList.remove('is-invalid', 'is-valid');
        lastValidUid = '';
        setButtonState(false);
    });

    rfidInput.addEventListener('input', function() {
        const uid = this.value.trim();
        clearTimeout(rfidCheckTimer);
        liveError.style.display = 'none';
        liveOk.style.display = 'none';
        this.classList.remove('is-invalid', 'is-valid');
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
                const res = await fetch(`{{ route('api.rfid.validate') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ uid: uid }),
                });
                const data = await res.json();

                if (!data.valid) {
                    rfidInput.classList.add('is-invalid');
                    rfidInput.classList.remove('is-valid');
                    liveError.textContent = '❌ Kode RFID belum terdaftar di database! Hubungi admin terlebih dahulu.';
                    liveError.style.display = 'block';
                    liveOk.style.display = 'none';
                    lastValidUid = '';
                    setButtonState(false);
                } else if (data.is_assigned) {
                    rfidInput.classList.add('is-invalid');
                    rfidInput.classList.remove('is-valid');
                    liveError.textContent = '⚠️ RFID ini sudah terpasang pada barang lain! Lepaskan tag dari barang sebelumnya terlebih dahulu.';
                    liveError.style.display = 'block';
                    liveOk.style.display = 'none';
                    lastValidUid = '';
                    setButtonState(false);
                } else if (!data.is_active) {
                    rfidInput.classList.add('is-invalid');
                    rfidInput.classList.remove('is-valid');
                    liveError.textContent = '⚠️ RFID ini sudah dinonaktifkan! Hubungi admin.';
                    liveError.style.display = 'block';
                    liveOk.style.display = 'none';
                    lastValidUid = '';
                    setButtonState(false);
                } else {
                    rfidInput.classList.remove('is-invalid');
                    rfidInput.classList.add('is-valid');
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
                rfidInput.classList.add('is-invalid');
            }
            setButtonState(false);
        }
    });

    setButtonState(false);

    document.getElementById('barang_id').addEventListener('change', function() {
        const option = this.selectedOptions[0];
        const equipmentName = option.textContent;
        const equipmentCode = option.getAttribute('data-kode');

        if (this.value) {
            document.getElementById('equipment-info').style.display = 'block';
            document.getElementById('selected-equipment').textContent = equipmentName;
            document.getElementById('equipment-code').textContent = equipmentCode;
        } else {
            document.getElementById('equipment-info').style.display = 'none';
        }
    });
</script>
@endsection
