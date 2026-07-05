<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanLabController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman lab
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin dapat melihat semua pengajuan peminjaman lab
        if ($user->role === 'admin') {
            $peminjaman = PeminjamanLab::with('user')
                ->orderBy('tanggal_pinjam', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->get();
        } else {
            // User/Dosen hanya dapat melihat pengajuan mereka sendiri
            $peminjaman = PeminjamanLab::where('user_id', $user->id)
                ->orderBy('tanggal_pinjam', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->get();
        }

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan formulir peminjaman lab
     */
    public function create()
    {
        return view('peminjaman.create');
    }

    /**
     * Menyimpan data pengajuan peminjaman lab
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lab' => 'required|string|max:255',
            'keperluan' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ], [
            'nama_lab.required' => 'Pilihan laboratorium wajib diisi.',
            'keperluan.required' => 'Keperluan peminjaman wajib diisi.',
            'tanggal_pinjam.required' => 'Tanggal peminjaman wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman tidak boleh hari yang sudah berlalu.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        PeminjamanLab::create([
            'user_id' => Auth::id(),
            'nama_lab' => $request->nama_lab,
            'keperluan' => $request->keperluan,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'pending', // Default status adalah pending
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Permintaan peminjaman lab berhasil diajukan.');
    }

    /**
     * Menyetujui peminjaman lab (Khusus Admin)
     */
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat menyetujui peminjaman.');
        }

        $peminjaman = PeminjamanLab::findOrFail($id);
        $peminjaman->update([
            'status' => 'disetujui',
            'catatan_admin' => 'Telah disetujui oleh Admin.'
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman laboratorium berhasil disetujui.');
    }

    /**
     * Menolak peminjaman lab (Khusus Admin)
     */
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat menolak peminjaman.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Alasan penolakan (catatan admin) wajib diisi.',
        ]);

        $peminjaman = PeminjamanLab::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman laboratorium telah ditolak.');
    }

    /**
     * Batalkan peminjaman (Khusus User pemilik pengajuan yang masih pending)
     */
    public function destroy($id)
    {
        $peminjaman = PeminjamanLab::findOrFail($id);
        $user = Auth::user();

        // User hanya bisa membatalkan miliknya sendiri yang masih pending, Admin bebas menghapus
        if ($user->role !== 'admin' && ($peminjaman->user_id !== $user->id || $peminjaman->status !== 'pending')) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan pengajuan ini.');
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman laboratorium berhasil dibatalkan.');
    }
}