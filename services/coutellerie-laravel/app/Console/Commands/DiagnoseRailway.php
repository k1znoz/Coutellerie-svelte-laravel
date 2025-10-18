<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class DiagnoseRailway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railway:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostic complet pour déploiement Railway';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚂 DIAGNOSTIC RAILWAY DEPLOYMENT');
        $this->info('================================');
        
        // Test variables critiques
        $this->checkEnvironmentVariables();
        
        // Test connexion DB
        $this->checkDatabaseConnection();
        
        // Test migrations
        $this->checkMigrations();
        
        // Test Filament
        $this->checkFilament();
        
        $this->info('✅ Diagnostic terminé!');
        return 0;
    }
    
    private function checkEnvironmentVariables()
    {
        $this->info('\n📋 Variables d\'environnement:');
        
        $vars = [
            'APP_KEY' => env('APP_KEY') ? 'DÉFINI' : '❌ MANQUANT',
            'APP_ENV' => env('APP_ENV', '❌ MANQUANT'),
            'APP_DEBUG' => env('APP_DEBUG', 'true') === 'false' ? '✅ false' : '⚠️ ' . env('APP_DEBUG', 'non défini'),
            'LOG_CHANNEL' => env('LOG_CHANNEL', '❌ MANQUANT'),
            'DB_CONNECTION' => env('DB_CONNECTION', '❌ MANQUANT'),
            'MYSQLHOST' => env('MYSQLHOST') ? 'DÉFINI' : '❌ MANQUANT (Railway)',
            'MYSQLDATABASE' => env('MYSQLDATABASE') ? 'DÉFINI' : '❌ MANQUANT (Railway)',
            'MYSQLUSER' => env('MYSQLUSER') ? 'DÉFINI' : '❌ MANQUANT (Railway)',
        ];
        
        foreach ($vars as $key => $value) {
            $this->line("  $key: $value");
        }
    }
    
    private function checkDatabaseConnection()
    {
        $this->info('\n🔗 Connexion base de données:');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('  ✅ Connexion réussie');
            $this->line('  📊 Driver: ' . $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME));
            $this->line('  🏠 Host: ' . config('database.connections.mysql.host'));
            $this->line('  🗃️ Database: ' . config('database.connections.mysql.database'));
        } catch (Exception $e) {
            $this->error('  ❌ Erreur de connexion: ' . $e->getMessage());
            
            // Debug info
            $this->line('  🔍 Configuration actuelle:');
            $this->line('    HOST: ' . config('database.connections.mysql.host'));
            $this->line('    PORT: ' . config('database.connections.mysql.port'));
            $this->line('    DATABASE: ' . config('database.connections.mysql.database'));
            $this->line('    USERNAME: ' . config('database.connections.mysql.username'));
        }
    }
    
    private function checkMigrations()
    {
        $this->info('\n📋 Migrations:');
        
        try {
            $migrations = DB::table('migrations')->count();
            $this->info("  ✅ $migrations migrations appliquées");
        } catch (Exception $e) {
            $this->error('  ❌ Impossible d\'accéder aux migrations');
            $this->line('  💡 Lancez: php artisan migrate --force');
        }
    }
    
    private function checkFilament()
    {
        $this->info('\n🎛️ Filament:');
        
        try {
            $userModel = config('auth.providers.users.model');
            $this->line("  📝 Modèle User: $userModel");
            
            $user = new $userModel();
            if ($user instanceof \Filament\Models\Contracts\FilamentUser) {
                $this->info('  ✅ FilamentUser correctement implémenté');
            } else {
                $this->error('  ❌ FilamentUser non implémenté');
            }
            
            // Test accès admin
            $users = DB::table('users')->count();
            $this->line("  👥 $users utilisateurs en base");
            
        } catch (Exception $e) {
            $this->error('  ❌ Erreur Filament: ' . $e->getMessage());
        }
    }
}
