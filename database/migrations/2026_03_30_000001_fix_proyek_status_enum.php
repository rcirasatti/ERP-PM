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
        Schema::table('proyek', function (Blueprint $table) {
            // Expand status enum to include all missing values
            // was: 'baru', 'instalasi', 'selesai'
            // now: 'baru', 'survei', 'instalasi', 'pengujian', 'selesai', 'bast'
            $table->enum('status', [
                'baru',
                'survei',
                'instalasi',
                'pengujian',
                'selesai',
                'bast'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            // Revert to original enum with only 3 values
            $table->enum('status', [
                'baru',
                'instalasi',
                'selesai'
            ])->change();
        });
    }
};
