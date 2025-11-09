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
        Schema::table('log_inventory', function (Blueprint $table) {
            // Change tanggal from date to datetime
            $table->dateTime('tanggal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_inventory', function (Blueprint $table) {
            // Revert back to date
            $table->date('tanggal')->change();
        });
    }
};
