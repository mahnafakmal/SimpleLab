<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogAkses;
use App\Models\Peminjaman;
use App\Models\RfidCard;
use App\Models\TagRfid;
use App\Models\User;
use App\Models\JadwalLab;
use App\Models\PeminjamanRuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $recentLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])->latest('created_at')->take(5)->get();
            $recentActivities = LogAkses::with(['user', 'rfidCard'])->latest('created_at')->take(5)->get();
            $tags = TagRfid::with('barang')->get();
            $cards = RfidCard::with('user')->get();
            $allLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])->orderBy('created_at', 'desc')->get();
            $allRooms = PeminjamanRuangan::orderBy('created_at', 'desc')->get();
            // Aggregated summaries per item name
            $itemsSummary = Barang::select('name', DB::raw('count(*) as total_count'))
                ->groupBy('name')
                ->orderBy('name')
                ->get();

            $availableSummary = Barang::select('name', DB::raw("SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available_count"))
                ->groupBy('name')
                ->orderBy('name')
                ->get();

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
                'allRooms',
                'itemsSummary',
                'availableSummary'
            ));
        }

        // For regular users: display home with lab equipment, status, and schedules
        $barangs = Barang::all();
        $totalAlat = Barang::count();
        $alatTersedia = Barang::where('status', 'available')->count();
        $alatDipinjam = Barang::where('status', 'borrowed')->count();
        
        // Jadwal umum
        $jadwalAll = JadwalLab::orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")->get();

        // Use the same home view for non-admin users (mahasiswa and dosen)
        $jadwalLabs = $jadwalAll;
        
        $peminjamanSaya = Peminjaman::with('barang')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $peminjamanRuanganSaya = PeminjamanRuangan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact(
            'barangs',
            'totalAlat',
            'alatTersedia',
            'alatDipinjam',
            'jadwalLabs',
            'peminjamanSaya',
            'peminjamanRuanganSaya'
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
     * Admin report: history of room bookings (peminjaman ruangan).
     */
    public function reportRuangan()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403);
        }

        $ruangan = PeminjamanRuangan::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.laporan.ruangan', compact('ruangan'));
    }
}
