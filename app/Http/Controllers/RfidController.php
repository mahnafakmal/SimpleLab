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

class RfidController extends Controller
{
    public function registerBarang(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'rfid_uid' => 'required|string|max:255|unique:tag_rfids,uid',
        ]);

        $barang = Barang::create([
            'name' => $request->name,
            'kategori' => $request->kategori,
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        TagRfid::create([
            'uid' => $request->rfid_uid,
            'barang_id' => $barang->id,
        ]);

        RiwayatLog::create([
            'event' => 'Registrasi Tag RFID Barang',
            'detail' => sprintf('Barang "%s" dengan RFID %s didaftarkan.', $barang->name, $request->rfid_uid),
        ]);

        return redirect('/')->with('success', 'Barang dan Tag RFID berhasil didaftarkan.');
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
