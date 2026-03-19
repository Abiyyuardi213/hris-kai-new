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
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->renameColumn('title', 'judul_lowongan');
            $table->renameColumn('deadline', 'end_date');
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->date('start_date')->after('judul_lowongan')->nullable();
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id', 'description', 'requirements', 'quantity']);
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->enum('status_new', ['open', 'closed'])->default('open')->after('end_date');
        });
        
        // Copy data and drop old status if needed, but here we can just replace
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        Schema::create('job_vacancy_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamps();
        });

        Schema::create('job_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->onDelete('cascade');
            $table->string('formation_name');
            $table->string('education');
            $table->string('major');
            $table->string('gender');
            $table->text('document_requirements')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_formations');
        Schema::dropIfExists('job_vacancy_details');
        
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->renameColumn('judul_lowongan', 'title');
            $table->renameColumn('end_date', 'deadline');
            $table->dropColumn('start_date');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->integer('quantity')->default(1);
        });
    }
};
