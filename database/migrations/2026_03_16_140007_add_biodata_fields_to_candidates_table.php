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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->string('religion')->nullable()->after('place_of_birth');
            $table->enum('gender', ['Lelaki', 'Perempuan'])->nullable()->after('religion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['place_of_birth', 'religion', 'gender']);
        });
    }
};
