<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogAkses;
use App\Models\Peminjaman;
use App\Models\RfidCard;
use App\Models\TagRfid;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Barang::count();
        $available = Barang::where('status', 'available')->count();
        $borrowed = Barang::where('status', 'borrowed')->count();
        $users = User::all();
        $recentLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])->latest('created_at')->take(5)->get();
        $recentActivities = LogAkses::with(['user', 'rfidCard'])->latest('created_at')->take(5)->get();
        $tags = TagRfid::with('barang')->get();
        $cards = RfidCard::with('user')->get();

        return view('dashboard', compact(
            'totalAssets',
            'available',
            'borrowed',
            'users',
            'recentLoans',
            'recentActivities',
            'tags',
            'cards'
        ));
    }
}
