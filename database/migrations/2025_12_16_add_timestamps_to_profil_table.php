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
        Schema::table('profil', function (Blueprint $table) {
            if (!Schema::hasColumn('profil', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil', function (Blueprint $table) {
            if (Schema::hasColumn('profil', 'created_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }
};
