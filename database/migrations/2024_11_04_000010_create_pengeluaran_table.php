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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->references('id')->on('proyek')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('kategori', ['material', 'gaji', 'bahan_bakar', 'peralatan', 'lainnya'])->default('lainnya');
            $table->text('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->foreignId('dibuat_oleh')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
