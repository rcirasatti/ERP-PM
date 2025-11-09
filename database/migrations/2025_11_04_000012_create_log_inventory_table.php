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
        Schema::create('log_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->enum('jenis', ['masuk', 'keluar'])->default('masuk');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->foreignId('proyek_id')->nullable()->references('id')->on('proyek')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_inventory');
    }
};
