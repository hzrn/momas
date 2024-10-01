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
        Schema::create('category_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->index();
            $table->foreignId('parent_id')->index()->nullable();
            $table->string('url');
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('created_by')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_infos');
    }
};
