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
            // Drop the old typo column if it exists
            if (Schema::hasColumn('penawaran', 'create_at')) {
                $table->dropColumn('create_at');
            }
            
            // Add proper timestamps if they don't exist
            if (!Schema::hasColumn('penawaran', 'created_at')) {
                $table->timestamp('created_at')->useCurrent();
            }
            if (!Schema::hasColumn('penawaran', 'updated_at')) {
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            if (Schema::hasColumn('penawaran', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('penawaran', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            $table->timestamp('create_at')->useCurrent();
        });
    }
};
