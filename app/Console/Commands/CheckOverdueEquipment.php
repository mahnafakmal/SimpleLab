<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\EquipmentOverdueNotification;

class CheckOverdueEquipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipment:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue equipment and send notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue equipment...');

        // Get all active loans that are overdue and not yet notified
        $overdueLoans = Peminjaman::where('status', 'active')
            ->where('due_date', '<', now())
            ->whereNull('overdue_notified_at')
            ->with(['user', 'barang'])
            ->get();

        $count = 0;
        foreach ($overdueLoans as $loan) {
            // Send notification to user (only if notifications table exists)
            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $loan->user->notify(new EquipmentOverdueNotification($loan->barang, $loan));
            }
            // mark as notified to avoid duplicate alerts
            $loan->overdue_notified_at = now();
            $loan->save();
            $count++;
        }

        $this->info("✓ Notifikasi keterlambatan dikirim ke {$count} peminjam");

        return Command::SUCCESS;
    }
}
