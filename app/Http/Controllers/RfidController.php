<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogAkses;
use App\Models\Peminjaman;
use App\Models\RfidCard;
use App\Models\RiwayatLog;
use App\Models\TagRfid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RfidController extends Controller
{
    public function registerBarang(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mendaftarkan barang.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'rfid_uid' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $tag = TagRfid::where('uid', $request->rfid_uid)->first();

        if (! $tag) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Kode RFID belum terdaftar di database! Hubungi admin untuk mendaftarkan tag RFID terlebih dahulu.');
        }

        if (! $tag->is_active) {
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ RFID ini sudah dinonaktifkan! Hubungi admin untuk mengaktifkannya.');
        }

        if ($tag->barang_id) {
            return redirect()->back()
                ->withInput()
                ->with('error', '⚠️ RFID ini sudah terpasang pada barang lain! Lepas tag dari barang sebelumnya terlebih dahulu.');
        }

        $data = [
            'name' => $request->name,
            'kategori' => $request->kategori,
            'kondisi' => 'Baik',
            'status' => 'available',
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $dest = public_path('images/barangs');
            if (! file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $data['image'] = 'images/barangs/' . $filename;
        }

        $barang = Barang::create($data);

        $tag->update([
            'barang_id' => $barang->id,
            'tag_type' => 'equipment_tag',
        ]);

        RiwayatLog::create([
            'event' => 'Registrasi Tag RFID Barang',
            'detail' => sprintf('Barang "%s" dengan RFID %s didaftarkan.', $barang->name, $request->rfid_uid),
        ]);

        return redirect('/')->with('success', 'Barang dan Tag RFID berhasil didaftarkan.');
    }

    public function editBarang($id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit barang.');
        }

        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function updateBarang(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui barang.');
        }

        $barang = Barang::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'kondisi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'kategori', 'kondisi', 'status']);

        if ($request->hasFile('image')) {
            // remove old image if exists
            if ($barang->image && file_exists(public_path($barang->image))) {
                @unlink(public_path($barang->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $dest = public_path('images/barangs');
            if (! file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $data['image'] = 'images/barangs/' . $filename;
        }

        $barang->update($data);

        RiwayatLog::create([
            'event' => 'Update Barang',
            'detail' => sprintf('Barang "%s" diperbarui oleh user.', $barang->name),
        ]);

        return redirect('/')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function deleteBarang(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus barang.');
        }

        $barang = Barang::findOrFail($id);

        // remove image file if exists
        if ($barang->image && file_exists(public_path($barang->image))) {
            @unlink(public_path($barang->image));
        }

        $name = $barang->name;
        $barang->delete();

        RiwayatLog::create([
            'event' => 'Hapus Barang',
            'detail' => sprintf('Barang "%s" dihapus oleh user.', $name),
        ]);

        return redirect('/')->with('success', sprintf('Barang "%s" berhasil dihapus.', $name));
    }

    public function registerUserCard(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_uid' => 'required|string|max:255|unique:rfid_cards,uid',
        ]);

        $card = RfidCard::create([
            'uid' => $request->card_uid,
            'user_id' => $request->user_id,
        ]);

        RiwayatLog::create([
            'event' => 'Registrasi Kartu RFID User',
            'detail' => sprintf('User ID %d terpasang kartu RFID %s.', $request->user_id, $request->card_uid),
        ]);

        return redirect('/')->with('success', 'Kartu RFID user berhasil didaftarkan.');
    }

    public function authenticateUser(Request $request)
    {
        $request->validate([
            'card_uid' => 'required|string|max:255',
            'action' => 'required|in:akses,peminjaman',
        ]);

        $card = RfidCard::where('uid', $request->card_uid)->first();

        if (! $card || ! $card->user) {
            return redirect('/')->with('error', 'Kartu RFID tidak dikenali.');
        }

        $actionLabel = $request->action === 'akses' ? 'Akses Masuk Lab' : 'Persiapan Peminjaman';

        LogAkses::create([
            'user_id' => $card->user_id,
            'rfid_card_id' => $card->id,
            'action' => $actionLabel,
            'notes' => sprintf('Autentikasi RFID oleh %s.', $card->user->name),
        ]);

        RiwayatLog::create([
            'event' => 'Autentikasi Kartu RFID User',
            'detail' => sprintf('User %s berhasil melakukan %s dengan kartu RFID %s.', $card->user->name, $actionLabel, $card->uid),
        ]);

        return redirect('/')->with('success', sprintf('User %s terautentikasi untuk %s.', $card->user->name, $actionLabel));
    }

    public function trackAsset(Request $request)
    {
        $request->validate([
            'tag_uid' => 'required|string|max:255',
            'lokasi' => 'required|in:masuk,keluar',
        ]);

        $tag = TagRfid::where('uid', $request->tag_uid)->with('barang')->first();

        if (! $tag || ! $tag->barang) {
            return redirect('/')->with('error', 'Tag RFID barang tidak terdaftar.');
        }

        $status = $request->lokasi === 'keluar' ? 'borrowed' : 'available';
        $tag->barang->update(['status' => $status]);

        LogAkses::create([
            'action' => 'Tracking Aset',
            'notes' => sprintf('Barang %s (%s) dilacak sebagai %s.', $tag->barang->name, $tag->uid, $request->lokasi),
        ]);

        RiwayatLog::create([
            'event' => 'Tracking Lokasi Aset',
            'detail' => sprintf('Tag RFID %s melaporkan barang %s pada lokasi %s.', $tag->uid, $tag->barang->name, $request->lokasi),
        ]);

        return redirect('/')->with('success', sprintf('Aset %s terupdate sebagai %s.', $tag->barang->name, $status));
    }

    public function borrowAsset(Request $request)
    {
        $request->validate([
            'card_uid' => 'required|string|max:255',
            'tag_uid' => 'required|string|max:255',
        ]);

        $card = RfidCard::where('uid', $request->card_uid)->first();
        $tag = TagRfid::where('uid', $request->tag_uid)->with('barang')->first();

        if (! $card || ! $card->user) {
            return redirect('/')->with('error', 'Kartu RFID user tidak dikenali.');
        }

        if (! $tag || ! $tag->barang) {
            return redirect('/')->with('error', 'Tag RFID barang tidak dikenali.');
        }

        $tag->barang->update(['status' => 'borrowed']);

        Peminjaman::create([
            'user_id' => $card->user_id,
            'barang_id' => $tag->barang->id,
            'tag_rfid_id' => $tag->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        LogAkses::create([
            'user_id' => $card->user_id,
            'rfid_card_id' => $card->id,
            'action' => 'Peminjaman Aset',
            'notes' => sprintf('User %s meminjam barang %s melalui RFID.', $card->user->name, $tag->barang->name),
        ]);

        RiwayatLog::create([
            'event' => 'Peminjaman RFID',
            'detail' => sprintf('User %s meminjam barang %s dengan tag %s.', $card->user->name, $tag->barang->name, $tag->uid),
        ]);

        return redirect('/')->with('success', sprintf('Peminjaman %s oleh %s berhasil.', $tag->barang->name, $card->user->name));
    }
}
