<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - SimpleLab</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <main style="max-width:900px;margin:40px auto;">
        <h2>Edit Barang</h2>
        @if(session('success'))<div class="alert success-alert">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert error-alert">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/barang/{{ $barang->id }}/update" method="POST" enctype="multipart/form-data" class="card form-card">
            @csrf
            <div class="input-group">
                <label>Nama Barang</label>
                <input type="text" name="name" value="{{ $barang->name }}" class="input-custom" required>
                <label>Kategori</label>
                <input type="text" name="kategori" value="{{ $barang->kategori }}" class="input-custom">
                <label>Kondisi</label>
                <input type="text" name="kondisi" value="{{ $barang->kondisi }}" class="input-custom">
                <label>Status</label>
                <select name="status" class="input-custom">
                    <option value="available" {{ $barang->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="borrowed" {{ $barang->status === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                </select>
                <label>Gambar (opsional)</label>
                <input type="file" name="image" accept="image/*" class="input-custom">
                @if($barang->image)
                    <div style="margin-top:8px;">
                        <img src="/{{ $barang->image }}" alt="{{ $barang->name }}" style="max-width:220px;display:block;border-radius:8px;">
                    </div>
                @endif
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;">
                <button class="btn-primary" type="submit">Simpan Perubahan</button>
                <a href="/" class="btn-secondary" style="padding:8px 12px;">Batal</a>
            </div>
        </form>
    </main>
</body>
</html>
