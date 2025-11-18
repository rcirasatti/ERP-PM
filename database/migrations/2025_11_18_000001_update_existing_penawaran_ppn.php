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
        // Update existing penawaran rows to calculate ppn and grand_total_with_ppn
        DB::statement('
            UPDATE penawaran 
            SET 
                ppn = (total_biaya + total_margin) * 0.11,
                grand_total_with_ppn = (total_biaya + total_margin) * 1.11
            WHERE ppn = 0 OR grand_total_with_ppn = 0
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset back to 0
        DB::statement('
            UPDATE penawaran 
            SET 
                ppn = 0,
                grand_total_with_ppn = 0
        ');
    }
};
