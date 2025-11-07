<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete duplicate inventory records, keeping the one with highest id for each material
        DB::statement('
            DELETE i1 FROM inventories i1
            JOIN (
                SELECT material_id, MAX(id) as max_id FROM inventories
                GROUP BY material_id
                HAVING COUNT(*) > 1
            ) i2 ON i1.material_id = i2.material_id
            WHERE i1.id != i2.max_id
        ');

        Schema::table('inventories', function (Blueprint $table) {
            $table->unique('material_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUnique(['material_id']);
        });
    }
};
