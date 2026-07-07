<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogAkses;
use App\Models\Peminjaman;
use App\Models\RfidCard;
use App\Models\TagRfid;
use App\Models\User;
use App\Models\LaporanKerusakan;
use App\Models\RiwayatLog;
use App\Notifications\EquipmentOverdueNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            $totalAssets = Barang::count();
            $available = Barang::where('status', 'available')->count();
            $borrowed = Barang::where('status', 'borrowed')->count();
            $availableItems = Barang::where('status', 'available')->get();
            $users = User::all();
            $totalDosen = User::where('role', 'dosen')->count();
            $totalMahasiswa = User::where('role', 'user')->count();
            // Show only active (ongoing) loans in the recent list so returned/cancelled ones disappear after update
            $recentLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])
                ->where('status', 'active')
                ->latest('created_at')
                ->take(5)
                ->get();
            // Exclude registration actions from recentActivities so registrations appear only in reports
            $recentActivities = LogAkses::where('action', 'not like', 'Registrasi%')
                ->with(['user', 'rfidCard'])
                ->latest('created_at')
                ->take(5)
                ->get();
            $tags = TagRfid::with('barang')->get();
            $cards = RfidCard::with('user')->get();
            $allLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])->orderBy('created_at', 'desc')->get();
            // Aggregated summaries per item name
            $itemsSummary = Barang::select('name', DB::raw('count(*) as total_count'))
                ->groupBy('name')
                ->orderBy('name')
                ->get();

            $availableSummary = Barang::select('name', DB::raw("SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available_count"))
                ->groupBy('name')
                ->orderBy('name')
                ->get();

            // Fetch dynamic damage reports count and pending requests count
            $totalDamaged = Barang::where('kondisi', '!=', 'Baik')->count();
            $pendingLoans = Peminjaman::where('status', 'pending')->count();
            $allReports = LaporanKerusakan::with(['user', 'barang'])->orderBy('created_at', 'desc')->get();
            $registrationsCount = RiwayatLog::where('event', 'like', 'Registrasi%')->count();

            return view('dashboard', compact(
                'totalAssets',
                'available',
                'borrowed',
                'users',
                'totalDosen',
                'totalMahasiswa',
                'recentLoans',
                'recentActivities',
                'tags',
                'cards',
                'availableItems',
                'allLoans',
                'itemsSummary',
                'availableSummary',
                'totalDamaged',
                'pendingLoans',
                'registrationsCount',
                'allReports'
            ));
        }

        // For regular users: display home with lab equipment, status, and schedules
        $barangs = Barang::all();
        $totalAlat = Barang::count();
        $alatTersedia = Barang::where('status', 'available')->count();
        $alatDipinjam = Barang::where('status', 'borrowed')->count();
        
        // Jadwal removed from home view
        
        $peminjamanSaya = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->take(5)
            ->get();

        // Get overdue loans for current user
        $overdueLoans = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->get();

        $laporanKerusakanSaya = LaporanKerusakan::with('barang')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Send overdue notifications for current user's overdue loans if not already sent.
        if (Schema::hasTable('notifications')) {
            foreach ($overdueLoans as $loan) {
                if (is_null($loan->overdue_notified_at)) {
                    $loan->user->notify(new EquipmentOverdueNotification($loan->barang, $loan));
                    $loan->overdue_notified_at = now();
                    $loan->save();
                }
            }
        }

        return view('home', compact(
            'barangs',
            'totalAlat',
            'alatTersedia',
            'alatDipinjam',
            'peminjamanSaya',
            'overdueLoans',
            'laporanKerusakanSaya'
        ));
    }

    /**
     * Show list of available Barang with total assets count.
     */
    public function availableItems()
    {
        $availableItems = Barang::where('status', 'available')->get();
        $totalAssets = Barang::count();

        return view('barang.tersedia', compact('availableItems', 'totalAssets'));
    }

    /**
     * Show all Barang (total assets list).
     */
    public function allItems()
    {
        $items = Barang::all();
        $totalAssets = Barang::count();

        return view('barang.semua', compact('items', 'totalAssets'));
    }

    /**
     * Show borrowed Barang (currently borrowed).
     */
    public function borrowedItems()
    {
        $borrowedItems = Barang::where('status', 'borrowed')->get();
        $totalAssets = Barang::count();

        return view('barang.dipinjam', compact('borrowedItems', 'totalAssets'));
    }

    /**
     * Admin report: history of peminjaman (barang).
     */
    public function reportPeminjaman()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403);
        }

        $peminjaman = Peminjaman::with(['barang', 'user', 'tagRfid'])->orderBy('created_at', 'desc')->get();
        return view('admin.laporan.peminjaman', compact('peminjaman'));
    }

    /**
     * Admin report: registrations (user accounts created)
     */
    public function reportRegistrasi()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403);
        }

        $registrations = RiwayatLog::where('event', 'like', 'Registrasi%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.laporan.registrasi', compact('registrations'));
    }

    /**
     * Admin report: history of room bookings (peminjaman ruangan).
     */
    public function reportRuangan()
    {
        // Room reports removed because booking feature is disabled.
        abort(404);
    }

    /**
     * Show the user profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        if (!$user || $user->role === 'admin') {
            abort(403, 'Halaman profil hanya dapat diakses oleh Dosen dan Mahasiswa.');
        }

        // Peminjaman history (riwayat peminjaman alat)
        $peminjaman = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Account log history (riwayat aktivitas akun)
        $logAkses = LogAkses::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeLoans = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $peminjamanSaya = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('profile', compact('user', 'peminjaman', 'logAkses', 'activeLoans', 'peminjamanSaya'));
    }

    /**
     * Show lab schedule to Dosen (read-only).
     */
    public function jadwalForDosen()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'dosen') {
            abort(403, 'Hanya Dosen yang dapat melihat jadwal lab di halaman ini.');
        }

        $schedules = \App\Models\JadwalLab::orderBy('hari')->orderBy('jam_mulai')->get();

        return view('jadwal.dosen', compact('user', 'schedules'));
    }
}
