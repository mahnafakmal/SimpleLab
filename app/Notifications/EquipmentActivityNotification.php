<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Barang;
use App\Models\Peminjaman;

class EquipmentActivityNotification extends Notification
{
    use Queueable;

    protected $barang;
    protected $loan;
    protected $action; // 'borrowed'|'returned'|'requested'

    public function __construct(Barang $barang, Peminjaman $loan = null, string $action = 'borrowed')
    {
        $this->barang = $barang;
        $this->loan = $loan;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $loanId = $this->loan ? $this->loan->id : null;
        $message = '';
        if ($this->action === 'borrowed') {
            $message = "Anda berhasil meminjam barang {$this->barang->name}.";
        } elseif ($this->action === 'requested') {
            $message = "Permintaan peminjaman barang {$this->barang->name} telah dikirim untuk persetujuan admin.";
        } elseif ($this->action === 'cancelled') {
            $message = "Permintaan peminjaman untuk barang {$this->barang->name} dibatalkan.";
        } else {
            $message = "Anda telah mengembalikan barang {$this->barang->name}.";
        }

        return [
            'type' => 'equipment_activity',
            'action' => $this->action,
            'barang_id' => $this->barang->id,
            'barang_name' => $this->barang->name,
            'barang_image' => $this->getImageUrl(),
            'loan_id' => $loanId,
            'message' => $message,
        ];
    }

    protected function getImageUrl(): string
    {
        $filename = $this->barang->image ?? null;
        if ($filename && file_exists(public_path('images/barangs/' . $filename))) {
            return asset('images/barangs/' . $filename);
        }
        return asset('images/barangs/logo-unimus.png');
    }
}
