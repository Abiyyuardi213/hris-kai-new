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
        Schema::create('perjalanan_dinas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pegawai_id');
            $table->string('no_surat_tugas')->nullable()->unique();
            $table->string('tujuan');
            $table->text('keperluan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('jenis_transportasi')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->default(0);
            $table->decimal('realisasi_biaya', 15, 2)->nullable();
            $table->enum('status', ['Pengajuan', 'Disetujui', 'Ditolak', 'Sedang Berjalan', 'Selesai', 'Dibatalkan'])->default('Pengajuan');
            $table->text('catatan_persetujuan')->nullable();
            $table->uuid('disetujui_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('perjalanan_dinas_peserta', function (Blueprint $table) {
            $table->id();
            $table->uuid('perjalanan_dinas_id');
            $table->uuid('pegawai_id');
            $table->timestamps();

            $table->foreign('perjalanan_dinas_id')->references('id')->on('perjalanan_dinas')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perjalanan_dinas_peserta');
        Schema::dropIfExists('perjalanan_dinas');
    }
};
