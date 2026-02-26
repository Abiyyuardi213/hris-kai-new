<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offboardings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->string('tipe_offboarding'); // "Resign", "Pensiun", "PHK", "Lainnya"
            $table->date('tanggal_efektif');
            $table->text('alasan_keluar')->nullable(); // Kuisioner: alasan keluar
            $table->text('saran_masukan')->nullable(); // Kuisioner: saran masukan

            // Clearance Checklist
            $table->boolean('clearance_id_card')->default(false);
            $table->boolean('clearance_laptop')->default(false);
            $table->boolean('clearance_dokumen')->default(false);

            // Perhitungan Pesangon / Pensiun
            $table->decimal('uang_pesangon', 15, 2)->default(0);

            // Status & Approval
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Rejected'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignUuid('processed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offboardings');
    }
};
