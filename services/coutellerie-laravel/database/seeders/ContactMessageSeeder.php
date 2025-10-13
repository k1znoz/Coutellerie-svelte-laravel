<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@example.com',
                'subject' => 'Demande de couteau personnalisé',
                'message' => 'Bonjour, je souhaiterais commander un couteau de cuisine personnalisé avec mes initiales gravées. Pourriez-vous me faire un devis ? Merci.',
                'status' => 'new',
                'ip_address' => '192.168.1.1',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie.martin@example.com',
                'subject' => 'Question sur les délais de fabrication',
                'message' => 'Bonjour, combien de temps faut-il prévoir pour la fabrication d\'un couteau de poche ? J\'aimerais l\'offrir pour Noël.',
                'status' => 'read',
                'ip_address' => '192.168.1.2',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Pierre Leroy',
                'email' => 'pierre.leroy@example.com',
                'subject' => 'Réparation d\'un couteau ancien',
                'message' => 'J\'ai un vieux couteau de famille qui nécessite une restauration. Est-ce que vous faites ce type de travail ? Le manche est abîmé et la lame a besoin d\'être affûtée.',
                'status' => 'replied',
                'ip_address' => '192.168.1.3',
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'Sophie Bernard',
                'email' => 'sophie.bernard@example.com',
                'subject' => 'Commande urgente',
                'message' => 'Bonjour, est-il possible d\'avoir un couteau prêt dans la semaine ? C\'est pour un cadeau d\'anniversaire très important.',
                'status' => 'new',
                'ip_address' => '192.168.1.4',
                'created_at' => now()->subHours(3),
            ],
        ];

        foreach ($messages as $messageData) {
            ContactMessage::create($messageData);
        }

        $this->command->info('Messages de contact créés avec succès !');
    }
}