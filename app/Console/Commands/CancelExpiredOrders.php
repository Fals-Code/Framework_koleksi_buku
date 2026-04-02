<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pesanan;
use Illuminate\Support\Carbon;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan pesanan pending yang sudah melewati batas waktu (15 menit)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Pesanan pending yang lebih tua dari 15 menit dianggap expired
        $expiredTime = Carbon::now()->subMinutes(15);

        $orders = Pesanan::where('status', 'pending')
                        ->where('created_at', '<', $expiredTime)
                        ->get();

        $count = $orders->count();

        foreach ($orders as $order) {
            $order->update(['status' => 'cancelled']);
            $this->info("Pesanan #{$order->nomor_pesanan} telah dibatalkan karena kedaluwarsa.");
        }

        if ($count > 0) {
            $this->info("Total {$count} pesanan dibatalkan.");
        } else {
            $this->info("Tidak ada pesanan pending yang kedaluwarsa.");
        }
    }
}
