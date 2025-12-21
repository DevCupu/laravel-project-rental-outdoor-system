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
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat')->comment('Nama peralatan untuk tampilan publik');
            $table->string('slug')->unique()->nullable()->comment('Slug opsional untuk URL detail alat');
            $table->string('kategori')->nullable()->comment('Kategori peralatan (tenda, matras, dll)');
            $table->text('deskripsi')->nullable()->comment('Deskripsi singkat fitur peralatan');
            $table->unsignedBigInteger('harga_sewa_per_hari')->comment('Tarif sewa per hari dalam rupiah');
            $table->unsignedInteger('stok_total')->comment('Jumlah unit siap sewa di gudang');
            $table->string('foto_path')->nullable()->comment('Lokasi file foto utama peralatan');
            $table->boolean('is_active')->default(true)->comment('Penanda alat masih ditampilkan di katalog');
            $table->timestamps();

            $table->index('nama_alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
