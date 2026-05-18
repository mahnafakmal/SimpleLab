<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagRfid extends Model
{
    use HasFactory;

    protected $table = 'tag_rfids';

    protected $fillable = [
        'uid',
        'barang_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
