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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->references('id')->on('proyek')->onDelete('cascade');
            $table->enum('jenis', ['surat_jalan', 'bast', 'laporan_teknis', 'foto', 'otdr', 'lainnya'])->default('lainnya');
            $table->text('nama_file');
            $table->text('url_file');
            $table->date('tanggal_upload');
            $table->text('catatan')->nullable();
            $table->foreignId('diunggah_oleh')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
