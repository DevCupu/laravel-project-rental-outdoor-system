<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LEGACY_STATUSES = ['belum_bayar', 'sudah_bayar'];
    private const NEW_STATUSES = ['menunggu_verifikasi', 'terverifikasi'];

    public function up(): void
    {
        $this->allowStatuses(array_merge(self::LEGACY_STATUSES, self::NEW_STATUSES), 'menunggu_verifikasi');

        DB::table('transaksis')->where('status_pembayaran', 'belum_bayar')->update(['status_pembayaran' => 'menunggu_verifikasi']);
        DB::table('transaksis')->where('status_pembayaran', 'sudah_bayar')->update(['status_pembayaran' => 'terverifikasi']);

        $this->allowStatuses(self::NEW_STATUSES, 'menunggu_verifikasi');
    }

    public function down(): void
    {
        $this->allowStatuses(array_merge(self::LEGACY_STATUSES, self::NEW_STATUSES), 'menunggu_verifikasi');

        DB::table('transaksis')->where('status_pembayaran', 'menunggu_verifikasi')->update(['status_pembayaran' => 'belum_bayar']);
        DB::table('transaksis')->where('status_pembayaran', 'terverifikasi')->update(['status_pembayaran' => 'sudah_bayar']);

        $this->allowStatuses(self::LEGACY_STATUSES, 'belum_bayar');
    }

    private function allowStatuses(array $statuses, string $default): void
    {
        $uniqueStatuses = array_values(array_unique($statuses));
        $enumValues = "'" . implode("','", $uniqueStatuses) . "'";

        DB::statement("ALTER TABLE transaksis MODIFY status_pembayaran ENUM({$enumValues}) NOT NULL DEFAULT '{$default}'");
    }
};
