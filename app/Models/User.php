<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function rfidCards()
    {
        return $this->hasMany(RfidCard::class);
    }

    // Get active loans for user
    public function getActiveLoans()
    {
        return $this->peminjaman()->where('status', 'active')->get();
    }

    // Get overdue loans
    public function getOverdueLoans()
    {
        return $this->getActiveLoans()->filter(function ($loan) {
            return $loan->isOverdue();
        });
    }

    // Check if user has any overdue items
    public function hasOverdueItems(): bool
    {
        return $this->getOverdueLoans()->count() > 0;
    }
}
