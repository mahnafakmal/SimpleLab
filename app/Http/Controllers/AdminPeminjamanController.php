<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AdminPeminjamanController extends Controller
{
    // Display admin overview of all loans and room bookings
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $allLoans = Peminjaman::with(['barang', 'user', 'tagRfid'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.sections.admin_peminjaman', compact('allLoans'));
    }

    // Update status of a equipment loan (e.g., mark as returned)
    public function updateLoanStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $loan = Peminjaman::findOrFail($id);
        $status = $request->input('status');
        // Accept only defined statuses
        if (!in_array($status, ['active', 'returned', 'cancelled'])) {
            return Redirect::back()->with('error', 'Status tidak valid');
        }
        $loan->status = $status;
        $loan->save();
        return Redirect::back()->with('success', 'Status peminjaman berhasil diperbarui');
    }

    // Update status of a room booking (approve / reject / cancel)
    public function updateRoomStatus(Request $request, $id)
    {
        // Room booking status update removed because room booking feature is disabled.
        return Redirect::back()->with('error', 'Fitur booking ruangan telah dinonaktifkan.');
    }
}
?>
