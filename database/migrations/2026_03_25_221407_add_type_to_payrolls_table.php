<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('payrolls', 'type')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->enum('type', ['payroll', 'thr', 'bonus'])->default('payroll')->after('year');
            });
        }

        $indexes = collect(DB::select("SHOW INDEXES FROM payrolls"))->pluck('Key_name');

        if ($indexes->contains('payrolls_pegawai_id_month_year_unique')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->dropForeign(['pegawai_id']);
                $table->dropUnique(['pegawai_id', 'month', 'year']);
                $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            });
        }

        if (!$indexes->contains('payrolls_pegawai_id_month_year_type_unique')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->unique(['pegawai_id', 'month', 'year', 'type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = collect(DB::select("SHOW INDEXES FROM payrolls"))->pluck('Key_name');

        if ($indexes->contains('payrolls_pegawai_id_month_year_type_unique')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->dropForeign(['pegawai_id']);
                $table->dropUnique(['pegawai_id', 'month', 'year', 'type']);
                $table->unique(['pegawai_id', 'month', 'year']);
                $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('payrolls', 'type')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
