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
        Schema::create('proyek_budget', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->references('id')->on('proyek')->onDelete('cascade');
            $table->decimal('jumlah_rencana', 15, 2);
            $table->decimal('jumlah_realisasi', 15, 2);
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_budget');
    }
};
