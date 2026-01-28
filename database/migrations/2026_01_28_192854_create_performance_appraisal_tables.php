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
        Schema::create('kpi_indicators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('weight')->default(10); // Bobot dalam persen
            $table->enum('category', ['Kinerja', 'Perilaku', 'Kompetensi'])->default('Kinerja');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignUuid('appraiser_id')->constrained('users')->onDelete('cascade'); // Penilai (Admin)
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->integer('tahun');
            $table->decimal('total_score', 5, 2)->default(0);
            $table->string('rating')->nullable(); // A, B, C, D
            $table->text('catatan_reviewer')->nullable();
            $table->enum('status', ['Draft', 'Selesai', 'Dibatalkan'])->default('Draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('performance_appraisal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('performance_appraisal_id')->constrained('performance_appraisals')->onDelete('cascade');
            $table->foreignUuid('kpi_indicator_id')->constrained('kpi_indicators')->onDelete('cascade');
            $table->integer('score')->default(0); // 1-100
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_appraisal_items');
        Schema::dropIfExists('performance_appraisals');
        Schema::dropIfExists('kpi_indicators');
    }
};
