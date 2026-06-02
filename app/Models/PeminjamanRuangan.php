<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_ruangan',
        'tanggal',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
