<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use App\Models\LogAkses;
use App\Models\RiwayatLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKerusakanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'deskripsi' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Create the damage report
        $report = LaporanKerusakan::create([
            'user_id' => $user->id,
            'barang_id' => $request->barang_id,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        // Update the item condition to 'Rusak'
        $barang = Barang::findOrFail($request->barang_id);
        $barang->update(['kondisi' => 'Rusak']);

        // Log access
        LogAkses::create([
            'user_id' => $user->id,
            'action' => 'Laporan Kerusakan',
            'notes' => sprintf('User %s melaporkan kerusakan alat "%s" via portal web.', $user->name, $barang->name),
        ]);

        // Log event history
        RiwayatLog::create([
            'event' => 'Laporan Kerusakan',
            'detail' => sprintf('User %s melaporkan kerusakan barang %s: "%s".', $user->name, $barang->name, $request->deskripsi),
        ]);

        return back()->with('success', sprintf('Laporan kerusakan alat %s berhasil dikirim.', $barang->name));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:pending,proses,selesai',
        ]);

        $report = LaporanKerusakan::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        // Map report status to item condition
        $barang = $report->barang;
        if ($request->status === 'selesai') {
            $barang->kondisi = 'Baik';
        } elseif ($request->status === 'proses') {
            $barang->kondisi = 'Diperbaiki';
        } else {
            $barang->kondisi = 'Rusak';
        }
        $barang->save();

        // Log event history
        RiwayatLog::create([
            'event' => 'Update Status Kerusakan',
            'detail' => sprintf('Admin memperbarui status laporan kerusakan #%d (%s) menjadi %s.', $report->id, $barang->name, $request->status),
        ]);

        return back()->with('success', sprintf('Status laporan kerusakan untuk %s berhasil diperbarui.', $barang->name));
    }
}
