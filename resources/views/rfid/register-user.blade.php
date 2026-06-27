@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">
                <i class="bi bi-person-badge"></i> Daftarkan Pengguna RFID
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
                    <form method="POST" action="{{ route('rfid.store-user') }}" class="needs-validation">
                        @csrf

                        <!-- User Selection -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Pengguna <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pengguna --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- RFID UID -->
                        <div class="mb-3">
                            <label for="rfid_uid" class="form-label">UID RFID <span class="text-danger">*</span></label>
                            <input type="text" name="rfid_uid" id="rfid_uid" class="form-control @error('rfid_uid') is-invalid @enderror" 
                                   placeholder="Tap kartu RFID di sini atau masukkan UID" autofocus required>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Letakkan kartu RFID di depan reader untuk scan otomatis
                            </small>
                            @error('rfid_uid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Card Holder Name -->
                        <div class="mb-3">
                            <label for="card_holder_name" class="form-label">Nama Pemegang Kartu <span class="text-danger">*</span></label>
                            <input type="text" name="card_holder_name" id="card_holder_name" class="form-control @error('card_holder_name') is-invalid @enderror" 
                                   placeholder="Nama pemegang kartu" required>
                            @error('card_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Daftarkan RFID
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb"></i> Petunjuk Pendaftaran
                    </h6>
                    <ul class="mb-0 small">
                        <li>Pilih pengguna yang akan didaftarkan kartu RFID-nya</li>
                        <li>Letakkan kartu RFID di depan reader</li>
                        <li>UID akan terbaca otomatis atau masukkan manual</li>
                        <li>Pastikan nama pemegang kartu sesuai dengan identitas pengguna</li>
                        <li>Klik "Daftarkan RFID" untuk menyimpan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('rfid_uid').addEventListener('focus', function() {
        // Clear field when focused for fresh scan
        this.value = '';
    });

    document.getElementById('user_id').addEventListener('change', function() {
        // Auto-fill card holder name based on selected user
        const option = this.selectedOptions[0];
        const nameFromEmail = option.textContent.split('(')[0].trim();
        document.getElementById('card_holder_name').value = nameFromEmail;
    });
</script>
@endsection
