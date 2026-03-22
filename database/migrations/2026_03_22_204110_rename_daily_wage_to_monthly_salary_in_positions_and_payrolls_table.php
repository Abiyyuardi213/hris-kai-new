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
        Schema::table('positions', function (Blueprint $table) {
            $table->renameColumn('gaji_per_hari', 'gaji_pokok');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->renameColumn('gaji_harian', 'gaji_pokok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->renameColumn('gaji_pokok', 'gaji_per_hari');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->renameColumn('gaji_pokok', 'gaji_harian');
        });
    }
};
