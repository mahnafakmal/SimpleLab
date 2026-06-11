<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kategori',
        'kondisi',
        'status',
        'image',
    ];

    public function tagRfid()
    {
        return $this->hasOne(TagRfid::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class);
    }
}
