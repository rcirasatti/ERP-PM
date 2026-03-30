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
        // Add unique constraint to prevent duplicate quotation numbers
        // This prevents race condition where two concurrent requests
        // could assign the same no_penawaran sequence number
        try {
            Schema::table('penawaran', function (Blueprint $table) {
                $table->unique('no_penawaran');
            });
        } catch (\Exception $e) {
            // Constraint might already exist, that's OK
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            $table->dropUnique(['no_penawaran']);
        });
    }
};
