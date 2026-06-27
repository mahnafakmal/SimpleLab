<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Barang;
use App\Models\Peminjaman;

class EquipmentReturnedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $barang;
    protected $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Barang $barang, Peminjaman $loan)
    {
        $this->barang = $barang;
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
            ->subject('Konfirmasi Pengembalian Barang - SimpleLab')
            ->greeting("Halo {$notifiable->name},")
            ->line("Barang berikut telah berhasil dikembalikan:")
            ->line("**Barang:** {$this->barang->name}")
            ->line("**Kategori:** {$this->barang->kategori}")
            ->line("**Tanggal Pengembalian:** {$this->loan->returned_at->format('d/m/Y H:i')}")
            ->line("**Durasi Peminjaman:** {$this->loan->started_at->diffInDays($this->loan->returned_at)} hari")
            ->action('Lihat Detail', url('/dashboard'))
            ->line('Terima kasih telah menggunakan SimpleLab!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'equipment_returned',
            'barang_id' => $this->barang->id,
            'barang_name' => $this->barang->name,
            'loan_id' => $this->loan->id,
            'returned_at' => $this->loan->returned_at->toIso8601String(),
            'message' => "Barang {$this->barang->name} berhasil dikembalikan",
        ];
    }
}
