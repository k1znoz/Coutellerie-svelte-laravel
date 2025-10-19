<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut pour Railway
        User::firstOrCreate(
            ['email' => 'admin@coutellerie.com'],
            [
                'name' => 'Admin Coutellerie',
                'email' => 'admin@coutellerie.com',
                'password' => Hash::make('admin123'), // Changez ce mot de passe !
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Utilisateur admin créé: admin@coutellerie.com / admin123');
    }
}
