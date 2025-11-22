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
        // 1. Créer la table categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 2. Créer la table types
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 3. Créer la table materials
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 4. Créer la table knives (APRÈS les autres)
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
            
            $table->timestamps();
        });

        // 5. Créer la table pivot knife_type
        Schema::create('knife_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knife_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['knife_id', 'type_id']);
        });

        // 6. Créer la table pivot knife_material
        Schema::create('knife_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knife_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['knife_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knife_material');
        Schema::dropIfExists('knife_type');
        Schema::dropIfExists('knives');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('types');
        Schema::dropIfExists('categories');
    }
};