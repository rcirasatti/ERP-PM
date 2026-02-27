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
            $table->string('nama')->nullable()->after('material_id')->comment('Nama item dari BoQ');
            $table->string('satuan')->nullable()->after('nama')->comment('Satuan item dari BoQ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_penawaran', function (Blueprint $table) {
            $table->dropColumn(['nama', 'satuan']);
        });
    }
};
