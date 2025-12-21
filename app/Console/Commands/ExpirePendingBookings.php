<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'booking:expire-pending')]
class ExpirePendingBookings extends Command
{
    protected $signature = 'booking:expire-pending {--minutes= : Override the pending lifetime in minutes}';

    protected $description = 'Cancel pending bookings that have exceeded the allowed confirmation window.';

    public function handle(): int
    {
        $configuredMinutes = (int) config('booking.pending_expiry_minutes', 120);
        $minutes = (int) ($this->option('minutes') ?: $configuredMinutes);
        $minutes = $minutes > 0 ? $minutes : $configuredMinutes;

        $cutoff = Carbon::now()->subMinutes($minutes);

        $expiredCount = 0;

        Booking::query()
            ->with('transaksi')
            ->where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use (&$expiredCount) {
                foreach ($bookings as $booking) {
                    DB::transaction(function () use ($booking, &$expiredCount) {
                        $booking->update(['status' => 'cancelled']);

                        if ($booking->transaksi && $booking->transaksi->status_pembayaran === 'menunggu_verifikasi') {
                            $booking->transaksi->update(['status_pembayaran' => 'menunggu_verifikasi']);
                        }

                        $expiredCount++;

                        Log::info('Auto-expired booking', [
                            'booking_id' => $booking->id,
                            'booking_code' => $booking->booking_code,
                            'expired_at' => now()->toDateTimeString(),
                        ]);
                    });
                }
            });

        if ($expiredCount === 0) {
            $this->info('No pending bookings to expire.');
        } else {
            $this->info("Expired {$expiredCount} pending booking(s).");
        }

        return self::SUCCESS;
    }
}
