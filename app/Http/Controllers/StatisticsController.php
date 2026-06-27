<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Get borrowing frequency statistics for charts
     */
    public function getBorrowingFrequency()
    {
        $frequencyData = Barang::select('barangs.id', 'barangs.name', DB::raw('COUNT(peminjamans.id) as borrow_count'))
            ->leftJoin('peminjamans', 'barangs.id', '=', 'peminjamans.barang_id')
            ->groupBy('barangs.id', 'barangs.name')
            ->orderBy('borrow_count', 'desc')
            ->take(15)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $frequencyData->map(function ($item) {
                return [
                    'name' => $item->name,
                    'frequency' => $item->borrow_count
                ];
            })
        ]);
    }

    /**
     * Get equipment status summary
     */
    public function getEquipmentStatus()
    {
        $total = Barang::count();
        $available = Barang::where('status', 'available')->count();
        $borrowed = Barang::where('status', 'borrowed')->count();
        $damaged = Barang::where('status', 'damaged')->count();

        return response()->json([
            'success' => true,
            'total' => $total,
            'available' => $available,
            'borrowed' => $borrowed,
            'damaged' => $damaged,
            'percentages' => [
                'available' => $total > 0 ? round(($available / $total) * 100, 1) : 0,
                'borrowed' => $total > 0 ? round(($borrowed / $total) * 100, 1) : 0,
                'damaged' => $total > 0 ? round(($damaged / $total) * 100, 1) : 0,
            ]
        ]);
    }

    /**
     * Get borrowing trends (last 30 days)
     */
    public function getBorrowingTrends()
    {
        $trends = Peminjaman::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trends->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count
                ];
            })
        ]);
    }

    /**
     * Get category distribution
     */
    public function getCategoryDistribution()
    {
        $categories = Barang::select('kategori', DB::raw('COUNT(*) as count'))
            ->groupBy('kategori')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($item) {
                return [
                    'category' => $item->kategori ?? 'Tanpa Kategori',
                    'count' => $item->count
                ];
            })
        ]);
    }

    /**
     * Get user borrowing statistics
     */
    public function getUserStatistics()
    {
        $user = Auth::user();
        $stats = [
            'totalBorrowed' => $user->peminjaman()->count(),
            'activeBorrows' => $user->peminjaman()->where('status', 'active')->count(),
            'overdueItems' => $user->getOverdueLoans()->count(),
            'returnedItems' => $user->peminjaman()->where('status', 'returned')->count(),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Get top borrowed items
     */
    public function getTopBorrowedItems($limit = 10)
    {
        $topItems = Barang::select('barangs.id', 'barangs.name', DB::raw('COUNT(peminjamans.id) as total_borrows'))
            ->leftJoin('peminjamans', 'barangs.id', '=', 'peminjamans.barang_id')
            ->groupBy('barangs.id', 'barangs.name')
            ->orderBy('total_borrows', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'items' => $topItems
        ]);
    }

    /**
     * Get equipment condition report
     */
    public function getConditionReport()
    {
        $conditions = Barang::select('kondisi', DB::raw('COUNT(*) as count'))
            ->groupBy('kondisi')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conditions->map(function ($item) {
                return [
                    'condition' => $item->kondisi,
                    'count' => $item->count
                ];
            })
        ]);
    }

    /**
     * Get overall dashboard statistics
     */
    public function getDashboardStats()
    {
        return response()->json([
            'success' => true,
            'equipment' => $this->getEquipmentStatus()->getData()->data,
            'trends' => $this->getBorrowingTrends()->getData()->data,
            'frequency' => $this->getBorrowingFrequency()->getData()->data,
            'categories' => $this->getCategoryDistribution()->getData()->data,
            'topItems' => $this->getTopBorrowedItems(5)->getData()->items,
        ]);
    }
}
