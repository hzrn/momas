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
        Schema::table('category_items', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_items', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable();
            $table->string('url')->nullable();
        });
    }
};
