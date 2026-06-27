<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - SimpleLab</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .edit-form-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }
        .form-group input,
        .form-group select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0066ff;
            box-shadow: 0 0 0 3px rgba(0,102,255,0.1);
        }
        .form-group input[type="file"] {
            padding: 8px;
        }
        .image-preview {
            margin-top: 12px;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e0e0e0;
        }
        .btn-primary, .btn-secondary {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #0066ff;
            color: white;
        }
        .btn-primary:hover {
            background: #0052cc;
        }
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .success-alert {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-alert {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .error-alert ul {
            margin: 0;
            padding-left: 20px;
        }
        .error-alert li {
            margin: 4px 0;
        }
        h2 {
            margin-bottom: 24px;
            color: #333;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="edit-form-container">
        <h2>Edit Barang</h2>
        
        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert error-alert">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/barang/{{ $barang->id }}/update" method="POST" enctype="multipart/form-data" class="form-card">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nama Barang</label>
                    <input type="text" id="name" name="name" value="{{ $barang->name }}" required>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" value="{{ $barang->kategori }}">
                </div>

                <div class="form-group">
                    <label for="kondisi">Kondisi</label>
                    <input type="text" id="kondisi" name="kondisi" value="{{ $barang->kondisi }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="available" {{ $barang->status === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ $barang->status === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="image">Gambar (opsional)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            @if($barang->image)
                <div class="image-preview">
                    <p style="font-size: 13px; color: #666; margin-bottom: 8px;">Gambar saat ini:</p>
                    <img src="/{{ $barang->image }}" alt="{{ $barang->name }}">
                </div>
            @endif

            <div class="form-actions">
                <button class="btn-primary" type="submit">Simpan Perubahan</button>
                <a href="/" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
