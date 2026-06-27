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

        // Get all active loans that are overdue
        $overdueLoans = Peminjaman::where('status', 'active')
            ->where('due_date', '<', now())
            ->with(['user', 'barang'])
            ->get();

        $count = 0;
        foreach ($overdueLoans as $loan) {
            // Send notification to user
            $loan->user->notify(new EquipmentOverdueNotification($loan->barang, $loan));
            $count++;
        }

        $this->info("✓ Notifikasi keterlambatan dikirim ke {$count} peminjam");

        return Command::SUCCESS;
    }
}
