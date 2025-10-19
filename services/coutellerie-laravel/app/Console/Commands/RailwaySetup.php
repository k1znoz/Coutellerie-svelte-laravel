<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RailwaySetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railway:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup complet pour déploiement Railway (migrations, seeders, assets)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚂 Railway Setup Started');
        
        // 1. Migrations
        $this->info('📋 Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());
        
        // 2. Admin User Seeder
        $this->info('👤 Creating admin user...');
        Artisan::call('db:seed', ['--class' => 'AdminUserSeeder']);
        $this->line(Artisan::output());
        
        // 3. Livewire Assets (CRITIQUE pour Filament)
        $this->info('📦 Publishing Livewire assets...');
        Artisan::call('vendor:publish', ['--tag' => 'livewire:assets', '--force' => true]);
        $this->line(Artisan::output());
        
        // 4. Filament Assets (méthode correcte pour v3)
        $this->info('🎨 Publishing Filament assets...');
        Artisan::call('filament:assets');
        $this->line(Artisan::output());
        
        // 5. Filament Optimization
        $this->info('⚡ Optimizing Filament...');
        try {
            Artisan::call('filament:optimize');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->warn('Filament optimize not available in this version');
        }
        
        // 6. Storage Link
        $this->info('🔗 Creating storage links...');
        try {
            Artisan::call('storage:link');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->warn('Storage link already exists or failed');
        }
        
        // 7. Optimizations
        $this->info('⚡ Optimizing for production...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        $this->info('✅ Railway setup completed successfully!');
        $this->line('🔑 Admin login: admin@coutellerie.com / admin123');
        
        return 0;
    }
}
