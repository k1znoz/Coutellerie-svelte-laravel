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
        Schema::create('knives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('length', 8, 2);
            $table->decimal('price', 10, 2);
            $table->json('images')->nullable();
            $table->boolean('available')->default(true);
            
            // Relation one-to-many avec Category
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Les relations many-to-many avec Type et Material
            // seront gérées par les tables pivots knife_type et knife_material
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knives');
    }
};
