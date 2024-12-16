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
        Schema::table('mosques', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable(); // To store latitude
            $table->decimal('longitude', 11, 8)->nullable(); // To store longitude
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
