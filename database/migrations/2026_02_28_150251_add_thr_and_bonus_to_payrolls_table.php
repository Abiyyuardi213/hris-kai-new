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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('thr', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('bonus', 15, 2)->default(0)->after('thr');
            $table->string('keterangan_bonus')->nullable()->after('bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['thr', 'bonus', 'keterangan_bonus']);
        });
    }
};
