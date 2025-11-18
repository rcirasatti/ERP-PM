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
        // Update existing proyek_budget to use grand_total_with_ppn from penawaran
        DB::statement('
            UPDATE proyek_budget pb
            INNER JOIN proyek p ON pb.proyek_id = p.id
            INNER JOIN penawaran pw ON p.penawaran_id = pw.id
            SET pb.jumlah_rencana = (pw.total_biaya + pw.total_margin) * 1.11
            WHERE pw.grand_total_with_ppn > 0
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old calculation (grand_total without PPN)
        DB::statement('
            UPDATE proyek_budget pb
            INNER JOIN proyek p ON pb.proyek_id = p.id
            INNER JOIN penawaran pw ON p.penawaran_id = pw.id
            SET pb.jumlah_rencana = (pw.total_biaya + pw.total_margin)
        ');
    }
};
