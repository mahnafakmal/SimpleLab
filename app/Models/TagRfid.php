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
        'user_id',
        'rfid_card_id',
        'tag_type',
        'card_holder_name',
        'notes',
        'is_active',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rfidCard()
    {
        return $this->belongsTo(RfidCard::class, 'rfid_card_id');
    }

    // Check if RFID tag is valid/registered
    public static function isValidTag($uid): bool
    {
        return self::where('uid', $uid)->exists();
    }

    // Get barang by RFID UID
    public static function getBarangByUid($uid)
    {
        return self::where('uid', $uid)->with('barang')->first()?->barang;
    }

    // Check if RFID belongs to registered user equipment
    public static function isRegisteredEquipment($uid): bool
    {
        return self::where('uid', $uid)->whereNotNull('barang_id')->exists();
    }
}
