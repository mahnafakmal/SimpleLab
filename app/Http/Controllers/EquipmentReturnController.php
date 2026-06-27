<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\TagRfid;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EquipmentReturnedNotification;
use App\Notifications\EquipmentOverdueNotification;

class EquipmentReturnController extends Controller
{
    /**
     * Show RFID scanner for equipment return
     */
    public function showReturnForm()
    {
        $user = Auth::user();
        $activeLoans = $user->getActiveLoans();

        return view('equipment.return-form', [
            'activeLoans' => $activeLoans,
            'overdueLoans' => $user->getOverdueLoans(),
        ]);
    }

    /**
     * Process RFID scan for equipment return
     */
    public function processScan(Request $request)
    {
        $rfidUid = $request->input('rfid_uid');
        $user = Auth::user();

        // Validate RFID tag is registered
        if (!TagRfid::isValidTag($rfidUid)) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tidak terdaftar dalam sistem',
                'error' => 'unregistered_rfid'
            ], 404);
        }

        // Get barang associated with RFID
        $barang = TagRfid::getBarangByUid($rfidUid);
        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan untuk RFID ini',
                'error' => 'no_equipment'
            ], 404);
        }

        // Find active loan for this user and equipment
        $loan = Peminjaman::where('user_id', $user->id)
            ->where('barang_id', $barang->id)
            ->where('status', 'active')
            ->first();

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki peminjaman aktif untuk barang ini',
                'equipment' => $barang->name,
                'error' => 'no_active_loan'
            ], 400);
        }

        // Validate equipment condition before return
        $conditionStatus = $this->validateEquipmentCondition($barang, $loan);

        if (!$conditionStatus['valid']) {
            $loan->status = 'pending_verification';
            $loan->save();

            return response()->json([
                'success' => false,
                'message' => 'Barang tidak dapat dikembalikan - ' . $conditionStatus['reason'],
                'equipment' => $barang->name,
                'requiresApproval' => true,
                'error' => 'condition_check_failed'
            ], 400);
        }

        // Mark as returned
        $loan->markReturned();
        $barang->update(['status' => 'available']);

        // Send return notification
        $user->notify(new EquipmentReturnedNotification($barang, $loan));

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dikembalikan',
            'equipment' => $barang->name,
            'returnedAt' => $loan->returned_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Validate equipment condition
     */
    private function validateEquipmentCondition(Barang $barang, Peminjaman $loan): array
    {
        // Check for damage reports
        $damageReports = $barang->laporanKerusakan()
            ->where('created_at', '>', $loan->started_at)
            ->where('created_at', '<=', now())
            ->count();

        if ($damageReports > 0) {
            return [
                'valid' => false,
                'reason' => 'Barang memiliki laporan kerusakan'
            ];
        }

        // Check equipment condition
        if ($barang->kondisi !== 'Baik') {
            return [
                'valid' => false,
                'reason' => "Kondisi barang tidak baik: {$barang->kondisi}"
            ];
        }

        return ['valid' => true];
    }

    /**
     * Get active loans for current user
     */
    public function getActiveLoans()
    {
        $user = Auth::user();
        $loans = $user->getActiveLoans()->map(function ($loan) {
            return [
                'id' => $loan->id,
                'equipment' => $loan->barang->name,
                'borrowedAt' => $loan->started_at->format('d/m/Y H:i'),
                'dueDate' => $loan->due_date ? $loan->due_date->format('d/m/Y') : 'Tidak ada batas',
                'isOverdue' => $loan->isOverdue(),
                'daysOverdue' => $loan->getDaysOverdue(),
                'rfidUid' => $loan->tagRfid?->uid,
            ];
        });

        return response()->json([
            'success' => true,
            'loans' => $loans
        ]);
    }

    /**
     * Mark equipment as damaged during return
     */
    public function reportDamage(Request $request, $loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        $user = Auth::user();

        if ($loan->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $barang = $loan->barang;
        $barang->update([
            'status' => 'damaged',
            'kondisi' => 'Rusak'
        ]);

        $loan->markReturned();

        return response()->json([
            'success' => true,
            'message' => 'Barang dilaporkan rusak'
        ]);
    }
}
