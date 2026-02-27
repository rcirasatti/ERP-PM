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
        Schema::table('item_penawaran', function (Blueprint $table) {
            // Make material_id nullable for BoQ items that don't link to materials
            $table->unsignedBigInteger('material_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_penawaran', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id')->nullable(false)->change();
        });
    }
};
