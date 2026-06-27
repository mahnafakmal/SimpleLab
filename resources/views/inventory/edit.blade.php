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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                                       value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                                @error('kode_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                                       value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    @foreach(['CPU', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'Proyektor', 'Kabel', 'Lainnya'] as $cat)
                                        <option value="{{ $cat }}" {{ old('kategori', $barang->kategori) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status', $barang->status) === 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="borrowed" {{ old('status', $barang->status) === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="rusak" {{ old('status', $barang->status) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi', $barang->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="cacat" {{ old('kondisi', $barang->kondisi) === 'cacat' ? 'selected' : '' }}>Cacat</option>
                                    <option value="rusak" {{ old('kondisi', $barang->kondisi) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $barang->lokasi) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="spesifikasi" class="form-label">Spesifikasi</label>
                            <input type="text" name="spesifikasi" class="form-control" value="{{ old('spesifikasi', $barang->spesifikasi) }}">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Foto Barang</label>
                            @if($barang->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $barang->image) }}" alt="{{ $barang->nama_barang }}" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
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
