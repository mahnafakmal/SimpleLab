<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalLab extends Model
{
    protected $fillable = [
        'hari',
        'mata_kuliah',
        'jam_mulai',
        'jam_selesai',
        'dosen',
        'kelas',
    ];
}
