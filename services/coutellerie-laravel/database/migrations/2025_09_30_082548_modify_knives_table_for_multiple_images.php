<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('knives', function (Blueprint $table) {
            // Sauvegarder les données existantes dans une nouvelle colonne temporaire
            $table->json('images_temp')->nullable()->after('image');
        });

        // Migrer les données existantes
        DB::statement("UPDATE knives SET images_temp = JSON_ARRAY(image) WHERE image IS NOT NULL");

        Schema::table('knives', function (Blueprint $table) {
            // Supprimer l'ancienne colonne et renommer la nouvelle
            $table->dropColumn('image');
        });

        Schema::table('knives', function (Blueprint $table) {
            $table->renameColumn('images_temp', 'images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knives', function (Blueprint $table) {
            // Récupérer la première image du tableau pour la remettre dans la colonne image
            $table->string('image')->nullable()->after('description');
        });

        // Migrer les données de retour
        DB::statement("UPDATE knives SET image = JSON_UNQUOTE(JSON_EXTRACT(images, '$[0]')) WHERE images IS NOT NULL");

        Schema::table('knives', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
