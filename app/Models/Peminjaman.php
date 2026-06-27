<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'barang_id',
        'tag_rfid_id',
        'started_at',
        'ended_at',
        'returned_at',
        'status',
        'due_date',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function tagRfid()
    {
        return $this->belongsTo(TagRfid::class, 'tag_rfid_id');
    }

    // Check if item is overdue
    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->due_date && $this->due_date < now();
    }

    // Get days overdue
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }

    // Mark as returned
    public function markReturned()
    {
        $this->status = 'returned';
        $this->returned_at = now();
        $this->save();
    }

    // Check if returned item matches borrow record
    public function validateReturn($barangId): bool
    {
        return $this->barang_id == $barangId && $this->status === 'active';
    }
}
