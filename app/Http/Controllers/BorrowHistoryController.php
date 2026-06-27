<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\LaporanKerusakan;
use App\Models\Barang;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class BorrowHistoryController extends Controller
{
    /**
     * Display user's borrow history
     */
    public function index()
    {
        $user = auth()->user();
        
        $activeLoans = Peminjaman::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('barang')
            ->orderBy('due_date', 'asc')
            ->get();

        $completedLoans = Peminjaman::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('barang')
            ->orderBy('returned_at', 'desc')
            ->paginate(10);

        $overdueCount = $user->getOverdueLoans()->count();

        return view('history.index', [
            'activeLoans' => $activeLoans,
            'completedLoans' => $completedLoans,
            'overdueCount' => $overdueCount,
        ]);
    }

    /**
     * Display borrow detail
     */
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $peminjaman->load('barang', 'user', 'laporanKerusakan');

        return view('history.show', compact('peminjaman'));
    }

    /**
     * Export borrow history to PDF
     */
    public function exportPdf()
    {
        $user = auth()->user();
        $loans = Peminjaman::where('user_id', $user->id)
            ->with('barang')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \PDF::loadView('history.pdf-export', [
            'loans' => $loans,
            'user' => $user,
        ]);

        return $pdf->download('riwayat-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Report damage for borrowed equipment
     */
    public function reportDamage(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'deskripsi_kerusakan' => 'required|string|max:1000',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'deskripsi_kerusakan.required' => 'Deskripsi kerusakan harus diisi',
            'tingkat_kerusakan.required' => 'Tingkat kerusakan harus dipilih',
        ]);

        $laporan = new LaporanKerusakan();
        $laporan->peminjaman_id = $peminjaman->id;
        $laporan->barang_id = $peminjaman->barang_id;
        $laporan->user_id = auth()->id();
        $laporan->deskripsi_kerusakan = $validated['deskripsi_kerusakan'];
        $laporan->tingkat_kerusakan = $validated['tingkat_kerusakan'];
        $laporan->tanggal_laporan = now();
        $laporan->status = 'dilaporkan';

        if ($request->hasFile('foto_kerusakan')) {
            $path = $request->file('foto_kerusakan')->store('damage-reports', 'public');
            $laporan->foto_kerusakan = $path;
        }

        $laporan->save();

        // Update barang status if damage is severe
        if ($validated['tingkat_kerusakan'] === 'berat') {
            $peminjaman->barang->update(['status' => 'rusak', 'kondisi' => 'rusak']);
        }

        return redirect()->route('history.show', $peminjaman->id)
            ->with('success', '✓ Laporan kerusakan berhasil dikirim!');
    }

    /**
     * Admin: View all damage reports
     */
    public function damageReports()
    {
        $this->authorize('admin');

        $reports = LaporanKerusakan::with('barang', 'user', 'peminjaman')
            ->orderBy('tanggal_laporan', 'desc')
            ->paginate(15);

        return view('history.damage-reports', compact('reports'));
    }

    /**
     * Admin: Update damage report status
     */
    public function updateDamageStatus(Request $request, LaporanKerusakan $laporan)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'status' => 'required|in:dilaporkan,diverifikasi,ditinjau,selesai',
            'catatan' => 'nullable|string|max:500',
        ]);

        $laporan->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan'],
        ]);

        return redirect()->route('history.damage-reports')
            ->with('success', '✓ Status laporan kerusakan berhasil diperbarui!');
    }

    /**
     * Admin: Export damage reports to PDF
     */
    public function exportDamageReportsPdf()
    {
        $this->authorize('admin');

        $reports = LaporanKerusakan::with('barang', 'user')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $pdf = \PDF::loadView('history.damage-reports-pdf', compact('reports'));

        return $pdf->download('laporan-kerusakan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Get overdue loans
     */
    public function overdue()
    {
        $user = auth()->user();
        $overdueLoans = $user->getOverdueLoans();

        return view('history.overdue', compact('overdueLoans'));
    }
}
