<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Knife;

class SyncKnifeCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-knife-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise et gère les catégories des couteaux';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('show')) {
            $this->showCategories();
            return;
        }

        if ($this->option('clean')) {
            $this->cleanCategories();
            return;
        }

        if ($this->option('init')) {
            $this->initializeCategories();
            return;
        }

        $this->info('Synchronisation des catégories de couteaux...');
        
        $categories = Knife::getAvailableCategories();
        
        if (empty($categories)) {
            $this->warn('Aucune catégorie trouvée dans la base de données.');
            $this->info('Les catégories par défaut seront utilisées dans Filament.');
            return;
        }

        $this->info('Catégories disponibles :');
        foreach ($categories as $category) {
            $count = Knife::where('category', $category)->count();
            $this->line("  - {$category} ({$count} couteaux)");
        }

        $this->info('Synchronisation terminée !');
    }

    private function showCategories()
    {
        $categories = Knife::getAvailableCategories();
        
        $this->info('=== Catégories de couteaux ===');
        
        if (empty($categories)) {
            $this->warn('Aucune catégorie trouvée.');
            return;
        }

        $this->table(['Catégorie', 'Nombre de couteaux'], 
            collect($categories)->map(function ($category) {
                return [
                    $category,
                    Knife::where('category', $category)->count()
                ];
            })->toArray()
        );
    }

    private function cleanCategories()
    {
        $this->info('Nettoyage des catégories...');
        
        $cleaned = Knife::whereNull('category')
            ->orWhere('category', '')
            ->update(['category' => 'Non catégorisé']);
            
        $this->info("✅ {$cleaned} couteaux mis à jour avec la catégorie 'Non catégorisé'");
    }
}
