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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('status_pegawai_id')->nullable()->constrained('employment_statuses')->onDelete('set null');
            $table->integer('sisa_cuti')->default(12);
            $table->foreignId('shift_kerja_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->foreignId('divisi_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->foreignId('jabatan_id')->nullable()->constrained('positions')->onDelete('set null');
            $table->foreignId('kantor_id')->nullable()->constrained('offices')->onDelete('set null');
            $table->string('nip')->unique();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email_pribadi')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
