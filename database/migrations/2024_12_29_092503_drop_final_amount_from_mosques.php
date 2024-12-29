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
        // This will remove the 'final_amount' column from the 'mosques' table
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn('final_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This will add the column back in case we need to roll back the migration
        Schema::table('mosques', function (Blueprint $table) {
            $table->decimal('final_amount', 10, 2);
        });
    }
};
