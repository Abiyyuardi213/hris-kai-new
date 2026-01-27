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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->integer('jumlah_hadir')->default(0);
            $table->decimal('gaji_harian', 15, 2);
            $table->decimal('tunjangan_jabatan', 15, 2);
            $table->decimal('total_gaji', 15, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignUuid('generated_by')->constrained('users');
            $table->timestamps();

            $table->unique(['pegawai_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
