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
            // Supprimer les anciennes colonnes de texte
            $table->dropColumn(['category', 'type', 'material']);

            // Ajouter les nouvelles colonnes de clé étrangère
            // onDelete('set null') signifie que si une catégorie est supprimée,
            // le couteau ne sera pas supprimé, mais son champ category_id deviendra null.
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knives', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes de clé étrangère
            $table->dropForeign(['category_id']);
            $table->dropForeign(['type_id']);
            $table->dropForeign(['material_id']);
            $table->dropColumn(['category_id', 'type_id', 'material_id']);

            // Recréer les anciennes colonnes de texte
            $table->string('category');
            $table->string('type');
            $table->string('material');
        });
    }
};
