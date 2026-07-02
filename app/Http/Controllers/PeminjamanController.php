<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\JadwalLab;
use App\Models\TagRfid;
use App\Models\LogAkses;
use App\Models\RiwayatLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Display borrow form with available equipment
    public function showBorrowForm()
    {
        $barangs = Barang::where('status', 'available')->where('kondisi', 'Baik')->get();
        $activeLoans = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();
        return view('equipment.borrow', compact('barangs', 'activeLoans'));
    }

    // Display return form with active loans
    public function showReturnForm()
    {
        $activeLoans = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();
        return redirect('/equipment/borrow');
    }

    // Borrow equipment via Web form
    public function borrowAlat(Request $request)
    {
        // Only Mahasiswa (role 'user') may borrow directly
        if (! Auth::check() || Auth::user()?->role !== 'user') {
            abort(403, 'Only Mahasiswa may borrow equipment via this form.');
        }
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'waktu_mulai' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'waktu_selesai' => ['required', 'date_format:Y-m-d\TH:i', 'after:waktu_mulai'],
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $waktuMulai = Carbon::parse($request->waktu_mulai);
        $waktuSelesai = Carbon::parse($request->waktu_selesai);

        if ($barang->status !== 'available') {
            return back()->with('error', 'Alat lab ini sedang tidak tersedia atau sudah dipinjam.');
        }

        // Ensure a Tag RFID exists for this item to satisfy DB constraints
        $tag = TagRfid::where('barang_id', $barang->id)->first();
        if (!$tag) {
            $tag = TagRfid::create([
                'uid' => 'WEB-' . strtoupper(uniqid()),
                'barang_id' => $barang->id,
            ]);
        }

        // Update equipment status
        $barang->update(['status' => 'borrowed']);

        // Create loan record
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'tag_rfid_id' => $tag->id,
            'started_at' => $waktuMulai,
            'due_date' => $waktuSelesai,
            'status' => 'active',
        ]);

        // Create log entries
        LogAkses::create([
            'user_id' => Auth::id(),
            'action' => 'Peminjaman Online',
            'notes' => sprintf('User %s meminjam alat "%s" via portal web.', Auth::user()?->name, $barang->name),
        ]);

        RiwayatLog::create([
            'event' => 'Peminjaman Web',
            'detail' => sprintf('User %s meminjam barang %s.', Auth::user()?->name, $barang->name),
        ]);

        return back()->with('success', sprintf('Berhasil meminjam alat %s untuk periode %s sampai %s.', $barang->name, $waktuMulai->isoFormat('D MMM YYYY, HH:mm'), $waktuSelesai->isoFormat('D MMM YYYY, HH:mm')));
    }

    // Dosen requests borrowing: creates a pending peminjaman for admin approval
    public function borrowAlatDosen(Request $request)
    {
        if (! Auth::check() || Auth::user()?->role !== 'dosen') {
            abort(403, 'Only Dosen may request borrowing via this form.');
        }

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'waktu_mulai' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'waktu_selesai' => ['required', 'date_format:Y-m-d\TH:i', 'after:waktu_mulai'],
            'notes' => 'nullable|string|max:500'
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $waktuMulai = Carbon::parse($request->waktu_mulai);
        $waktuSelesai = Carbon::parse($request->waktu_selesai);

        if ($barang->status !== 'available') {
            return back()->with('error', 'Alat sedang tidak tersedia.');
        }

        // Create a pending peminjaman; do not set barang status yet
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'tag_rfid_id' => null,
            'started_at' => $waktuMulai,
            'due_date' => $waktuSelesai,
            'status' => 'pending',
        ]);

        RiwayatLog::create([
            'event' => 'Request Peminjaman Dosen',
            'detail' => sprintf('Dosen %s meminta peminjaman barang %s.', Auth::user()?->name, $barang->name),
        ]);

        return back()->with('success', 'Permintaan peminjaman telah dikirim. Tunggu persetujuan admin.');
    }

    // Return equipment via Web form
    public function returnAlat($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'active')
            ->findOrFail($id);

        $barang = $peminjaman->barang;

        // Update loan status
        $peminjaman->update([
            'ended_at' => now(),
            'status' => 'returned',
        ]);

        // Update equipment status
        $barang->update(['status' => 'available']);

        // Create log entries
        LogAkses::create([
            'user_id' => Auth::id(),
            'action' => 'Pengembalian Online',
            'notes' => sprintf('User %s mengembalikan alat "%s" via portal web.', Auth::user()?->name, $barang->name),
        ]);

        RiwayatLog::create([
            'event' => 'Pengembalian Web',
            'detail' => sprintf('User %s mengembalikan barang %s.', Auth::user()?->name, $barang->name),
        ]);

        return back()->with('success', sprintf('Alat lab %s telah berhasil dikembalikan.', $barang->name));
    }

    // Book Room via Web form with conflict checking against admin schedule & existing bookings
    public function borrowRuangan(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string|after:jam_mulai',
            'keperluan' => 'required|string|max:500',
        ]);

        // Convert day to Indonesian name to match schedule table
        $daysIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayNameEnglish = Carbon::parse($request->tanggal)->format('l');
        $hari = $daysIndonesian[$dayNameEnglish];

        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;

        // 1. Check for conflicts with the Admin's Weekly Lab Schedules (jadwal_labs)
        $overlappingSchedules = JadwalLab::where('hari', $hari)
            ->get()
            ->filter(function($schedule) use ($jamMulai, $jamSelesai) {
                // Time overlap: (start1 < end2) && (end1 > start2)
                return ($jamMulai < $schedule->jam_selesai) && ($jamSelesai > $schedule->jam_mulai);
            });

        if ($overlappingSchedules->isNotEmpty()) {
            $conflict = $overlappingSchedules->first();
            return back()->with('error', sprintf(
                'Konflik Jadwal! Ruangan tidak dapat dipinjam karena ada kegiatan kuliah/praktikum: "%s" (%s, pukul %s - %s).',
                $conflict->mata_kuliah,
                $hari,
                $conflict->jam_mulai,
                $conflict->jam_selesai
            ))->withInput();
        }

        // 2. Check for conflicts with existing Approved Room Bookings (peminjaman_ruangans)
        $overlappingBookings = PeminjamanRuangan::where('nama_ruangan', $request->nama_ruangan)
            ->where('tanggal', $request->tanggal)
            ->where('status', 'approved')
            ->get()
            ->filter(function($booking) use ($jamMulai, $jamSelesai) {
                return ($jamMulai < $booking->jam_selesai) && ($jamSelesai > $booking->jam_mulai);
            });

        if ($overlappingBookings->isNotEmpty()) {
            $conflict = $overlappingBookings->first();
            return back()->with('error', sprintf(
                'Ruangan sudah dipesan! Ruangan %s telah dibooking oleh %s pada tanggal %s (pukul %s - %s) untuk keperluan: "%s".',
                $request->nama_ruangan,
                $conflict->user->name,
                Carbon::parse($request->tanggal)->isoFormat('D MMM YYYY'),
                $conflict->jam_mulai,
                $conflict->jam_selesai,
                $conflict->keperluan
            ))->withInput();
        }

        // 3. Create booking if clean
        PeminjamanRuangan::create([
            'user_id' => Auth::id(),
            'nama_ruangan' => $request->nama_ruangan,
            'tanggal' => $request->tanggal,
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'keperluan' => $request->keperluan,
            'status' => 'approved',
        ]);

        // Create log entries
        LogAkses::create([
            'user_id' => Auth::id(),
            'action' => 'Pemesanan Ruangan',
            'notes' => sprintf('User %s membooking ruangan "%s" untuk tanggal %s.', Auth::user()?->name, $request->nama_ruangan, $request->tanggal),
        ]);

        RiwayatLog::create([
            'event' => 'Booking Ruang',
            'detail' => sprintf('User %s membooking ruang %s.', Auth::user()?->name, $request->nama_ruangan),
        ]);

        return back()->with('success', sprintf('Berhasil memesan %s untuk tanggal %s!', $request->nama_ruangan, Carbon::parse($request->tanggal)->isoFormat('D MMM YYYY')));
    }

    // Cancel Room Booking
    public function cancelRuangan($id)
    {
        $booking = PeminjamanRuangan::where('user_id', Auth::id())
            ->findOrFail($id);

        $namaRuangan = $booking->nama_ruangan;
        $booking->delete();

        return back()->with('success', sprintf('Pemesanan ruangan %s telah berhasil dibatalkan.', $namaRuangan));
    }
}
