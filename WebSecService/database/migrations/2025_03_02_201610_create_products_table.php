<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // Add the 'code' column
            $table->string('model')->nullable(); // Add the 'model' column
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('photo')->nullable(); // Add the 'photo' column
            $table->text('description')->nullable(); // Add the 'description' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}