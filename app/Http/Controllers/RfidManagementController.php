<?php

namespace App\Http\Controllers;

use App\Models\TagRfid;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;

class RfidManagementController extends Controller
{
    /**
     * Display RFID management dashboard
     */
    public function index()
    {
        $rfidCards = TagRfid::with('barang')->paginate(10);
        $registeredUsers = User::whereHas('rfidCards')->count();
        $registeredEquipment = Barang::whereHas('tagRfid')->count();

        return view('rfid.index', [
            'rfidCards' => $rfidCards,
            'registeredUsers' => $registeredUsers,
            'registeredEquipment' => $registeredEquipment,
        ]);
    }

    /**
     * Show user RFID registration form
     */
    public function showUserRegistration()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('rfid.register-user', compact('users'));
    }

    /**
     * Register user RFID card
     */
    public function registerUserRfid(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid_uid' => 'required|unique:tag_rfids,uid',
            'card_holder_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ], [
            'user_id.required' => 'Pengguna harus dipilih',
            'user_id.exists' => 'Pengguna tidak ditemukan',
            'rfid_uid.required' => 'UID RFID harus diisi',
            'rfid_uid.unique' => 'UID RFID sudah terdaftar sebelumnya',
            'card_holder_name.required' => 'Nama pemegang kartu harus diisi',
        ]);

        TagRfid::create([
            'user_id' => $validated['user_id'],
            'uid' => $validated['rfid_uid'],
            'tag_type' => 'user_card',
            'card_holder_name' => $validated['card_holder_name'],
            'notes' => $validated['notes'],
            'is_active' => true,
        ]);

        return redirect()->route('rfid.index')
            ->with('success', '✓ Kartu RFID pengguna berhasil didaftarkan!');
    }

    /**
     * Show equipment tag registration form
     */
    public function showEquipmentRegistration()
    {
        $barangs = Barang::where('status', '!=', 'rusak')->get();
        return view('rfid.register-equipment', compact('barangs'));
    }

    /**
     * Register equipment RFID tag
     */
    public function registerEquipmentTag(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'rfid_uid' => 'required|string',
        ], [
            'barang_id.required' => 'Barang harus dipilih',
            'barang_id.exists' => 'Barang tidak ditemukan',
            'rfid_uid.required' => 'UID RFID harus diisi',
        ]);

        $tag = TagRfid::where('uid', $validated['rfid_uid'])->first();

        if (! $tag) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rfid_uid' => '❌ Kode RFID belum terdaftar di database! Hubungi admin untuk mendaftarkan tag RFID terlebih dahulu.']);
        }

        if (! $tag->is_active) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rfid_uid' => '⚠️ RFID ini sudah dinonaktifkan! Hubungi admin untuk mengaktifkannya.']);
        }

        if ($tag->barang_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rfid_uid' => '⚠️ RFID ini sudah terpasang pada barang lain (ID: ' . $tag->barang_id . ')! Lepas tag dari barang sebelumnya terlebih dahulu.']);
        }

        $tag->update([
            'barang_id' => $validated['barang_id'],
            'tag_type' => 'equipment_tag',
            'is_active' => true,
        ]);

        return redirect()->route('rfid.index')
            ->with('success', '✓ Tag RFID barang berhasil didaftarkan!');
    }

    /**
     * Deactivate RFID tag
     */
    public function deactivate($id)
    {
        $rfid = TagRfid::findOrFail($id);
        $rfid->update(['is_active' => false]);

        return redirect()->route('rfid.index')
            ->with('success', '✓ RFID tag berhasil dinonaktifkan!');
    }

    /**
     * Activate RFID tag
     */
    public function activate($id)
    {
        $rfid = TagRfid::findOrFail($id);
        $rfid->update(['is_active' => true]);

        return redirect()->route('rfid.index')
            ->with('success', '✓ RFID tag berhasil diaktifkan!');
    }

    /**
     * Delete RFID tag
     */
    public function destroy($id)
    {
        TagRfid::findOrFail($id)->delete();

        return redirect()->route('rfid.index')
            ->with('success', '✓ RFID tag berhasil dihapus!');
    }

    /**
     * Test RFID scanner - validate if UID exists
     */
    public function validateRfidUid(Request $request)
    {
        $validated = $request->validate([
            'uid' => 'required|string',
        ]);

        $rfid = TagRfid::where('uid', $validated['uid'])->first();

        if (!$rfid) {
            return response()->json([
                'valid' => false,
                'message' => '❌ RFID tidak terdaftar dalam database!',
                'type' => 'user_card',
            ], 404);
        }

        if (!$rfid->is_active) {
            return response()->json([
                'valid' => false,
                'message' => '⚠️ RFID ini sudah dinonaktifkan!',
                'type' => $rfid->tag_type,
            ], 403);
        }

        $data = [
            'valid' => true,
            'uid' => $rfid->uid,
            'type' => $rfid->tag_type,
            'is_active' => $rfid->is_active,
            'is_assigned' => $rfid->barang_id !== null,
        ];

        if ($rfid->tag_type === 'user_card' && $rfid->user) {
            $data['user_name'] = $rfid->user->name;
            $data['user_id'] = $rfid->user->id;
            $data['card_holder_name'] = $rfid->card_holder_name;
        } elseif ($rfid->tag_type === 'equipment_tag' && $rfid->barang) {
            $data['barang_name'] = $rfid->barang->name;
            $data['barang_id'] = $rfid->barang->id;
        }

        return response()->json($data, 200);
    }
}
