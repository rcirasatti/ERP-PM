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
        Schema::table('penawaran', function (Blueprint $table) {
            $table->decimal('ppn', 15, 2)->default(0)->after('total_margin')->comment('PPN 11% value');
            $table->decimal('grand_total_with_ppn', 15, 2)->default(0)->after('ppn')->comment('Grand total including 11% PPN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            $table->dropColumn(['ppn', 'grand_total_with_ppn']);
        });
    }
};
