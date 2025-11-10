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
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Drop dependent tables first
        Schema::dropIfExists('item_penawaran');
        
        // Drop the corrupted table if it exists
        Schema::dropIfExists('penawaran');
        
        // Recreate penawaran table with proper schema
        Schema::create('penawaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_penawaran')->unique();
            $table->foreignId('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['draft', 'disetujui', 'ditolak', 'dibatalkan'])->default('draft');
            $table->decimal('total_margin', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->timestamps();
        });
        
        // Re-create item_penawaran table
        Schema::create('item_penawaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penawaran_id')->references('id')->on('penawaran')->onDelete('cascade');
            $table->foreignId('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_asli', 15, 2);
            $table->decimal('persentase_margin', 5, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->timestamps();
        });
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('penawaran');
        Schema::enableForeignKeyConstraints();
    }
};
