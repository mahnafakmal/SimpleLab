<?php

namespace App\Http\Controllers;

use App\Models\JadwalLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Show laboratory schedules
     */
    public function index()
    {
        $todaySchedules = JadwalLab::getToday();
        $upcomingSchedules = JadwalLab::getUpcoming(14);

        return view('schedule.index', [
            'todaySchedules' => $todaySchedules,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }

    /**
     * Show admin schedule management page
     */
    public function adminPage()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengelola jadwal.');
        }

        return view('schedule.admin');
    }

    /**
     * Get schedules as JSON (for AJAX/Calendar)
     */
    public function getSchedules()
    {
        $schedules = JadwalLab::orderBy('hari')->orderBy('jam_mulai')->get();

        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'title' => "{$schedule->mata_kuliah} ({$schedule->kelas})",
                'day' => $schedule->getDayName(),
                'time' => $schedule->getTimeRange(),
                'instructor' => $schedule->dosen,
                'room' => $schedule->ruangan ?? 'N/A',
                'capacity' => $schedule->kapasitas ?? 'N/A',
                'hari' => $schedule->hari,
                'mata_kuliah' => $schedule->mata_kuliah,
                'kelas' => $schedule->kelas,
                'jam_mulai' => substr($schedule->jam_mulai, 0, 5),
                'jam_selesai' => substr($schedule->jam_selesai, 0, 5),
                'ruangan' => $schedule->ruangan,
                'kapasitas' => $schedule->kapasitas,
            ];
        });

        return response()->json([
            'success' => true,
            'schedules' => $formattedSchedules
        ]);
    }

    /**
     * Create new schedule (Admin)
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'hari' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'mata_kuliah' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'dosen' => 'required|string',
            'kelas' => 'required|string',
            'ruangan' => 'nullable|string',
            'kapasitas' => 'nullable|integer|min:1',
        ]);

        $schedule = JadwalLab::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'schedule' => $schedule
        ]);
    }

    /**
     * Update schedule (Admin)
     */
    public function update(Request $request, $id)
    {
        $this->authorize('manage-schedule');

        $schedule = JadwalLab::findOrFail($id);

        $validated = $request->validate([
            'hari' => 'sometimes|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'mata_kuliah' => 'sometimes|string',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i',
            'dosen' => 'sometimes|string',
            'kelas' => 'sometimes|string',
            'ruangan' => 'sometimes|nullable|string',
            'kapasitas' => 'sometimes|nullable|integer|min:1',
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'schedule' => $schedule
        ]);
    }

    /**
     * Delete schedule (Admin)
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $schedule = JadwalLab::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}
