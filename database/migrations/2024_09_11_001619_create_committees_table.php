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
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->index();
            $table->string('name');
            $table->string('phone_num');
            $table->string('position');
            $table->text('address');
            $table->string('photo')->nullable(); // Column to store image path (optional)
            $table->foreignId('created_by')->index(); // Foreign Key to user (who created it)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
