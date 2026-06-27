<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Barang;
use App\Models\Peminjaman;

class EquipmentOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $barang;
    protected $loan;
    protected $daysOverdue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Barang $barang, Peminjaman $loan)
    {
        $this->barang = $barang;
        $this->loan = $loan;
        $this->daysOverdue = $loan->getDaysOverdue();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Peringatan: Barang Terlambat Dikembalikan - SimpleLab')
            ->greeting("Halo {$notifiable->name},")
            ->line("Anda memiliki barang yang terlambat dikembalikan:")
            ->line("")
            ->line("**Barang:** {$this->barang->name}")
            ->line("**Kategori:** {$this->barang->kategori}")
            ->line("**Tenggat Waktu:** {$this->loan->due_date->format('d/m/Y')}")
            ->line("**Terlambat:** {$this->daysOverdue} hari")
            ->line("")
            ->action('Kembalikan Sekarang', url('/equipment/return'))
            ->line('Mohon segera mengembalikan barang. Keterlambatan peminjaman dapat menghambat pengguna lain.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'equipment_overdue',
            'barang_id' => $this->barang->id,
            'barang_name' => $this->barang->name,
            'loan_id' => $this->loan->id,
            'due_date' => $this->loan->due_date->toIso8601String(),
            'days_overdue' => $this->daysOverdue,
            'message' => "Barang {$this->barang->name} terlambat {$this->daysOverdue} hari",
            'severity' => $this->daysOverdue > 7 ? 'critical' : 'warning',
        ];
    }
}
