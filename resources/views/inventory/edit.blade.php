@extends('layouts.app-enhanced')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Barang</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('inventory.show', $barang->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('inventory.update', $barang->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="kode_barang" class="form-label">Kode Barang</label>
                                <input id="kode_barang" type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                                       value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                                @error('kode_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input id="nama_barang" type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                                       value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    @foreach(['CPU', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'Proyektor', 'Kabel', 'Lainnya'] as $cat)
                                        <option value="{{ $cat }}" {{ old('kategori', $barang->kategori) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status', $barang->status) === 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="borrowed" {{ old('status', $barang->status) === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="rusak" {{ old('status', $barang->status) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi', $barang->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="cacat" {{ old('kondisi', $barang->kondisi) === 'cacat' ? 'selected' : '' }}>Cacat</option>
                                    <option value="rusak" {{ old('kondisi', $barang->kondisi) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input id="lokasi" type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $barang->lokasi) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <label for="spesifikasi" class="form-label">Spesifikasi</label>
                                <input id="spesifikasi" type="text" name="spesifikasi" class="form-control" value="{{ old('spesifikasi', $barang->spesifikasi) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-12 col-lg-4">
                                <label class="form-label">Foto Barang</label>
                                @if($barang->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $barang->image) }}" alt="{{ $barang->nama_barang }}" style="max-width: 100%;border-radius:8px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 col-lg-8">
                                <label for="image" class="form-label">Unggah Foto Baru</label>
                                <input id="image" type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                            <button type="reset" class="btn btn-outline-secondary" style="min-width:110px;">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" style="min-width:140px;">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
