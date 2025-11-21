<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Récupérer les informations de l'admin depuis les variables d'environnement
        $adminEmail = env('ADMIN_EMAIL');
        $adminName = env('ADMIN_NAME', 'Admin');
        $adminPassword = env('ADMIN_PASSWORD');

        // 2. Ne rien faire si les variables d'environnement ne sont pas définies
        // Cela évite les erreurs et la création d'un utilisateur vide.
        if (!$adminEmail || !$adminPassword) {
            $this->command->warn('Création de l\'utilisateur admin ignorée. Veuillez définir les variables d\'environnement ADMIN_EMAIL et ADMIN_PASSWORD.');
            return;
        }

        // 3. Vérifier si l'utilisateur existe déjà pour ne pas le recréer
        $user = User::firstOrNew(['email' => $adminEmail]);

        // 4. Créer ou mettre à jour l'utilisateur avec les informations de l'environnement
        $user->name = $adminName;
        $user->password = Hash::make($adminPassword);
        $user->save();

        $this->command->info('Utilisateur admin ' . $adminEmail . ' traité avec succès.');
    }
}
