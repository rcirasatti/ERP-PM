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
        Schema::create('perawatan_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->references('id')->on('aset')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('kondisi', ['baik', 'perawatan', 'rusak'])->default('baik');
            $table->text('catatan')->nullable();
            $table->decimal('biaya', 15, 2);
            $table->foreignId('pemegang')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('dibuat_oleh')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan_aset');
    }
};
