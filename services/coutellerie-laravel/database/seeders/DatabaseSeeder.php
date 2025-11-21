<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            // Décommentez les lignes suivantes si vous voulez aussi lancer ces seeders en production
            // KnifeSeeder::class,
            // ContactMessageSeeder::class,
        ]);
    }
}
