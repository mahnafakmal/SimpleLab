<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanLab extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_labs';

    protected $fillable = [
        'user_id',
        'nama_lab',
        'keperluan',
        'tanggal_pinjam',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan_admin'
    ];

    /**
     * Relasi ke model User (Siapa yang meminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}