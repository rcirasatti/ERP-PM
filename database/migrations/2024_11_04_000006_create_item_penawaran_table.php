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
        Schema::create('item_penawaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penawaran_id')->references('id')->on('penawaran')->onDelete('cascade');
            $table->foreignId('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('persentase_margin', 5, 2);
            $table->decimal('harga_jual', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penawaran');
    }
};
