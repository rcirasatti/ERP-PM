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
        
        // Drop and recreate item_penawaran with correct structure
        Schema::dropIfExists('item_penawaran');
        
        Schema::create('item_penawaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penawaran_id')->constrained('penawaran')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_asli', 15, 2); // Harga dari material
            $table->decimal('persentase_margin', 5, 2)->default(0); // Margin dalam %
            $table->decimal('harga_jual', 15, 2); // Harga setelah margin
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
        Schema::dropIfExists('item_penawaran');
    }
};
