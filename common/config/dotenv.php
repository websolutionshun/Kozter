<?php
/**
 * DotEnv betöltő helper fájl
 * 
 * Ez a fájl betölti a .env fájlból a környezeti változókat
 * és biztosítja, hogy azok elérhetőek legyenek az alkalmazásban.
 * 
 * Használat:
 * require_once __DIR__ . '/dotenv.php';
 */

use Dotenv\Dotenv;

// .env fájl helye (projekt gyökérkönyvtár)
$envPath = dirname(dirname(__DIR__));

// DotEnv betöltése, ha létezik a .env fájl
if (file_exists($envPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($envPath);
    $dotenv->load();
    
    // Alapértelmezett értékek beállítása, ha nincsenek megadva
    $dotenv->required(['YII_ENV', 'YII_DEBUG']);
    
    // YII_ENV és YII_DEBUG konstansok beállítása, ha még nincsenek definiálva
    if (!defined('YII_ENV')) {
        define('YII_ENV', $_ENV['YII_ENV'] ?? 'dev');
    }
    
    if (!defined('YII_DEBUG')) {
        define('YII_DEBUG', filter_var($_ENV['YII_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
    }
}

/**
 * Környezeti változó lekérése alapértelmezett értékkel
 * 
 * @param string $key A környezeti változó kulcsa
 * @param mixed $default Alapértelmezett érték, ha a változó nem létezik
 * @return mixed
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
} 