<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAkses extends Model
{
    use HasFactory;

    protected $table = 'log_akses';

    protected $fillable = [
        'user_id',
        'rfid_card_id',
        'action',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rfidCard()
    {
        return $this->belongsTo(RfidCard::class);
    }
}
