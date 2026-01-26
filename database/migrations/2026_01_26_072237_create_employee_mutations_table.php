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
        Schema::create('employee_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('employee_id')->constrained('pegawais')->onDelete('cascade');
            $table->enum('type', ['promosi', 'demosi', 'rotasi', 'mutasi']);

            // From
            $table->foreignId('from_division_id')->nullable()->constrained('divisions');
            $table->foreignId('from_position_id')->nullable()->constrained('positions');
            $table->foreignId('from_office_id')->nullable()->constrained('offices');

            // To
            $table->foreignId('to_division_id')->nullable()->constrained('divisions');
            $table->foreignId('to_position_id')->nullable()->constrained('positions');
            $table->foreignId('to_office_id')->nullable()->constrained('offices');

            $table->date('mutation_date');
            $table->text('reason');
            $table->string('file_sk')->nullable(); // SK Mutasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_mutations');
    }
};
