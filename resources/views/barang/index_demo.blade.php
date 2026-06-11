@extends('layouts.simplelab')

@section('content')
<div class="page-header">
    <h2>Inventaris Barang</h2>
    <div class="actions">
        <div class="search">
            <div class="input-icon">
                <i class="fa fa-search"></i>
                <input type="text" placeholder="Cari nama / kode barang...">
            </div>
        </div>
        <button class="btn">Scan RFID</button>
        <button class="btn btn-primary" data-modal-open="modalTambah">Tambah Barang</button>
    </div>
</div>

    <div class="table-wrap">
    <table class="table">
        <thead><tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Kondisi</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @if(!empty($barangs) && $barangs->count())
                @foreach($barangs as $b)
                    <tr>
                        <td>{{ 'A'.str_pad($b->id,3,'0',STR_PAD_LEFT) }}</td>
                        <td>{{ $b->name }}</td>
                        <td>{{ $b->kategori }}</td>
                        <td>{{ $b->kondisi }}</td>
                        <td>
                            @if(strtolower($b->status) == 'tersedia')
                                <span class="badge available">Tersedia</span>
                            @elseif(strtolower($b->status) == 'dipinjam')
                                <span class="badge borrowed">Dipinjam</span>
                            @elseif(strtolower($b->kondisi) == 'rusak')
                                <span class="badge rusak">Rusak</span>
                            @else
                                <span class="badge">{{ $b->status }}</span>
                            @endif
                        </td>
                        <td><button class="btn small">Edit</button> <button class="btn small">Hapus</button></td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6">Belum ada data barang</td></tr>
            @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $barangs->links() ?? '' }}
    </div>

    <!-- Modal Tambah Barang (simple demo) -->
    <div id="modalTambah" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;padding:20px;border-radius:10px;width:520px;">
            <h3>Tambah Barang</h3>
            <form method="POST" action="{{ route('demo.barang.store') }}">
                @csrf
                <label>Nama Barang</label>
                <input name="name" type="text" style="width:100%;padding:8px;margin:8px 0;border:1px solid #e6eef8;border-radius:8px" required>
                <label>Kategori</label>
                <input name="kategori" type="text" style="width:100%;padding:8px;margin:8px 0;border:1px solid #e6eef8;border-radius:8px">
                <label>Kondisi</label>
                <input name="kondisi" type="text" style="width:100%;padding:8px;margin:8px 0;border:1px solid #e6eef8;border-radius:8px">
                <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;">
                    <button class="btn" type="button" data-modal-close="modalTambah">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
