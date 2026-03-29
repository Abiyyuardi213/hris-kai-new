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
            $table->decimal('potongan_mandiri_inhealth', 15, 2)->default(0)->after('tunjangan_pajak');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('potongan_mandiri_inhealth', 15, 2)->default(0)->after('tunjangan_jp_bpjs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('potongan_mandiri_inhealth');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('potongan_mandiri_inhealth');
        });
    }
};
