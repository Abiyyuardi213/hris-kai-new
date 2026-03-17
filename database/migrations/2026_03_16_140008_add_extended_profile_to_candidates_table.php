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
            $table->enum('marital_status', ['Belum Menikah', 'Menikah', 'Janda/Duda'])->nullable()->after('gender');
            $table->string('nationality')->default('Indonesia')->after('marital_status');
            $table->string('npwp')->nullable()->after('nationality');
            $table->json('social_media')->nullable()->after('npwp');
            $table->string('province')->nullable()->after('social_media');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('village')->nullable()->after('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['marital_status', 'nationality', 'npwp', 'social_media', 'province', 'city', 'district', 'village']);
        });
    }
};
