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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->comment('Relasi ke header booking');
            $table->foreignId('alat_id')->constrained()->onDelete('cascade')->comment('Alat yang disewa');
            $table->integer('jumlah')->comment('Jumlah unit yang dipinjam');
            $table->integer('subtotal')->comment('Subtotal harga alat x jumlah x hari');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
