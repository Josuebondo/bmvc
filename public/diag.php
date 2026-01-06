<?php

/**
 * Script de diagnostic simple
 */

echo "=== DIAGNOSTIC ===\n\n";

// 1. Vérifier PHP
echo "1. Version PHP: " . phpversion() . "\n";
echo "   display_errors: " . ini_get('display_errors') . "\n";
echo "   error_reporting: " . error_reporting() . "\n\n";

// 2. Vérifier les dossiers
echo "2. Dossiers:\n";
echo "   __DIR__: " . __DIR__ . "\n";
echo "   APP_PATH: " . dirname(__DIR__) . "\n";
echo "   Vues: " . (is_dir(dirname(__DIR__) . '/app/Vues') ? '✓' : '✗') . "\n";
echo "   Vendor: " . (file_exists(dirname(__DIR__) . '/vendor/autoload.php') ? '✓' : '✗') . "\n";
echo "   .env: " . (file_exists(dirname(__DIR__) . '/.env') ? '✓' : '✗') . "\n\n";

// 3. Charger l'autoload
echo "3. Chargement autoload...\n";
try {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    echo "   ✓ Autoload chargé\n\n";
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n\n";
    exit;
}

// 4. Charger .env
echo "4. Chargement .env...\n";
try {
    $env = parse_ini_file(dirname(__DIR__) . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
    echo "   ✓ .env chargé\n";
    echo "   DEBOGAGE: " . env('DEBOGAGE') . "\n\n";
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n\n";
}

// 5. Tester Application
echo "5. Création Application...\n";
try {
    $app = new \Core\Application(dirname(__DIR__));
    echo "   ✓ Application créée\n\n";
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n\n";
    exit;
}

echo "=== DIAGNOSTIC TERMINÉ ===\n";
