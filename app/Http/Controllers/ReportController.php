<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display circulation report page
     */
    public function circulation()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $loans = Peminjaman::whereBetween('created_at', [$startDate, $endDate])
            ->with('barang', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_loans' => $loans->count(),
            'returned' => $loans->where('status', 'completed')->count(),
            'active' => $loans->where('status', 'active')->count(),
            'overdue' => $loans->filter(fn ($l) => $l->isOverdue())->count(),
            'avg_duration' => $this->calculateAverageDuration($loans),
        ];

        return view('reports.circulation', [
            'loans' => $loans,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export circulation report to PDF
     */
    public function exportCirculationPdf()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $loans = Peminjaman::whereBetween('created_at', [$startDate, $endDate])
            ->with('barang', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_loans' => $loans->count(),
            'returned' => $loans->where('status', 'completed')->count(),
            'active' => $loans->where('status', 'active')->count(),
            'overdue' => $loans->filter(fn ($l) => $l->isOverdue())->count(),
        ];

        $pdf = PDF::loadView('reports.circulation-pdf', [
            'loans' => $loans,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $pdf->download('laporan-sirkulasi-' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Export circulation report to Excel
     */
    public function exportCirculationExcel()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $loans = Peminjaman::whereBetween('created_at', [$startDate, $endDate])
            ->with('barang', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Excel::download(
            new \App\Exports\CirculationReportExport($loans),
            'laporan-sirkulasi-' . date('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Display equipment frequency report
     */
    public function equipmentFrequency()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $frequencyData = Barang::withCount([
            'peminjaman' => fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate])
        ])
            ->orderByDesc('peminjaman_count')
            ->get();

        return view('reports.equipment-frequency', [
            'frequency' => $frequencyData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Display damage report
     */
    public function damageReport()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $damageReports = LaporanKerusakan::whereBetween('tanggal_laporan', [$startDate, $endDate])
            ->with('barang', 'user')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $stats = [
            'total_reports' => $damageReports->count(),
            'ringan' => $damageReports->where('tingkat_kerusakan', 'ringan')->count(),
            'sedang' => $damageReports->where('tingkat_kerusakan', 'sedang')->count(),
            'berat' => $damageReports->where('tingkat_kerusakan', 'berat')->count(),
        ];

        return view('reports.damage-report', [
            'reports' => $damageReports,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export damage report to PDF
     */
    public function exportDamagePdf()
    {
        $this->authorize('admin');

        $startDate = request('start_date') ? Carbon::createFromFormat('Y-m-d', request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::createFromFormat('Y-m-d', request('end_date')) : now()->endOfMonth();

        $damageReports = LaporanKerusakan::whereBetween('tanggal_laporan', [$startDate, $endDate])
            ->with('barang', 'user')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $pdf = PDF::loadView('reports.damage-report-pdf', [
            'reports' => $damageReports,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $pdf->download('laporan-kerusakan-' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Calculate average borrow duration
     */
    private function calculateAverageDuration($loans)
    {
        $completedLoans = $loans->where('status', 'completed')->filter(fn ($l) => $l->returned_at);

        if ($completedLoans->count() === 0) {
            return 0;
        }

        $totalDays = $completedLoans->sum(function ($loan) {
            return $loan->started_at->diffInDays($loan->returned_at);
        });

        return round($totalDays / $completedLoans->count(), 1);
    }

    /**
     * Dashboard summary API
     */
    public function dashboardSummary()
    {
        return response()->json([
            'total_barang' => Barang::count(),
            'barang_tersedia' => Barang::where('status', 'available')->count(),
            'barang_dipinjam' => Barang::where('status', 'borrowed')->count(),
            'barang_rusak' => Barang::where('status', 'rusak')->count(),
            'peminjaman_aktif' => Peminjaman::where('status', 'active')->count(),
            'peminjaman_overdue' => Peminjaman::where('status', 'active')
                ->where('due_date', '<', now())
                ->count(),
            'laporan_kerusakan_baru' => LaporanKerusakan::where('status', 'dilaporkan')->count(),
        ]);
    }
}
