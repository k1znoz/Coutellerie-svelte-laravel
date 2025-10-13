<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Knife;

class KnifeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table avant de la remplir
        Knife::truncate();

        // Créer tous les couteaux (index 0 à 8)
        for ($i = 0; $i < 9; $i++) {
            Knife::factory()->specific($i)->create();
        }

        $this->command->info('9 couteaux créés avec succès !');
    }
}
