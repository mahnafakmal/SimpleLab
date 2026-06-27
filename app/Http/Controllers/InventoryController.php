<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display equipment inventory list
     */
    public function index()
    {
        $barangs = Barang::withCount('peminjaman')
            ->withCount('laporanKerusakan')
            ->paginate(15);

        $stats = [
            'total' => Barang::count(),
            'available' => Barang::where('status', 'available')->count(),
            'borrowed' => Barang::where('status', 'borrowed')->count(),
            'damaged' => Barang::where('status', 'rusak')->count(),
        ];

        return view('inventory.index', compact('barangs', 'stats'));
    }

    /**
     * Show create equipment form
     */
    public function create()
    {
        $categories = ['CPU', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'Proyektor', 'Kabel', 'Lainnya'];
        return view('inventory.create', compact('categories'));
    }

    /**
     * Store new equipment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang|max:50',
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'spesifikasi' => 'nullable|string|max:500',
            'lokasi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang harus diisi',
            'kode_barang.unique' => 'Kode barang sudah ada',
            'nama_barang.required' => 'Nama barang harus diisi',
            'kategori.required' => 'Kategori harus dipilih',
        ]);

        $barang = new Barang($validated);
        $barang->status = 'available';
        $barang->kondisi = 'baik';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('barangs', 'public');
            $barang->image = $path;
        }

        $barang->save();

        return redirect()->route('inventory.show', $barang->id)
            ->with('success', '✓ Barang berhasil ditambahkan!');
    }

    /**
     * Display equipment detail
     */
    public function show(Barang $barang)
    {
        $barang->load('peminjaman', 'laporanKerusakan', 'tagRfid');
        $riwayatPeminjaman = $barang->peminjaman()->latest()->take(10)->get();

        return view('inventory.show', compact('barang', 'riwayatPeminjaman'));
    }

    /**
     * Show edit equipment form
     */
    public function edit(Barang $barang)
    {
        $categories = ['CPU', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'Proyektor', 'Kabel', 'Lainnya'];
        return view('inventory.edit', compact('barang', 'categories'));
    }

    /**
     * Update equipment
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'status' => 'required|in:available,borrowed,rusak',
            'kondisi' => 'required|in:baik,cacat,rusak',
            'spesifikasi' => 'nullable|string|max:500',
            'lokasi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $barang->update($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('barangs', 'public');
            $barang->update(['image' => $path]);
        }

        return redirect()->route('inventory.show', $barang->id)
            ->with('success', '✓ Barang berhasil diperbarui!');
    }

    /**
     * Delete equipment
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('inventory.index')
            ->with('success', '✓ Barang berhasil dihapus!');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'barang_ids' => 'required|array',
            'barang_ids.*' => 'exists:barangs,id',
            'status' => 'required|in:available,borrowed,rusak',
        ]);

        Barang::whereIn('id', $validated['barang_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->route('inventory.index')
            ->with('success', '✓ Status barang berhasil diperbarui!');
    }

    /**
     * Export inventory to CSV
     */
    public function exportCsv()
    {
        $barangs = Barang::all();

        $filename = 'inventaris_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        fputcsv($handle, ['Kode Barang', 'Nama Barang', 'Kategori', 'Status', 'Kondisi', 'Lokasi', 'Spesifikasi']);

        foreach ($barangs as $barang) {
            fputcsv($handle, [
                $barang->kode_barang,
                $barang->nama_barang,
                $barang->kategori,
                $barang->status,
                $barang->kondisi,
                $barang->lokasi,
                $barang->spesifikasi,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            fn () => print($csv),
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Get inventory stats API
     */
    public function stats()
    {
        return response()->json([
            'total' => Barang::count(),
            'available' => Barang::where('status', 'available')->count(),
            'borrowed' => Barang::where('status', 'borrowed')->count(),
            'damaged' => Barang::where('status', 'rusak')->count(),
            'good' => Barang::where('kondisi', 'baik')->count(),
            'damaged_condition' => Barang::where('kondisi', 'rusak')->count(),
        ]);
    }
}
