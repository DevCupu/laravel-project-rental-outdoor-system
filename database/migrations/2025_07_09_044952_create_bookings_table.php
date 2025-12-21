<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyewa')->comment('Nama lengkap penyewa tanpa akun');
            $table->string('no_hp')->comment('Kontak WhatsApp penyewa');
            $table->date('tanggal_mulai')->comment('Tanggal pengambilan alat');
            $table->date('tanggal_selesai')->comment('Tanggal pengembalian alat');
            $table->enum('status', ['pending', 'disetujui', 'kembali', 'dibatalkan'])->default('pending')->comment('Status awal sebelum migrasi flow baru dijalankan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
