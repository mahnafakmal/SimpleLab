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

    // Get borrowing frequency
    public function getBorrowingCount(): int
    {
        return $this->peminjaman()->whereIn('status', ['active', 'returned'])->count();
    }

    // Get total borrow time in days
    public function getTotalBorrowDays(): int
    {
        return $this->peminjaman()
            ->whereNotNull('returned_at')
            ->get()
            ->sum(function ($p) {
                return $p->returned_at->diffInDays($p->started_at);
            });
    }

    // Check if item is available
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    // Get active loan for this item
    public function getActiveLoan()
    {
        return $this->peminjaman()->where('status', 'active')->first();
    }

    // Get overdue loans
    public function hasOverdueLoan(): bool
    {
        $active = $this->getActiveLoan();
        return $active && $active->isOverdue();
    }
}
