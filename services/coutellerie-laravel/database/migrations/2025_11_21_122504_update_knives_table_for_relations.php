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
        Schema::table('knives', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn(['category', 'type', 'material']);

            // Ajouter SEULEMENT la clé étrangère pour category (one-to-many)
            // Les types et matériaux seront gérés par les tables pivots
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knives', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Recréer les anciennes colonnes
            $table->string('category');
            $table->string('type');
            $table->string('material');
        });
    }
};
