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
        Schema::create('penawaran', function (Blueprint $table) {
            $table->id();
            $table->text('no_penawaran');
            $table->foreignId('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['draft', 'disetujui', 'ditolak', 'dibatalkan'])->default('draft');
            $table->decimal('total_margin', 15, 2);
            $table->decimal('total_biaya', 15, 2);
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran');
    }
};
