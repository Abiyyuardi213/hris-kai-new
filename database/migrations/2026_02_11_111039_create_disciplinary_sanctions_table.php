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
        Schema::create('disciplinary_sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('employee_id')->constrained('pegawais')->onDelete('cascade');
            $table->string('type'); // Verbal, SP1, SP2, SP3, Termination
            $table->text('description')->nullable(); // Alasan/Detail pelanggaran
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Tanggal berakhirnya sanksi (SP biasanya 6 bulan)
            $table->string('document_path')->nullable(); // Upload file SP yg ditandatangani
            $table->string('status')->default('Active'); // Active, Expired, Revoked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinary_sanctions');
    }
};
