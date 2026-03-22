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
            $table->decimal('tunjangan_perumahan', 15, 2)->default(0)->after('gaji_pokok');
            $table->decimal('tunjangan_pajak', 15, 2)->default(0)->after('tunjangan_perumahan');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('tunjangan_perumahan', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('tunjangan_admin_bank', 15, 2)->default(10000)->after('tunjangan_perumahan');
            $table->decimal('tunjangan_jpk', 15, 2)->default(0)->after('tunjangan_admin_bank'); // 4%
            $table->decimal('tunjangan_pajak', 15, 2)->default(0)->after('tunjangan_jpk');
            $table->decimal('er_jamsostek_jkk', 15, 2)->default(0)->after('tunjangan_pajak'); // 0.24%
            $table->decimal('er_jamsostek_jht', 15, 2)->default(0)->after('er_jamsostek_jkk'); // 3.7%
            $table->decimal('er_jamsostek_jkm', 15, 2)->default(0)->after('er_jamsostek_jht'); // 0.3%
            $table->decimal('tunjangan_jpk_pensiun', 15, 2)->default(0)->after('er_jamsostek_jkm'); // 2%
            $table->decimal('tunjangan_jp_bpjs', 15, 2)->default(0)->after('tunjangan_jpk_pensiun'); // 2%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['tunjangan_perumahan', 'tunjangan_pajak']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'tunjangan_perumahan',
                'tunjangan_admin_bank',
                'tunjangan_jpk',
                'tunjangan_pajak',
                'er_jamsostek_jkk',
                'er_jamsostek_jht',
                'er_jamsostek_jkm',
                'tunjangan_jpk_pensiun',
                'tunjangan_jp_bpjs'
            ]);
        });
    }
};
