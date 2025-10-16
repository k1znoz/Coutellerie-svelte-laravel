<?php
// Ensure Laravel dependencies are installed before redirecting
$vendorAutoload = __DIR__ . '/services/coutellerie-laravel/vendor/autoload.php';

if (!file_exists($vendorAutoload)) {
    // Try to install dependencies
    $laravelDir = __DIR__ . '/services/coutellerie-laravel';
    if (is_dir($laravelDir)) {
        chdir($laravelDir);
        
        // Check if composer is available
        $composerCheck = shell_exec('which composer 2>/dev/null');
        if ($composerCheck) {
            echo "Installing Laravel dependencies...\n";
            shell_exec('composer install --no-dev --optimize-autoloader --no-interaction 2>&1');
        }
    }
}

// Check again if vendor/autoload.php exists
if (!file_exists($vendorAutoload)) {
    http_response_code(503);
    die('Service temporarily unavailable: Laravel dependencies not installed. Vendor autoload not found at: ' . $vendorAutoload);
}

// Redirect to Laravel application
chdir(__DIR__ . '/services/coutellerie-laravel/public');
require_once $vendorAutoload;
require_once __DIR__ . '/services/coutellerie-laravel/public/index.php';