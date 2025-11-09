<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop tables in reverse order of creation
        Schema::dropIfExists('perawatan_aset');
        Schema::dropIfExists('aset');
        Schema::dropIfExists('log_inventory');
        Schema::dropIfExists('dokumen');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('proyek_budget');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('item_penawaran');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('proyek');
        Schema::dropIfExists('penawaran');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('profil');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
