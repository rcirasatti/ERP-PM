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
            // AI Status untuk tracking fase analisis
            $table->enum('ai_status', ['pending', 'analyzed', 'approved'])->default('pending')->after('status');
            
            // Hasil prediksi model ML
            $table->decimal('ai_prediksi_lr', 15, 2)->nullable()->after('ai_status')->comment('Linear Regression prediction');
            $table->decimal('ai_prediksi_ma', 15, 2)->nullable()->after('ai_prediksi_lr')->comment('Moving Average prediction');
            
            // Status margin berdasarkan prediksi AI
            $table->enum('margin_status', ['aman', 'overrun', 'unknown'])->default('unknown')->after('ai_prediksi_ma');
            
            // Keterangan dari AI (misal: alasan overrun)
            $table->text('ai_notes')->nullable()->after('margin_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            $table->dropColumn(['ai_status', 'ai_prediksi_lr', 'ai_prediksi_ma', 'margin_status', 'ai_notes']);
        });
    }
};
