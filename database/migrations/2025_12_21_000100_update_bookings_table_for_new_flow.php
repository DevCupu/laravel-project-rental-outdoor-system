<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEGACY_STATUSES = ['pending', 'disetujui', 'kembali', 'dibatalkan'];
    private const NEW_STATUSES = ['pending', 'confirmed', 'picked_up', 'returned', 'cancelled'];

    private const LEGACY_TO_NEW = [
        'disetujui' => 'confirmed',
        'kembali' => 'returned',
        'dibatalkan' => 'cancelled',
    ];

    private const NEW_TO_LEGACY = [
        'picked_up' => 'disetujui',
        'confirmed' => 'disetujui',
        'returned' => 'kembali',
        'cancelled' => 'dibatalkan',
    ];

    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'booking_code')) {
                $table->string('booking_code')->nullable()->after('id')->comment('Kode booking unik untuk komunikasi WA');
            }

            if (!Schema::hasColumn('bookings', 'total_hari')) {
                $table->unsignedInteger('total_hari')->default(1)->after('tanggal_selesai')->comment('Durasi sewa dalam hari');
            }

            if (!Schema::hasColumn('bookings', 'total_harga')) {
                $table->unsignedBigInteger('total_harga')->default(0)->after('total_hari')->comment('Total harga dihitung otomatis');
            }
        });

        $this->allowStatuses(array_merge(self::LEGACY_STATUSES, self::NEW_STATUSES));
        $this->replaceStatuses(self::LEGACY_TO_NEW);
        $this->allowStatuses(self::NEW_STATUSES);
    }

    public function down(): void
    {
        $this->allowStatuses(array_merge(self::LEGACY_STATUSES, self::NEW_STATUSES));
        $this->replaceStatuses(self::NEW_TO_LEGACY);
        $this->allowStatuses(self::LEGACY_STATUSES);

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'booking_code')) {
                $table->dropColumn('booking_code');
            }
            if (Schema::hasColumn('bookings', 'total_hari')) {
                $table->dropColumn('total_hari');
            }
            if (Schema::hasColumn('bookings', 'total_harga')) {
                $table->dropColumn('total_harga');
            }
        });
    }

    private function allowStatuses(array $statuses): void
    {
        $uniqueStatuses = array_values(array_unique($statuses));
        $enumValues = "'" . implode("','", $uniqueStatuses) . "'";

        DB::statement("ALTER TABLE bookings MODIFY status ENUM({$enumValues}) NOT NULL DEFAULT 'pending'");
    }

    private function replaceStatuses(array $mapping): void
    {
        foreach ($mapping as $from => $to) {
            DB::table('bookings')->where('status', $from)->update(['status' => $to]);
        }
    }
};
