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
        Schema::table('materials', function (Blueprint $table) {
            // Buat supplier_id nullable (untuk jasa, tol, dll yang tidak punya supplier)
            $table->foreignId('supplier_id')->nullable()->change();
            
            // Tambah kolom type: BARANG, JASA, TOL, LAINNYA
            $table->enum('type', ['BARANG', 'JASA', 'TOL', 'LAINNYA'])->default('BARANG')->after('nama');
            
            // Tambah flag untuk track inventory (hanya BARANG yang track_inventory=true)
            $table->boolean('track_inventory')->default(true)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['type', 'track_inventory']);
            $table->foreignId('supplier_id')->nullable(false)->change();
        });
    }
};
