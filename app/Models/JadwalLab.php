<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalLab extends Model
{
    protected $fillable = [
        'hari',
        'mata_kuliah',
        'jam_mulai',
        'jam_selesai',
        'dosen',
        'kelas',
        'ruangan',
        'kapasitas',
    ];

    // Get day name in Indonesian
    public function getDayName(): string
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        return $days[$this->hari] ?? $this->hari;
    }

    // Check if schedule is today
    public function isToday(): bool
    {
        return $this->hari === now()->format('l');
    }

    // Get today's schedules
    public static function getToday()
    {
        $today = now()->format('l');
        return self::where('hari', $today)->orderBy('jam_mulai')->get();
    }

    // Get upcoming schedules
    public static function getUpcoming($days = 7)
    {
        return self::orderBy('hari')->orderBy('jam_mulai')->limit($days)->get();
    }

    // Format time display
    public function getTimeRange(): string
    {
        return "{$this->jam_mulai} - {$this->jam_selesai}";
    }
}
