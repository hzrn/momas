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

            Schema::create('items', function (Blueprint $table) {
                $table->id(); // Primary Key
                $table->foreignId('mosque_id')->index(); // Foreign Key to mosque
                $table->foreignId('category_item_id')->index()->default(0); // Foreign Key to category item
                $table->string('name'); // Item name
                $table->string('description')->nullable(); // Item description
                $table->integer('quantity'); // Quantity column
                $table->decimal('price', 8, 2); // Price column with precision (8 digits total, 2 decimal places)
                $table->foreignId('created_by')->index(); // Foreign Key to user (who created it)
                $table->timestamps(); // Timestamps (created_at, updated_at)
            });
        }
        
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
