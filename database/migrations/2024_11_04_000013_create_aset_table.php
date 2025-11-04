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
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            $table->enum('jenis', ['alat', 'kendaraan', 'perlengkapan'])->default('alat');
            $table->enum('kondisi', ['baik', 'perawatan', 'rusak'])->default('baik');
            $table->foreignId('pemegang')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->date('tanggal_pembelian');
            $table->decimal('harga_pembelian', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
